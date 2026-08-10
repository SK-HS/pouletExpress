<!DOCTYPE html>
<html lang="fr" class="light">
@include('layouts.client._head')
<body class="bg-slate-50 dark:bg-[#0b1320] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col font-sans transition-colors duration-300">

    <!-- ==========================================
         TOP NAVBAR (Shared Header)
         ========================================== -->
          @include('layouts.client._header')

    <!-- ==========================================
         MAIN CONTENT (Page Mon Profil & Inscription)
         ========================================== -->
         @yield('content')

    <!-- ==========================================
         FOOTER (Shared Footer)
         ========================================== -->
 
            @include('layouts.client._footer')
            
            @include('layouts.client._menu')
            @include('layouts.client._script')

            @stack('scripts')
   
   
</body>
</html>
