/* PouletExpress Driver Space - Multi-Page Application Logic */

// Execute immediately to prevent flash of wrong theme
(function() {
  if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
})();

document.addEventListener('DOMContentLoaded', () => {
  highlightActiveNavLink();
  initLoginForm();
  initStatusToggle();
  initOrdersTabs();
  initThemeToggle();
  initModals();
});

// Highlight Active Nav Link based on Current Page URL
function highlightActiveNavLink() {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';

  document.querySelectorAll('.nav-link, .mobile-nav-item').forEach(link => {
    const href = link.getAttribute('href');
    if (!href) return;

    if (href === currentPath || (currentPath === '' && href === 'index.html') || (currentPath === 'index.html' && href === 'tableau-de-bord.html')) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}

// Login Form Handling (Redirects to tableau-de-bord.html)
function initLoginForm() {
  const loginForm = document.getElementById('loginForm');
  if (!loginForm) return;

  window.togglePassword = function() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('passIcon');
    if (!passwordInput) return;
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.textContent = 'visibility_off';
    } else {
      passwordInput.type = 'password';
      icon.textContent = 'visibility';
    }
  };

  // loginForm.addEventListener('submit', (e) => {
  //   e.preventDefault();
  //   const btn = loginForm.querySelector('button[type="submit"]');
  //   const originalText = btn.innerHTML;

  //   btn.disabled = true;
  //   btn.innerHTML = `<span class="animate-spin material-symbols-outlined">sync</span> Connexion en cours...`;

  //   setTimeout(() => {
  //     btn.classList.replace('bg-emerald-700', 'bg-emerald-600');
  //     btn.innerHTML = `<span class="material-symbols-outlined">check_circle</span> Connecté !`;
      
  //     setTimeout(() => {
  //       window.location.href = 'connexion/livreur';
  //     }, 800);
  //   }, 1000);
  // });
}

// Online / Offline Driver Status Toggle
// function initStatusToggle() {
//   let isOnline = localStorage.getItem('driver_online') !== 'false';

//   function updateStatusUI() {
//     document.querySelectorAll('.driver-status-badge').forEach(badge => {
//       if (isOnline) {
//         badge.className = 'driver-status-badge flex items-center gap-2 px-3 py-1 rounded-full badge-online text-xs font-bold transition-all';
//         badge.innerHTML = `<span class="pulse-dot"></span><span>En ligne</span>`;
//       } else {
//         badge.className = 'driver-status-badge flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300 text-xs font-bold transition-all';
//         badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span><span>Hors ligne</span>`;
//       }
//     });
//   }

//   updateStatusUI();

//   window.toggleDriverStatus = function() {
//     isOnline = !isOnline;
//     localStorage.setItem('driver_online', isOnline);
//     updateStatusUI();
//     showToast(isOnline ? 'Vous êtes maintenant EN LIGNE' : 'Vous êtes maintenant HORS LIGNE');
//   };
// }




// Orders Filter & Tabs for commandes.html
function initOrdersTabs() {
  window.filterOrders = function(tabElement, statusFilter) {
    const tabs = document.querySelectorAll('.order-tab-btn');
    tabs.forEach(tab => {
      tab.classList.remove('border-emerald-600', 'text-emerald-700', 'font-bold');
      tab.classList.add('border-transparent', 'text-slate-500');
    });

    tabElement.classList.remove('border-transparent', 'text-slate-500');
    tabElement.classList.add('border-emerald-600', 'text-emerald-700', 'font-bold');

    const rows = document.querySelectorAll('.order-row');
    rows.forEach(row => {
      const rowStatus = row.getAttribute('data-status');
      if (statusFilter === 'all' || rowStatus === statusFilter) {
        row.style.display = 'flex';
      } else {
        row.style.display = 'none';
      }
    });
  };

  const searchInput = document.getElementById('orderSearchInput');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('.order-row');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(term)) {
          row.style.display = 'flex';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
}

// Modal System
function initModals() {
  const modal = document.getElementById('orderDetailModal');
  const closeModalBtn = document.getElementById('closeModalBtn');

  window.openOrderDetail = function(orderId, farmer, client, items, total, status) {
    if (!modal) return;
    
    document.getElementById('modalOrderId').textContent = orderId;
    document.getElementById('modalFarmerName').textContent = farmer;
    document.getElementById('modalClientName').textContent = client;
    document.getElementById('modalItems').textContent = items;
    document.getElementById('modalTotal').textContent = total;
    
    const statusBadge = document.getElementById('modalStatus');
    statusBadge.textContent = status;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  };

  window.closeModal = function() {
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  };

  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', closeModal);
  }

  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });
  }
}

// Dark / Light Theme Toggle (Fixed & Bulletproof)
function initThemeToggle() {
  window.toggleTheme = function() {
    const isDarkCurrently = document.documentElement.classList.contains('dark');
    if (isDarkCurrently) {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('theme', 'light');
      showToast('Mode Clair activé');
    } else {
      document.documentElement.classList.add('dark');
      localStorage.setItem('theme', 'dark');
      showToast('Mode Sombre activé');
    }
  };

  document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
    btn.onclick = function(e) {
      e.preventDefault();
      toggleTheme();
    };
  });
}

// Toast Notification Helper
function showToast(message) {
  let toastContainer = document.getElementById('toastContainer');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'toastContainer';
    toastContainer.className = 'fixed bottom-20 md:bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none';
    document.body.appendChild(toastContainer);
  }

  const toast = document.createElement('div');
  toast.className = 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 px-4 py-3 rounded-xl shadow-2xl font-semibold text-sm flex items-center gap-3 transition-all';
  toast.innerHTML = `<span class="material-symbols-outlined text-emerald-400 dark:text-emerald-600">info</span><span>${message}</span>`;

  toastContainer.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(10px)';
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}
