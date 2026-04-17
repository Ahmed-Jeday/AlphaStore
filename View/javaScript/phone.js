(function(){
    // ----- SCOPED DOM ELEMENTS -----
    const container = document.querySelector('.privacy-phone-module');
    const phone = document.getElementById('phoneDevice');
    const screen = document.getElementById('screenDisplay');
    const notif = document.getElementById('notifCard');
    const angleValSpan = document.getElementById('angleValue');
    const modeBtns = document.querySelectorAll('.mode-btn');
    const snooper = document.getElementById('phone-snooper');

    // ----- generate corner 3D layers (preserve depth) -----
    const corners = ['tl', 'tr', 'bl', 'br'];
    corners.forEach(c => {
        const cornerEl = document.createElement('div');
        cornerEl.className = `corner corner-${c}`;
        for (let i = 1; i <= 16; i++) {
            const layer = document.createElement('div');
            layer.className = 'c-layer';
            layer.style.transform = `translateZ(-${i}px)`;
            cornerEl.appendChild(layer);
        }
        phone.appendChild(cornerEl);
    });

    // ----- state -----
    let mode = 'full';
    let targetRx = 0, targetRy = 0, targetPriv = 0;
    let rx = 0, ry = 0, priv = 0;
    let hasInteracted = false;
    let time = 0;
    let animationId = null;

    // ----- helper lerp -----
    function lerp(a, b, t) { return a + (b - a) * t; }

    // ----- update mode buttons UI -----
    modeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modeBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            mode = btn.dataset.mode;
        });
    });

    // ----- reset tilt & privacy (when mouse leaves) -----
    function resetTiltAndInteraction() {
        hasInteracted = false;
        targetRx = 0;
        targetRy = 0;
        targetPriv = 0;
        angleValSpan.textContent = '0°';
        if (snooper) snooper.style.opacity = '0';
    }

    // ----- update snooper position (only inside container) -----
    function updateSnooperPosition(clientX, clientY) {
        if (!snooper) return;
        snooper.style.transform = `translate(${clientX}px, ${clientY}px) translate(-50%, -50%)`;
        snooper.style.opacity = '1';
    }

    // ----- mouse move handler (only inside module container) -----
    function onContainerMouseMove(e) {
        if (!phone) return;
        hasInteracted = true;
        const rect = phone.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;
        const dx = e.clientX - cx;
        const dy = e.clientY - cy;
        const maxDist = Math.min(window.innerWidth, window.innerHeight) * 0.45;
        let dist = Math.sqrt(dx * dx + dy * dy);
        let normDist = Math.min(dist / maxDist, 1);
        const maxTilt = 40;
        let newRy = (dx / maxDist) * maxTilt;
        let newRx = -(dy / maxDist) * maxTilt;
        targetRy = Math.max(-maxTilt, Math.min(maxTilt, newRy));
        targetRx = Math.max(-maxTilt, Math.min(maxTilt, newRx));
        let angle = Math.round(normDist * 85);
        targetPriv = Math.pow(normDist, 1.3) * 0.95;
        angleValSpan.textContent = angle + '°';
        updateSnooperPosition(e.clientX, e.clientY);
    }

    function onContainerMouseLeave() {
        resetTiltAndInteraction();
        if (snooper) snooper.style.opacity = '0';
    }

    function onContainerMouseEnter(e) {
        // optional: just ensures snooper shows on move
        if (snooper) snooper.style.opacity = '0';
    }

    // Touch events for mobile
    function onContainerTouchMove(e) {
        e.preventDefault();
        if (!phone || !e.touches.length) return;
        hasInteracted = true;
        const touch = e.touches[0];
        const rect = phone.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;
        const dx = touch.clientX - cx;
        const dy = touch.clientY - cy;
        const maxDist = Math.min(window.innerWidth, window.innerHeight) * 0.45;
        let dist = Math.sqrt(dx * dx + dy * dy);
        let normDist = Math.min(dist / maxDist, 1);
        const maxTilt = 40;
        targetRy = Math.max(-maxTilt, Math.min(maxTilt, (dx / maxDist) * maxTilt));
        targetRx = Math.max(-maxTilt, Math.min(maxTilt, -(dy / maxDist) * maxTilt));
        let angle = Math.round(normDist * 85);
        targetPriv = Math.pow(normDist, 1.3) * 0.95;
        angleValSpan.textContent = angle + '°';
        if (snooper) {
            snooper.style.transform = `translate(${touch.clientX}px, ${touch.clientY}px) translate(-50%, -50%)`;
            snooper.style.opacity = '1';
        }
    }

    function onContainerTouchEnd(e) {
        resetTiltAndInteraction();
        if (snooper) snooper.style.opacity = '0';
    }

    // attach events to container (module)
    if (container) {
        container.addEventListener('mousemove', onContainerMouseMove);
        container.addEventListener('mouseleave', onContainerMouseLeave);
        container.addEventListener('mouseenter', onContainerMouseEnter);
        container.addEventListener('touchmove', onContainerTouchMove, { passive: false });
        container.addEventListener('touchend', onContainerTouchEnd);
        container.addEventListener('touchstart', (e) => { hasInteracted = true; }, { passive: true });
    }

    // ----- animation loop (tilt smoothing + privacy layers)-----
    function tick() {
        if (!hasInteracted) {
            time += 0.015;
            targetRy = Math.sin(time) * 25;
            targetRx = Math.cos(time * 0.8) * 15;
            let dist = Math.sqrt(targetRx * targetRx + targetRy * targetRy);
            let maxTiltCheck = 40;
            let normDist = Math.min(dist / maxTiltCheck, 1);
            targetPriv = Math.pow(normDist, 1.3) * 0.95;
            let angleDeg = Math.round(normDist * 85);
            angleValSpan.textContent = angleDeg + '°';
        }
        rx = lerp(rx, targetRx, 0.08);
        ry = lerp(ry, targetRy, 0.08);
        priv = lerp(priv, targetPriv, 0.1);
        if (phone) phone.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg)`;
        if (mode === 'full') {
            if (screen) screen.style.setProperty('--priv-opacity', priv);
            if (notif) notif.style.setProperty('--notif-priv', 0);
        } else {
            if (screen) screen.style.setProperty('--priv-opacity', 0);
            if (notif) notif.style.setProperty('--notif-priv', priv);
        }
        animationId = requestAnimationFrame(tick);
    }
    tick();

    // ensure reset also works when leaving window (optional but safe)
    window.addEventListener('beforeunload', ()=>{
        if(animationId) cancelAnimationFrame(animationId);
    });
})();// your code goes here
(function(){
    // ----- SCOPED DOM ELEMENTS -----
    const container = document.querySelector('.privacy-phone-module');
    const phone = document.getElementById('phoneDevice');
    const screen = document.getElementById('screenDisplay');
    const notif = document.getElementById('notifCard');
    const angleValSpan = document.getElementById('angleValue');
    const modeBtns = document.querySelectorAll('.mode-btn');
    const snooper = document.getElementById('phone-snooper');

    // ----- generate corner 3D layers (preserve depth) -----
    const corners = ['tl', 'tr', 'bl', 'br'];
    corners.forEach(c => {
        const cornerEl = document.createElement('div');
        cornerEl.className = `corner corner-${c}`;
        for (let i = 1; i <= 16; i++) {
            const layer = document.createElement('div');
            layer.className = 'c-layer';
            layer.style.transform = `translateZ(-${i}px)`;
            cornerEl.appendChild(layer);
        }
        phone.appendChild(cornerEl);
    });

    // ----- state -----
    let mode = 'full';
    let targetRx = 0, targetRy = 0, targetPriv = 0;
    let rx = 0, ry = 0, priv = 0;
    let hasInteracted = false;
    let time = 0;
    let animationId = null;

    // ----- helper lerp -----
    function lerp(a, b, t) { return a + (b - a) * t; }

    // ----- update mode buttons UI -----
    modeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modeBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            mode = btn.dataset.mode;
        });
    });

    // ----- reset tilt & privacy (when mouse leaves) -----
    function resetTiltAndInteraction() {
        hasInteracted = false;
        targetRx = 0;
        targetRy = 0;
        targetPriv = 0;
        angleValSpan.textContent = '0°';
        if (snooper) snooper.style.opacity = '0';
    }

    // ----- update snooper position (only inside container) -----
    function updateSnooperPosition(clientX, clientY) {
        if (!snooper) return;
        snooper.style.transform = `translate(${clientX}px, ${clientY}px) translate(-50%, -50%)`;
        snooper.style.opacity = '1';
    }

    // ----- mouse move handler (only inside module container) -----
    function onContainerMouseMove(e) {
        if (!phone) return;
        hasInteracted = true;
        const rect = phone.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;
        const dx = e.clientX - cx;
        const dy = e.clientY - cy;
        const maxDist = Math.min(window.innerWidth, window.innerHeight) * 0.45;
        let dist = Math.sqrt(dx * dx + dy * dy);
        let normDist = Math.min(dist / maxDist, 1);
        const maxTilt = 40;
        let newRy = (dx / maxDist) * maxTilt;
        let newRx = -(dy / maxDist) * maxTilt;
        targetRy = Math.max(-maxTilt, Math.min(maxTilt, newRy));
        targetRx = Math.max(-maxTilt, Math.min(maxTilt, newRx));
        let angle = Math.round(normDist * 85);
        targetPriv = Math.pow(normDist, 1.3) * 0.95;
        angleValSpan.textContent = angle + '°';
        updateSnooperPosition(e.clientX, e.clientY);
    }

    function onContainerMouseLeave() {
        resetTiltAndInteraction();
        if (snooper) snooper.style.opacity = '0';
    }

    function onContainerMouseEnter(e) {
        // optional: just ensures snooper shows on move
        if (snooper) snooper.style.opacity = '0';
    }

    // Touch events for mobile
    function onContainerTouchMove(e) {
        e.preventDefault();
        if (!phone || !e.touches.length) return;
        hasInteracted = true;
        const touch = e.touches[0];
        const rect = phone.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;
        const dx = touch.clientX - cx;
        const dy = touch.clientY - cy;
        const maxDist = Math.min(window.innerWidth, window.innerHeight) * 0.45;
        let dist = Math.sqrt(dx * dx + dy * dy);
        let normDist = Math.min(dist / maxDist, 1);
        const maxTilt = 40;
        targetRy = Math.max(-maxTilt, Math.min(maxTilt, (dx / maxDist) * maxTilt));
        targetRx = Math.max(-maxTilt, Math.min(maxTilt, -(dy / maxDist) * maxTilt));
        let angle = Math.round(normDist * 85);
        targetPriv = Math.pow(normDist, 1.3) * 0.95;
        angleValSpan.textContent = angle + '°';
        if (snooper) {
            snooper.style.transform = `translate(${touch.clientX}px, ${touch.clientY}px) translate(-50%, -50%)`;
            snooper.style.opacity = '1';
        }
    }

    function onContainerTouchEnd(e) {
        resetTiltAndInteraction();
        if (snooper) snooper.style.opacity = '0';
    }

    // attach events to container (module)
    if (container) {
        container.addEventListener('mousemove', onContainerMouseMove);
        container.addEventListener('mouseleave', onContainerMouseLeave);
        container.addEventListener('mouseenter', onContainerMouseEnter);
        container.addEventListener('touchmove', onContainerTouchMove, { passive: false });
        container.addEventListener('touchend', onContainerTouchEnd);
        container.addEventListener('touchstart', (e) => { hasInteracted = true; }, { passive: true });
    }

    // ----- animation loop (tilt smoothing + privacy layers)-----
    function tick() {
        if (!hasInteracted) {
            time += 0.015;
            targetRy = Math.sin(time) * 25;
            targetRx = Math.cos(time * 0.8) * 15;
            let dist = Math.sqrt(targetRx * targetRx + targetRy * targetRy);
            let maxTiltCheck = 40;
            let normDist = Math.min(dist / maxTiltCheck, 1);
            targetPriv = Math.pow(normDist, 1.3) * 0.95;
            let angleDeg = Math.round(normDist * 85);
            angleValSpan.textContent = angleDeg + '°';
        }
        rx = lerp(rx, targetRx, 0.08);
        ry = lerp(ry, targetRy, 0.08);
        priv = lerp(priv, targetPriv, 0.1);
        if (phone) phone.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg)`;
        if (mode === 'full') {
            if (screen) screen.style.setProperty('--priv-opacity', priv);
            if (notif) notif.style.setProperty('--notif-priv', 0);
        } else {
            if (screen) screen.style.setProperty('--priv-opacity', 0);
            if (notif) notif.style.setProperty('--notif-priv', priv);
        }
        animationId = requestAnimationFrame(tick);
    }
    tick();

    // ensure reset also works when leaving window (optional but safe)
    window.addEventListener('beforeunload', ()=>{
        if(animationId) cancelAnimationFrame(animationId);
    });
})();