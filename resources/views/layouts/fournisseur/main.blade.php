<!DOCTYPE html>
<html lang="fr" class="light">
@include('layouts.fournisseur._head')
<body class="bg-background font-body-md text-on-surface">
    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity" id="mobile-overlay"></div>
    <!-- ==========================================
         TOP NAVBAR (Shared Header)
         ========================================== -->
          @include('layouts.fournisseur._header')
           @include('layouts.fournisseur._menu')
    <!-- ==========================================
         MAIN CONTENT (Page Mon Profil & Inscription)
         ========================================== -->
         @yield('content')

    <!-- ==========================================
         FOOTER (Shared Footer)
         ========================================== -->
 
            @include('layouts.fournisseur._footer')
            
           
            @include('layouts.fournisseur._script')

            @stack('scripts')
   
   
</body>
</html>
