/**
 * PouletExpress - Application Logic & Dynamic Interactions (script.js)
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- Initializations ---
    initTheme();
    initTabs();
    initOrderFilter();
    initLiveTrackerSimulator();
    initRegistrationForm();
    initNewOrderModal();
    initMobileDrawer();
});

/* ==========================================================================
   1. Theme Toggle (Dark / Light)
   ========================================================================== */
function initTheme() {
    const themeBtn = document.getElementById('theme-toggle-btn');
    const savedTheme = localStorage.getItem('px_theme');

    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        if (themeBtn) updateThemeIcon(true);
    } else {
        document.documentElement.classList.remove('dark');
        if (themeBtn) updateThemeIcon(false);
    }

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('px_theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
            showToast(isDark ? 'Mode sombre activé 🌙' : 'Mode clair activé ☀️', 'info');
        });
    }
}

function updateThemeIcon(isDark) {
    const themeBtn = document.getElementById('theme-toggle-btn');
    if (!themeBtn) return;
    const icon = themeBtn.querySelector('.material-symbols-outlined');
    if (icon) {
        icon.textContent = isDark ? 'light_mode' : 'dark_mode';
    }
}

/* ==========================================================================
   2. Tab Navigation System (Single Page Application)
   ========================================================================== */
function initTabs() {
    const navLinks = document.querySelectorAll('[data-tab-target]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetTabId = link.getAttribute('data-tab-target');
            if (targetTabId) {
                switchTab(targetTabId);
            }
        });
    });

    // Check hash on load
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(`tab-${hash}`)) {
        switchTab(hash);
    }
}

