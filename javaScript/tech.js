 (function() {
    "use strict";

    // ----- GET ALL VIDEO ELEMENTS INSIDE SLIDES -----
    const allVideos = document.querySelectorAll('.bg-video');
    
    // Helper: safely play a video with promise catch (avoid "play() interrupted" errors)
    function playVideoSafely(videoElement) {
      if (!videoElement) return;
      // Reset to beginning for a fresh start (optional, but keeps sync)
      videoElement.currentTime = 0;
      const playPromise = videoElement.play();
      if (playPromise !== undefined) {
        playPromise.catch(error => {
          // Autoplay prevented or any other error: log but don't break UX
          console.warn("Video autoplay blocked or error:", error);
          // Some browsers may need user interaction, but since videos are muted & we start after user click or swiper interaction, 
          // fallback: we keep muted and retry on next slide change.
          // Actually muted videos with playsinline are allowed to autoplay, but this is just safety.
        });
      }
    }

    // Pause all videos and reset them (to stop background audio/video resources)
    function pauseAllVideos() {
      allVideos.forEach(video => {
        if (!video.paused) {
          video.pause();
        }
        // Do NOT reset currentTime here to avoid flicker; we'll reset when playing the active one
      });
    }

    // Play video of the currently active slide (only the one inside active .swiper-slide)
    function playActiveSlideVideo(swiperInstance) {
      // get active slide element
      const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
      if (!activeSlide) return;

      // Find video inside active slide's .hero-container or directly inside slide
      const activeVideo = activeSlide.querySelector('.bg-video');
      if (!activeVideo) return;

      // Pause all other videos to save resources and avoid overlapping audio
      pauseAllVideos();

      // Reset currentTime for clean start (optional: gives a consistent experience)
      activeVideo.currentTime = 0;
      
      // Play the active video
      playVideoSafely(activeVideo);
    }

    // Initialize Swiper with all required features: Autoplay, Navigation, Pagination
    // This fixes the main issues:
    // - NEXT/PREV buttons now work because we explicitly bind navigation elements.
    // - AUTOPLAY works because we enable autoplay module with delay.
    // - Video transition on slide change is managed with 'slideChangeTransitionEnd' (smoother than 'slideChange').
    const swiper = new Swiper('.swiper', {
      // Enable looping (infinite scroll)
      loop: true,
      
      // --- AUTOPLAY FIX: automatic slideshow with 5 seconds delay ---
      autoplay: {
        delay: 10000,          // 5 seconds between slides
        disableOnInteraction: false,  // keeps autoplay running after user clicks next/prev
        pauseOnMouseEnter: false,     // optional: won't pause on hover
      },
      
      // --- NAVIGATION FIX: binds next/prev buttons to swiper actions ---
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      
      // --- PAGINATION FIX: clickable bullets allow direct navigation ---
      pagination: {
        el: '.swiper-pagination',
        clickable: true,      // users can click bullets to navigate
        dynamicBullets: false,
      },
      
      // Optional: smooth transition speed
      speed: 800,
      
      // Keyboard control for accessibility
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },
      
      // --- VIDEO HANDLING: event listeners for reliable autoplay on each slide ---
      on: {
        // When swiper initialization is complete, play the first slide video
        init: function () {
          // small delay to ensure DOM & videos are ready
          setTimeout(() => {
            playActiveSlideVideo(this);
          }, 100);
        },
        
        // Fires after slide change transition ends (best moment to swap videos)
        slideChangeTransitionEnd: function () {
          // After transition, play the video of the new active slide
          playActiveSlideVideo(this);
        },
        
        // Optional: when autoplay starts or resumes, ensure video is playing
        autoplayStart: function () {
          playActiveSlideVideo(this);
        },
        
        // If user interacts with navigation buttons, autoplay remains active due to disableOnInteraction: false
        // But we still ensure video stays in sync
        transitionStart: function () {
          // (Optional) we don't pause video early to avoid flicker, but we could.
          // For seamless experience we don't pause until transition end.
        }
      }
    });
    
    // --- ADDITIONAL FIX: Ensure videos start playing if the page loads with hidden/swiper ready but before 'init' 
    // Also, handle any video loading errors: retry mechanism for background videos
    function preloadAndPrimeVideos() {
      allVideos.forEach(video => {
        // Load metadata and ensure video is ready but not playing yet
        video.load();
        // Force mute and playsinline again for safety
        video.muted = true;
        video.setAttribute('playsinline', 'true');
        // if video data is already loaded, we are good
      });
    }
    preloadAndPrimeVideos();
    
    // Edge case: In case browser blocks first video autoplay even when muted,
    // we add a one-time user interaction (touch/click) on window to start playback.
    // This is a robust fallback that respects modern browser policies.
    let firstInteractionDone = false;
    function enableAutoplayOnFirstInteraction() {
      if (firstInteractionDone) return;
      firstInteractionDone = true;
      // When user interacts anywhere, ensure active video plays
      const activeVideoEl = document.querySelector('.swiper-slide-active .bg-video');
      if (activeVideoEl && activeVideoEl.paused) {
        playVideoSafely(activeVideoEl);
      }
      // Also resume swiper autoplay if it was paused (though it normally runs)
      if (swiper && swiper.autoplay && swiper.autoplay.paused) {
        swiper.autoplay.resume();
      }
    }
    
    // Listen to first user interaction (touch, click) to unblock any pending autoplay
    window.addEventListener('click', enableAutoplayOnFirstInteraction, { once: true });
    window.addEventListener('touchstart', enableAutoplayOnFirstInteraction, { once: true });
    
    // Also when swiper is dynamically changed and some video fails, we re-trigger
    // after a small delay for each slide transition retry (extra reliability)
    swiper.on('slideChangeTransitionEnd', function () {
      // small double-check: some mobile browsers might need a second attempt
      setTimeout(() => {
        const activeVid = document.querySelector('.swiper-slide-active .bg-video');
        if (activeVid && activeVid.paused) {
          playVideoSafely(activeVid);
        }
      }, 50);
    });
    
    // Handle visibility change (when user switches tab, stop videos to save resources)
    document.addEventListener('visibilitychange', function() {
      if (document.hidden) {
        // Tab hidden: pause all videos (optional but good performance)
        allVideos.forEach(v => { if (!v.paused) v.pause(); });
      } else {
        // Tab visible again: resume active slide video
        if (swiper) {
          const activeVid = document.querySelector('.swiper-slide-active .bg-video');
          if (activeVid && activeVid.paused) {
            playVideoSafely(activeVid);
          }
        }
      }
    });
    
    // Log that swiper is ready and navigation is fully functional
    console.log('✅ Swiper initialized: navigation, autoplay, and video autoplay fixed');
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