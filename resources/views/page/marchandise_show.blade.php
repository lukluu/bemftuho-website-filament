@extends('layouts.app')
@section('title', config('app.name') .' - '. $marchandise->name)
@push('styles')
<style>
    .hover-overlay {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(100, 0, 255, 0.4));
        opacity: 0;
        transition: all 0.4s ease-in-out;
        transform: translateY(20px);
        pointer-events: none;
    }

    .card:hover .hover-overlay,
    .carousel-item:hover .hover-overlay {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .event-banner {
        position: relative;
        background: url('{{ asset("marchandise.webp") }}') no-repeat center center;
        background-size: cover;
        width: 100%;
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

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        color: #ffffffcc;
    }

    .breadcrumb-item a {
        color: #ffffffcc;
    }

    .breadcrumb-item.active {
        color: #fff;
        font-weight: 500;
    }

    .event-card {
        position: relative;
        margin-top: -100px;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        z-index: 3;
    }

    .event-thumbnail img {
        max-width: 100%;
        border-radius: 0.75rem;
    }

    .label-seminar {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        display: inline-block;
    }
</style>


@endpush

@push('scripts')

@endpush
@section('content')
<section class="bg-nav"></section>
<section class="marchandise pb-5">
    <header class=" event-banner">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-2">Detail Marchandise</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('marchandise') }}" class="text-white text-decoration-none">Marchandise</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $marchandise->name }}</li>
                </ol>
            </nav>
        </div>
    </header>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="event-card p-4">
                    <div class="row g-4 align-items-center ">
                        <div class="col-md-4 text-center event-thumbnail">
                            <img src="{{ asset('storage/'.$marchandise->image_path) }}" alt="Thumbnail Event">
                        </div>
                        <div class="col-md-4">
                            <h4 class="mt-2">{{ $marchandise->name }}</h4>
                            <p class="text-muted mb-1">Oleh {{ $kabinet->nama_kabinet }} {{$kabinet->periode}}</p>
                            <p>{{$marchandise->description}}</p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1">
                                <strong>Stock</strong><br>
                                <span class="text-muted">{{ $marchandise->stock }}</span>
                            </p>
                            <p class="mb-1">
                                <strong>Harga</strong><br>
                                <span class="text-muted">Rp {{ number_format($marchandise->price, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        <div class="col-md-2">
                            <a href="https://wa.me/{{ '62' . ltrim($marchandise->phone_number, '0') }}" target="_blank" class="btn btn-primary w-100">Hubungi <i class="fab fa-whatsapp"></i></a>
                        </div>

                        <div class="d-flex flex-column align-items-center">
                            <p class="fw-semibold mb-0 pt-0">Bagikan:</p>
                            <x-share :url="request()->fullUrl()" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection