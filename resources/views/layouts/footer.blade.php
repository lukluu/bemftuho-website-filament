<footer class="footer">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-between py-3 text-center text-md-start">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <div class="footer-logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('logo-footer.png') }}" class="img-fluid" style="width: 100px;" alt="logo">
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-6 text-center text-md-end">
                <div class="social-icons">
                    <a href="#" class="me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-youtube"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        <div class="row menu-footer">
            <div class="col-md-4 mb-4 d-flex align-items-center text-center text-md-start gap-3">
                <div class="kabinet-logo">
                    <a href="{{ route('profile') }}"><img src="{{ asset('storage/'.$kabinet->logo) ?? asset('storage/default/no_image.png') }}" class="img-fluid" style="width: 60px;" alt="logo"></a>
                </div>
                <h5 class="footer-title my-auto">Kabinet {{ $kabinet->nama_kabinet }} {{ $kabinet->periode }}</h5>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="footer-title fw-bold">Learning Catalog</h5>
                <ul class="footer-links">
                    <li><a href="#">Find an Academy</a></li>
                    <li><a href="#">Career Resources</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h5 class="footer-title fw-bold">Teach with us</h5>
                <ul class="footer-links">
                    <li><a href="#">Partner with us</a></li>
                    <li><a href="#">Find an ASC or ITC Partner</a></li>
                </ul>
            </div>
        </div>

    </div>
    <div class="container-fluid mx-0 px-0 py-1 bg bg-dark">
        <div class="container">

            <div class="footer-bottom">
                <div class="copyright">
                    © {{ date('Y') }} BEM FT-UHO.
                </div>
                <!-- <div class="designer">
                    designed by <a href="https://github.com/AdityaKurniawan" target="_blank">Aditya Kurniawan</a>
                </div> -->
            </div>
        </div>
    </div>
</footer>