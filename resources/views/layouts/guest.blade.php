<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="{{asset('assets/img/LogoSLMatahari.png')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center text-white">
                    <small><i class="fa fa-phone-alt mr-2"></i>+012 345 6789</small>
                    <small class="px-3">|</small>
                    <small><i class="fa fa-envelope mr-2"></i>info@example.com</small>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-white px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-white pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
            <a href="{{route('home')}}" class="navbar-brand ml-lg-3">

                <h3 class="m-0 text-uppercase text-primary"><img src="{{asset('assets/img/LogoSLMatahari.png')}}" alt="" width="40"> Matahari</h3>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mx-auto py-0">
                    <a href="{{ route('home') }}" class="nav-item nav-link active">Beranda</a>
                    <a href="{{ route('about') }}" class="nav-item nav-link">Tentang Kami</a>
                    <a href="{{ route('gallery') }}" class="nav-item nav-link">Galeri</a>
                    <a href="{{route('contact')}}" class="nav-item nav-link">Kontak</a>
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary py-2 px-4">Masuk</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
    {{ $slot }}


    <!-- Footer Start -->
    <div class="container-fluid position-relative overlay-top bg-dark text-white-50 py-5" style="margin-top: 90px;">
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <h1 class="mt-n2 text-uppercase text-white">
                            <img src="{{ asset('assets/img/LogoSLMatahari.png') }}" width="40" alt="Logo"> Matahari
                        </h1>
                    </a>
                    <p class="m-0">
                        Sekolah Lansia “Matahari” di Desa Sridadi, Kecamatan Sirampog, Kabupaten Brebes didirikan pada 22 Juli 2022 sebagai bentuk kepedulian dari Dinas DP3KB Kabupaten Brebes. Kami mengusung kurikulum 7 Dimensi Lansia Tangguh: spiritual, intelektual, fisik, emosional, sosial kemasyarakatan, profesional & vokasional, dan lingkungan — menjadikan lansia tetap aktif, bahagia, dan berdaya.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-5">
                    <h3 class="text-white mb-4">Kontak Kami</h3>
                    <p><i class="fa fa-map-marker-alt mr-2"></i>Desa Sridadi, Kec. Sirampog, Kab. Brebes, Jawa Tengah</p>
                    <p><i class="fa fa-phone-alt mr-2"></i>+62 812-3456-7890</p>
                    <p><i class="fa fa-envelope mr-2"></i>info@sekolahlansiamatahari.or.id</p>
                    <div class="d-flex justify-content-start mt-4">
                        <a class="text-white mr-4" href="#"><i class="fab fa-2x fa-facebook-f"></i></a>
                        <a class="text-white mr-4" href="#"><i class="fab fa-2x fa-instagram"></i></a>
                        <a class="text-white mr-4" href="#"><i class="fab fa-2x fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <h3 class="text-white mb-4">Dimensi Tangguh</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <span class="text-white-50 mb-2"><i class="fa fa-angle-right mr-2"></i>Spiritual</span>
                        <span class="text-white-50 mb-2"><i class="fa fa-angle-right mr-2"></i>Intelektual</span>
                        <span class="text-white-50 mb-2"><i class="fa fa-angle-right mr-2"></i>Fisik</span>
                        <span class="text-white-50 mb-2"><i class="fa fa-angle-right mr-2"></i>Emosional</span>
                        <span class="text-white-50 mb-2"><i class="fa fa-angle-right mr-2"></i>Sosial Kemasyarakatan</span>
                        <span class="text-white-50 mb-2"><i class="fa fa-angle-right mr-2"></i>Profesional & Vokasional</span>
                        <span class="text-white-50"><i class="fa fa-angle-right mr-2"></i>Lingkungan</span>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <h3 class="text-white mb-4">Tautan Cepat</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-white-50 mb-2" href="{{ route('about') }}"><i class="fa fa-angle-right mr-2"></i>Tentang Kami</a>
                        <a class="text-white-50 mb-2" href="{{ route('gallery') }}"><i class="fa fa-angle-right mr-2"></i>Galeri</a>
                        <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>FAQ</a>
                        <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Bantuan</a>
                        <a class="text-white-50" href="{{route('contact')}}"><i class="fa fa-angle-right mr-2"></i>Kontak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white-50 border-top py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                    <p class="m-0">&copy; 2025 Sekolah Lansia Matahari. Seluruh hak cipta dilindungi.</p>
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <p class="m-0">Dikembangkan oleh <a class="text-white" href="https://galihbagaskoro.my.id">Galih Bagaskoro</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->



    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary rounded-0 btn-lg-square back-to-top"><i
            class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>