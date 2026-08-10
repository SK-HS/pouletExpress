<!DOCTYPE html>
<html lang="fr" class="light">

<!--head start-->
@include('layouts._head')
<!--head end-->
<body class="bg-surface text-on-surface pb-24 lg:pb-0">
  <!-- Spinner Start -->

   
    <!-- Spinner End -->
  @include('layouts._header')

      
     @yield('content')


    @include('layouts._footer')
      <!-- Scroll Top -->
 
  <!-- Preloader -->
 
    @include('layouts._script')
    @stack('scripts')
    @include('layouts._menu')
    @include('layouts._script_additionnel')
  <!--script end-->
</body>

</html>
