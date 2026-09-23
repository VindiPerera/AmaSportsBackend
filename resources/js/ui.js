/**
 * AmaX UI - Lightweight zero-dependency interactive helpers
 * Handles dropdowns, modals, tabs, mobile drawer, and dismissible alerts.
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Dropdown Menus (data-dropdown-toggle="id")
    document.querySelectorAll('[data-dropdown-toggle]').forEach(trigger => {
        const targetId = trigger.getAttribute('data-dropdown-toggle');
        const target = document.getElementById(targetId);
        if (!target) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !target.classList.contains('hidden');
            // Close all other dropdowns
            document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                if (menu !== target) menu.classList.add('hidden');
            });
            target.classList.toggle('hidden', isOpen);
            trigger.setAttribute('aria-expanded', !isOpen);
        });
    });

    // Close dropdowns on outside click or ESC
    document.addEventListener('click', () => {
        document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
            menu.classList.add('hidden');
        });
        document.querySelectorAll('[data-dropdown-toggle]').forEach(t => {
            t.setAttribute('aria-expanded', 'false');
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-dropdown-menu]').forEach(menu => menu.classList.add('hidden'));
            document.querySelectorAll('[data-modal]').forEach(modal => modal.classList.add('hidden'));
            document.body.classList.remove('overflow-hidden');
        }
    });

    // 2. Modals (data-modal-open="id" & data-modal-close="id")
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal-open');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal-close');
            const modal = document.getElementById(modalId) || btn.closest('[data-modal]');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });

    // 3. Tab Switching (data-tab-group="group" & data-tab-target="paneId")
    document.querySelectorAll('[data-tab-group]').forEach(tabGroup => {
        const groupName = tabGroup.getAttribute('data-tab-group');
        const tabBtns = tabGroup.querySelectorAll('[data-tab-target]');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-tab-target');
                const panes = document.querySelectorAll(`[data-tab-pane="${groupName}"]`);
                
                // Toggle active styles on buttons
                tabBtns.forEach(b => {
                    b.classList.remove('tab-active', 'border-brand-red', 'text-brand-red', 'bg-white', 'shadow-xs');
                    b.classList.add('text-slate-500', 'hover:text-slate-900');
                    b.setAttribute('aria-selected', 'false');
                });

                btn.classList.add('tab-active', 'border-brand-red', 'text-brand-red', 'bg-white', 'shadow-xs');
                btn.classList.remove('text-slate-500');
                btn.setAttribute('aria-selected', 'true');

                // Switch panes
                panes.forEach(pane => {
                    if (pane.id === targetId) {
                        pane.classList.remove('hidden');
                    } else {
                        pane.classList.add('hidden');
                    }
                });
            });
        });
    });

    // 4. Mobile Menu Navigation (data-mobile-menu-toggle="id")
    document.querySelectorAll('[data-mobile-menu-toggle]').forEach(trigger => {
        const menuId = trigger.getAttribute('data-mobile-menu-toggle');
        const menu = document.getElementById(menuId);
        if (!menu) return;

        trigger.addEventListener('click', () => {
            const isClosed = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            trigger.setAttribute('aria-expanded', isClosed);
            
            // Toggle hamburger icon if SVG paths exist
            const iconOpen = trigger.querySelector('.icon-menu-open');
            const iconClose = trigger.querySelector('.icon-menu-close');
            if (iconOpen && iconClose) {
                iconOpen.classList.toggle('hidden', isClosed);
                iconClose.classList.toggle('hidden', !isClosed);
            }
        });
    });

    // 5. Flash Alert Dismissal
    document.querySelectorAll('[data-alert-dismiss]').forEach(btn => {
        btn.addEventListener('click', () => {
            const alert = btn.closest('[data-alert]');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-4px)';
                setTimeout(() => alert.remove(), 200);
            }
        });
    });
});
