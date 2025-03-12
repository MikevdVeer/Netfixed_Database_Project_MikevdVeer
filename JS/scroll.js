// Change navbar background on scroll
window.addEventListener("scroll", function () {
    var navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
});

$(document).ready(function () {
    $('.slick-carousel').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,               
        autoplaySpeed: 4000,
        arrows: true, 
        dots: false,
        infinite: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true
                }
            }
        ],
    });
    
});