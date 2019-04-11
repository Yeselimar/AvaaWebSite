<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="keywords" content="Bootstrap, Parallax, Template, Registration, Landing">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <meta name="author" content="Grayrids">
      <title>AVAA - @yield('title')</title>
      <!-- Bootstrap CSS -->
      <link href="{{asset('info_sitio/img/favicon.png')}}" type="image/x-icon" rel="shortcut icon" />
      <link rel="stylesheet" href="{{asset('info_sitio/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/font-awesome.min.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/materialdesignicons.min.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/line-icons.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/owl.carousel.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/owl.theme.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/nivo-lightbox.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/magnific-popup.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/slicknav.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/animate.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/main.css')}}">
      <link rel="stylesheet" href="{{asset('info_sitio/css/responsive.css')}}">
      <!-- datatable css -->
      <link href="{{asset('css/datatable.css')}}" rel="stylesheet"/>
      <link href="{{asset('plugins/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet"/>
      <link href="{{asset('plugins/owlcarousel/assets/owl.theme.default.min.css')}}" rel="stylesheet"/>

      <link href="{{asset('plugins/toastr/toastr.min.css')}}" rel="stylesheet" />
      <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet">
       @yield('personalcss')
   </head>
   <body>
      <!-- Header Section Start -->
      <header id="hero-area" data-stellar-background-ratio="0.5">
         <!-- Navbar Start -->
         @include('web_site.layouts.nav')
      </header>

      @yield('content')

      @include('web_site.layouts.footer')



      <!-- jQuery first, then Tether, then Bootstrap JS. -->
      <script src="{{asset('info_sitio/js/jquery-min.js')}}"></script>
      <script src="{{asset('info_sitio/js/popper.min.js')}}"></script>
      <script src="{{asset('info_sitio/js/bootstrap.min.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.mixitup.js')}}"></script>
      <script src="{{asset('info_sitio/js/nivo-lightbox.js')}}"></script>
      <script src="{{asset('info_sitio/js/owl.carousel.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.stellar.min.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.nav.js')}}"></script>
      <script src="{{asset('info_sitio/js/scrolling-nav.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.easing.min.js')}}"></script>
     {{-- <script src="{{asset('info_sitio/js/smoothscroll.js')}}"></script>  --}}
      <script src="{{asset('info_sitio/js/jquery.slicknav.js')}}"></script>
      <script src="{{asset('info_sitio/js/wow.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.vide.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.counterup.min.js')}}"></script>
      <script src="{{asset('info_sitio/js/jquery.magnific-popup.min.js')}}"></script>
      <script src="{{asset('info_sitio/js/waypoints.min.js')}}"></script>
      <!--<script src="{{asset('info_sitio/js/form-validator.min.js')}}"></script>-->
      <!--<script src="{{asset('info_sitio/js/contact-form-script.js')}}"></script>-->
      <script src="{{asset('info_sitio/js/main.js')}}"></script>

      <script src="{{asset('/plugins/matchheight/jquery.matchHeight.js')}}"></script> <!-- Matcheight -->
      <script src="{{asset('/plugins/owlcarousel/owl.carousel.min.js')}}"></script> <!-- OwlCarousel -->


      <!-- DataTable javaScript -->
      <!--<script src="{{asset('js/datatable.js')}}"></script>-->


      <script src="{{asset('/plugins/toastr/toastr.min.js')}}"></script>

      <script src="{{asset('js/vue.js')}}"></script><!-- agregado para hacer funcionar el formulario de contacto-->
      <script src="{{asset('js/axios.js')}}"></script>

      @yield('personaljs')
   </body>
</html>
