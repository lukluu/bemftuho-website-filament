@extends('layouts.app')

@section('title', config('app.name') .' - '. 'Home Page')
@push('styles')
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .category-title {
        border-left: 4px solid var(--secondary-color);
        padding-left: 10px;
        margin-bottom: 15px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .article-badge {
        background-color: orange;
        color: white;
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 3px;
    }

    .href:hover .title-text {
        text-decoration: underline;
        color: var(--primary-color);
    }

    .article-meta {
        font-size: 0.8rem;
        color: gray;
    }

    .read-more {
        font-size: 0.85rem;
        display: inline-block;
        margin-top: 10px;
        color: #0d6efd;
        text-decoration: none;
    }

    .read-more:hover {
        text-decoration: underline;
    }

    /* Gambar Utama Konsisten */
    .main-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 5px;
    }

    /* Thumbnail Konsisten */
    .small-thumb {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    h6 {
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    p {
        font-size: 0.875rem;
    }

    /* Projects area
================================================== */
    /* Project filter nav */
    .swiper {
        width: 100%;
        padding-top: 50px;
        padding-bottom: 50px;
    }

    .swiper-slide {
        background-position: center;
        background-size: cover;
        width: 400px;
        height: 300px;
        border-radius: 10px;
        overflow: hidden;
    }

    .swiper-container-3d .swiper-slide-shadow-left {
        background-image: linear-gradient(to left, #000, #fff0);
        border-right: 1px solid #000;
        border-radius: 10px;
    }

    .swiper-container-3d .swiper-slide-shadow-right {
        background-image: linear-gradient(to right, #000, #fff0);
        box-shadow: 0 0 0 1px #000;
        border-radius: 10px;
    }

    .swiper-pagination-bullet {
        background: #696969;
        transition: all 0.5s ease 0s;
        border-radius: 8px;
    }

    .swiper-pagination-bullet-active {
        background: #ffc107;
        width: 30px;
    }

    .info {
        position: absolute;
        /*bottom: -1px;*/
        width: calc(100% + 2px);
        height: calc(50% + 2px);
        text-align: center;
        background: linear-gradient(180deg, #fff0 0, #0008 50px), linear-gradient(180deg, #fff0, #0009);
        padding: 15px;
        padding-top: 70px;
        left: calc(-100% - 3px);
        left: 0;
        bottom: calc(-100% - 3px);
        box-sizing: border-box;
        transition: var(--tst);
    }

    .swiper-slide-active .info {
        bottom: 0;
        transition: var(--tst);
    }

    .info span {
        width: 100%;
        display: inline-block;
        box-sizing: border-box;
        color: var(--white-color);
        text-align: center;
        position: relative;
        text-transform: uppercase;
        font-size: 12px;
        /*mix-blend-mode: color-burn;*/
    }

    .info span:hover {
        /*mix-blend-mode: luminosity;*/
        background: #0008;
        filter: invert(1);
    }

    /* .info span:before,
    .info span:after {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        background: #fff8;
        height: 100%;
        max-width: 2em;
    } */



    .animated-bg {
        position: relative;
        z-index: 1;
        background: linear-gradient(-45deg, var(--primary-color), var(--secondary-color), var(--primary-color), var(--secondary-color));
        background-size: 400% 400%;
        animation: gradientMove 15s ease infinite;

        padding: 2rem;
    }

    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .video-wrapper {
        position: relative;
        z-index: 2;
        border-radius: 1rem;
        overflow: hidden;
    }

    .youtube-iframe {
        border: none;
        pointer-events: none;
        /* agar tidak bisa diklik sampai tombol play ditekan */
    }

    .custom-play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        padding: 20px;
        cursor: pointer;
        z-index: 3;
        color: white;
        font-size: 2rem;
        transition: background 0.3s;
    }

    .custom-play-button:hover {
        background-color: rgba(0, 0, 0, 0.9);
    }

    .about-section {
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .about-section::before {
        content: "";
        position: absolute;
        top: 50%;
        /* selalu mulai dari tengah */
        transform: translateY(-25%);
        /* naik sedikit ke atas agar pas setengah video */
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;

        /* background animasi */
        background: linear-gradient(-45deg, var(--primary-color), var(--secondary-color), var(--primary-color));
        background-size: 600% 600%;
        animation: gradientMove 15s ease infinite;
        opacity: 1;
    }

    @keyframes gradientAnimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .pengumuman-section {
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
    }

    .announcement-card {
        position: relative;
        transition: all 0.3s ease-in-out;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .animated-line {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        background: linear-gradient(to right, #0d6efd, #6610f2);
        animation: slideLine 3s linear infinite;
    }

    @keyframes slideLine {
        0% {
            transform: translateX(-100%);
        }

        50% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(100%);
        }
    }

    /* Animasi slide */
    .carousel-item {
        transition: transform 0.6s ease, opacity 0.6s ease-in-out;
    }

    /* Tombol di luar card */
    .custom-carousel-wrapper {
        position: relative;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 45px;
        height: 45px;
        top: 40%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }

    .carousel-control-prev {
        left: 10px;
    }

    .carousel-control-next {
        right: 10px;
    }


    @media (max-width: 768px) {
        /* .carousel-control-prev {
            left: px;
        }

        .carousel-control-next {
            right: -25px;
        } */
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".swiper", {
        effect: "coverflow",
        loop: true,
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "1",
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true
        },
        pagination: {
            el: ".swiper-pagination"
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 1.5
            },
            // when window width is >= 480px
            580: {
                slidesPerView: 2
            },
            // when window width is >= 480px
            767: {
                slidesPerView: 3
            },
            992: {
                slidesPerView: 3.5
            },
            1200: {
                slidesPerView: 4
            },
            1400: {
                slidesPerView: 4.5
            }
        }
    });
</script>
<script>
    let player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            events: {
                'onReady': function(event) {
                    event.target.pauseVideo(); // agar tidak langsung jalan
                }
            }
        });
    }

    function playVideo() {
        document.querySelector('.youtube-iframe').style.pointerEvents = 'auto';
        document.querySelector('.custom-play-button').style.display = 'none';
        player.playVideo();
    }

    // Muat API YouTube
    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    document.body.appendChild(tag);
</script>
@endpush
@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="text-center mb-5 pb-2">
                    <h1 class="text-white text-uppercase">KABINET {{ $kabinet->nama_kabinet }}</h1>
                    <p class="fs-4 fs-lg-3 text-white">BEM FT-UHO <br>Periode {{ $kabinet->periode }}</p>
                    <a href="#section_2" class="btn custom-btn smoothscroll mt-3" style="border: .5px solid #fff;">Start Listening</a>
                </div>
                <div class="owl-carousel owl-theme">
                    <div class="owl-carousel-info-wrap item">
                        @if ($kabinet->logo)
                        <img src="{{ asset('storage/'.$kabinet->logo)}}"
                            class="owl-carousel-image img-fluid" alt="">
                        @else
                        <img src="{{ asset('storage/default/no_image.png') }}"
                            class="owl-carousel-image img-fluid" alt="">
                        @endif
                    </div>
                    @foreach($kabinet->kabinetMahasiswaJabatan as $jabatan)
                    <div class="owl-carousel-info-wrap item">
                        <!-- @if($jabatan->mahasiswa && $jabatan->mahasiswa->foto_mahasiswa)
                        @php
                        $fotoUrl = filter_var($jabatan->mahasiswa->foto_mahasiswa, FILTER_VALIDATE_URL)
                        ? $jabatan->mahasiswa->foto_mahasiswa
                        : asset('storage/' . ltrim($jabatan->mahasiswa->foto_mahasiswa, '/'));
                        @endphp
                        <img src="{{ $fotoUrl }}" class="owl-carousel-image img-fluid" alt="Foto {{ $jabatan->mahasiswa->nama ?? 'Mahasiswa' }}" loading="lazy">
                        @else
                        <img src="{{ asset('storage/default/no_image.png') }}" class="owl-carousel-image img-fluid" alt="Tidak ada foto" loading="lazy">
                        @endif -->
                        <img src="{{ $jabatan->mahasiswa?->foto_mahasiswa ? asset('storage/'.$jabatan->mahasiswa->foto_mahasiswa) : asset('storage/default/no_image.png') }}" class="owl-carousel-image img-fluid" alt="Foto {{ $jabatan->mahasiswa->nama ?? 'Mahasiswa' }}" loading="lazy">
                        <div class="owl-carousel-info px-3 py-2">
                            <p class=" text-white py-1 px-2 fw-bold text-center" style="font-size: 0.75rem; font-family: 'Sono', sans-serif; line-height: 1; background-color:var(--secondary-color); border-radius: var( --border-radius-large);">
                                {{ $jabatan->jabatan->nama_jabatan }}
                            </p>
                            <h5 class="mb-2">
                                {{ $jabatan->mahasiswa->nama }}
                            </h5>
                        </div>

                        <div class="social-share">
                            <ul class="social-icon">
                                @foreach ($jabatan->mahasiswa->sosmedMhs as $sosmedMhs)
                                <li class="social-icon-item">
                                    <a href="https://www.{{ $sosmedMhs->sosmed->name }}.com/{{ $sosmedMhs->link }}/" target="_blank" class="social-icon-link bi-{{ strtolower($sosmedMhs->sosmed->name) }}"></a>
                                </li>
                                @endforeach

                            </ul>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

<section class="about-section text-center text-white py-5 position-relative" id="section_2">
    <div class="container">

        <!-- Video -->
        <div class="video-wrapper position-relative mb-4 rounded overflow-hidden">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/EUwgYXX6BZQ?si=SPTDXIuWQhl80EEA"
                    title="YouTube video player"
                    allowfullscreen></iframe>
            </div>
        </div>


        <!-- Heading -->
        <div class="row justify-content-center">
            <h4 class="fw-bold text-white">Tentang Kami</span></h4>
            <div class="col-lg-6 col-12 text-center">
                <p class=" text-white text-center fs-lg-4">
                    Badan Eksekutif Mahasiswa Fakultas Teknik Universitas Halu Oleo (BEM FT UHO)
                    adalah organisasi mahasiswa yang bertujuan untuk mewadahi aspirasi mahasiswa
                    dan menjalankan program-program yang bermanfaat bagi civitas akademika FT UHO.
                </p>
                <a href="{{ route('profile') }}" class="bg bg-white px-3 py-1 rounded-pill">Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>


    </div>
    <section class="container-fluid mt-5 pb-0 mb-0">
        <hr>
        <h4 class="fw-bold text-white">HMJ/HMPS dan Lembaga Mahasiswa Fakultas Teknik</span></h4>
        <div class="logo-marquee pb-0 mb-0">
            <div class="logo-marquee-content">
                @foreach ($kelembagaans as $kelembagaan)
                <a href="https://www.instagram.com/bemteknikuho" target="_blank" rel="noopener noreferrer">
                    <div class="logo-item">
                        <img src="{{ asset('storage/'.$kelembagaan->logo) }}" alt="Logo 12">
                    </div>
                </a>
                @endforeach
                @foreach ($kelembagaans as $kelembagaan)
                <div class="logo-item">
                    <img src="{{ asset('storage/'.$kelembagaan->logo) }}" alt="Logo 12">
                </div>
                @endforeach
            </div>
        </div>
    </section>

</section>
<section class="pengumuman-section py-5 pb-0">
    <div class="container">
        <div class="custom-carousel-wrapper d-flex justify-content-center">
            <div id="pengumumanCarousel" class="carousel slide w-100"
                data-bs-ride="carousel"
                data-bs-touch="true"
                data-bs-interval="7000">
                <div class="carousel-inner">

                    @foreach ($pengumumanList as $index => $pengumuman)
                    <div class=" mb-4 carousel-item {{ $index === 0 ? 'active' : '' }} text-center">
                        <div class="announcement-card p-4 mb-4 shadow-sm rounded-4 bg-white position-relative overflow-hidden">
                            <div class="badge bg-primary mb-2 px-3 py-1 rounded-pill">📢 Pengumuman Terbaru</div>
                            <h5 class="fw-bold">{{ $pengumuman->title }}</h5>
                            <p class="mb-1 text-muted">
                                <i class="bi bi-calendar-event me-2"></i>Terakhir:
                                {{ \Carbon\Carbon::parse($pengumuman->tanggal)->translatedFormat('d F Y') }}
                            </p>
                            <a href="{{ route('pengumuman.show', $pengumuman) }}" class="mt-2 d-inline-block">Lihat Selengkapnya <i class="bi bi-arrow-right"></i></a>
                            <div class="animated-line"></div>
                        </div>

                    </div>
                    <!-- Animasi garis bawah -->

                    @endforeach
                </div>

                <!-- Tombol Navigasi -->
                <button class="carousel-control-prev" type="button" data-bs-target="#pengumumanCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#pengumumanCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="trending-podcast-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="section-title-wrap mb-5">
                    <h4 class="section-title">Event Terbaru</h4>
                </div>
            </div>
            @foreach ($events as $event)

            <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                <div class="custom-block custom-block-full kabinet-logo">
                    <div class="custom-block-image-wrap">
                        @if ($event->image)
                        <a href="{{ route('event.show', $event) }}">
                            <img src="{{ asset('storage/'.$event->image)}}" class="custom-block-image img-fluid" alt="">
                        </a>
                        @else
                        <a href="{{ route('event.show', $event) }}">
                            <img src="{{ asset('storage/default/no_image.png') }}"
                                class="custom-block-image img-fluid" alt="">
                        </a>
                        @endif
                    </div>

                    <div class="custom-block-info">
                        <h5 class="mb-2">
                            <a href="{{ route('event.show', $event) }}">
                                {{ $event->title }}
                            </a>
                        </h5>
                        <div class="article-meta mb-2">{{ $event->created_at->format('d/m/Y') }}</div>
                        <p class="mb-0">{{ Str::words($event->description, 7, '...') }}</p>

                        <div class="custom-block-bottom mt-3 text-white">
                            <a href="{{ route('event.show', $event) }}" class="text-white m-auto fw-bold btn custom-btn" style="font-family: var(--title-font-family);">Daftar Sekarang <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="social-share d-flex flex-column ms-auto">
                        <span class="badge ms-auto">

                            {{ $event->price == 0 ? 'Gratis' : 'Fee'}}

                        </span>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</section>


<section>
    <div class="animated-bg mt-5 overflow-hidden text-center">
        <h3 class="section-title text-center mx-auto">Galeri Kami</h3>
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-tonin-rocodromo.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-normal-caliz.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-cumbre-totem.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-oscar-raul-hueco-hoces2.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-gallego-cueva-mora.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-chimenea-tortuga.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-blues-ojos-bonitos-tres-coronas.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-capuchon-sarcofago.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-rosario-cueva-mora.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-me-pesa-hasta-el-aire-dehesilla.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>an>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(https://cdn.josetxu.com/img/gp-anonima-tres-coronas.jpg)">
                    <div class="info">
                        <span title="Climber">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, veniam.</span>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div> <!-- /container -->
</section>

<section class="mt-5">
    <div class="container">
        <div class="row">
            <!-- Hukum Islam -->
            @foreach ($categories as $category)
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="category-title h3">{{ $category->name }}</div>

                @php
                $posts = $category->posts->sortByDesc('created_at');
                $firstPost = $posts->first();
                $otherPosts = $posts->skip(1);
                @endphp

                @if ($firstPost)

                <div class="mb-3 custom-block">
                    <a href="{{ route('berita.show', [$category, $firstPost]) }}" class="href" style="display: block;">
                        <img src="{{ asset('storage/'.$firstPost->post_image) }}" class="main-image img-fluid" alt="Artikel Utama" style="width: 100%; object-fit: cover; ">
                        <div class="mt-2 custom-block-info">
                            <span class="badge badge-dark mb-1 float-end">{{ $firstPost->category->name }}</span>
                            <div class="article-meta">{{ $firstPost->user->name }} | {{ $firstPost->created_at->format('d/m/Y') }}</div>
                            <h6 class="title-text">{{ $firstPost->title }}</h6>
                        </div>
                    </a>
                </div>


                @endif

                {{-- Post lainnya --}}
                @foreach ($otherPosts->take(2) as $post)

                <div class="d-flex mb-2 mt-3">
                    <img src="{{ asset('storage/'.$post->post_image) }}" class="me-2 small-thumb " alt="">
                    <div>
                        <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                        <a href="{{ route('berita.show', [$category, $post]) }}" class="href">
                            <h5 class="mb-0 h6 title-text">{{ $post->title }}</h5>
                        </a>

                    </div>
                </div>
                @endforeach

                <a href="{{ route('categoryPost.show', $category) }}" class="read-more">Selengkapnya →</a>
            </div>
            @endforeach

        </div>
    </div>

</section>

@endsection