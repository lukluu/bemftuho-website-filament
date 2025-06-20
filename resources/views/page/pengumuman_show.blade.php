@extends('layouts.app')
@section('title', config('app.name') .' - '. $pengumuman->title)
@push('styles')
<style>
    .post-content {
        font-size: 1rem;
        line-height: 1.7;
        color: #333;
    }

    .post-content h1,
    .post-content h2,
    .post-content h3 {
        font-weight: bold;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .post-content p {
        margin-bottom: 1rem;
        font-size: 1rem;

    }

    .post-content ul,
    .post-content ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .post-content a {
        color: #3490dc;
        text-decoration: underline;
    }

    .post-content img {
        max-width: 100%;
        margin: 0 auto;
        display: block;
        height: auto;
    }

    .post-content figcaption {
        display: none;
    }

    .post-content blockquote {
        border-left: 4px solid #ccc;
        padding-left: 1rem;
        font-style: italic;
        color: #555;
        margin: 1rem 0;
    }

    /* sdfrsd */
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

    @media (max-width: 767.98px) {
        .custom-text {
            font-size: 14px !important;
            font-weight: 600 !important;
        }
    }

    @media (max-width: 767.98px) {
        .responsive-container {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: var(--bs-gutter-x, 1rem);
            padding-right: var(--bs-gutter-x, 1rem);
        }
    }

    @media (min-width: 768px) {
        .responsive-container {
            max-width: 960px;
            /* atau sesuai ukuran container md */
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

@endpush
@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


@endpush
@section('content')
<section class="bg-nav"></section>
<section class="">
    <div class="container px-5 my-5">
        <div class="row gx-5">
            <div class="col-12">
                <!-- Post content-->
                <article class="mb-0 text-center">
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-1">{{ $pengumuman->title }}</h1>
                        <!-- Post meta content-->
                        <div class="text-muted fst-italic mb-2">
                            {{ $pengumuman->created_at->translatedFormat('l, d F Y') }}
                        </div>
                        <!-- Post categories-->
                        <a class="badge bg-primary bg-gradient rounded-pill mb-2" href="#!">Pengumuman <i class="bi bi-megaphone"></i></a>
                    </header>
                    <figure class="mb-2"><img class="img-fluid rounded" src="{{ asset('storage/'.$pengumuman->image) }}" alt="..." /></figure>
                    <div class="d-flex mb-3 mt-3 justify-content-center flex-column align-items-center">
                        <p class="text-muted fw-semibold mb-0 pt-0">Bagikan:</p>
                        <x-share :url="request()->fullUrl()" />
                    </div>
                </article>

                <div class="post-content mb-5 mt-5">
                    {!! $pengumuman->content !!}
                </div>
                <span class="fw-semibold mt-2 h2">Bagikan: </span>
                <x-share :url="request()->fullUrl()" />
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-title-wrap mb-5">
            <h4 class="section-title">Pengumuman Lain</h4>
        </div>
        <div class="row gx-0">
            <div class="col-lg-8">
                @foreach ($pengumumans as $pengumuman)
                <div class="mb-4 d-flex align-items-start flex-row">
                    <img src="{{ asset('storage/'.$pengumuman->image) }}" alt="event" class="me-3 img-fluid" style="width: 100px; height: auto; border-radius: 5px; flex-shrink: 0;">
                    <div style="flex: 1;">
                        <div class="small text-muted">{{ $pengumuman->created_at->format('d/m/Y') }}</div>
                        <span class="badge bg-dark bg-gradient rounded-pill">Pengumuman</span>

                        <div>
                            <a class="" href="{{ route('pengumuman.show', $pengumuman) }}">
                                <h5 class="mb-0">{{ $pengumuman->title }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                {{ $pengumumans->links() }}
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
                                    @foreach ($events->take(2) as $event)
                                    <div class="carousel-item position-relative {{ $loop->first ? 'active' : '' }}">
                                        <span class="badge bg-success position-absolute top-0 end-0 m-2 z-3">Event</span>
                                        <a href="{{ route('event.show', $event) }}" class="d-block w-100">
                                            <img src="{{ asset('storage/' . $event->image) }}"
                                                class="d-block w-100"
                                                alt="{{ $event->title }}"
                                                style="object-fit: cover; height: 200px;">
                                        </a>
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
                        <a href="detail-page.html" class="href">
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