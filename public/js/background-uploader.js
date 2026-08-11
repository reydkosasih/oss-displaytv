/**
 * Global Background Uploader Engine for Display TV
 * Manages itemized sequential file uploads, byte-level progress tracking,
 * floating drawer widget UI, and cross-page state persistence.
 */
(function ($) {
    'use strict';

    function formatBytes(bytes, decimals = 2) {
        if (!bytes || bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    const BackgroundUploader = {
        queue: [],
        activeXhr: null,
        isUploading: false,
        baseUrl: '',

        init: function (config) {
            this.baseUrl = config.baseUrl || '';
            this.bindEvents();
            this.restoreState();
        },

        bindEvents: function () {
            const self = this;

            $(document).on('click', '#btnToggleUploadWidget', function () {
                self.toggleMinimize();
            });

            $(document).on('click', '#btnCancelUploadQueue', function () {
                self.cancelQueue();
            });

            $(document).on('click', '#btnCloseUploadWidget', function () {
                $('#globalUploadWidget').addClass('hidden');
            });
        },

        restoreState: function () {
            const isMinimized = localStorage.getItem('uploadWidgetMinimized') === 'true';
            if (isMinimized) {
                $('#uploadWidgetContent').addClass('hidden');
                $('#btnToggleUploadWidget i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
        },

        enqueue: function (items) {
            const self = this;
            if (!Array.isArray(items)) {
                items = [items];
            }

            items.forEach(function (item) {
                const id = 'up_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
                self.queue.push({
                    id: id,
                    file: item.file,
                    type: item.type || (item.file && item.file.type.startsWith('video/') ? 'video' : 'image'),
                    title: item.title || (item.file ? item.file.name.replace(/\.[^/.]+$/, "") : 'Konten Baru'),
                    categoryId: item.categoryId || 0,
                    duration: item.duration || 10,
                    isActive: item.isActive !== undefined ? item.isActive : 1,
                    status: 'queued', // queued | uploading | completed | failed
                    progress: 0,
                    errorMsg: '',
                    fileSize: item.file ? item.file.size : 0
                });
            });

            $('#globalUploadWidget').removeClass('hidden');
            this.updateWidgetUI();

            if (!this.isUploading) {
                this.processNextInQueue();
            }
        },

        processNextInQueue: function () {
            const self = this;
            const nextItem = this.queue.find(item => item.status === 'queued');

            if (!nextItem) {
                this.isUploading = false;
                this.updateWidgetUI();
                this.onQueueCompleted();
                return;
            }

            this.isUploading = true;
            nextItem.status = 'uploading';
            nextItem.progress = 0;
            this.updateWidgetUI();

            const formData = new FormData();
            formData.append('title', nextItem.title);
            formData.append('category_id', nextItem.categoryId);
            formData.append('display_duration_seconds', nextItem.duration);
            formData.append('is_active', nextItem.isActive);

            let endpoint = '';
            if (nextItem.type === 'image') {
                endpoint = this.baseUrl + 'admin/content/store-image';
                formData.append('image_file', nextItem.file);
            } else {
                endpoint = this.baseUrl + 'admin/content/store-video';
                formData.append('video_source', 'upload');
                formData.append('video_file', nextItem.file);
                formData.append('video_duration_seconds', nextItem.duration);
            }

            this.activeXhr = $.ajax({
                url: endpoint,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                xhr: function () {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            const pct = Math.round((e.loaded / e.total) * 100);
                            nextItem.progress = pct;
                            self.updateWidgetUI();
                        }
                    }, false);
                    return xhr;
                },
                success: function (res) {
                    if (res.status === 'success') {
                        nextItem.status = 'completed';
                        nextItem.progress = 100;
                    } else {
                        nextItem.status = 'failed';
                        nextItem.errorMsg = res.message || 'Gagal mengunggah berkas.';
                    }
                    self.updateWidgetUI();
                    self.processNextInQueue();
                },
                error: function (xhr) {
                    nextItem.status = 'failed';
                    let msg = 'Gagal mengunggah berkas.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errs = xhr.responseJSON.errors;
                        msg = Object.values(errs)[0] || msg;
                    }
                    nextItem.errorMsg = msg;
                    self.updateWidgetUI();
                    self.processNextInQueue();
                }
            });
        },

        cancelQueue: function () {
            if (this.activeXhr) {
                this.activeXhr.abort();
                this.activeXhr = null;
            }

            this.queue.forEach(item => {
                if (item.status === 'queued' || item.status === 'uploading') {
                    item.status = 'failed';
                    item.errorMsg = 'Dibatalkan oleh pengguna.';
                }
            });

            this.isUploading = false;
            this.updateWidgetUI();
        },

        toggleMinimize: function () {
            const $content = $('#uploadWidgetContent');
            const $icon = $('#btnToggleUploadWidget i');

            $content.toggleClass('hidden');
            const isHidden = $content.hasClass('hidden');
            localStorage.setItem('uploadWidgetMinimized', isHidden);

            if (isHidden) {
                $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        },

        updateWidgetUI: function () {
            if (this.queue.length === 0) return;

            const total = this.queue.length;
            const completed = this.queue.filter(i => i.status === 'completed').length;
            const failed = this.queue.filter(i => i.status === 'failed').length;
            const inProgress = this.queue.filter(i => i.status === 'uploading' || i.status === 'queued').length;

            let totalPctSum = 0;
            this.queue.forEach(i => {
                if (i.status === 'completed') totalPctSum += 100;
                else if (i.status === 'uploading') totalPctSum += i.progress;
            });
            const overallPct = Math.round(totalPctSum / total);

            if (this.isUploading) {
                $('#uploadWidgetTitle').text(`Mengunggah (${completed + failed + 1}/${total})`);
                $('#uploadWidgetStatusBadge').html(`<i class="fa-solid fa-spinner fa-spin text-blue-400 mr-1"></i>${overallPct}%`);
            } else if (inProgress === 0) {
                if (failed === 0) {
                    $('#uploadWidgetTitle').text('Unggah Selesai');
                    $('#uploadWidgetStatusBadge').html('<i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i>100%');
                } else {
                    $('#uploadWidgetTitle').text('Unggah Selesai (Ada Gagal)');
                    $('#uploadWidgetStatusBadge').html(`<span class="text-rose-400 font-semibold">${completed} sukses, ${failed} gagal</span>`);
                }
            }

            $('#uploadWidgetProgressBar').css('width', overallPct + '%');

            let itemsHtml = '';
            this.queue.forEach(item => {
                let statusBadge = '';
                if (item.status === 'queued') {
                    statusBadge = `<span class="text-[10px] text-slate-400 italic">Menunggu...</span>`;
                } else if (item.status === 'uploading') {
                    statusBadge = `<span class="text-[10px] text-blue-400 font-mono font-bold">${item.progress}%</span>`;
                } else if (item.status === 'completed') {
                    statusBadge = `<span class="text-[10px] text-emerald-400 font-bold"><i class="fa-solid fa-check mr-1"></i>Sukses</span>`;
                } else if (item.status === 'failed') {
                    statusBadge = `<span class="text-[10px] text-rose-400 font-bold" title="${item.errorMsg}"><i class="fa-solid fa-xmark mr-1"></i>Gagal</span>`;
                }

                const icon = item.type === 'image' 
                    ? `<i class="fa-solid fa-image text-blue-400 text-xs"></i>`
                    : `<i class="fa-solid fa-film text-sky-400 text-xs"></i>`;

                itemsHtml += `
                    <div class="p-2 bg-slate-800/80 rounded-lg border border-slate-700/60 flex items-center justify-between gap-2 text-xs">
                        <div class="flex items-center space-x-2 min-w-0 flex-1">
                            <div class="w-6 h-6 rounded bg-slate-900 flex items-center justify-center shrink-0">
                                ${icon}
                            </div>
                            <div class="truncate flex-1">
                                <p class="font-medium text-slate-200 text-[11px] truncate">${item.title}</p>
                                <p class="text-[9px] text-slate-400 font-mono">${formatBytes(item.fileSize)}</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            ${statusBadge}
                        </div>
                    </div>
                `;
            });

            $('#uploadWidgetItemsContainer').html(itemsHtml);
        },

        onQueueCompleted: function () {
            const completed = this.queue.filter(i => i.status === 'completed').length;
            const failed = this.queue.filter(i => i.status === 'failed').length;

            if (completed > 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true
                    }).fire({
                        icon: 'success',
                        title: `Berhasil mengunggah ${completed} berkas media di latar belakang.`
                    });
                }
            }

            window.dispatchEvent(new CustomEvent('backgroundUploadCompleted', {
                detail: {
                    completed: completed,
                    failed: failed,
                    queue: this.queue
                }
            }));
        }
    };

    window.BackgroundUploader = BackgroundUploader;

})(jQuery);