function switchTab(tabId) {
    // Hide all tab contents
    const allTabs = document.querySelectorAll('.tab-content');
    allTabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Deactivate all nav links
    const allNavLinks = document.querySelectorAll('[data-tab-target]');
    allNavLinks.forEach(link => {
        link.classList.remove('active');
    });

    // Show target tab content
    const targetTab = document.getElementById(`tab-${tabId}`);
    if (targetTab) {
        targetTab.classList.add('active');
        window.location.hash = tabId;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Activate matching nav links
    const matchingLinks = document.querySelectorAll(`[data-tab-target="${tabId}"]`);
    matchingLinks.forEach(link => {
        link.classList.add('active');
    });

    // Close mobile drawer if open
    closeMobileDrawer();
}

/* ==========================================================================
   3. Order Filtering in Dashboard
   ========================================================================== */
function initOrderFilter() {
    const filterBtns = document.querySelectorAll('[data-order-filter]');
    const orderRows = document.querySelectorAll('#orders-table-body tr');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Active style
            filterBtns.forEach(b => {
                b.classList.remove('bg-emerald-700', 'text-white', 'btn-primary');
                b.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
            });
            btn.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
            btn.classList.add('bg-emerald-700', 'text-white');

            const filterValue = btn.getAttribute('data-order-filter');

            orderRows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (filterValue === 'tous' || rowStatus === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
}

/* ==========================================================================
   4. Live Tracker Simulator (Suivi de commande)
   ========================================================================== */
let currentStepIndex = 2; // Default step: En livraison (index 2)
const stepsData = [
    { name: 'Commande Passée', time: '08:45 AM', icon: 'check_circle' },
    { name: 'Préparation', time: '09:15 AM', icon: 'inventory' },
    { name: 'En livraison', time: '14:20 PM', icon: 'local_shipping' },
    { name: 'Livré', time: '15:30 PM', icon: 'home' }
];

function initLiveTrackerSimulator() {
    const simBtn = document.getElementById('simulate-step-btn');
    const callBtn = document.getElementById('call-driver-btn');

    if (simBtn) {
        simBtn.addEventListener('click', () => {
            currentStepIndex = (currentStepIndex + 1) % stepsData.length;
            updateTrackerUI(currentStepIndex);
            showToast(`Étape mise à jour : ${stepsData[currentStepIndex].name}`, 'info');
        });
    }

    if (callBtn) {
        callBtn.addEventListener('click', () => {
            showToast('📞 Appel en cours avec le livreur Koffi Konan (+225 07 00 11 22)...', 'success');
        });
    }

    // Start live countdown timer simulation
    initCountdownTimer();
}

function updateTrackerUI(stepIdx) {
    const stepsElements = document.querySelectorAll('.stepper-step');
    const progressBar = document.getElementById('stepper-progress-bar');
    const statusPill = document.getElementById('tracking-status-pill');

    if (progressBar) {
        const percentage = (stepIdx / (stepsData.length - 1)) * 100;
        progressBar.style.width = `${percentage}%`;
    }

    if (statusPill) {
        statusPill.textContent = stepsData[stepIdx].name;
    }

    stepsElements.forEach((stepEl, idx) => {
        const circle = stepEl.querySelector('.step-circle');
        const text = stepEl.querySelector('.step-text');

        if (idx <= stepIdx) {
            stepEl.classList.remove('opacity-40');
            if (circle) {
                circle.className = 'step-circle w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md ring-4 ring-emerald-100 dark:ring-emerald-900/50';
            }
            if (text) text.className = 'step-text font-bold text-emerald-700 dark:text-emerald-400 text-xs mt-2 text-center';
        } else {
            stepEl.classList.add('opacity-40');
            if (circle) {
                circle.className = 'step-circle w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 flex items-center justify-center';
            }
            if (text) text.className = 'step-text font-semibold text-slate-400 text-xs mt-2 text-center';
        }
    });
}

function initCountdownTimer() {
    let minutesLeft = 12;
    let secondsLeft = 45;
    const timerDisplay = document.getElementById('live-eta-timer');

    if (!timerDisplay) return;

    setInterval(() => {
        if (secondsLeft > 0) {
            secondsLeft--;
        } else {
            if (minutesLeft > 0) {
                minutesLeft--;
                secondsLeft = 59;
            }
        }
        const formattedMin = minutesLeft < 10 ? `0${minutesLeft}` : minutesLeft;
        const formattedSec = secondsLeft < 10 ? `0${secondsLeft}` : secondsLeft;
        timerDisplay.textContent = `${formattedMin}:${formattedSec} min`;
    }, 1000);
}

/* ==========================================================================
   5. Registration Form Logic
   ========================================================================== */
function initRegistrationForm() {
    const regForm = document.getElementById('registration-form');
    const togglePassBtn = document.getElementById('toggle-password-btn');
    const passInput = document.getElementById('reg-password');

    if (togglePassBtn && passInput) {
        togglePassBtn.addEventListener('click', () => {
            const isPass = passInput.type === 'password';
            passInput.type = isPass ? 'text' : 'password';
            togglePassBtn.querySelector('.material-symbols-outlined').textContent = isPass ? 'visibility_off' : 'visibility';
        });
    }

    // if (regForm) {
    //     regForm.addEventListener('submit', (e) => {
    //         e.preventDefault();
    //         const fullName = document.getElementById('reg-fullname').value || 'Jean Koffi';
            
    //         // Update profile info across site
    //         const userNameElements = document.querySelectorAll('.user-fullname');
    //         userNameElements.forEach(el => el.textContent = fullName);

    //         showToast(`Bienvenue chez PouletExpress, ${fullName} ! Votre compte a été créé avec succès. 🎉`, 'success');

    //         // Automatically switch to Dashboard after 1.5 seconds
    //         setTimeout(() => {
    //             switchTab('dashboard');
    //         }, 1200);
    //     });
    // }
}

/* ==========================================================================
   6. New Order Modal Logic
   ========================================================================== */
function initNewOrderModal() {
    const modal = document.getElementById('new-order-modal');
    const openBtns = document.querySelectorAll('[data-open-modal="new-order"]');
    const closeBtns = document.querySelectorAll('[data-close-modal]');
    const orderForm = document.getElementById('new-order-form');

    if (!modal) return;

    openBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('active');
        });
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    if (orderForm) {
        orderForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const product = document.getElementById('order-product-select').value;
            const qty = document.getElementById('order-qty').value;
            const priceUnit = product.includes('chair') ? 3500 : 2500;
            const total = priceUnit * qty;

            const orderId = `#PX-${Math.floor(1000 + Math.random() * 9000)}`;
            const todayStr = "Aujourd'hui";

            // Add row to table
            const tbody = document.getElementById('orders-table-body');
            if (tbody) {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-800';
                tr.setAttribute('data-status', 'en-cours');
                tr.innerHTML = `
                    <td class="p-4 font-mono text-sm font-semibold text-emerald-700 dark:text-emerald-400">${orderId}</td>
                    <td class="p-4 text-sm">${todayStr}</td>
                    <td class="p-4 text-sm font-bold">${total.toLocaleString('fr-FR')} FCFA</td>
                    <td class="p-4">
                        <span class="badge badge-warning">
                            <span class="pulse-dot"></span> En cours
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <button class="p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg text-slate-500 hover:text-emerald-600 transition-colors" onclick="switchTab('suivi')">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </td>
                `;
                tbody.insertBefore(tr, tbody.firstChild);
            }

            modal.classList.remove('active');
            showToast(`Commande ${orderId} validée avec succès (${total.toLocaleString('fr-FR')} FCFA) ! 🚀`, 'success');
        });
    }
}

/* ==========================================================================
   7. Mobile Drawer Navigation
   ========================================================================== */
function initMobileDrawer() {
    const drawerBtn = document.getElementById('mobile-drawer-toggle');
    const drawer = document.getElementById('mobile-drawer');

    if (drawerBtn && drawer) {
        drawerBtn.addEventListener('click', () => {
            drawer.classList.toggle('hidden');
        });
    }
}

function closeMobileDrawer() {
    const drawer = document.getElementById('mobile-drawer');
    if (drawer && !drawer.classList.contains('hidden')) {
        drawer.classList.add('hidden');
    }
}

/* ==========================================================================
   8. Toast Notification Utility
   ========================================================================== */
function showToast(message, type = 'info') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const iconName = type === 'success' ? 'check_circle' : 'info';
    const iconColor = type === 'success' ? 'text-emerald-600' : 'text-sky-600';

    toast.innerHTML = `
        <span class="material-symbols-outlined ${iconColor}">${iconName}</span>
        <span class="text-sm font-semibold">${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
