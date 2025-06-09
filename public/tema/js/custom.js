(function ($) {
    "use strict";

    // MENU
    $(".navbar-collapse a").on("click", function () {
        $(".navbar-collapse").collapse("hide");
    });

    // CUSTOM LINK
    $(".smoothscroll").click(function () {
        var el = $(this).attr("href");
        var elWrapped = $(el);
        var header_height = $(".navbar").height();

        scrollToDiv(elWrapped, header_height);
        return false;

        function scrollToDiv(element, navheight) {
            var offset = element.offset();
            var offsetTop = offset.top;
            var totalScroll = offsetTop - 70;

            $("body,html").animate(
                {
                    scrollTop: totalScroll,
                },
                300
            );
        }
    });

    $(".owl-carousel").owlCarousel({
        center: true,
        loop: true,
        margin: 15,
        // autoplayHoverPause: true,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2,
            },
            767: {
                items: 3,
            },
            1200: {
                items: 4,
            },
        },
    });
})(window.jQuery);

document.addEventListener("DOMContentLoaded", function () {
    const marqueeContent = document.querySelector(".logo-marquee-content");
    const logoItems = document.querySelectorAll(".logo-item");

    // Duplicate the logo items for seamless looping
    logoItems.forEach((item) => {
        const clone = item.cloneNode(true);
        marqueeContent.appendChild(clone);
    });

    // Adjust animation duration based on content width
    function adjustAnimation() {
        const contentWidth = marqueeContent.scrollWidth / 2;
        const viewportWidth = window.innerWidth;
        const speedRatio = contentWidth / viewportWidth;

        // Base duration is 30s for desktop, adjust proportionally
        let duration = 30 * speedRatio;

        // Ensure minimum duration of 15s and max of 60s
        duration = Math.max(15, Math.min(60, duration));

        // For mobile, reduce duration further
        if (window.innerWidth < 768) {
            duration = duration * 0.7;
        }

        marqueeContent.style.animationDuration = `${duration}s`;
    }

    // Initial adjustment
    adjustAnimation();

    // Adjust on window resize
    window.addEventListener("resize", adjustAnimation);
});
