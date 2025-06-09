@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="text-center mb-5 pb-2">
                    <h1 class="text-white text-uppercase">KABINET {{ $kabinet->nama_kabinet }}</h1>
                    <p class="fs-4 fs-lg-3 text-white">BEM FT-UHO <br>Periode {{ $kabinet->periode }}</p>
                    <a href="#section_2" class="btn custom-btn smoothscroll mt-3" style="border: .5px solid #fff;">Start listening</a>
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
                        @if ($jabatan->mahasiswa->hasMedia('mahasiswa'))
                        <img src="{{ $jabatan->mahasiswa->getFirstMediaUrl('mahasiswa', 'thumb') }}"
                            class="owl-carousel-image img-fluid" alt="">
                        @else
                        <img src="{{ asset('storage/default/no_image.png') }}"
                            class="owl-carousel-image img-fluid" alt="">
                        @endif
                        <div class="owl-carousel-info">
                            <h5 class="mb-2">
                                {{ $jabatan->mahasiswa->nama }}
                            </h5>
                            <p class=" text-white py-1 px-2 fw-bold" style="font-size: 0.75rem; font-family: 'Sono', sans-serif; line-height: 1; background-color:var(--secondary-color); border-radius: var( --border-radius-large);">
                                {{ $jabatan->jabatan->nama_jabatan }}
                            </p>
                        </div>

                        <div class="social-share">
                            <ul class="social-icon">
                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-twitter"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-facebook"></a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

<section class="video-about mb-0 section-padding" style="margin-bottom: 0 !important;" id="section_2">
    <div class="container" style="margin-bottom: 0 !important;">
        <div class="row mb-0" style="margin-bottom: 0 !important;">
            <div class="col-12 mb-4 mb-lg-0">
                <div class="custom-block d-flex flex-column flex-lg-row" style="margin-bottom: 0 !important;">
                    <div class="col-lg-6 col-md-12 col-12 mb-4 mb-lg-0">
                        <div class="ratio ratio-16x9"> <!-- Perubahan utama di sini -->
                            <iframe src="https://www.youtube.com/embed/EUwgYXX6BZQ?si=SPTDXIuWQhl80EEA"
                                title="YouTube video player"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 ps-lg-4">
                        <div class="video-description">
                            <h3 class="description-title">Tentang Kami</h3>
                            <p class="description-text">
                                Badan Eksekutif Mahasiswa Fakultas Teknik Universitas Halu Oleo (BEM FT UHO)
                                adalah organisasi mahasiswa yang bertujuan untuk mewadahi aspirasi mahasiswa
                                dan menjalankan program-program yang bermanfaat bagi civitas akademika FT UHO.
                            </p>
                            <a href="{{ route('about') }}" class="btn custom-btn mt-3">Selengkapnya -></a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<section class="latest-podcast-section section-padding pb-0 mt-0" id="section_10">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-12">
                <div class="section-title-wrap mb-5">
                    <h4 class="section-title">Pengumuman Terbaru</h4>
                </div>
            </div>
            @foreach ($pengumumans->sortByDesc('created_at')->take(2) as $pengumuman)
            <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block d-flex">
                    <div class="custom-block-icon-wrap">
                        <a href="detail-page.html" class="custom-block-image-wrap">
                            @if ($pengumuman->image)
                            <img src="{{ asset('storage/'.$pengumuman->image)}}"
                                class="custom-block-image img-fluid" alt="">
                            @else
                            <img src="{{ asset('storage/default/no_image.png') }}"
                                class="custom-block-image img-fluid" alt="">
                            @endif
                        </a>
                    </div>
                    <div class="custom-block-info">
                        <div class="custom-block-top d-flex mb-1">
                            <small class="me-4">
                                <i class="bi-clock-fill custom-icon"></i>
                                {{ $pengumuman->created_at->diffForHumans() }}
                            </small>
                        </div>

                        <h5 class="mb-2">
                            <a href="detail-page.html">
                                {{ $pengumuman->title }}
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<section class="topics-section section-padding pb-0" id="section_3">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="section-title-wrap mb-5">
                    <h4 class="section-title">Topik Artikel</h4>
                </div>
            </div>
            @foreach ($categories as $category)
            <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block custom-block-overlay">
                    <a href="detail-page.html" class="custom-block-image-wrap">
                        @if ($category->image)
                        <img src="{{ asset('storage/'.$category->image)}}"
                            class="custom-block-image img-fluid" alt="">
                        @else
                        <img src="{{ asset('storage/default/no_image.png') }}"
                            class="custom-block-image img-fluid" alt="">
                        @endif
                    </a>
                    <div class="custom-block-info custom-block-overlay-info">
                        <h5 class="mb-1">
                            <a href="listing-page.html">
                                {{ $category->name }}
                            </a>
                        </h5>
                        <p class="badge mb-0">Jumlah Post {{ $category->posts->count() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
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
                        <a href="detail-page.html">
                            <img src="{{ asset('storage/'.$event->image)}}" class="custom-block-image img-fluid" alt="">
                        </a>
                        @else
                        <a href="detail-page.html">
                            <img src="{{ asset('storage/default/no_image.png') }}"
                                class="custom-block-image img-fluid" alt="">
                        </a>
                        @endif
                    </div>

                    <div class="custom-block-info">
                        <h5 class="mb-2">
                            <a href="detail-page.html">
                                {{ $event->title }}
                            </a>
                        </h5>
                        <p class="mb-0">{{ Str::words($event->description, 7, '...') }}</p>

                        <div class="custom-block-bottom mt-3 text-white">
                            <a href="detail-page.html" class="text-white m-auto fw-bold btn custom-btn" style="font-family: var(--title-font-family);">Daftar Sekarang <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="social-share d-flex flex-column ms-auto">
                        <span class="badge ms-auto">
                            @if ($event->is_free == 1)
                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            @else
                            {{ $event->is_free ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</section>


<section class="container-fluid py-5">
    <!-- Logo Swiper Container -->
    <div class="logo-marquee">
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
@endsection