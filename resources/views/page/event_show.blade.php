@extends('layouts.app')

@section('title', config('app.name') .' - '. $event->title)
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
        background: url('{{ asset("keteknikan.jpg") }}') no-repeat center center;
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

    .event-card {
        position: relative;
        z-index: 3;
        margin-top: -100px;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
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
</style>


@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll('#eventTab .nav-link');
        const contents = {
            deskripsi: document.getElementById('deskripsi'),
            dokumentasi: document.getElementById('dokumentasi')
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                // Hapus class active dari semua tab
                tabs.forEach(t => t.classList.remove('active'));

                // Tambahkan class active ke tab yang diklik
                this.classList.add('active');

                // Sembunyikan semua konten
                for (const key in contents) {
                    contents[key].classList.add('d-none');
                }

                // Tampilkan konten yang dipilih
                const selected = this.getAttribute('data-tab');
                contents[selected].classList.remove('d-none');
            });
        });
    });
</script>

@endpush
@section('content')
<section class="bg-nav"></section>
<section class="event" style="background-color:#f9f9f9;">
    <header class=" event-banner">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-2">Detail Event</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('event') }}" class="text-white text-decoration-none">Events</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $event->title }}</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="event-card p-4">
                    <div class="row g-4">
                        <div class="col-md-3 text-center event-thumbnail">
                            <img src="{{ asset('storage/'.$event->image) }}" alt="Thumbnail Event">
                        </div>
                        <div class="col-md-6">
                            <span class="label-seminar badge bg-primary bg-gradient">{{ $event->category->name }}</span>
                            <h4 class="mt-2">{{ $event->title }}</h4>
                            <p class="text-muted mb-1">Diselenggarakan {{ $event->kabinet->nama_kabinet }} {{$event->kabinet->periode}}</p>
                        </div>
                        <div class="col-md-3">
                            @php
                            use Carbon\Carbon;
                            @endphp

                            @if(Carbon::now()->lessThan($event->end_date))
                            <p class="mb-1">
                                <strong>Terbuka Hingga:</strong><br>
                                <span class="text-primary">{{ $event->end_date->format('d F Y H:i') }}</span>
                            </p>
                            @else
                            <p class="mb-1">
                                <strong class="text-danger">Pendaftaran Ditutup</strong><br>
                                <span class="text-muted">Penutupan: {{ $event->end_date->format('d F Y H:i') }}</span>
                            </p>
                            @endif
                            <p class="mb-0"><strong>
                                    Partisipan:</strong>
                                <br>
                                @if ($event->max_participants == null)
                                <span class="badge bg-primary">Tak Terbatas</span>
                                @else
                                <span class="badge bg-dark">Max {{ $event->max_participants}} </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container">

        <ul class="nav nav-tabs" id="eventTab">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-tab="deskripsi">Deskripsi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="dokumentasi">Dokumentasi</a>
            </li>
        </ul>
        <div class="row gx-4 mt-4 deskripsi" id="deskripsi">
            <div class="col-lg-8 ">
                <img src="{{ asset('storage/'.$event->image) }}" alt="" class="img-fluid border rounded" style="height: 300px; width:100%;">
                <div class="mt-4">
                    <p>{{ $event->description }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <ul class="list-group list-group-flush bg-light">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto my-2">
                                <div class="fw-bold">Biaya Pendaftaran</div>
                                <span class="text-muted">
                                    {{ $event->is_free ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="badge {{ $event->is_free ? 'text-bg-success' : 'text-bg-dark' }} rounded-pill">
                                {{ $event->is_free ? 'Gratis' : 'Berbayar' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start ">
                            <div class="ms-2 me-auto py-2">
                                <div class="fw-bold mt-1">Jadwal Pelaksanaan</div>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</span>
                            </div>

                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto py-2">
                                <div class="fw-bold mt-1">Batas Pendaftaran</div>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto py-2">
                                <div class="fw-bold">Lokasi</div>
                                <span class="text-muted"> {{ $event->location ?? 'Belum ditentukan' }}</span>

                            </div>
                        </li>


                        @if($event->registration_link)
                        <li class="list-group-item bg-primary text-white p-0">
                            <a href="{{ $event->registration_link }}" target="_blank" class="d-flex justify-content-center align-items-center text-white text-decoration-none" style="height: 60px;">
                                <div class="fw-bold">
                                    Daftar
                                </div>
                            </a>
                        </li>

                        @endif
                    </ul>
                </div>


            </div>
        </div>
        <div class="row mt-4 tab-content dokumentasi d-none" id="dokumentasi">
            <div class="gallery">
                @forelse ($event->getMedia('dokumentasi') as $media)
                <div class="gallery-item">
                    <img src="{{ $media->getUrl() }}" alt="Dokumentasi">
                </div>
                @empty
                <p class="no-doc">Belum ada dokumentasi.</p>
                @endforelse
            </div>
            @push('styles')
            <style>
                .gallery {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    /* 2 kolom */
                    gap: 16px;
                }

                .gallery-item {
                    width: 100%;
                    height: 200px;
                    overflow: hidden;
                    border-radius: 8px;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
                }

                .gallery-item img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                    border-radius: 8px;
                }

                .no-doc {
                    grid-column: span 3;
                    text-align: center;
                    color: #999;
                }
            </style>
            @endpush

        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="mb-5">
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
        </div>
    </div>
</section>
@endsection