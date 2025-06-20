@extends('layouts.app')
@section('title', config('app.name') .' - '.'Marchandise')
@push('styles')
<style>
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

    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
    }

    .card-hover img {
        transition: transform 0.3s ease;
    }

    .card-hover:hover img {
        transform: scale(1.05);
    }

    .btn-hover:hover {
        transform: translateY(-2px);
        background-color: #0d6efd !important;
        /* Bootstrap primary */
        color: white !important;
    }
</style>

@endpush
@push('scripts')

@endpush
@section('content')
<section class="bg-nav"></section>
<section class="marchandise">
    <header class="event-banner">
        <h2 class="text-white">Marchandise Kami</h2>
    </header>
    <div class="container py-5">
        <h3 class="mb-4 text-center fw-bold">Produk Terbaru</h3>

        <div class="row justify-content-center">
            @foreach ($marchandises as $marchandise)
            <div class="col-md-4 mb-3">
                <div class="card shadow border-0 rounded-4 card-hover">
                    <a href="{{ route('marchandise.show', $marchandise) }}">
                        <img src="{{ asset('storage/'.$marchandise->image_path) }}" class="card-img-top rounded-top-4" alt="{{ $marchandise->name }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $marchandise->name }}</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-semibold">Rp {{ number_format($marchandise->price, 0, ',', '.') }}</span>
                            <a href="https://wa.me/{{ '62' . ltrim($marchandise->phone_number, '0') }}" target="_blank" class="btn btn-primary btn-sm rounded-pill btn-hover">Hubungi Penjual <i class="fab fa-whatsapp ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            {{ $marchandises->links() }}
        </div>
    </div>
</section>


@endsection