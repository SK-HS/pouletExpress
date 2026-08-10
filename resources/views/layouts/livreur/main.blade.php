<!DOCTYPE html>
<html lang="fr" class="light">
@include('layouts.livreur._head')
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

     @include('layouts.livreur._menu')
  <!-- ==================== DESKTOP SIDEBAR NAVIGATION ==================== -->
@include('layouts.livreur._header')

  <!-- ==================== TOP HEADER BAR ==================== -->
 
   @yield('content')
  <!-- ==================== MAIN CONTENT AREA ==================== -->

@include('layouts.livreur._footer')
  <!-- ==================== MOBILE BOTTOM NAVIGATION BAR ==================== -->
 
@include('layouts.livreur._script')
 
</body>
</html>
