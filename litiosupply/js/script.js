$(function () {
    $('.nav-btn').on('click', function () {
        $(this).toggleClass('open');
    });
});

$(window).ready(function () {
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll > 100) {
            $("#header").addClass('glass-effect');
        } else {
            $("#header").removeClass("glass-effect");
        }
    });
    
    $('[trucks-photos] .owl-carousel').owlCarousel({
        loop: true,
        margin: 15,
        mouseDrag: true,
        autoplay: false,
        autoplayTimeout: 5000,
        dots: true,
        nav: false,
        navText: ["<span class='lnr ti-angle-left'></span>", "<span class='lnr ti-angle-right'></span>"],
        autoplayHoverPause: true,
        responsiveClass: true,
        items: 1
    });
    
})

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault(); // Отменяем стандартное поведение

            let targetId = this.getAttribute("href").substring(1);
            let targetElement = document.getElementById(targetId);

            if (targetElement) {
                let offset = window.innerWidth > 768 ? 110 : 80; // 110px для десктопа, 0px для мобильных
                let targetPosition = targetElement.offsetTop - offset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: "smooth"
                });
            }

            // Закрываем navbar-collapse на мобильных устройствах
            // let navbarCollapse = document.querySelector(".navbar-collapse.show");
            // if (navbarCollapse) {
            //     let bsCollapse = new bootstrap.Collapse(navbarCollapse, {
            //         toggle: true
            //     });
            // }
        });
    });
	
});