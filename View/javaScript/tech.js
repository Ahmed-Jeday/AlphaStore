 (function() {
    "use strict";

    // ----- GET ALL VIDEO ELEMENTS INSIDE SLIDES -----
    const allVideos = document.querySelectorAll('.bg-video');
    const muteBtn = document.getElementById('toggle-video-mute');
    const playBtn = document.getElementById('toggle-video-play');
    
    let isGlobalMuted = true;
    let isGlobalPaused = false;

    // Helper: safely play a video with promise catch (avoid "play() interrupted" errors)
    function playVideoSafely(videoElement) {
      if (!videoElement || isGlobalPaused) return;
      
      // Ensure global mute state is applied
      videoElement.muted = isGlobalMuted;
      
      const playPromise = videoElement.play();
      if (playPromise !== undefined) {
        playPromise.catch(error => {
          // Autoplay prevented or any other error: log but don't break UX
          console.warn("Video autoplay blocked or error:", error);
        });
      }
    }

    // Pause all videos
    function pauseAllVideos() {
      allVideos.forEach(video => {
        if (!video.paused) {
          video.pause();
        }
      });
    }

    // Play video of the currently active slide
    function playActiveSlideVideo(swiperInstance) {
      // get active slide element
      const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
      if (!activeSlide) return;

      // Find video inside active slide
      const activeVideo = activeSlide.querySelector('.bg-video');
      if (!activeVideo) return;

      // Pause all other videos
      pauseAllVideos();

      // Reset currentTime for clean start when switching slides
      activeVideo.currentTime = 0;
      
      // Play the active video
      playVideoSafely(activeVideo);
    }

    // Initialize Swiper
    const swiper = new Swiper('.swiper', {
      loop: true,
      autoplay: {
        delay: 100000,
        disableOnInteraction: false,
        pauseOnMouseEnter: false,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      speed: 800,
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },
      on: {
        init: function () {
          setTimeout(() => {
            playActiveSlideVideo(this);
          }, 100);
        },
        slideChangeTransitionEnd: function () {
          playActiveSlideVideo(this);
        },
        autoplayStart: function () {
          if (!isGlobalPaused) playActiveSlideVideo(this);
        }
      }
    });

    // --- VIDEO CONTROLS LOGIC ---
    if (muteBtn) {
      muteBtn.addEventListener('click', function() {
        isGlobalMuted = !isGlobalMuted;
        // Apply to all videos immediately
        allVideos.forEach(v => v.muted = isGlobalMuted);
        
        // Update icon
        const icon = muteBtn.querySelector('i');
        if (isGlobalMuted) {
          icon.className = 'fas fa-volume-mute';
          muteBtn.setAttribute('title', 'Unmute');
        } else {
          icon.className = 'fas fa-volume-up';
          muteBtn.setAttribute('title', 'Mute');
        }
      });
    }

    if (playBtn) {
      playBtn.addEventListener('click', function() {
        isGlobalPaused = !isGlobalPaused;
        const activeVid = document.querySelector('.swiper-slide-active .bg-video');
        
        const icon = playBtn.querySelector('i');
        if (isGlobalPaused) {
          // Pause all videos and stop swiper autoplay
          allVideos.forEach(v => v.pause());
          swiper.autoplay.stop();
          icon.className = 'fas fa-play';
          playBtn.setAttribute('title', 'Play');
        } else {
          // Resume active video and swiper autoplay
          if (activeVid) playVideoSafely(activeVid);
          swiper.autoplay.start();
          icon.className = 'fas fa-pause';
          playBtn.setAttribute('title', 'Pause');
        }
      });
    }
    
    // --- ADDITIONAL FIX: Ensure videos start playing correctly ---
    function preloadAndPrimeVideos() {
      allVideos.forEach(video => {
        video.load();
        video.muted = isGlobalMuted;
        video.setAttribute('playsinline', 'true');
      });
    }
    preloadAndPrimeVideos();
    
    let firstInteractionDone = false;
    function enableAutoplayOnFirstInteraction() {
      if (firstInteractionDone) return;
      firstInteractionDone = true;
      const activeVideoEl = document.querySelector('.swiper-slide-active .bg-video');
      if (activeVideoEl && activeVideoEl.paused && !isGlobalPaused) {
        playVideoSafely(activeVideoEl);
      }
      if (swiper && swiper.autoplay && swiper.autoplay.paused && !isGlobalPaused) {
        swiper.autoplay.resume();
      }
    }
    
    window.addEventListener('click', enableAutoplayOnFirstInteraction, { once: true });
    window.addEventListener('touchstart', enableAutoplayOnFirstInteraction, { once: true });
    
    // Resume retry on transition end
    swiper.on('slideChangeTransitionEnd', function () {
      setTimeout(() => {
        const activeVid = document.querySelector('.swiper-slide-active .bg-video');
        if (activeVid && activeVid.paused && !isGlobalPaused) {
          playVideoSafely(activeVid);
        }
      }, 50);
    });
    
    document.addEventListener('visibilitychange', function() {
      if (document.hidden) {
        allVideos.forEach(v => { if (!v.paused) v.pause(); });
      } else {
        if (swiper && !isGlobalPaused) {
          const activeVid = document.querySelector('.swiper-slide-active .bg-video');
          if (activeVid && activeVid.paused) {
            playVideoSafely(activeVid);
          }
        }
      }
    });
    
    // --- TODAY'S DEALS COUNTDOWN ---
    function updateCountdown() {
        const hoursEl = document.getElementById('cd-h');
        const minsEl = document.getElementById('cd-m');
        const secsEl = document.getElementById('cd-s');

        if (!hoursEl || !minsEl || !secsEl) return;

        let h = parseInt(hoursEl.innerText);
        let m = parseInt(minsEl.innerText);
        let s = parseInt(secsEl.innerText);

        if (s > 0) {
            s--;
        } else if (m > 0 || h > 0) {
            if (s === 0) {
                if (m > 0) {
                    m--;
                    s = 59;
                } else if (h > 0) {
                    h--;
                    m = 59;
                    s = 59;
                }
            }
        }

        hoursEl.innerText = h.toString().padStart(2, '0');
        minsEl.innerText = m.toString().padStart(2, '0');
        secsEl.innerText = s.toString().padStart(2, '0');
    }

    if (document.getElementById('countdown')) {
        setInterval(updateCountdown, 1000);
    }

    console.log('✅ Swiper initialized with video controls and countdown');
  })();




