//loader animation
document.querySelector("html").classList.add("scroll-hide");
function updateProgressBar(progress) {
  const progressBar = document.getElementById("progress-bar");
  progressBar.style.width = progress + "%";
}

function simulateProgress() {
  let progress = 0;
  const interval = setInterval(() => {
    progress += 1;
    updateProgressBar(progress);
    if (progress === 100) {
      clearInterval(interval);
      setTimeout(() => {
        const loader_text = document.querySelectorAll('.loader-text h3');
        loader_text.forEach((text, index) => {
          const loader_single_text = new SplitType(text, {
            types: 'chars'
          });
          gsap.from(loader_single_text.chars, {
            opacity: 0,
            x: 50,
            duration: 0.5,
            stagger: 0.1,
            delay: 0.8
          });
        })

        gsap.to(".progress-wrapper", 1.2, {
          scale: 1.5,
          opacity: 0,
          display: "none",
          ease: "power3.inOut",
          delay: 0.2
        });

        gsap.to(".revealer", 2.2, {
          top: "0%",
          ease: "power3.inOut",
          delay: 1
        });

        gsap.to(".loader", 1, {
          yPercent: -100,
          ease: "power3.inOut",
          delay: 1.9
        });
        setTimeout(() => {
          document.querySelector("html").classList.remove("scroll-hide");
        }, 2600);

      }, 500);
    }
  }, 0);
}

window.addEventListener('load', function () {
  simulateProgress();
});



//Nav links animation
const splitTypes = document.querySelectorAll('.nav-links li a');
splitTypes.forEach((link, i) => {
  const text = new SplitType(link, {
    types: 'chars'
  });

  text.chars.forEach((char, i) => {
    char.style.transitionDelay = `${i * 0.05}s`;
    char.setAttribute('data-letter', char.textContent);
  });
});

//Slider animation
let slides = document.querySelectorAll('.img-slider ul li');
let secondSlides = document.querySelectorAll('.second-img-slider ul li');
let thirdSlides = document.querySelectorAll('.third-img-slider ul li');
let second_slider = document.querySelector('.second-img-slider');

let currentActive = 1;
let secondCurrentActive = 2;
let thirdCurrentActive = 0;

const img_slider_first_child = document.querySelector('.img-slider li:first-child');
const img_slider_last_child = document.querySelector('.img-slider li:last-child');
const third_img_slider_first_child = document.querySelector('.third-img-slider li:first-child');
const second_img_slider_last_child = document.querySelector('.second-img-slider li:last-child');

if (img_slider_first_child.classList.contains('show_class') && third_img_slider_first_child.classList.contains('active')) {
  third_img_slider_first_child.style.opacity = '0';
} else {
  third_img_slider_first_child.style.opacity = '1';
}


gsap.to('.img-slider li, .second-img-slider li, .third-img-slider li', {
  clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)',
  duration: 0.5,
  delay: 3.2,
});


window.addEventListener('click', (event) => {
  setTimeout(() => {
    if (img_slider_first_child.classList.contains('show_class') && third_img_slider_first_child.classList.contains('active')) {
      third_img_slider_first_child.style.opacity = '0';
    } else {
      third_img_slider_first_child.style.opacity = '1';
    }
    if (img_slider_last_child.classList.contains('show_class') && second_img_slider_last_child.classList.contains('active')) {
      second_slider.classList.add('clip');
    } else {
      second_slider.classList.remove('clip');
    }
  }, 201);

  if (event.clientX > window.innerWidth / 2) {
    if (currentActive < slides.length) {
      slides[currentActive].classList.add('active');
      currentActive++;
    }
    if (currentActive >= 2 && secondCurrentActive < secondSlides.length) {
      secondSlides[secondCurrentActive].classList.add('active');
      secondCurrentActive++;
    }
    if (currentActive >= 2 && thirdCurrentActive < thirdSlides.length - 1) {
      thirdSlides[thirdCurrentActive].classList.add('active');
      thirdCurrentActive++;
    }
  } else {
    if (currentActive > 1 && currentActive <= slides.length) {
      slides[currentActive - 1].classList.remove('active');
      currentActive--;
    }
    if (currentActive <= slides.length - 2 && secondCurrentActive > 2) {
      secondSlides[secondCurrentActive - 1].classList.remove('active');
      secondCurrentActive--;
    }
    if (currentActive <= slides.length - 1 && thirdCurrentActive > 0) {
      thirdSlides[thirdCurrentActive - 1].classList.remove('active');
      thirdCurrentActive--;
    }
  }
});


