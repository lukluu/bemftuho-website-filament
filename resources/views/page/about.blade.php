@extends('layouts.app')

@section('title', config('app.name') .' - '.'Kabinet')
@section('content')
<section class="bg-nav"></section>
<section class="latest-podcast-section section-padding mb-5" id="section_2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-image-wrap custom-block-image-detail-page">
                                <img src="{{ asset('storage/'.$kabinet->logo) }}" class="custom-block-image img-fluid" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-12">
                        <div class="custom-block-info">

                            <h2 class="mb-2">Kabinet {{ $kabinet->nama_kabinet }} {{ $kabinet->periode }}</h2>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button custom-btn-accordion" type="button" data-bs-toggle="collapse" data-bs-target="#visi" aria-expanded="true" aria-controls="visi">
                                            VISI
                                        </button>
                                    </h2>
                                    <div id="visi" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <strong>{{$kabinet->visi}}</strong>
                                        </div>
                                    </div>
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed custom-btn-accordion" type="button" data-bs-toggle="collapse" data-bs-target="#misi" aria-expanded="false" aria-controls="misi">
                                            MISI
                                        </button>
                                    </h2>
                                    <div id="misi" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <strong>{!! $kabinet->misi !!}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12 mt-5">
                <div class="section-title-wrap mb-5">
                    <h4 class="section-title">Pengurus</h4>
                </div>
            </div>

            @foreach($kabinet->kabinetMahasiswaJabatan as $jabatan)
            <div class="col-lg-3 col-md-6 col-6 mb-4 mb-lg-0">
                <div class="team-thumb bg-white shadow-lg">
                    <!-- @if ($jabatan->mahasiswa->hasMedia('mahasiswa'))
                    <img src="{{ $jabatan->mahasiswa->getFirstMediaUrl('mahasiswa', 'thumb') }}"
                        class="about-image img-fluid" alt="">
                    @else
                    <img src="{{ asset('logo.png') }}"
                        class="about-image img-fluid" alt="">
                    @endif -->
                    <img src="{{ $jabatan->mahasiswa?->foto_mahasiswa ? asset('storage/'.$jabatan->mahasiswa->foto_mahasiswa) : asset('storage/default/no_image.png') }}" class="about-image img-fluid" alt="Foto {{ $jabatan->mahasiswa->nama ?? 'Mahasiswa' }}" loading="lazy">
                    <div class="team-info">
                        <span class="badge mb-2">{{ $jabatan->jabatan->nama_jabatan }}</span>
                        <p class="h6 fw-bold">
                            {{ $jabatan->mahasiswa->nama }}
                        </p>

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
            </div>
            @endforeach
            <div class="col-lg-12 col-12 mt-5">
                <div class="section-title-wrap mb-5">
                    <h4 class="section-title">Kabinet Terdahulu</h4>
                </div>
            </div>

            @foreach($kabinets as $kabinet)
            <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                <div class="custom-block custom-block-full">
                    <div class="custom-block-image-wrap">
                        <a href="detail-page.html">
                            <img src="{{ $kabinet->logo? asset('storage/'.$kabinet->logo) : asset('storage/default/no_image.png') }}" class="custom-block-image img-fluid" alt="">
                        </a>
                    </div>

                    <div class="custom-block-info">
                        <h3 class="text-center p-0">
                            <a href="{{ route('kabinet.show', $kabinet) }}" class="mb-0 p-0">
                                {{ $kabinet->nama_kabinet }}
                            </a>
                        </h3>
                        <p class="mb-4 text-center p-0 text-muted">Periode {{ $kabinet->periode }}</p>

                        <div class="profile-block d-flex">
                            @php
                            $ketua = $kabinet->kabinetMahasiswaJabatan->firstWhere('jabatan.nama_jabatan', 'Ketua Umum')
                            ?? $kabinet->kabinetMahasiswaJabatan->first();

                            $foto = $ketua?->mahasiswa?->foto_mahasiswa
                            ? asset('storage/' . $ketua->mahasiswa->foto_mahasiswa)
                            : asset('storage/default/no_image.png');
                            @endphp

                            <img src="{{ $foto }}" class="profile-block-image img-fluid" alt="Foto Ketua">
                            <p class="fw-bold">
                                {{ $ketua->mahasiswa->nama ?? 'Belum ada ketua' }}
                                <br>
                                <span class="text-muted">{{ $ketua->jabatan->nama_jabatan ?? 'Jabatan' }}</span>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
</section>
@endsection