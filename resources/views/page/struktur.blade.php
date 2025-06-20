@extends('layouts.app')
@section('title', config('app.name') .' - '.'Strukutur Organisasi')
@push('styles')
<style>
    .event-banner {
        position: relative;
        background: url('{{ asset("ft-uho.png") }}') no-repeat center center;
        background-size: cover;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        color: white;
        /* agar teks tetap terlihat */
    }

    .event-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        /* warna overlay gelap, 0.5 = 50% opacity */
        z-index: -1;
    }

    .slider-image-wrapper {
        position: relative;
        overflow: hidden;
    }

    .slider-image-wrapper img {
        transition: transform 0.5s ease;
    }

    .slider-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .overlay {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0));

        opacity: 0;
        transition: opacity 0.5s ease;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 20px;
    }

    .slider-image-wrapper:hover .overlay {
        opacity: 1;
    }

    .overlay-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
        z-index: 2;
    }

    .overlay-text {
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        text-align: center;
        animation: fadeInUp 0.6s ease forwards;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
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
</style>

@endpush
@section('content')
<section class="bg-nav"></section>
<section class="struktur">
    <header class="event-banner">
        <h2 class="text-white">Struktur Organisasi</h2>
    </header>
    <div class="container my-5">

        <div class="row gx-5">

            <div class="col-lg-8 mb-5">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <img src="{{ asset('storage/'.$kabinet->struktur_organisasi) }}" class="card-img" alt="">
                </div>
            </div>
            <!-- block kategori berita -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 mb-4">
                    <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <!-- Slide 1 -->
                            @foreach ($events as $event)

                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="slider-image-wrapper border rounded">
                                    <img src="{{ asset('storage/' . $event->image) }}" class="d-block w-100" alt="Slide {{ $event->title }}">
                                    <div class="overlay-badge badge bg-primary"> Event <i class="bi bi-fire text-danger ms-1"></i> </div>
                                    <div class="overlay">
                                        <a href="{{ route('event.show', $event) }}">

                                            <div class="overlay-text px-4">{{ $event->title }}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Tombol Prev & Next -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#customCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <ul class="list-group list-group-flush">
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
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection