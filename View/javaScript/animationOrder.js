   const canvas = document.getElementById("scene");
    const ctx = canvas.getContext("2d");

    let w = 0;
    let h = 0;
    let dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));

    const world = {
      belt: null,
      hole: null,
      truck: null,
      truckSpawnX: 0,
      truckTargetX: 0,
      truckY: 0,
      box: null,
      phase: "backing",
      dropDelay: 2.2,
      fadeDuration: 0.7,
      departDelay: -1,
      beltOffset: 0,
      puffs: []
    };

    const wallLogo = new Image();
    wallLogo.crossOrigin = "anonymous";
    wallLogo.src = "../media/Untitled design.png";

    const boxLogo = new Image();
    boxLogo.crossOrigin = "anonymous";
    boxLogo.src = "../media/icon-removebg-preview.png";

    function clamp(v, min, max) {
      return Math.max(min, Math.min(max, v));
    }

    function lerp(a, b, t) {
      return a + (b - a) * t;
    }

    function rand(min, max) {
      return min + Math.random() * (max - min);
    }

    function normalizeAngle(a) {
      while (a > Math.PI) a -= Math.PI * 2;
      while (a < -Math.PI) a += Math.PI * 2;
      return a;
    }

    function lerpAngle(a, b, t) {
      const diff = normalizeAngle(b - a);
      return a + diff * t;
    }

    function nearestRightAngle(angle) {
      return Math.round(angle / (Math.PI * 0.5)) * (Math.PI * 0.5);
    }

    function roundRectPath(x, y, width, height, radius) {
      const r = Math.min(radius, width * 0.5, height * 0.5);
      ctx.beginPath();
      ctx.moveTo(x + r, y);
      ctx.lineTo(x + width - r, y);
      ctx.quadraticCurveTo(x + width, y, x + width, y + r);
      ctx.lineTo(x + width, y + height - r);
      ctx.quadraticCurveTo(x + width, y + height, x + width - r, y + height);
      ctx.lineTo(x + r, y + height);
      ctx.quadraticCurveTo(x, y + height, x, y + height - r);
      ctx.lineTo(x, y + r);
      ctx.quadraticCurveTo(x, y, x + r, y);
      ctx.closePath();
    }

    function drawImageContain(img, cx, cy, maxW, maxH) {
      if (!img || !img.complete || !img.naturalWidth || !img.naturalHeight) return;

      const scale = Math.min(maxW / img.naturalWidth, maxH / img.naturalHeight);
      const dw = img.naturalWidth * scale;
      const dh = img.naturalHeight * scale;

      ctx.drawImage(img, cx - dw * 0.5, cy - dh * 0.5, dw, dh);
    }

    function emitPuff(x, y, amount = 2) {
      for (let i = 0; i < amount; i++) {
        world.puffs.push({
          x: x + rand(-8, 8),
          y: y + rand(-4, 4),
          vx: rand(-22, 22),
          vy: rand(-30, -10),
          life: rand(0.35, 0.7),
          age: 0,
          size: rand(4, 10)
        });
      }
    }

    function getBoxCorners(box) {
      const half = box.size * 0.5;
      const c = Math.cos(box.angle);
      const s = Math.sin(box.angle);

      return [
        { x: box.x + (-half * c - -half * s), y: box.y + (-half * s + -half * c) },
        { x: box.x + ( half * c - -half * s), y: box.y + ( half * s + -half * c) },
        { x: box.x + ( half * c -  half * s), y: box.y + ( half * s +  half * c) },
        { x: box.x + (-half * c -  half * s), y: box.y + (-half * s +  half * c) }
      ];
    }

    function getBoxBounds(box) {
      const corners = getBoxCorners(box);
      let minX = Infinity;
      let maxX = -Infinity;
      let minY = Infinity;
      let maxY = -Infinity;

      for (const p of corners) {
        if (p.x < minX) minX = p.x;
        if (p.x > maxX) maxX = p.x;
        if (p.y < minY) minY = p.y;
        if (p.y > maxY) maxY = p.y;
      }

      return { minX, maxX, minY, maxY };
    }

    function resize() {
      w = window.innerWidth;
      h = window.innerHeight;
      canvas.width = Math.floor(w * dpr);
      canvas.height = Math.floor(h * dpr);
      canvas.style.width = w + "px";
      canvas.style.height = h + "px";
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      buildWorld();
    }

    window.addEventListener("resize", resize);

    function buildWorld() {
      const beltHeight = Math.max(18, h * 0.03);
      const beltTop = h * 0.58;
      const beltStart = w * 0.18;
      const beltEnd = w * 0.68;

      world.belt = {
        x1: beltStart,
        x2: beltEnd,
        top: beltTop,
        height: beltHeight,
        speed: Math.max(78, w * 0.085)
      };

      world.hole = {
        size: clamp(w * 0.05, 48, 64),
        x: beltStart + 10,
        y: h * 0.16
      };

      world.truckY = beltTop + h * 0.19;
      world.truckSpawnX = w + 300;

      spawnTruck();
      spawnBox();

      world.phase = "backing";
      world.dropDelay = 2.2;
      world.fadeDuration = 0.7;
      world.departDelay = -1;
      world.beltOffset = 0;
      world.puffs = [];
    }

    function spawnTruck() {
      const scale = clamp(w / 1200, 0.8, 1.15);
      const bedW = 190 * scale;
      const cabW = 82 * scale;
      const bodyH = 66 * scale;

      world.truck = {
        x: world.truckSpawnX,
        y: world.truckY,
        vx: 0,
        bedW,
        cabW,
        bodyH,
        width: bedW + cabW,
        wheelR: 15 * scale,
        blink: 0
      };

      world.truckTargetX = world.belt.x2 + bedW * 0.48 + 60;
    }

    function spawnBox() {
      const size = clamp(w * 0.034, 28, 42);
      world.box = {
        size,
        x: world.hole.x,
        y: world.hole.y,
        vx: 0,
        vy: 0,
        angle: rand(-0.08, 0.08),
        av: 0,
        alpha: 0,
        state: "queued",
        truckSettle: 0,
        onTruck: false
      };
    }

    function releaseBox() {
      const b = world.box;
      const hole = world.hole;

      b.state = "exitingHole";
      b.x = hole.x - hole.size * 0.08;
      b.y = hole.y + hole.size * 0.08;
      b.vx = rand(70, 110);
      b.vy = 0;
      b.angle = rand(-0.2, 0.2);
      b.av = rand(-2.8, 2.8);
      b.alpha = 1;
      b.truckSettle = 0;
      b.onTruck = false;
    }

    function getTruckBed() {
      const t = world.truck;
      return {
        left: t.x - t.bedW + 12,
        right: t.x - 12,
        top: t.y - t.bodyH + 12,
        floor: t.y - 12
      };
    }

    function updateTruck(dt) {
      const t = world.truck;
      t.blink += dt * 5;

      if (world.phase === "backing") {
        const dx = world.truckTargetX - t.x;
        t.vx = dx * 2.55;
        t.x += t.vx * dt;

        if (Math.abs(dx) < 1.2) {
          t.x = world.truckTargetX;
          t.vx = 0;
          world.phase = "loading";
        }
      } else if (world.phase === "departing") {
        t.vx = lerp(t.vx, 230, dt * 2.25);
        t.x += t.vx * dt;

        if (Math.random() < 0.16) {
          emitPuff(t.x - 10, t.y + t.wheelR + 6, 1);
        }

        if (t.x - t.width > w + 90) {
          spawnTruck();
          spawnBox();
          world.phase = "backing";
          world.dropDelay = 1.8;
          world.departDelay = -1;
        }
      }
    }

    function resolveHorizontalSurface(box, x1, x2, surfaceY, options) {
      const bounds = getBoxBounds(box);
      const overlap = bounds.maxX > x1 && bounds.minX < x2;

      if (!overlap) return false;
      if (bounds.maxY <= surfaceY) return false;
      if (box.vy < -20) return false;

      const penetration = bounds.maxY - surfaceY;
      box.y -= penetration;

      if (box.vy > 0) {
        box.vy *= -(options.bounce ?? 0.18);
      }

      if (Math.abs(box.vy) < 16) {
        box.vy = 0;
      }

      const carry = options.carry ?? 0;
      const friction = options.friction ?? 0.1;
      box.vx = lerp(box.vx, carry, friction);

      box.av *= options.spinDamp ?? 0.86;

      if (Math.abs(box.vy) < 18) {
        const target = nearestRightAngle(box.angle);
        box.angle = lerpAngle(box.angle, target, options.snap ?? 0.16);

        if (Math.abs(normalizeAngle(target - box.angle)) < 0.015) {
          box.angle = target;
          box.av *= 0.55;
        }
      }

      return true;
    }

    function resolveRightWall(box, wallX, y1, y2, bounce = 0.18) {
      const bounds = getBoxBounds(box);
      const overlapY = bounds.maxY > y1 && bounds.minY < y2;

      if (!overlapY) return false;
      if (bounds.maxX <= wallX) return false;

      const penetration = bounds.maxX - wallX;
      box.x -= penetration;

      if (box.vx > 0) {
        box.vx *= -bounce;
      }

      box.av *= 0.85;
      return true;
    }

    function updateBox(dt) {
      const b = world.box;
      const belt = world.belt;
      const bed = getTruckBed();
      const gravity = 1300;
      const half = b.size * 0.5;

      if (b.state === "queued") {
        b.x = world.hole.x;
        b.y = world.hole.y + world.hole.size * 0.08;
        b.vx = 0;
        b.vy = 0;
        b.av = 0;

        if (world.phase === "loading") {
          const fadeStart = world.fadeDuration;
          const progress = 1 - clamp(world.dropDelay / fadeStart, 0, 1);
          b.alpha = progress;
        } else {
          b.alpha = 0;
        }
        return;
      }

      if (b.state === "exitingHole") {
        b.x += b.vx * dt;
        b.angle += b.av * dt;
        b.av *= 0.996;

        const holeRight = world.hole.x + world.hole.size * 0.5;
        if (b.x + half > holeRight + 4) {
          b.state = "dynamic";
          b.vy = rand(8, 24);
          b.vx *= rand(0.92, 1.06);
          b.av += rand(-1.1, 1.1);
        }
        return;
      }

      b.vy += gravity * dt;
      b.x += b.vx * dt;
      b.y += b.vy * dt;
      b.angle += b.av * dt;

      let onBelt = false;
      let onTruckFloor = false;

      if (b.x + half > belt.x1 && b.x - half < belt.x2) {
        onBelt = resolveHorizontalSurface(
          b,
          belt.x1,
          belt.x2,
          belt.top,
          {
            carry: belt.speed,
            friction: 0.11,
            bounce: 0.16,
            spinDamp: 0.84,
            snap: 0.2
          }
        );
      }

      if (!onBelt && b.x + half > bed.left && b.x - half < bed.right) {
        onTruckFloor = resolveHorizontalSurface(
          b,
          bed.left,
          bed.right,
          bed.floor,
          {
            carry: world.phase === "departing" ? world.truck.vx : 0,
            friction: 0.16,
            bounce: 0.18,
            spinDamp: 0.82,
            snap: 0.16
          }
        );
      }

      if (b.x + half > bed.left && b.x - half < bed.right + 10 && b.y + half > bed.top) {
        resolveRightWall(b, bed.right, bed.top, bed.floor, 0.18);
      }

      if (onBelt) {
        b.onTruck = false;
        b.truckSettle = 0;
      }

      if (onTruckFloor) {
        b.onTruck = true;

        const movingWithTruck = world.phase === "departing" ? world.truck.vx : 0;
        if (Math.abs(b.vx - movingWithTruck) < 14 && Math.abs(b.vy) < 10 && Math.abs(b.av) < 0.9) {
          b.truckSettle += dt;
        } else {
          b.truckSettle = 0;
        }

        if (b.truckSettle > 0.35 && world.departDelay < 0) {
          world.departDelay = 0.45;
        }
      } else if (b.y < bed.top - 20 || b.x < bed.left - 20 || b.x > bed.right + 60) {
        b.onTruck = false;
        b.truckSettle = 0;
      }

      if (b.y > h + 200) {
        spawnBox();
        world.dropDelay = 0.8;
      }
    }

    function updatePuffs(dt) {
      for (let i = world.puffs.length - 1; i >= 0; i--) {
        const p = world.puffs[i];
        p.age += dt;
        p.x += p.vx * dt;
        p.y += p.vy * dt;
        p.vy += 45 * dt;

        if (p.age >= p.life) {
          world.puffs.splice(i, 1);
        }
      }
    }

    function update(dt) {
      world.beltOffset += world.belt.speed * dt;

      if (world.dropDelay > 0) {
        world.dropDelay -= dt;
      }

      updateTruck(dt);

      if (world.phase === "loading" && world.box.state === "queued" && world.dropDelay <= 0) {
        releaseBox();
      }

      updateBox(dt);
      updatePuffs(dt);

      if (world.departDelay >= 0) {
        world.departDelay -= dt;
        if (world.departDelay <= 0 && world.phase !== "departing") {
          world.phase = "departing";
        }
      }
    }

    function drawBackground() {
      const bg = ctx.createLinearGradient(0, 0, 0, h);
      bg.addColorStop(0, "#eef4f8");
      bg.addColorStop(1, "#dde6ed");
      ctx.fillStyle = bg;
      ctx.fillRect(0, 0, w, h);

      ctx.fillStyle = "#e6edf2";
      ctx.fillRect(0, h * 0.08, w, h * 0.1);

      ctx.fillStyle = "#c8d2da";
      for (let i = 0; i < 8; i++) {
        const x = (i / 7) * w;
        ctx.fillRect(x - 3, h * 0.12, 6, h * 0.56);
      }

      const signW = clamp(w * 0.26, 240, 420);
      const signH = signW / (721 / 137);
      const signX = w * 0.52 - signW * 0.5;
      const signY = h * 0.22 - signH * 0.5;

      ctx.save();
      ctx.fillStyle = "#f7fafc";
      roundRectPath(signX, signY, signW, signH, 10);
      ctx.fill();

      ctx.strokeStyle = "rgba(60,80,95,0.12)";
      ctx.lineWidth = 2;
      roundRectPath(signX, signY, signW, signH, 10);
      ctx.stroke();

      if (wallLogo.complete && wallLogo.naturalWidth) {
        drawImageContain(
          wallLogo,
          signX + signW * 0.5,
          signY + signH * 0.5,
          signW - 24,
          signH - 16
        );
      }
      ctx.restore();

      ctx.fillStyle = "#d9e1e7";
      ctx.fillRect(0, h * 0.72, w, h * 0.28);

      ctx.fillStyle = "#bcc7cf";
      ctx.fillRect(0, h * 0.72, w, 4);

      ctx.strokeStyle = "rgba(90,110,125,0.15)";
      ctx.lineWidth = 2;
      for (let x = -20; x < w + 40; x += 60) {
        ctx.beginPath();
        ctx.moveTo(x, h * 0.82);
        ctx.lineTo(x + 28, h * 0.78);
        ctx.stroke();
      }
    }

    function drawHole() {
      const hole = world.hole;
      ctx.save();
      ctx.fillStyle = "#151c22";
      ctx.fillRect(hole.x - hole.size * 0.5, hole.y - hole.size * 0.5, hole.size, hole.size);
      ctx.restore();
    }

    function drawConveyor() {
      const belt = world.belt;
      const beltY = belt.top - belt.height * 0.5;
      const outerTop = beltY - 4;
      const outerHeight = belt.height + 8;
      const outerBottom = outerTop + outerHeight;
      const supportHeight = h * 0.11;
      const legBaseY = outerBottom + supportHeight;
      const supportCount = 4;

      ctx.save();

      ctx.fillStyle = "#243443";
      roundRectPath(belt.x1, outerTop, belt.x2 - belt.x1, outerHeight, belt.height * 0.5 + 4);
      ctx.fill();

      ctx.fillStyle = "#3f5668";
      roundRectPath(belt.x1, beltY, belt.x2 - belt.x1, belt.height, belt.height * 0.5);
      ctx.fill();

      ctx.save();
      roundRectPath(belt.x1, beltY, belt.x2 - belt.x1, belt.height, belt.height * 0.5);
      ctx.clip();

      ctx.strokeStyle = "#8ea3b2";
      ctx.lineWidth = 4;
      const gap = 28;
      const len = 14;
      const travel = world.beltOffset % gap;

      for (let x = belt.x1 - gap; x < belt.x2 + gap; x += gap) {
        const px = x + travel;
        ctx.beginPath();
        ctx.moveTo(px, belt.top - 7);
        ctx.lineTo(px + len, belt.top - 7);
        ctx.stroke();
      }

      ctx.restore();

      ctx.strokeStyle = "#2f4353";
      ctx.lineWidth = 5;
      ctx.lineCap = "round";

      for (let i = 0; i < supportCount; i++) {
        const sx = lerp(belt.x1 + 48, belt.x2 - 48, supportCount === 1 ? 0.5 : i / (supportCount - 1));
        const topY = outerBottom;
        const baseSpread = 22;
        const leftBaseX = sx - baseSpread;
        const rightBaseX = sx + baseSpread;

        ctx.beginPath();
        ctx.moveTo(sx - 8, topY);
        ctx.lineTo(leftBaseX, legBaseY);
        ctx.moveTo(sx + 8, topY);
        ctx.lineTo(rightBaseX, legBaseY);
        ctx.stroke();

        ctx.strokeStyle = "#506575";
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(sx - 2, topY + 18);
        ctx.lineTo(sx + 2, legBaseY - 18);
        ctx.stroke();

        ctx.strokeStyle = "#2f4353";
        ctx.lineWidth = 5;
        ctx.beginPath();
        ctx.moveTo(sx - 16, topY + 6);
        ctx.lineTo(sx + 16, topY + 6);
        ctx.moveTo(leftBaseX - 8, legBaseY);
        ctx.lineTo(rightBaseX + 8, legBaseY);
        ctx.stroke();
      }

      ctx.fillStyle = "#2b3c49";
      ctx.fillRect(belt.x1 + 18, outerBottom + 3, belt.x2 - belt.x1 - 36, 6);

      ctx.restore();
    }

    function drawTruck() {
      const t = world.truck;
      const scale = t.bodyH / 66;

      ctx.save();
      ctx.translate(t.x, t.y);

      ctx.fillStyle = "rgba(0,0,0,0.08)";
      ctx.beginPath();
      ctx.ellipse(-t.bedW * 0.35, t.wheelR + 16, t.bedW * 0.65, t.wheelR * 0.85, 0, 0, Math.PI * 2);
      ctx.fill();

      ctx.fillStyle = "#5a7282";
      roundRectPath(-t.bedW, -t.bodyH + 8, t.bedW, t.bodyH - 12, 10);
      ctx.fill();

      ctx.fillStyle = "#273640";
      roundRectPath(-t.bedW + 14, -t.bodyH + 18, t.bedW - 28, t.bodyH - 34, 8);
      ctx.fill();

      ctx.fillStyle = "#dbe4ea";
      roundRectPath(-t.bedW + 10, -t.bodyH + 10, t.bedW - 20, 6, 3);
      ctx.fill();

      ctx.fillStyle = "#5a7282";
      ctx.beginPath();
      ctx.moveTo(8, -t.bodyH + 14);
      ctx.lineTo(t.cabW - 16, -t.bodyH + 14);
      ctx.lineTo(t.cabW + 2, -t.bodyH + 34);
      ctx.lineTo(t.cabW + 2, -8);
      ctx.lineTo(0, -8);
      ctx.lineTo(0, 0);
      ctx.lineTo(8, 0);
      ctx.closePath();
      ctx.fill();

      ctx.fillStyle = "#dcecf6";
      ctx.beginPath();
      ctx.moveTo(18, -t.bodyH + 24);
      ctx.lineTo(t.cabW - 20, -t.bodyH + 24);
      ctx.lineTo(t.cabW - 8, -t.bodyH + 38);
      ctx.lineTo(t.cabW - 14, -22);
      ctx.lineTo(18, -22);
      ctx.closePath();
      ctx.fill();

      const blinkOn = Math.sin(t.blink) > 0;
      ctx.fillStyle = blinkOn && world.phase === "backing" ? "#ffe88d" : "#e3c56d";
      ctx.fillRect(-t.bedW - 6, -t.bodyH + 18, 6, 12);

      ctx.fillStyle = "#ffdcb0";
      ctx.fillRect(t.cabW - 2, -16, 7, 10);

      const wheelXs = [-t.bedW + 40 * scale, -t.bedW + 132 * scale, t.cabW - 20 * scale];
      for (const wx of wheelXs) {
        ctx.fillStyle = "#141b20";
        ctx.beginPath();
        ctx.arc(wx, 7, t.wheelR + 1.5, 0, Math.PI * 2);
        ctx.fill();

        ctx.fillStyle = "#7a8790";
        ctx.beginPath();
        ctx.arc(wx, 7, t.wheelR * 0.48, 0, Math.PI * 2);
        ctx.fill();
      }

      ctx.restore();
    }

    function drawBoxShape(x, y, size) {
      ctx.fillStyle = "#d3a15f";
      ctx.fillRect(x, y, size, size);

      ctx.fillStyle = "#b88950";
      ctx.fillRect(x, y, size, 6);
      ctx.fillRect(x, y, 6, size);

      ctx.strokeStyle = "#8a6435";
      ctx.lineWidth = 2;
      ctx.strokeRect(x, y, size, size);

      ctx.strokeStyle = "#efcf9b";
      ctx.beginPath();
      ctx.moveTo(x + size * 0.5, y);
      ctx.lineTo(x + size * 0.5, y + size);
      ctx.moveTo(x + 6, y + size * 0.5);
      ctx.lineTo(x + size - 6, y + size * 0.5);
      ctx.stroke();

      if (boxLogo.complete && boxLogo.naturalWidth) {
        const pad = size * 0.12;
        ctx.save();
        ctx.globalAlpha = 0.95;
        drawImageContain(
          boxLogo,
          x + size * 0.5,
          y + size * 0.5,
          size - pad * 2,
          size - pad * 2
        );
        ctx.restore();
      }
    }

    function drawBox() {
      const b = world.box;
      if (!b) return;

      ctx.save();
      ctx.globalAlpha = b.alpha == null ? 1 : b.alpha;

      if (b.state === "queued" || b.state === "exitingHole") {
        ctx.save();
        ctx.beginPath();
        ctx.rect(
          world.hole.x - world.hole.size * 0.5,
          world.hole.y - world.hole.size * 0.5,
          world.hole.size,
          world.hole.size
        );
        ctx.clip();

        ctx.translate(b.x, b.y);
        ctx.rotate(b.angle);
        drawBoxShape(-b.size * 0.5, -b.size * 0.5, b.size);
        ctx.restore();

        if (b.state === "exitingHole") {
          const holeRight = world.hole.x + world.hole.size * 0.5;
          if (b.x + b.size * 0.5 > holeRight) {
            ctx.save();
            ctx.translate(b.x, b.y);
            ctx.rotate(b.angle);
            ctx.beginPath();
            ctx.rect(holeRight, -1000, w, 2000);
            ctx.clip();
            drawBoxShape(-b.size * 0.5, -b.size * 0.5, b.size);
            ctx.restore();
          }
        }
      } else {
        ctx.translate(b.x, b.y);
        ctx.rotate(b.angle);
        drawBoxShape(-b.size * 0.5, -b.size * 0.5, b.size);
      }

      ctx.restore();
    }

    function drawPuffs() {
      for (const p of world.puffs) {
        const alpha = 1 - p.age / p.life;
        ctx.fillStyle = `rgba(120,132,144,${alpha * 0.22})`;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size * (0.7 + p.age * 1.15), 0, Math.PI * 2);
        ctx.fill();
      }
    }

    function draw() {
      ctx.clearRect(0, 0, w, h);
      drawBackground();
      drawHole();
      drawConveyor();
      drawTruck();
      drawBox();
      drawPuffs();
    }

    let last = performance.now();

    function frame(now) {
      const dt = Math.min(0.033, (now - last) / 1000);
      last = now;
      update(dt);
      draw();
      requestAnimationFrame(frame);
    }

    resize();
    requestAnimationFrame(frame);