<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleAndRecurrenceToPlaylistItems extends Migration
{
    public function up()
    {
        $fields = [
            'is_scheduled' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'sort_order',
            ],
            'schedule_type' => [
                'type'       => 'ENUM',
                'constraint' => ['always', 'date_range', 'recurring_weekly', 'custom_range'],
                'default'    => 'always',
                'after'      => 'is_scheduled',
            ],
            'start_date' => [
                'type'  => 'DATE',
                'null'  => true,
                'after' => 'schedule_type',
            ],
            'end_date' => [
                'type'  => 'DATE',
                'null'  => true,
                'after' => 'start_date',
            ],
            'days_of_week' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'end_date',
            ],
            'start_time' => [
                'type'  => 'TIME',
                'null'  => true,
                'after' => 'days_of_week',
            ],
            'end_time' => [
                'type'  => 'TIME',
                'null'  => true,
                'after' => 'start_time',
            ],
            'updated_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'created_at',
            ],
        ];

        $this->forge->addColumn('tv_playlist_items', $fields);
    }

    public function down()
    {
        $columns = [
            'is_scheduled',
            'schedule_type',
            'start_date',
            'end_date',
            'days_of_week',
            'start_time',
            'end_time',
            'updated_at',
        ];

        foreach ($columns as $col) {
            if ($this->db->fieldExists($col, 'tv_playlist_items')) {
                $this->forge->dropColumn('tv_playlist_items', $col);
            }
        }
    }
}
