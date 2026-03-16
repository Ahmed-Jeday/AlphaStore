// Main banner slider
if (document.querySelector('.mySwiper')) {
  new Swiper('.mySwiper', {
    pagination: {
      el: '.swiper-pagination',
      dynamicBullets: true,
      clickable: true
    },
    autoplay: {
      delay: 5000
    },
    loop: true
  });
}


// Optional product slider (only initializes if markup exists)
if (document.querySelector('.slide_product')) {
  new Swiper('.slide_product', {
    slidesPerView: 5,
    spaceBetween: 20,
    autoplay: {
      delay: 2500,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    },
    loop: true,
    breakpoints: {
      1200: {
        slidesPerView: 5,
        spaceBetween: 20
      },
      1000: {
        slidesPerView: 4,
        spaceBetween: 20
      },
      700: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      0: {
        slidesPerView: 2,
        spaceBetween: 10
      }
    }
  });
}







