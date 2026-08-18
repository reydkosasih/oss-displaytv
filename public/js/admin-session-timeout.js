/**
 * Admin Session Inactivity Timeout Manager
 * FSI Display TV Management System
 *
 * Features:
 * - Detects user idle inactivity (mouse, keyboard, scroll, touch)
 * - Multi-tab synchronization via localStorage
 * - SweetAlert2 countdown modal before session expires
 * - Keep-alive ping to backend on session renewal
 * - Automatic redirect to logout on expiration
 */
(function (window, document, $) {
    'use strict';

    const AdminSessionTimeout = {
        // Configurations
        config: {
            idleDuration: 15 * 60 * 1000,     // 15 minutes total idle limit
            warningDuration: 60 * 1000,      // 60 seconds warning countdown
            throttleInterval: 5 * 1000,      // Throttle user activity writes (5s)
            checkInterval: 1000,             // Internal timer tick (1s)
            storageKey: 'tv_admin_last_activity_ts',
            keepAliveUrl: '/admin/keep-alive',
            logoutUrl: '/auth/logout?reason=timeout',
            manualLogoutUrl: '/auth/logout'
        },

        // State variables
        state: {
            lastActivityTime: Date.now(),
            lastThrottledWrite: 0,
            checkTimer: null,
            countdownTimer: null,
            isWarningOpen: false,
            isLoggingOut: false
        },

        /**
         * Initialize the session timeout manager
         */
        init: function (customOptions) {
            if (customOptions) {
                $.extend(this.config, customOptions);
            }

            // Sync with current time or existing localStorage time if valid
            const storedTime = parseInt(localStorage.getItem(this.config.storageKey), 10);
            const now = Date.now();

            if (storedTime && !isNaN(storedTime) && (now - storedTime) < this.config.idleDuration) {
                this.state.lastActivityTime = storedTime;
            } else {
                this.state.lastActivityTime = now;
                localStorage.setItem(this.config.storageKey, now.toString());
            }

            this.bindEvents();
            this.startMonitoring();
        },

        /**
         * Bind DOM and cross-tab storage events
         */
        bindEvents: function () {
            const self = this;
            const events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'];

            events.forEach(function (eventName) {
                window.addEventListener(eventName, function () {
                    self.handleUserActivity();
                }, { passive: true });
            });

            // Cross-tab synchronization via localStorage 'storage' event
            window.addEventListener('storage', function (e) {
                if (e.key === self.config.storageKey && e.newValue) {
                    const remoteTime = parseInt(e.newValue, 10);
                    if (!isNaN(remoteTime)) {
                        self.state.lastActivityTime = remoteTime;

                        // If warning modal is open on this tab, close it because user is active in another tab
                        if (self.state.isWarningOpen) {
                            self.dismissWarningModal(false);
                        }
                    }
                }
            });
        },

        /**
         * Throttled user activity handler
         */
        handleUserActivity: function () {
            // If currently showing warning modal, don't silently reset without user clicking modal
            if (this.state.isWarningOpen || this.state.isLoggingOut) {
                return;
            }

            const now = Date.now();
            this.state.lastActivityTime = now;

            if (now - this.state.lastThrottledWrite > this.config.throttleInterval) {
                this.state.lastThrottledWrite = now;
                try {
                    localStorage.setItem(this.config.storageKey, now.toString());
                } catch (err) {
                    // Ignore storage write issues (e.g. private browsing storage limits)
                }
            }
        },

        /**
         * Start periodic timer checking
         */
        startMonitoring: function () {
            const self = this;
            if (this.state.checkTimer) {
                clearInterval(this.state.checkTimer);
            }

            this.state.checkTimer = setInterval(function () {
                self.checkIdleState();
            }, this.config.checkInterval);
        },

        /**
         * Check current elapsed idle time against thresholds
         */
        checkIdleState: function () {
            if (this.state.isLoggingOut) return;

            const now = Date.now();
            const elapsed = now - this.state.lastActivityTime;
            const warningThreshold = this.config.idleDuration - this.config.warningDuration;

            if (elapsed >= this.config.idleDuration) {
                // Time completely expired
                this.performLogout();
            } else if (elapsed >= warningThreshold) {
                // Within warning countdown window
                if (!this.state.isWarningOpen) {
                    const remainingMs = this.config.idleDuration - elapsed;
                    this.showWarningModal(Math.max(1, Math.ceil(remainingMs / 1000)));
                }
            } else {
                // Active / within safe duration
                if (this.state.isWarningOpen) {
                    this.dismissWarningModal(false);
                }
            }
        },

        /**
         * Show SweetAlert2 countdown modal
         */
        showWarningModal: function (initialSeconds) {
            const self = this;
            this.state.isWarningOpen = true;

            let secondsLeft = initialSeconds;

            if (typeof Swal === 'undefined') {
                return;
            }

            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: '<span class="text-slate-800 dark:text-slate-100 text-lg font-bold">Sesi Anda Akan Berakhir!</span>',
                html: `
                    <div class="space-y-3 text-left py-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                            Tidak ada aktivitas terdeteksi. Untuk keamanan akun Anda, sesi akan otomatis keluar dalam:
                        </p>
                        <div class="flex items-center justify-center p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400">
                            <i class="fa-solid fa-clock-rotate-left mr-2 text-base"></i>
                            <span class="text-base font-extrabold" id="swalTimeoutCountdown">${secondsLeft} detik</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 text-center">
                            Klik <b>"Tetap Login"</b> untuk melanjutkan sesi Anda.
                        </p>
                    </div>
                `,
                icon: 'warning',
                iconColor: '#f59e0b',
                background: isDark ? '#0f172a' : '#ffffff',
                color: isDark ? '#f8fafc' : '#0f172a',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-shield-halved mr-1.5"></i> Tetap Login',
                cancelButtonText: '<i class="fa-solid fa-right-from-bracket mr-1.5"></i> Logout Sekarang',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#e11d48',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl',
                    confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 shadow-md',
                    cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5'
                },
                didOpen: () => {
                    const countdownEl = document.getElementById('swalTimeoutCountdown');
                    
                    if (self.state.countdownTimer) {
                        clearInterval(self.state.countdownTimer);
                    }

                    self.state.countdownTimer = setInterval(() => {
                        const now = Date.now();
                        const currentElapsed = now - self.state.lastActivityTime;
                        const currentRemaining = Math.max(0, Math.ceil((self.config.idleDuration - currentElapsed) / 1000));

                        if (countdownEl) {
                            countdownEl.textContent = `${currentRemaining} detik`;
                        }

                        if (currentRemaining <= 0) {
                            clearInterval(self.state.countdownTimer);
                            self.performLogout();
                        }
                    }, 500);
                },
                willClose: () => {
                    if (self.state.countdownTimer) {
                        clearInterval(self.state.countdownTimer);
                        self.state.countdownTimer = null;
                    }
                    self.state.isWarningOpen = false;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    self.extendSession();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = self.config.manualLogoutUrl;
                }
            });
        },

        /**
         * Dismiss the warning modal programmatically
         */
        dismissWarningModal: function (andPing) {
            if (typeof Swal !== 'undefined' && this.state.isWarningOpen) {
                Swal.close();
            }
            this.state.isWarningOpen = false;
            if (this.state.countdownTimer) {
                clearInterval(this.state.countdownTimer);
                this.state.countdownTimer = null;
            }

            if (andPing) {
                this.extendSession();
            }
        },

        /**
         * Send ping/keep-alive request to server and reset local timers
         */
        extendSession: function () {
            const self = this;
            const now = Date.now();

            self.state.lastActivityTime = now;
            self.state.lastThrottledWrite = now;

            try {
                localStorage.setItem(self.config.storageKey, now.toString());
            } catch (e) {}

            // Send AJAX keep-alive ping to backend
            $.ajax({
                url: self.config.keepAliveUrl,
                method: 'GET',
                cache: false,
                dataType: 'json'
            }).done(function () {
                if (typeof showToast === 'function') {
                    showToast('success', 'Sesi login berhasil diperpanjang.');
                }
            }).fail(function (xhr) {
                // If already unauthenticated at server level
                if (xhr.status === 401) {
                    self.performLogout();
                }
            });
        },

        /**
         * Trigger auto-logout due to inactivity
         */
        performLogout: function () {
            if (this.state.isLoggingOut) return;
            this.state.isLoggingOut = true;

            if (this.state.checkTimer) clearInterval(this.state.checkTimer);
            if (this.state.countdownTimer) clearInterval(this.state.countdownTimer);

            try {
                localStorage.removeItem(this.config.storageKey);
            } catch (e) {}

            if (typeof Swal !== 'undefined') {
                Swal.close();
            }

            window.location.href = this.config.logoutUrl;
        }
    };

    // Expose to window
    window.AdminSessionTimeout = AdminSessionTimeout;

})(window, document, jQuery);
