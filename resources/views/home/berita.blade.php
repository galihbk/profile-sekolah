<x-guest-layout>
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid page-header position-relative overlay-bottom" style="margin-bottom: 90px;">
        <div class="container text-center py-5">
            <h1 class="text-white display-3">Berita atau Informasi</h1>
            <div class="d-inline-flex text-white mb-5">
                <p class="m-0 text-uppercase"><a class="text-white" href="">Beranda</a></p>
                <i class="fa fa-angle-double-right pt-1 px-3"></i>
                <p class="m-0 text-uppercase">News</p>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">

     
    <div class="row">
        @foreach($beritaTerbaru as $berita)
        <div class="col-lg-4 col-md-6 pb-4">
            <a class="courses-list-item position-relative d-block overflow-hidden mb-2" href="{{route('berita.show', $berita->id)}}">
                <img class="img-fluid" src="{{ asset('storage/image/' . $berita->image) }}" alt="">
                <div class="courses-text">
                    <h4 class="text-center text-white px-3">{{ $berita->judul }}</h4>
                    <div class="border-top w-100 mt-3">
                        <div class="d-flex justify-content-between p-4">
                            <span class="text-white">
    <i class="fa fa-calendar mr-2"></i>
    {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y H:i') }}
</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
         @endforeach
    </div>
   </div>
    </div>

</x-guest-layout>
