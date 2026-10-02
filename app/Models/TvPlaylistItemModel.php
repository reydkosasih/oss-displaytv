<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class TvPlaylistItemModel extends Model
{
    protected $table            = 'tv_playlist_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tv_id',
        'content_id',
        'sort_order',
        'is_scheduled',
        'schedule_type',
        'start_date',
        'end_date',
        'days_of_week',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get sorted playlist items for a TV
     *
     * @param int $tvId
     * @param bool $filterActiveOnly If true, only returns items that are active right now
     * @param string|null $checkDateTime Format: 'Y-m-d H:i:s' (default now)
     * @return array
     */
    public function getPlaylistForTv(int $tvId, bool $filterActiveOnly = false, ?string $checkDateTime = null): array
    {
        $db = \Config\Database::connect();

        // 1. Get assigned categories for this TV
        $assignedCategories = $db->table('tv_categories')
                                 ->select('category_id')
                                 ->where('tv_id', $tvId)
                                 ->get()
                                 ->getResultArray();

        $catIds = array_column($assignedCategories, 'category_id');

        if (empty($catIds)) {
            return [];
        }

        // 2. Fetch active contents under these categories with left join to tv_playlist_items
        $contents = $db->table('contents c')
                       ->select('
                            c.*,
                            cat.name as category_name,
                            cat.color as category_color,
                            COALESCE(pi.sort_order, 999999) as sort_order,
                            pi.id as playlist_item_id,
                            COALESCE(pi.is_scheduled, 0) as is_scheduled,
                            COALESCE(pi.schedule_type, "always") as schedule_type,
                            pi.start_date,
                            pi.end_date,
                            pi.days_of_week,
                            pi.start_time,
                            pi.end_time
                       ')
                       ->join('categories cat', 'cat.id = c.category_id')
                       ->join('tv_playlist_items pi', 'pi.content_id = c.id AND pi.tv_id = ' . (int) $tvId, 'left')
                       ->whereIn('c.category_id', $catIds)
                       ->where('c.deleted_at', null)
                       ->where('c.is_active', 1)
                       ->orderBy('sort_order', 'ASC')
                       ->orderBy('c.created_at', 'DESC')
                       ->get()
                       ->getResultArray();

        $checkDt = $checkDateTime ? new DateTime($checkDateTime) : new DateTime('now', new \DateTimeZone('Asia/Jakarta'));

        // Process schedule metadata & status
        $processed = [];
        foreach ($contents as $item) {
            $item['days_of_week_arr'] = !empty($item['days_of_week']) ? json_decode($item['days_of_week'], true) : [];
            $scheduleInfo             = $this->evaluateScheduleStatus($item, $checkDt);
            $item['schedule_status']  = $scheduleInfo['status'];
            $item['schedule_badge']   = $scheduleInfo['badge'];
            $item['schedule_text']    = $scheduleInfo['text'];
            $item['is_active_now']    = $scheduleInfo['is_active_now'];

            if ($filterActiveOnly) {
                if ($item['is_active_now']) {
                    $processed[] = $item;
                }
            } else {
                $processed[] = $item;
            }
        }

        return $processed;
    }

    /**
     * Evaluate if a playlist item is active at a given DateTime
     */
    public function isItemActiveAt(array $item, DateTime $dt): bool
    {
        if (empty($item['is_scheduled']) || (int) $item['is_scheduled'] === 0 || ($item['schedule_type'] ?? '') === 'always') {
            return true;
        }

        $currentDate = $dt->format('Y-m-d');
        $currentTime = $dt->format('H:i:s');
        $currentDay  = strtolower($dt->format('D')); // mon, tue, wed, thu, fri, sat, sun

        // 1. Date Range Check
        if (!empty($item['start_date']) && $currentDate < $item['start_date']) {
            return false;
        }
        if (!empty($item['end_date']) && $currentDate > $item['end_date']) {
            return false;
        }

        // 2. Days of Week Recurrence Check
        $days = !empty($item['days_of_week_arr']) ? $item['days_of_week_arr'] : (!empty($item['days_of_week']) ? json_decode($item['days_of_week'], true) : []);
        if (!empty($days) && is_array($days)) {
            // Normalize days to lowercase 3-letter strings
            $normalizedDays = array_map(function ($d) {
                return strtolower(trim($d));
            }, $days);

            if (!in_array($currentDay, $normalizedDays, true)) {
                return false;
            }
        }

        // 3. Operational Time Range Check
        if (!empty($item['start_time']) && !empty($item['end_time'])) {
            $st = $item['start_time'];
            $et = $item['end_time'];
            if (strlen($st) === 5) $st .= ':00';
            if (strlen($et) === 5) $et .= ':00';

            if ($st <= $et) {
                if ($currentTime < $st || $currentTime > $et) {
                    return false;
                }
            } else {
                // Overnight e.g. 22:00:00 to 04:00:00
                if ($currentTime < $st && $currentTime > $et) {
                    return false;
                }
            }
        } elseif (!empty($item['start_time'])) {
            $st = $item['start_time'];
            if (strlen($st) === 5) $st .= ':00';
            if ($currentTime < $st) return false;
        } elseif (!empty($item['end_time'])) {
            $et = $item['end_time'];
            if (strlen($et) === 5) $et .= ':00';
            if ($currentTime > $et) return false;
        }

        return true;
    }

    /**
     * Evaluate human-readable schedule status, badges, and texts
     */
    public function evaluateScheduleStatus(array $item, DateTime $dt): array
    {
        $isScheduled = !empty($item['is_scheduled']) && (int) $item['is_scheduled'] === 1 && ($item['schedule_type'] ?? '') !== 'always';

        if (!$isScheduled) {
            return [
                'status'        => 'always',
                'badge'         => 'Selalu Tayang',
                'text'          => '-',
                'is_active_now' => true,
            ];
        }

        $currentDate = $dt->format('Y-m-d');
        $currentTime = $dt->format('H:i:s');
        $currentDay  = strtolower($dt->format('D'));

        $startDate = $item['start_date'] ?? null;
        $endDate   = $item['end_date'] ?? null;
        $days      = !empty($item['days_of_week_arr']) ? $item['days_of_week_arr'] : (!empty($item['days_of_week']) ? json_decode($item['days_of_week'], true) : []);
        $startTime = !empty($item['start_time']) ? substr($item['start_time'], 0, 5) : null;
        $endTime   = !empty($item['end_time']) ? substr($item['end_time'], 0, 5) : null;

        // Build human-readable text parts
        $parts = [];

        // Date text
        if ($startDate && $endDate) {
            $parts[] = date('d M Y', strtotime($startDate)) . ' — ' . date('d M Y', strtotime($endDate));
        } elseif ($startDate) {
            $parts[] = 'Mulai ' . date('d M Y', strtotime($startDate));
        } elseif ($endDate) {
            $parts[] = 'Hingga ' . date('d M Y', strtotime($endDate));
        }

        // Days text
        $dayNamesIndo = [
            'mon' => 'Sen', 'tue' => 'Sel', 'wed' => 'Rab',
            'thu' => 'Kam', 'fri' => 'Jum', 'sat' => 'Sab', 'sun' => 'Min'
        ];
        if (!empty($days) && is_array($days)) {
            $dayLabels = [];
            foreach ($days as $d) {
                $dl = strtolower(trim($d));
                if (isset($dayNamesIndo[$dl])) {
                    $dayLabels[] = $dayNamesIndo[$dl];
                }
            }
            if (count($dayLabels) === 7) {
                $parts[] = 'Setiap Hari';
            } elseif (count($dayLabels) === 5 && !in_array('Sab', $dayLabels) && !in_array('Min', $dayLabels)) {
                $parts[] = 'Hari Kerja (Sen-Jum)';
            } elseif (count($dayLabels) === 2 && in_array('Sab', $dayLabels) && in_array('Min', $dayLabels)) {
                $parts[] = 'Akhir Pekan (Sab-Min)';
            } else {
                $parts[] = implode(', ', $dayLabels);
            }
        }

        // Time text
        if ($startTime && $endTime) {
            $parts[] = "{$startTime} - {$endTime} WIB";
        } elseif ($startTime) {
            $parts[] = "Mulai {$startTime} WIB";
        } elseif ($endTime) {
            $parts[] = "Sampai {$endTime} WIB";
        }

        $fullText = !empty($parts) ? implode(' • ', $parts) : 'Jadwal Khusus';

        // Check if expired
        if ($endDate && $currentDate > $endDate) {
            return [
                'status'        => 'expired',
                'badge'         => 'Sudah Berakhir',
                'text'          => $fullText,
                'is_active_now' => false,
            ];
        }

        // Check if future
        if ($startDate && $currentDate < $startDate) {
            return [
                'status'        => 'upcoming',
                'badge'         => 'Akan Datang',
                'text'          => $fullText,
                'is_active_now' => false,
            ];
        }

        // Currently within date range; check day & hour
        $isActive = $this->isItemActiveAt($item, $dt);

        if ($isActive) {
            return [
                'status'        => 'active',
                'badge'         => 'Aktif Tayang',
                'text'          => $fullText,
                'is_active_now' => true,
            ];
        }

        return [
            'status'        => 'off_hours',
            'badge'         => 'Di Luar Jam/Hari',
            'text'          => $fullText,
            'is_active_now' => false,
        ];
    }

    /**
     * Get monthly calendar overview data for a TV
     */
    public function getMonthlyCalendarSchedule(int $tvId, int $year, int $month): array
    {
        $allPlaylistItems = $this->getPlaylistForTv($tvId, false);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysData    = [];

        $dayNamesIndo = [
            'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu',
            'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu', 'Sun' => 'Minggu'
        ];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $dayDt   = new DateTime($dateStr . ' 12:00:00', new \DateTimeZone('Asia/Jakarta'));
            $dayAbbr = $dayDt->format('D'); // Mon, Tue, etc.

            $activeOnDay = [];
            $totalDurationSec = 0;

            foreach ($allPlaylistItems as $item) {
                // Check if active on this specific calendar day (ignoring strict hour for calendar day view)
                $isScheduled = !empty($item['is_scheduled']) && (int) $item['is_scheduled'] === 1 && ($item['schedule_type'] ?? '') !== 'always';

                $matchesDay = true;
                if ($isScheduled) {
                    if (!empty($item['start_date']) && $dateStr < $item['start_date']) {
                        $matchesDay = false;
                    }
                    if (!empty($item['end_date']) && $dateStr > $item['end_date']) {
                        $matchesDay = false;
                    }
                    $days = !empty($item['days_of_week_arr']) ? $item['days_of_week_arr'] : (!empty($item['days_of_week']) ? json_decode($item['days_of_week'], true) : []);
                    if (!empty($days) && is_array($days)) {
                        $normalizedDays = array_map('strtolower', array_map('trim', $days));
                        if (!in_array(strtolower($dayAbbr), $normalizedDays, true)) {
                            $matchesDay = false;
                        }
                    }
                }

                if ($matchesDay) {
                    $dur = (int) ($item['display_duration_seconds'] ?: $item['video_duration_seconds'] ?: 10);
                    $totalDurationSec += $dur;

                    $activeOnDay[] = [
                        'id'                       => (int) $item['id'],
                        'content_id'               => (int) $item['id'],
                        'title'                    => $item['title'],
                        'type'                     => $item['type'],
                        'category_name'            => $item['category_name'],
                        'category_color'           => $item['category_color'],
                        'file_url'                 => $item['file_url'] ?? null,
                        'video_source'             => $item['video_source'] ?? null,
                        'duration'                 => $dur,
                        'is_scheduled'             => (int) $item['is_scheduled'],
                        'schedule_type'            => $item['schedule_type'],
                        'start_time'               => !empty($item['start_time']) ? substr($item['start_time'], 0, 5) : null,
                        'end_time'                 => !empty($item['end_time']) ? substr($item['end_time'], 0, 5) : null,
                        'schedule_text'            => $item['schedule_text'],
                        'sort_order'               => (int) $item['sort_order'],
                    ];
                }
            }

            $daysData[$dateStr] = [
                'day'                => $d,
                'date'               => $dateStr,
                'day_name'           => $dayNamesIndo[$dayAbbr] ?? $dayAbbr,
                'day_abbr'           => strtolower($dayAbbr),
                'active_count'       => count($activeOnDay),
                'total_duration_sec' => $totalDurationSec,
                'items'              => $activeOnDay,
            ];
        }

        return [
            'year'        => $year,
            'month'       => $month,
            'days_in_month' => $daysInMonth,
            'days'        => $daysData,
        ];
    }

    /**
     * Update or save schedule configuration for a specific playlist item
     */
    public function updateItemSchedule(int $tvId, int $contentId, array $data): bool
    {
        $existing = $this->where('tv_id', $tvId)->where('content_id', $contentId)->first();

        $saveData = [
            'tv_id'         => $tvId,
            'content_id'    => $contentId,
            'is_scheduled'  => !empty($data['is_scheduled']) ? 1 : 0,
            'schedule_type' => $data['schedule_type'] ?? 'always',
            'start_date'    => !empty($data['start_date']) ? $data['start_date'] : null,
            'end_date'      => !empty($data['end_date']) ? $data['end_date'] : null,
            'days_of_week'  => !empty($data['days_of_week']) ? (is_array($data['days_of_week']) ? json_encode(array_values($data['days_of_week'])) : $data['days_of_week']) : null,
            'start_time'    => !empty($data['start_time']) ? $data['start_time'] : null,
            'end_time'      => !empty($data['end_time']) ? $data['end_time'] : null,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            return $this->update($existing['id'], $saveData);
        }

        // Determine next sort_order if item didn't exist in tv_playlist_items
        $maxSort = $this->where('tv_id', $tvId)->selectMax('sort_order')->first();
        $saveData['sort_order'] = ($maxSort['sort_order'] ?? 0) + 1;
        $saveData['created_at'] = date('Y-m-d H:i:s');

        return (bool) $this->insert($saveData);
    }

    /**
     * Reset a playlist item to "Always Active"
     */
    public function resetItemSchedule(int $tvId, int $contentId): bool
    {
        return $this->updateItemSchedule($tvId, $contentId, [
            'is_scheduled'  => 0,
            'schedule_type' => 'always',
            'start_date'    => null,
            'end_date'      => null,
            'days_of_week'  => null,
            'start_time'    => null,
            'end_time'      => null,
        ]);
    }

    /**
     * Update sort order for a TV playlist (preserves existing schedule settings)
     */
    public function updateSortOrder(int $tvId, array $contentIds)
    {
        $existingItems = $this->where('tv_id', $tvId)->findAll();
        $itemMap = [];
        foreach ($existingItems as $it) {
            $itemMap[$it['content_id']] = $it;
        }

        // Delete all rows for this TV
        $this->where('tv_id', $tvId)->delete();

        if (!empty($contentIds)) {
            $data = [];
            foreach ($contentIds as $index => $cId) {
                $cId = (int) $cId;
                $old = $itemMap[$cId] ?? [];

                $data[] = [
                    'tv_id'         => $tvId,
                    'content_id'    => $cId,
                    'sort_order'    => $index + 1,
                    'is_scheduled'  => $old['is_scheduled'] ?? 0,
                    'schedule_type' => $old['schedule_type'] ?? 'always',
                    'start_date'    => $old['start_date'] ?? null,
                    'end_date'      => $old['end_date'] ?? null,
                    'days_of_week'  => $old['days_of_week'] ?? null,
                    'start_time'    => $old['start_time'] ?? null,
                    'end_time'      => $old['end_time'] ?? null,
                    'created_at'    => $old['created_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
            }
            $this->insertBatch($data);
        }
    }
}
