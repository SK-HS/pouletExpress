<!DOCTYPE html>
<html lang="en">

<!--head start-->
@include('layouts._head')
<!--head end-->
<body class="index-page">
  <!-- Spinner Start -->

   
    <!-- Spinner End -->
  @include('layouts._header')

      
     @yield('content')


    @include('layouts._footer')
      <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>
    @include('layouts._script')
    @include('layouts._script_additionnel')
  <!--script end-->
</body>

</html>
