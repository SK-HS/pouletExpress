document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    const overlay = document.getElementById('mobile-overlay');

    function toggleSidebar() {
        if(sidebar) {
            sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('translate-x-0');
        }
        if(overlay) {
            overlay.classList.toggle('hidden');
        }
    }

    if(openBtn) openBtn.addEventListener('click', toggleSidebar);
    if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
    if(overlay) overlay.addEventListener('click', toggleSidebar);

    // Simple interactivity for navigation
    // document.querySelectorAll('nav a').forEach(link => {
    //     link.addEventListener('click', function(e) {
    //         if(this.innerText.trim() === 'Déconnexion') return;
    //         // e.preventDefault(); // removed to allow normal navigation for the new multi-page structure
            
    //         // On mobile, close sidebar after clicking a link
    //         if (window.innerWidth < 1024) toggleSidebar();
            
    //         /* Logic to handle active states is moved to server-side or a simple router.
    //            For this static implementation, we might just let normal navigation handle it,
    //            or keep it for visual demo purposes if preventing default.
    //         */
    //     });
    // });

        // Cible UNIQUEMENT les liens à l'intérieur du grand menu gauche
    document.querySelectorAll('#sidebar nav a').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.innerText.trim() === 'Déconnexion') return;
            
            // On mobile, close sidebar after clicking a link
            if (window.innerWidth < 1024) toggleSidebar();
        });
    });

    

    // Hover effect for table rows
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', () => row.classList.add('scale-[1.002]'));
        row.addEventListener('mouseleave', () => row.classList.remove('scale-[1.002]'));
    });

    // Dark Mode Toggle Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    
    // Initial check
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
                localStorage.setItem('theme', 'dark');
            }
        });
    }
});
