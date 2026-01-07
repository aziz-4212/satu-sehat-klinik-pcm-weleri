/**
 * Satu Sehat - Modern Admin Theme JavaScript
 * Enhanced functionality and interactions
 */

class SatuSehatTheme {
    constructor() {
        this.init();
    }

    init() {
        this.initAnimations();
        this.initNotifications();
        this.initTooltips();
        this.initProgressBars();
        this.initQuickActions();
        this.bindEvents();
    }

    initAnimations() {
        // Add fade-in animation to elements as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("fade-in");
                }
            });
        });

        document
            .querySelectorAll(".card, .info-box, .widget-stat")
            .forEach((el) => {
                observer.observe(el);
            });
    }

    initNotifications() {
        // Configure toastr defaults
        if (typeof toastr !== "undefined") {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                positionClass: "toast-top-right",
                preventDuplicates: false,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                timeOut: "5000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
            };
        }
    }

    initTooltips() {
        // Initialize Bootstrap tooltips
        if (typeof $ !== "undefined") {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }

    initProgressBars() {
        // Animate progress bars
        document.querySelectorAll(".progress-bar").forEach((bar) => {
            const width = bar.style.width;
            bar.style.width = "0%";
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    }

    initQuickActions() {
        // Previously added loading states to buttons here.
        // Disabled on request: do not alter button behavior on click to avoid blocking submit.
    }

    bindEvents() {
        // Sidebar toggle enhancement
        document.addEventListener("click", (e) => {
            if (e.target.matches('[data-widget="pushmenu"]')) {
                document.body.classList.toggle("sidebar-collapse");
                localStorage.setItem(
                    "sidebar-collapsed",
                    document.body.classList.contains("sidebar-collapse")
                );
            }
        });

        // Remember sidebar state
        const sidebarCollapsed = localStorage.getItem("sidebar-collapsed");
        if (sidebarCollapsed === "true") {
            document.body.classList.add("sidebar-collapse");
        }

        // Card expand/collapse
        document.addEventListener("click", (e) => {
            if (e.target.matches('[data-card-widget="collapse"]')) {
                const card = e.target.closest(".card");
                const cardBody = card.querySelector(".card-body");
                const icon = e.target.querySelector("i");

                if (cardBody.style.display === "none") {
                    cardBody.style.display = "block";
                    icon.className = "fas fa-minus";
                } else {
                    cardBody.style.display = "none";
                    icon.className = "fas fa-plus";
                }
            }
        });

        // Full screen toggle
        document.addEventListener("click", (e) => {
            if (
                e.target.matches('[data-widget="fullscreen"]') ||
                e.target.closest('[data-widget="fullscreen"]')
            ) {
                this.toggleFullscreen();
            }
        });
    }

    addLoadingState(button) {
        // Disabled on request: no automatic loading state for buttons
        return;
    }

    showNotification(type, title, message) {
        if (typeof toastr !== "undefined") {
            toastr[type](message, title);
        }
    }

    updateStatistic(elementId, newValue, animate = true) {
        const element = document.getElementById(elementId);
        if (!element) return;

        if (animate) {
            this.animateNumber(
                element,
                parseInt(element.textContent),
                newValue,
                1000
            );
        } else {
            element.textContent = newValue;
        }
    }

    animateNumber(element, start, end, duration) {
        const range = end - start;
        const startTime = Date.now();

        const timer = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const progress = elapsed / duration;

            if (progress >= 1) {
                element.textContent = end.toLocaleString();
                clearInterval(timer);
            } else {
                const current = Math.floor(start + range * progress);
                element.textContent = current.toLocaleString();
            }
        }, 16);
    }

    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch((err) => {
                console.log(
                    `Error attempting to enable full-screen: ${err.message}`
                );
            });
        } else {
            document.exitFullscreen();
        }
    }

    // Theme color management
    setThemeColor(primaryColor, secondaryColor) {
        document.documentElement.style.setProperty(
            "--primary-color",
            primaryColor
        );
        document.documentElement.style.setProperty(
            "--secondary-color",
            secondaryColor
        );
        localStorage.setItem(
            "theme-colors",
            JSON.stringify({ primaryColor, secondaryColor })
        );
    }

    // Data sync functionality
    async syncWithSatuSehat() {
        try {
            this.showNotification(
                "info",
                "Sinkronisasi",
                "Memulai sinkronisasi data dengan Satu Sehat..."
            );

            // Simulate API call
            await new Promise((resolve) => setTimeout(resolve, 3000));

            this.showNotification(
                "success",
                "Berhasil",
                "Data berhasil disinkronkan dengan Satu Sehat!"
            );
            this.updateSyncStatus("success");
        } catch (error) {
            this.showNotification(
                "error",
                "Gagal",
                "Terjadi kesalahan saat sinkronisasi data."
            );
            this.updateSyncStatus("error");
        }
    }

    updateSyncStatus(status) {
        const statusElements = document.querySelectorAll(".sync-status");
        statusElements.forEach((el) => {
            if (status === "success") {
                el.innerHTML =
                    '<i class="fas fa-check-circle text-success mr-1"></i> Tersinkron';
                el.className = "badge badge-success sync-status";
            } else if (status === "error") {
                el.innerHTML =
                    '<i class="fas fa-times-circle text-danger mr-1"></i> Error';
                el.className = "badge badge-danger sync-status";
            }
        });
    }

    // Real-time updates
    startRealTimeUpdates() {
        setInterval(() => {
            this.updateDashboardStats();
        }, 30000); // Update every 30 seconds
    }

    async updateDashboardStats() {
        try {
            // Simulate API call to get updated stats
            const response = await fetch("/api/dashboard-stats");
            const data = await response.json();

            // Update UI elements
            if (data.totalPatients) {
                this.updateStatistic("total-patients", data.totalPatients);
            }
            if (data.syncedData) {
                this.updateStatistic("synced-data", data.syncedData);
            }
            if (data.activeDoctors) {
                this.updateStatistic("active-doctors", data.activeDoctors);
            }
        } catch (error) {
            console.log("Failed to update dashboard stats:", error);
        }
    }

    // Utility functions
    formatNumber(number) {
        return new Intl.NumberFormat("id-ID").format(number);
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
        }).format(amount);
    }

    formatDate(date) {
        return new Intl.DateTimeFormat("id-ID", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        }).format(new Date(date));
    }
}

