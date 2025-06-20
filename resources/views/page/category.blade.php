@extends('layouts.app')
@section('title', config('app.name') .' - '.'Category Berita')
@push('styles')
<!-- Swiper JS CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* section more post */
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

    /* post */
    .carousel-caption h5 {
        font-size: .8rem !important;
        font-weight: bold;
        color: #fff;
    }

    @media (max-width: 768px) {
        .carousel-caption {
            font-size: 0.8rem;
            bottom: 10px;
        }
    }

    .carousel-inner img {
        border-radius: 10px;
        object-fit: cover;
        height: 180px;

    }

    .category-item {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .category-item:hover {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    }

    .category-item:hover a {
        color: #fff !important;
    }

    .category-item:hover .category-badge {
        background-color: #fff !important;
        color: var(--dark-color) !important;
    }


    /* btn */
    .btn-custom {
        color: var(--white-color) !important;
    }

    .btn-custom:hover {
        color: #0d6efd !important;
    }

    /* hero */
    .post-highlight-carousel {
        width: 100%;
        height: 80vh;
        min-height: 500px;
        position: relative;
    }

    .post-highlight-carousel .swiper-slide {
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .post-slide {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .post-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .post-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.3));
        display: flex;
        justify-content: center;
        /* Tambahkan ini untuk horizontal center */
        align-items: center;
        /* Ubah dari flex-end ke center untuk vertikal center */
        padding: 3rem;
        color: white;
    }

    .post-content {
        text-align: center;
        /* Ubah dari left ke center */
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        /* Ubah dari padding-bottom saja */
    }


    .post-meta {
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .post-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
        transition: all 0.3s ease-in-out;
    }

    .post-excerpt {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
        max-width: 800px;
    }

    .post-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: white !important;
        background: rgba(255, 255, 255, 0.2);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 1.5rem !important;
        font-weight: bold;
    }

    .swiper-pagination-bullet {
        background: white !important;
        opacity: 0.5 !important;
    }

    .swiper-pagination-bullet-active {
        opacity: 1 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .post-highlight-carousel {
            height: 70vh;
        }

        .post-title {
            font-size: 2rem;
        }

        .post-overlay {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .post-highlight-carousel {
            height: 60vh;
            min-height: 400px;
        }

        .post-title {
            font-size: 1.5rem;
            /* Ukuran lebih kecil untuk tablet */
            margin-bottom: 0.8rem;
        }

        .post-excerpt {
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
        }

        .post-overlay {
            padding: 1.5rem;
        }

        .post-meta {
            font-size: 0.8rem;
            gap: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .post-highlight-carousel {
            height: 50vh;
            min-height: 350px;
        }

        .post-title {
            font-size: 1.2rem;
            /* Ukuran lebih kecil untuk mobile */
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .post-excerpt {
            font-size: 0.8rem;
            display: -webkit-box;
            /* Batasi jumlah baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .post-overlay {
            padding: 1rem;
        }

        .post-meta {
            font-size: 0.7rem;
            gap: 0.5rem;
        }

        .post-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .post-actions .btn {
            width: 100%;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush
@push('scripts')

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Swiper
        const swiper = new Swiper('.post-highlight-carousel', {
            // Optional parameters
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            speed: 1000,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // And if we need scrollbar
            scrollbar: {
                el: '.swiper-scrollbar',
            },

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                // when window width is >= 768px
                768: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                // when window width is >= 992px
                992: {
                    slidesPerView: 1,
                    spaceBetween: 0
                }
            }
        });

        // Pause autoplay when hovering over the carousel
        const carousel = document.querySelector('.post-highlight-carousel');
        carousel.addEventListener('mouseenter', function() {
            swiper.autoplay.stop();
        });

        carousel.addEventListener('mouseleave', function() {
            swiper.autoplay.start();
        });
    });
</script>
@endpush
@section('content')
<section class="bg-nav"></section>
<section>
    <div class="container-fluid px-0">
        <div class="swiper post-highlight-carousel">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="post-slide">
                        <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" class="img-fluid">
                        <div class="post-overlay">
                            <div class="post-content px-sm-3">
                                <h5 class="post-title text-white text-sm bg bg-purple-800">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($posts as $berita)
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="post-slide">
                        <img src="{{ asset('storage/'.$berita->post_image) }}" alt="Post Image" class="img-fluid">
                        <div class="post-overlay">
                            <div class="post-content px-sm-3">
                                <div class="post-meta text-center">
                                    <div class="badge bg-primary bg-gradient rounded-pill">{{ $berita->category->name }}</div>
                                    <span class="post-author">{{ $berita->user->name }}</span>
                                    <span class="post-date">{{ $berita->created_at->format('d M Y') }}</span>
                                </div>
                                <h5 class="post-title text-white text-sm">{{ $berita->title }}</h5>
                                <div class="post-actions">
                                    <a class="stretched-link text-white" href="{{ route('berita.show', [$berita->category, $berita]) }}">
                                        <span class=" post-author">Read more</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Add Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-title-wrap mb-5">
            <h4 class="section-title">{{ $category->name }} Lain</h4>
        </div>
        <div class="row gx-0">
            <div class="col-lg-8">
                @foreach ($postss as $post)
                <div class="mb-4 d-flex align-items-start flex-row">
                    <img src="{{ asset('storage/'.$post->post_image) }}" alt="event" class="me-3 img-fluid" style="width: 100px; height: auto; border-radius: 5px; flex-shrink: 0;">
                    <div style="flex: 1;">
                        <div class="small text-muted">{{ $post->created_at->format('d/m/Y') }}</div>
                        <span class="badge bg-dark bg-gradient rounded-pill mb-2">{{ $post->category->name }}</span>
                        <div>
                            <a class="" href="{{ route('berita.show', [$post->category, $post]) }}">
                                <h5 class="mb-0">{{ $post->title }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                {{ $postss->links() }}
            </div>
            <!-- block kategori berita -->
            <div class="col-lg-4">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <ul class="list-group list-group-flush mb-4">
                            <h5 class="card-title mb-3">Kategori Berita</h5>
                            @foreach ($categories as $category)
                            <li class="list-group-item category-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('categoryPost.show', $category) }}" class="text-decoration-none flex-grow-1 text-secondary">
                                    {{ $category->name }}
                                </a>
                                <span class="badge bg-secondary rounded-pill category-badge">{{ $category->posts()->count() }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <!-- Carousel Gambar -->
                        <div>
                            <h5 class="card-title mb-3">Trending Lain</h5>
                            <div id="categoryCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-inner">
                                    {{-- Slide pertama --}}
                                    <div class="carousel-item active position-relative">
                                        {{-- Badge di sudut kanan atas --}}
                                        <span class="badge bg-primary position-absolute top-0 end-0 m-2 z-3">Pengumuman</span>

                                        {{-- Gambar --}}
                                        <img src="{{ asset('storage/' . $pengumuman->image) }}"
                                            class="d-block w-100"
                                            alt="{{ $pengumuman->title }}"
                                            style="object-fit: cover; height: 200px;">

                                        {{-- Caption --}}
                                        <div class="carousel-caption d-block bg-dark bg-opacity-50 rounded p-2">
                                            <h5 class="mb-0">{{ $pengumuman->title }}</h5>
                                        </div>
                                    </div>

                                    {{-- Slide berikutnya --}}
                                    @foreach ($events->take(2) as $event)
                                    <div class="carousel-item position-relative">
                                        <span class="badge bg-success position-absolute top-0 end-0 m-2 z-3">Event</span>

                                        <img src="{{ asset('storage/' . $event->image) }}"
                                            class="d-block w-100"
                                            alt="{{ $event->title }}"
                                            style="object-fit: cover; height: 200px;">

                                        <div class="carousel-caption d-block bg-dark bg-opacity-50 rounded p-2">
                                            <h5 class="mb-0">{{ $event->title }}</h5>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</section>
<section class="py-5">
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
                        <img src="{{ asset('storage/'.$firstPost->post_image) }}" class="main-image" alt="Artikel Utama">
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
                    <img src="{{ asset('storage/'.$post->post_image) }}" class="me-2 small-thumb" alt="">
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