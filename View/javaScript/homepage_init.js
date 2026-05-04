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

    // 2. Banner Tech Slider (jQuery dependent)
    if (typeof jQuery !== 'undefined') {
        $(function() {
            let on = 0;
            let time = 500;
            let pos = 1, pos2 = 2, pos3 = 3;
            let play;

            function rotateLeft() {
                if (on === 0) {
                    on = 1;
                    $('.p_slider__item:nth-of-type(' + pos + ')').animate({ left: '200px' }, 200).css('z-index', '0');
                    $('.p_slider__item:nth-of-type(' + pos2 + ')').animate({ left: '-200px' }, 200);

                    setTimeout(function () {
                        $('.p_slider__item:nth-of-type(' + pos2 + ')').css({
                            transform: 'scale(0.6)',
                            opacity: '0.8',
                            filter: 'blur(2px)',
                            'z-index': '1'
                        });

                        pos++; pos2++; pos3++;
                        if (pos > 3) pos = 1;
                        if (pos2 > 3) pos2 = 1;
                        if (pos3 > 3) pos3 = 1;
                    }, 400);

                    $('.p_slider__item:nth-of-type(' + pos3 + ')').animate({ left: '0px' }, 200).css({
                        transform: 'scale(1)',
                        opacity: '1',
                        filter: 'blur(0px)',
                        'z-index': '2'
                    });

                    setTimeout(() => on = 0, time);
                }
            }

            function rotateRight() {
                if (on === 0) {
                    on = 1;
                    $('.p_slider__item:nth-of-type(' + pos3 + ')').animate({ left: '-200px' }, 200).css('z-index', '0');
                    $('.p_slider__item:nth-of-type(' + pos2 + ')').animate({ left: '200px' }, 200);

                    setTimeout(function () {
                        $('.p_slider__item:nth-of-type(' + pos2 + ')').css({
                            transform: 'scale(0.6)',
                            opacity: '0.8',
                            filter: 'blur(2px)'
                        });

                        pos--; pos2--; pos3--;
                        if (pos < 1) pos = 3;
                        if (pos2 < 1) pos2 = 3;
                        if (pos3 < 1) pos3 = 3;
                    }, 400);

                    $('.p_slider__item:nth-of-type(' + pos + ')').animate({ left: '0px' }, 200).css({
                        transform: 'scale(1)',
                        opacity: '1',
                        filter: 'blur(0px)'
                    });

                    setTimeout(() => on = 0, time);
                }
            }

            $('.right').click(rotateRight);
            $('.left').click(rotateLeft);

            play = setInterval(rotateLeft, 3000);

            $('.p_slider__item img').on('mouseenter', function () {
                clearInterval(play);
                $(this).animate({ top: '-14px' }, 300);
            }).on('mouseleave', function () {
                $(this).stop(true).animate({ top: '0px' }, 300);
                play = setInterval(rotateLeft, 3000);
            });
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
