@extends('layouts.app')

@section('title', config('app.name') .' - '.'Event')
@push('styles')
<style>
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
<section class="py-5 event section-padding">
    <div class="container">
        <div class="card border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="row gx-0">
                    <div class="col-lg-4 col-xl-5">
                        @php
                        $bgUrl = asset('storage/' . $eventFirst->image);
                        @endphp
                        <div class="bg-featured-blog" style="background-image: url('{{ $bgUrl }}') !important;  height: 100%;
  width: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 15rem;"></div>
                    </div>
                    <div class="col-lg-8 col-xl-7 py-lg-5">
                        <div class="p-4 p-md-5">
                            <div class="badge bg-primary bg-gradient rounded-pill mb-2">Event Terbaru</div>
                            <div class="badge bg-primary bg-gradient rounded-pill mb-2">{{ $eventFirst->category->name }}</div>
                            @if ($eventFirst->is_free == 1)
                            <div class="badge bg-dark bg-gradient rounded-pill mb-2"> {{ $eventFirst->price == 0 ? 'Gratis' : 'Rp ' . number_format($eventFirst->price, 0, ',', '.') }}</div>
                            @endif
                            <div>
                                <h3>{{ $eventFirst->title }}</h3>
                            </div>
                            <p>{{ Str::limit(strip_tags($eventFirst->description), 110, '...') }}</p>
                            <a class="stretched-link text-decoration-none" href="{{ route('event.show', $eventFirst) }}">
                                Read more
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-title-wrap mb-5">
            <h4 class="section-title">Event Lain</h4>
        </div>
        <div class="row gx-0">
            <div class="col-lg-8">
                <!-- News item-->
                @foreach ($events as $event)
                <div class="mb-4 d-flex align-items-start flex-row">
                    <img src="{{ asset('storage/'.$event->image) }}" alt="event" class="me-3 img-fluid" style="width: 100px; height: auto; border-radius: 5px; flex-shrink: 0;">
                    <div style="flex: 1;">
                        <div class="small text-muted">May 12, 2023</div>
                        <span class="badge bg-primary bg-gradient rounded-pill mb-2">{{ $event->category->name }}</span>
                        @if ($event->is_free == 1)
                        <span class="badge bg-dark bg-gradient rounded-pill mb-2">
                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                        </span>
                        @endif
                        <div>
                            <a class="" href="{{ route('event.show', $event) }}">
                                <h5 class="mb-0">{{ $event->title }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                {{ $events->links() }}
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 mb-4">
                    <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <!-- Slide 1 -->
                            <div class="carousel-item active">
                                <div class="slider-image-wrapper border rounded">
                                    <img src="{{ asset('storage/' . $pengumuman->image) }}" class="d-block w-100" alt="Slide {{ $pengumuman->title }}">
                                    <div class="overlay-badge badge bg-primary"> Pengumuman <i class="bi bi-fire text-danger ms-1"></i> </div>
                                    <div class="overlay">
                                        <a href="{{ route('pengumuman.show', $pengumuman) }}">

                                            <div class="overlay-text px-4">{{ $pengumuman->title }}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @foreach ($beritas->take(2) as $berita)

                            <div class="carousel-item">
                                <div class="slider-image-wrapper border rounded">
                                    <img src="{{ asset('storage/' . $berita->post_image) }}" class="d-block w-100" alt="Slide {{ $berita->title }}">
                                    <div class="overlay-badge badge bg-primary"> Berita <i class="bi bi-fire text-danger ms-1"></i> </div>
                                    <div class="overlay">
                                        <a href="{{ route('berita.show', [$berita->category, $berita]) }}">

                                            <div class="overlay-text px-4">{{ $berita->title }}</div>
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
    </div>
</section>
@endsection