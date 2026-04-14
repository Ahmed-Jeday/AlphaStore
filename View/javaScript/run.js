import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.mjs';

document.addEventListener("DOMContentLoaded", () => {
  const backgroundWrapper = document.querySelectorAll(".co-background");

  const swiper = new Swiper(".co-carousel", {
    loop: false,
    watchSlidesProgress: true,
    speed: 1000,
    grabCursor: true,
    navigation: {
      nextEl: ".co-carousel__nav-button.next",
      prevEl: ".co-carousel__nav-button.prev",
    },
    on: {
      touchEnd: function(swiper) {
        const time = Date.now() - swiper.touchEventsData.touchStartTime;
        const distance = Math.abs(swiper.touches.diff);
        const velocity = distance / time;
        let newSpeed = 1000 - (velocity * 800);
        swiper.params.speed = Math.max(200, Math.min(1000, newSpeed));
      },
      progress: function (swiper) {
        swiper.slides.forEach((slide, index) => {
          const progress = slide.progress;
          if (progress >= -1 && progress <= 1) {
            const rotateShoeMax = 15;
            const progressPositive = Math.abs(progress);
            const translateX = progress * -100;
            const rotateShoe = progressPositive * rotateShoeMax - rotateShoeMax;
            const textMaskY = progressPositive * 50;
            const scale = 1 - progressPositive * 0.2;
            const opacity = 0.5 - progressPositive * 0.5;

            const cardEl = slide.querySelector('.co-card');
            const shoeImg = slide.querySelector('.co-card__shoe-img');
            const shoeShadow = slide.querySelector('.co-card__shoe-shadow');
            
            if (cardEl) cardEl.style.transform = `scale(${scale})`;
            if (shoeImg) shoeImg.style.transform = `translate3d(${translateX}px, 0, 0) rotate(${rotateShoe}deg)`;
            if (shoeShadow) shoeShadow.style.transform = `translate3d(${translateX / 2}px, 0, 0)`;
            
            if (backgroundWrapper[index]) {
              backgroundWrapper[index].style.opacity = opacity.toFixed(2);
            }

            const textsMask = slide.querySelectorAll('.text-mask span');
            textsMask.forEach((t, i) => {
              t.style.transform = `translate3d(0, ${textMaskY * (i + 1)}px, 0)`;
            });
          }
        });
      },
      setTransition: function (swiper, speed) {
        swiper.slides.forEach((slide, index) => {
          const card = slide.querySelector('.co-card');
          const shoeImg = slide.querySelector('.co-card__shoe-img');
          const shadow = slide.querySelector('.co-card__shoe-shadow');
          if (card) card.style.transition = `${speed}ms`;
          if (shoeImg) shoeImg.style.transition = `${speed}ms`;
          if (shadow) shadow.style.transition = `${speed}ms`;
          if (backgroundWrapper[index]) backgroundWrapper[index].style.transition = `${speed}ms`;
          
          const textSpans = slide.querySelectorAll('.text-mask span');
          textSpans.forEach(t => {
            t.style.transition = `${speed}ms`;
          });
        });
      },
      transitionEnd: function (swiper) {
        swiper.params.speed = 1000;
      }
    }
  });
});