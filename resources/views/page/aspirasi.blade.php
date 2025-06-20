@extends('layouts.app')
@section('title', config('app.name') .' - '.'Aspirasi')
@push('styles')
<style>
    .btn-custom {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border: none;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateX(5px);
        box-shadow: -5px 5px 15px rgba(46, 204, 113, 0.3);
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
                    <div class="col-lg-8">
                        @php
                        $bgUrl = asset('storage/' . $aspirasiFirst->hero);
                        @endphp
                        <div class="bg-featured-blog" style="background-image: url('{{ $bgUrl }}') !important;  height: 100%;
  width: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 15rem;"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="p-4 p-md-5 d-flex flex-column justify-content-center align-items-center text-center gap-3">

                            <div>
                                <h3>Ayoo!<br> Suarakan Aspirasi Kamu</h3>
                            </div>

                            <a href="{{ $aspirasiFirst->link }}" class="text-decoration-none">
                                <button class="btn text-white btn-custom d-flex align-items-center gap-2">
                                    Aspirasikan Sekarang
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection