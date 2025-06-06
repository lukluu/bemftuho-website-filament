    <!-- JAVASCRIPT FILES -->
    <script src="{{asset('tema')}}/js/jquery.min.js"></script>
    <script src="{{asset('tema')}}/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('tema')}}/js/owl.carousel.min.js"></script>
    <script src="{{asset('tema')}}/js/custom.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) { // Mulai sticky setelah scroll 100px
                navbar.classList.add('sticky');
                // ganti logo brand pakai method
                navbar.querySelector('.logo-image').src = '{{asset("tema")}}/images/pro-talk-logo-sticky.png';
            } else {
                navbar.classList.remove('sticky');
                navbar.querySelector('.logo-image').src = '{{asset("tema")}}/images/pod-talk-logo.png';
            }
        });
    </script>