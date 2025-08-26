<x-guest-layout>
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid page-header position-relative overlay-bottom" style="margin-bottom: 90px;">
        <div class="container text-center py-5">
            <h1 class="text-white display-3">Detail Berita / Informasi</h1>
            <div class="d-inline-flex text-white mb-5">
                <p class="m-0 text-uppercase"><a class="text-white" href="">Beranda</a></p>
                <i class="fa fa-angle-double-right pt-1 px-3"></i>
                <p class="m-0 text-uppercase">Detail Berita / Informasi</p>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-5">
                        <div class="section-title position-relative mb-5">
                            <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Detail Berita Informasi</h6>
                            <h1 class="display-4">{{$berita->judul}}</h1>
                        </div>
                        <img class="img-fluid rounded w-100 mb-4" src="{{ asset('storage/image/' . $berita->image) }}" alt="Image">
{{ strip_tags($berita->description) }}
                    </div>

                     <h2 class="mb-3">Berita Lainnya</h2>
                    <div class="owl-carousel related-carousel position-relative" style="padding: 0 30px;">
                         @foreach($beritaTerbaru as $berita)
                        <a class="courses-list-item position-relative d-block overflow-hidden mb-2" href="detail.html">
                            <img class="img-fluid" src="{{ asset('storage/image/' . $berita->image) }}" alt="">
                            <div class="courses-text">
                                <h4 class="text-center text-white px-3">{{$berita->judul}}</h4>
                                <div class="border-top w-100 mt-3">
                                    <div class="d-flex justify-content-between p-4">
                                        <span class="text-white"><i class="fa fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
               </div>
            </div>
        </div>
    </div>

</x-guest-layout>
