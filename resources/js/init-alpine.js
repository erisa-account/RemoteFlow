import Alpine from 'alpinejs'

// Expose your data() factory globally
window.data = function() {
    function getThemeFromLocalStorage() {
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
        }
        return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    function setThemeToLocalStorage(val) {
        window.localStorage.setItem('dark', val)
    }

    return {
        dark: getThemeFromLocalStorage(),
        toggleTheme() {
            this.dark = !this.dark
            setThemeToLocalStorage(this.dark)
        },

        // ─── SIDE MENU ─────────────────────────────────────────────────────────
        isSideMenuOpen: false,
        toggleSideMenu() { 
            this.isSideMenuOpen = !this.isSideMenuOpen
        },
        closeSideMenu() {
            this.isSideMenuOpen = false
        },

        // ─── NOTIFICATIONS MENU ───────────────────────────────────────────────
        isNotificationsMenuOpen: false,
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
        },
        closeNotificationsMenu() {
            this.isNotificationsMenuOpen = false
        },

        // ─── PROFILE MENU ──────────────────────────────────────────────────────
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false
        },

        // ─── PAGES MENU ───────────────────────────────────────────────────────
        isPagesMenuOpen: false,
        togglePagesMenu() {
            this.isPagesMenuOpen = !this.isPagesMenuOpen
        },

        // ─── MODAL ─────────────────────────────────────────────────────────────
        isModalOpen: false,
        trapCleanup: null,
        openModal() {
            this.isModalOpen = true
            this.trapCleanup = focusTrap(document.querySelector('#modal'))
        },
        closeModal() {
            this.isModalOpen = false
            this.trapCleanup && this.trapCleanup()
        },
    }
}

Alpine.start()