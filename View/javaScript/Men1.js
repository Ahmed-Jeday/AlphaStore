/* ─────────────────────────────────────────
   URBANE — main.js
   ───────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
    gsap.registerPlugin(ScrollTrigger);

    /* ══════════════════════════════════════
       CUSTOM CURSOR
    ══════════════════════════════════════ */
    const cursor = document.getElementById('cursor');

    document.addEventListener('mousemove', e => {
        gsap.to(cursor, {
            x: e.clientX,
            y: e.clientY,
            duration: 0.15,
            ease: 'power2.out'
        });
    });

    document.querySelectorAll('a, button, .product-card, .cat-card').forEach(el => {
        el.addEventListener('mouseenter', () => cursor.classList.add('grow'));
        el.addEventListener('mouseleave', () => cursor.classList.remove('grow'));
    });

    /* ══════════════════════════════════════
       CAROUSEL — original logic preserved
    ══════════════════════════════════════ */
    const scroller       = document.querySelector('.scroller');
    const track          = document.querySelector('.scroll-track');
    const slides         = gsap.utils.toArray('.scroll-marker');
    const bgImgs         = gsap.utils.toArray('.bg-img');
    const descItems      = gsap.utils.toArray('.description-wrap p');
    const captionItems   = gsap.utils.toArray('.caption-item');
    const indicatorLinks = gsap.utils.toArray('.indicators a');
    const progressFill   = document.querySelector('.progress-fill');

    // Progress bar
    gsap.fromTo(progressFill,
        { scaleX: 0 },
        {
            scaleX: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: track,
                scroller: scroller,
                start: 'left left',
                end: () => `+=${track.offsetWidth - scroller.offsetWidth}`,
                scrub: true,
                horizontal: true
            }
        }
    );

    // Individual slide animations
    slides.forEach((slide, i) => {
        const bg      = bgImgs[i];
        const desc    = descItems[i];
        const caption = captionItems[i];
        const link    = indicatorLinks[i];

        if (i === 0) {
            // First slide: full screen, scales & fades out
            gsap.set(bg, { clipPath: 'inset(0% 0% 0% 0% round 0rem)', autoAlpha: 1 });

            gsap.timeline({
                scrollTrigger: {
                    trigger: slide,
                    scroller,
                    horizontal: true,
                    start: 'left left',
                    end: 'right left',
                    scrub: true,
                    onToggle: self => link.classList.toggle('active', self.isActive)
                }
            }).to(bg, { scale: 1.5, x: '-16%', autoAlpha: 0, ease: 'none' });

        } else {
            // Other slides: expand from card preview
            gsap.timeline({
                scrollTrigger: {
                    trigger: slide,
                    scroller,
                    horizontal: true,
                    start: 'left right',
                    end: 'right right',
                    scrub: true,
                    onEnter:     () => gsap.set(bg, { autoAlpha: 1 }),
                    onLeaveBack: () => gsap.set(bg, { autoAlpha: 0 }),
                    onToggle: self => link.classList.toggle('active', self.isActive)
                }
            }).fromTo(bg,
                { clipPath: 'inset(15% 2% 15% 85% round 5rem)', scale: 1, x: '0%' },
                { clipPath: 'inset(0% 0% 0% 0% round 0rem)', ease: 'none' }
            );

            gsap.timeline({
                scrollTrigger: {
                    trigger: slide,
                    scroller,
                    horizontal: true,
                    start: 'left left',
                    end: 'right left',
                    scrub: true
                }
            }).to(bg, { scale: 1.5, x: '-16%', autoAlpha: 0, ease: 'none' });
        }

        // Content (description + caption) fade in/out
        const contentTl = gsap.timeline({
            scrollTrigger: {
                trigger: slide,
                scroller,
                horizontal: true,
                start: 'left center',
                end: 'right center',
                scrub: true
            }
        });

        contentTl
            .fromTo([desc, caption],
                { autoAlpha: 0, y: 50 },
                { autoAlpha: 1, y: 0, duration: 0.5, stagger: 0.1 }
            )
            .to([desc, caption],
                { autoAlpha: 0, y: -50, duration: 0.5, stagger: 0.1 },
                '+=0.5'
            );
    });

    // Indicator clicks
    indicatorLinks.forEach((link, i) => {
        link.addEventListener('click', e => {
            e.preventDefault();
            scroller.scrollTo({ left: slides[i].offsetLeft, behavior: 'smooth' });
        });
    });

    // Mouse wheel → horizontal scroll
    scroller.addEventListener('wheel', e => {
        if (e.deltaY !== 0) {
            const isAtStart = scroller.scrollLeft <= 0;
            const isAtEnd   = scroller.scrollLeft + scroller.offsetWidth >= scroller.scrollWidth - 1;
            if ((e.deltaY > 0 && !isAtEnd) || (e.deltaY < 0 && !isAtStart)) {
                e.preventDefault();
                scroller.scrollLeft += e.deltaY;
            }
        }
    }, { passive: false });

    /* ══════════════════════════════════════
       SCROLL REVEALS (sections below carousel)
    ══════════════════════════════════════ */
    gsap.utils.toArray('.reveal').forEach(el => {
        gsap.fromTo(el,
            { opacity: 0, y: 45 },
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%',
                    toggleActions: 'play none none none'
                }
            }
        );
    });

    /* ══════════════════════════════════════
       BESTSELLERS — drag-to-scroll
    ══════════════════════════════════════ */
    const row = document.getElementById('bestRow');
    let isDragging = false;
    let startX, scrollLeft;

    row.addEventListener('mousedown', e => {
        isDragging = true;
        startX     = e.pageX - row.offsetLeft;
        scrollLeft = row.scrollLeft;
    });

    row.addEventListener('mouseleave', () => { isDragging = false; });
    row.addEventListener('mouseup',    () => { isDragging = false; });

    row.addEventListener('mousemove', e => {
        if (!isDragging) return;
        e.preventDefault();
        const x    = e.pageX - row.offsetLeft;
        const walk = (x - startX) * 1.5;
        row.scrollLeft = scrollLeft - walk;
    });
});
