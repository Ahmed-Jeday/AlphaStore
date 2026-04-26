const swiper = new Swiper(".mySwiper", {
    slidesPerView: 3,
    spaceBetween: 30,
    centeredSlides: true,
    loop: true,

    pagination: {
        el: ".new-arrivals__pagination",
        clickable: true,
    },

    navigation: {
        nextEl: ".new-arrivals-next",
        prevEl: ".new-arrivals-prev",
    },

    breakpoints: {
        0: {
            slidesPerView: 1,
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30,
        }
    }
});