// Global functions for backward compatibility
window.syncData = function () {
    satuSehatTheme.syncWithSatuSehat();
};

window.generateReport = function () {
    satuSehatTheme.showNotification(
        "info",
        "Laporan",
        "Memproses permintaan laporan..."
    );
    setTimeout(() => {
        satuSehatTheme.showNotification(
            "success",
            "Selesai",
            "Laporan berhasil dibuat dan akan dikirim ke email Anda."
        );
    }, 2000);
};

window.showLoading = function () {
    document.getElementById("loadingOverlay")?.classList.remove("hidden");
};

window.hideLoading = function () {
    document.getElementById("loadingOverlay")?.classList.add("hidden");
};

// Initialize theme when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    window.satuSehatTheme = new SatuSehatTheme();

    // Start real-time updates if on dashboard
    if (
        window.location.pathname === "/" ||
        window.location.pathname.includes("dashboard")
    ) {
        satuSehatTheme.startRealTimeUpdates();
    }

    // Load saved theme colors
    const savedColors = localStorage.getItem("theme-colors");
    if (savedColors) {
        const colors = JSON.parse(savedColors);
        satuSehatTheme.setThemeColor(
            colors.primaryColor,
            colors.secondaryColor
        );
    }
});

// Service Worker registration for PWA capabilities (optional)
if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker
            .register("/sw.js")
            .then((registration) => {
                console.log("SW registered: ", registration);
            })
            .catch((registrationError) => {
                console.log("SW registration failed: ", registrationError);
            });
    });
}
