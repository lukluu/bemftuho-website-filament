        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand me-lg-5 me-0" href="{{url('/')}}">
                    <img src="{{asset('tema')}}/images/pod-talk-logo.png" class="logo-image img-fluid" alt="templatemo pod talk">
                </a>
                <!-- 
                <form action="#" method="get" class="custom-form search-form flex-fill me-3" role="search">
                    <div class="input-group input-group-lg">
                        <input name="search" type="search" class="form-control" id="search" placeholder="Search Podcast"
                            aria-label="Search">

                        <button type="submit" class="form-control" id="submit">
                            <i class="bi-search"></i>
                        </button>
                    </div>
                </form> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('welcome') ? ' active' : '' }}" aria-current="page" href="{{ url('/') }}">Beranda</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ Route::is('profile') || Route::is('kabinet.show') || Route::is('struktur-organisasi') ? ' active' : '' }}" href="#" id="navbarLightDropdownMenuLink" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Tentang</a>

                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profile</a></li>

                                <li><a class="dropdown-item" href="{{ route('struktur-organisasi') }}">Struktur</a></li>
                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::is('pengumuman') || Route::is('pengumuman.show') ? ' active' : '' }}" href="{{ url('/pengumuman') }}">Pengumuman</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::is('event') || Route::is('event.show') ? ' active' : '' }}" href="{{ url('/event') }}">Event</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::is('berita') || Route::is('berita.show') || Route::is('categoryPost.show') ? ' active' : '' }}" href="{{ url('/berita') }}">Berita</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::is('aspirasi') ? ' active' : '' }}" href="{{ url('/aspirasi') }}">Aspirasi</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::is('marchandise') || Route::is('marchandise.show') ? ' active' : '' }}" href="{{ url('/marchandise') }}">Marchendise</a>
                        </li>

                    </ul>

                    <!-- <div class="ms-4">
                        <a href="#section_3" class="btn custom-btn custom-border-btn smoothscroll">Get started</a>
                    </div> -->
                </div>
            </div>
        </nav>