/**
 * AlphaStore Homepage Initialization
 * Handles countdown, banner slider, and landing swiper.
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Countdown Timer
    const cdH = document.getElementById('cd-h');
    const cdM = document.getElementById('cd-m');
    const cdS = document.getElementById('cd-s');
    
    if (cdH && cdM && cdS) {
        let secs = 8 * 3600 + 45 * 60;
        const timer = setInterval(() => {
            if (secs <= 0) {
                clearInterval(timer);
                return;
            }
            secs--;
            cdH.textContent = String(Math.floor(secs / 3600)).padStart(2, '0');
            cdM.textContent = String(Math.floor((secs % 3600) / 60)).padStart(2, '0');
            cdS.textContent = String(secs % 60).padStart(2, '0');
        }, 1000);
    }

    // 2. Banner Tech Slider (Modern & Robust)
    if (typeof jQuery !== 'undefined') {
        $(function() {
            let rotationInterval;
            const $slider = $('.p_slider');
            const $items = $('.p_slider__item');
            let currentIndex = 1; // 0-based index of the active item (starts at 1 for the 2nd item)

            function updateSlider() {
                $items.removeClass('active prev next');
                
                const total = $items.length;
                const prevIndex = (currentIndex - 1 + total) % total;
                const nextIndex = (currentIndex + 1) % total;

                $items.eq(prevIndex).addClass('prev');
                $items.eq(currentIndex).addClass('active');
                $items.eq(nextIndex).addClass('next');
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % $items.length;
                updateSlider();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + $items.length) % $items.length;
                updateSlider();
            }

            // Click handlers
            $(document).on('click', '.banner-tech .right', nextSlide);
            $(document).on('click', '.banner-tech .left', prevSlide);

            // Allow clicking on the side items to move to them
            $(document).on('click', '.p_slider__item.prev', prevSlide);
            $(document).on('click', '.p_slider__item.next', nextSlide);

            const startAuto = () => {
                clearInterval(rotationInterval);
                rotationInterval = setInterval(nextSlide, 4000);
            };

            const stopAuto = () => clearInterval(rotationInterval);

            // Initialize
            updateSlider();
            startAuto();

            $slider.on('mouseenter', stopAuto).on('mouseleave', startAuto);
        });
    }

    // 3. Landing Swiper
    if (typeof Swiper !== 'undefined') {
        const landingThumbs = new Swiper('.landingThumbs', {
            spaceBetween: 10,
            slidesPerView: 3,
            freeMode: true,
            watchSlidesProgress: true,
        });

        const landingSwiper = new Swiper('.landingSwiper', {
            loop: true,
            spaceBetween: 0,
            speed: 1200,
            effect: 'coverflow',
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            thumbs: {
                swiper: landingThumbs,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            on: {
                slideChangeTransitionStart: function () {
                    if (typeof gsap !== 'undefined') {
                        gsap.from('.landingSwiper .swiper-slide-active h3', { x: -200, opacity: 0, duration: 1, delay: 0.5 });
                        gsap.from('.landingSwiper .info p, .landingSwiper .info span', { y: 10, opacity: 0, stagger: 0.1, delay: 0.5 });
                        gsap.from('.landingSwiper .pricing .price-value, .landingSwiper .btn-block', { y: 10, opacity: 0, stagger: 0.1, delay: 0.5 });
                        gsap.from('.landingSwiper .main-img', { y: 50, opacity: 0, duration: 1, delay: 1 });
                    }
                },
            }
        });
    }

    // 4. CTA Buttons
    const bannerBtn = document.getElementById("banner-btn");
    if (bannerBtn) {
        bannerBtn.addEventListener("click", () => window.location.href = "tech.html");
    }
});