// parte 2

(() => {
    const modelViewers = document.querySelectorAll('model-viewer');
    const cards = document.querySelectorAll('.card');
    const defaultOrbit = '64deg 25deg 64m';
    const hoverOrbit = '90deg -42deg 50m';
    const defaultTarget = '8m 1m 1m';
    const hoverTarget = '4m 1m 1m';
    const applyOrbit = (modelViewer, orbit, target) => {
      modelViewer.setAttribute('camera-orbit', orbit);
      modelViewer.setAttribute('camera-target', target);
      modelViewer.setAttribute('interpolation-decay', '124');
    };
    cards.forEach((card, index) => {
      const modelViewer = modelViewers[index];
      if (modelViewer) {
        applyOrbit(modelViewer, defaultOrbit, defaultTarget);
        card.addEventListener('mouseenter', () => applyOrbit(modelViewer, hoverOrbit, hoverTarget));
        card.addEventListener('mouseleave', () => applyOrbit(modelViewer, defaultOrbit, defaultTarget));
        modelViewer.addEventListener('load', () => {
          modelViewer.classList.add('loaded');
        });
      } else {
        console.log(`No model found for card at i:${index}`);
      }
    });
})();
function changeModelStyle(element, deg, invert = 0) {
    const card = element.closest('.card');
    const modelViewer = card.querySelector('model-viewer');
    if (modelViewer) { modelViewer.style.filter = `hue-rotate(${deg}deg) invert(${invert})`; }
}

//part 3
var backgrounds = document.querySelectorAll('.background');

const slider = document.querySelector('.slider-images');
const images = Array.from(slider.children);

let imageIndex = 0;

function updateSlider() {
    images.forEach(image => {
        image.classList.remove('active', 'previous', 'next', 'inactive');
    });

    images[imageIndex].classList.add('active');

    if (imageIndex - 1 >= 0) {
        images[imageIndex - 1].classList.add('previous');
    } else {
        images[images.length - 1].classList.add('previous');
    }

    if (imageIndex + 1 < images.length) {
        images[imageIndex + 1].classList.add('next');
    } else {
        images[0].classList.add('next');
    }

    images.forEach((image, index) => {
        if (index !== imageIndex && index !== (imageIndex - 1 + images.length) % images.length && index !== (imageIndex + 1) % images.length) {
            image.classList.add('inactive');
        }
    });

    backgrounds.forEach((background) => {
        background.style.opacity = 0;
    });
    if (images[imageIndex].classList.contains('active')) {
        backgrounds[imageIndex].style.opacity = 1;
    }
    imageIndex = (imageIndex + 1) % images.length;
}
updateSlider();


setInterval(updateSlider, 8000);

images[1].classList.add('next');
images[2].classList.add('inactive');
images[3].classList.add('inactive');
images[4].classList.add('previous');
images[0].classList.add('active');