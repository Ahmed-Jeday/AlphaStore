        // --- TEXT SPLITTING ---
        // Manually split hero text for animation control
        const words = document.querySelectorAll('.hero h1 .word');
        words.forEach(word => {
            const text = word.innerText;
            word.innerHTML = '';
            text.split('').forEach(char => {
                const span = document.createElement('span');
                span.classList.add('char');
                span.innerText = char;
                word.appendChild(span);
            });
        });

        // --- MAGNET & CURSOR ---
        const cursor = document.getElementById('cursor');
        const magneticElements = document.querySelectorAll('.magnetic');

        let mouseX = 0, mouseY = 0;
        let cursorX = 0, cursorY = 0;

        window.addEventListener('mousemove', e => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function lerp(start, end, factor) {
            return start + (end - start) * factor;
        }

        function animateCursor() {
            cursorX = lerp(cursorX, mouseX, 0.15);
            cursorY = lerp(cursorY, mouseY, 0.15);
            cursor.style.transform = `translate(${cursorX}px, ${cursorY}px) translate(-50%, -50%)`;
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        magneticElements.forEach(el => {
            el.addEventListener('mousemove', (e) => {
                const rect = el.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                const dist = 0.5; // Magnetic strength

                const moveX = (e.clientX - centerX) * dist;
                const moveY = (e.clientY - centerY) * dist;

                el.style.transform = `translate(${moveX}px, ${moveY}px)`;
                cursor.classList.add('magnet');
            });

            el.addEventListener('mouseleave', () => {
                el.style.transform = 'translate(0, 0)';
                cursor.classList.remove('magnet');
            });
        });

        // --- NAVBAR STATE & 3D TILT ---
        const nav = document.querySelector('.brutal-nav');
        let isScrolled = false;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                if (!isScrolled) {
                    nav.classList.add('scrolled');
                    isScrolled = true;
                }
            } else {
                if (isScrolled) {
                    nav.classList.remove('scrolled');
                    nav.style.transform = '';
                    isScrolled = false;
                }
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (!isScrolled) return;
            const cx = window.innerWidth / 2;
            const cy = 100; // Pivot near top

            // Subtle tilt
            const rx = (e.clientY - cy) * 0.02;
            const ry = (e.clientX - cx) * 0.02;

            // Constrain
            const clamp = (num, min, max) => Math.min(Math.max(num, min), max);

            nav.style.transform = `translateX(-50%) perspective(1000px) rotateX(${-clamp(rx, -10, 10)}deg) rotateY(${clamp(ry, -10, 10)}deg)`;
        });

        // --- SCROLL VELOCITY SKEW ---
        const content = document.getElementById('scroll-content');
        let currentScroll = 0;
        let targetScroll = 0;
        let skew = 0;

        // Note: For native scroll skewing, we just monitor scroll speed
        // We aren't hijacking scroll here (keeping it native for UX), just adding effect
        let lastScrollTop = 0;

        function scrollLoop() {
            const scrollTop = window.scrollY;
            const velocity = scrollTop - lastScrollTop;
            lastScrollTop = scrollTop;

            // Smooth skew approach
            // Target skew is based on velocity
            // We clamp it to avoid too much distortion
            const maxSkew = 5.0;
            const speed = Math.min(Math.max(velocity * 0.1, -maxSkew), maxSkew);

            // Lerp current skew to target speed
            skew = lerp(skew, speed, 0.1);

            // round to avoid blurry pixel issues if needed, but smooth is better for transform
            if (Math.abs(skew) > 0.01) {
                content.style.transform = `skewY(${skew}deg)`;
            } else {
                content.style.transform = `skewY(0deg)`;
            }

            requestAnimationFrame(scrollLoop);
        }
        scrollLoop();

        // --- HACKER TEXT RE-INIT ---
        const alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        document.querySelectorAll('[data-text]').forEach(link => {
            link.addEventListener('mouseenter', event => {
                let iter = 0;
                const original = event.target.dataset.text;
                clearInterval(event.target.interval);

                event.target.interval = setInterval(() => {
                    event.target.innerText = original.split("")
                        .map((l, i) => {
                            if (i < iter) return original[i];
                            return alpha[Math.floor(Math.random() * 26)]
                        })
                        .join("");

                    if (iter >= original.length) clearInterval(event.target.interval);
                    iter += 1 / 3;
                }, 30);
            });
            link.addEventListener('mouseleave', e => {
                clearInterval(e.target.interval);
                e.target.innerText = e.target.dataset.text;
            });
        });