const second_imgSlider = document.querySelector('.second-img-slider ul');
let second_timer;
window.addEventListener('mousemove', function (e) {
  clearTimeout(second_timer);
  let xPos = e.clientX - window.innerWidth / 2;
  if (xPos < 0) {
    xPos = 0;
  }
  const rotation = xPos > 0 ? 15 : 0;
  gsap.to(second_imgSlider, {
    x: `${xPos}px`,
    rotation: rotation,
    duration: 1,
    ease: 'power2.out'
  });
  second_timer = setTimeout(function () {
    gsap.to(second_imgSlider, {
      rotation: 0,
      duration: 0.5,
      ease: 'power2.out'
    });
  }, 500);
});

const third_imgSlider = document.querySelector('.third-img-slider ul');
let third_timer;
window.addEventListener('mousemove', function (e) {
  clearTimeout(third_timer);
  let xPos = e.clientX - window.innerWidth / 2;
  if (xPos > 0) {
    xPos = 0;
  }
  const rotation = xPos < 0 ? -15 : 0;
  gsap.to(third_imgSlider, {
    x: `${xPos}px`,
    rotation: rotation,
    duration: 1,
    ease: 'power2.out'
  });
  third_timer = setTimeout(function () {
    gsap.to(third_imgSlider, {
      rotation: 0,
      duration: 0.5,
      ease: 'power2.out'
    });
  }, 500);
});



//content slider
const contentItems = document.querySelectorAll('.content-slider li');
let content_activeIndex = 0;

window.addEventListener('click', function (e) {
  setTimeout(() => {
    if (e.clientX > window.innerWidth / 2) {
      if (content_activeIndex < contentItems.length - 1) {
        content_activeIndex++;
      }
      contentItems[content_activeIndex].classList.add('active');
    } else {
      if (content_activeIndex > 0) {
        contentItems[content_activeIndex].classList.remove('active');
        content_activeIndex--;
      }
    }
  }, 200);
});

//img height calculation — géré en CSS (calc(100% - 110px))


//text animation
const details = document.querySelectorAll('.img-slider li');
let details_activeIndex = 0;

window.addEventListener('click', function (e) {
  setTimeout(() => {
    if (e.clientX > window.innerWidth / 2) {
      if (details_activeIndex < details.length - 1) {
        details[details_activeIndex].classList.remove('show_class');
        details_activeIndex++;
        details[details_activeIndex].classList.add('show_class');
        animateText(details[details_activeIndex]);
      }
    } else {
      if (details_activeIndex > 0) {
        details[details_activeIndex].classList.remove('show_class');
        details_activeIndex--;
        details[details_activeIndex].classList.add('show_class');
        animateText(details[details_activeIndex]);
      }
    }
  }, 200);
});

function animateText(element) {
  const splitTypes = element.querySelectorAll('.img-details h6');
  splitTypes.forEach((char, i) => {
    const text = new SplitType(char, {
      types: 'chars'
    });
    gsap.from(text.chars, {
      y: 50,
      duration: 0.5,
      stagger: 0.1,
    });
  });
  const splitTypes_profession = element.querySelectorAll('.img-details span');
  splitTypes_profession.forEach((char, i) => {
    const text = new SplitType(char, {
      types: 'chars'
    });
    gsap.from(text.chars, {
      y: 50,
      duration: 0.5,
      stagger: 0.1,
      delay: 0.2
    });
  });
}
setTimeout(() => {
  animateText(details[0]);
}, 3000);

// Mouse Cursor Animation — limité à la zone du slider (.h)
// Mouse Cursor Animation
gsap.set(".arrow", { xPercent: -50, yPercent: -50 });
const arrow = document.querySelector(".arrow");
const pos = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
const mouse = { x: pos.x, y: pos.y };
const speed = 0.15;

const xSet = gsap.quickSetter(arrow, "x", "px");
const ySet = gsap.quickSetter(arrow, "y", "px");

// Zone limitée = le ul de img-slider
const sliderZone = document.querySelector('.img-slider ul');

window.addEventListener("mousemove", (e) => {
  const rect = sliderZone.getBoundingClientRect();

  // Vérifie si la souris est dans la zone de l'image
  const inZone = (
    e.clientX >= rect.left &&
    e.clientX <= rect.right &&
    e.clientY >= rect.top &&
    e.clientY <= rect.bottom
  );

  if (inZone) {
    arrow.style.opacity = 1;
    mouse.x = e.x;
    mouse.y = e.y;

    const img = document.querySelector('.arrow-img-wrapper');
    if (e.clientX > rect.left + rect.width / 2) {
      img.style.transform = 'scaleX(-1)';
    } else {
      img.style.transform = 'scaleX(1)';
    }
  } else {
    arrow.style.opacity = 0;
  }
});

gsap.ticker.add(() => {
  const dt = 1.0 - Math.pow(1.0 - speed, gsap.ticker.deltaRatio());
  pos.x += (mouse.x - pos.x) * dt;
  pos.y += (mouse.y - pos.y) * dt;
  xSet(pos.x);
  ySet(pos.y);
});