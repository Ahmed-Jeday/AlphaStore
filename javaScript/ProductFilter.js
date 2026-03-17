// ── Configuration ─────────────────────────────────────────────────────────
const MAX_VISIBLE = 7;

// ── DOM References ─────────────────────────────────────────────────────────
const filters = [...document.querySelectorAll(".filter")];
const grid = document.querySelector(".products");
const allProducts = [...document.querySelectorAll(".product")];

// ── Build the "See More" card ──────────────────────────────────────────────
const seeMoreCard = document.createElement("div");
const seeLessBtn = document.getElementById("see-less-btn");
seeMoreCard.className = "see-more-card";
seeMoreCard.innerHTML = `
  <div class="see-more-inner">
  <img  src="../img/right-arrow.png" alt="">
  
 
    
    <strong>See More</strong>
    <span id="remaining-count"></span>
    <div class="see-more-badge" id="see-more-badge"></div>
  </div>
`;
grid.appendChild(seeMoreCard);

// ── Animation settings (read from CSS transition) ──────────────────────────
const computedStyle = getComputedStyle(allProducts[0]);
const duration = parseFloat(computedStyle.transitionDuration) * 1000 || 350;
const easing = computedStyle.transitionTimingFunction || "ease";
const animationSettings = { duration, easing };

// ── State ──────────────────────────────────────────────────────────────────
let isAnimating = false;
let showAll = false;
let currentFilter = null;

// ── Helpers ────────────────────────────────────────────────────────────────

/**
 * Returns the list of products that match the current filter.
 * If filter is null, all products match.
 */
function getMatchingProducts(filter) {
  return allProducts.filter(p =>
    filter == null || p.dataset.filter.split(",").includes(filter)
  );
}

/**
 * Applies is-hidden to products outside the current selection/pagination,
 * and updates the "See More" card visibility + badge count.
 */
function applyVisibility() {
  const matched = getMatchingProducts(currentFilter);
  const limit = showAll ? Infinity : MAX_VISIBLE;
  const overflow = matched.slice(limit); // products over the limit

  allProducts.forEach(p => {
    const inMatch = matched.includes(p);
    const overLimit = overflow.includes(p);
    if (!inMatch || overLimit) {
      p.classList.add("is-hidden");
    } else {
      p.classList.remove("is-hidden");
    }
  });

  // Show/hide the "See More" card
  const hasMore = matched.length > MAX_VISIBLE && !showAll;
  if (hasMore) {
    seeMoreCard.classList.remove("is-hidden");
    const extra = matched.length - MAX_VISIBLE;
    document.getElementById("remaining-count").textContent = "";
    document.getElementById("see-more-badge").textContent  = `+${extra} articles`;
    seeLessBtn.classList.remove("is-visible");
  } else {
    seeMoreCard.classList.add("is-hidden");
    if (showAll && matched.length > MAX_VISIBLE) {
      seeLessBtn.classList.add("is-visible");
    } else {
      seeLessBtn.classList.remove("is-visible");
    }
  }
}

// ── Animated filter selection ──────────────────────────────────────────────
const makeSelection = async (filter) => {
  if (isAnimating) return;
  isAnimating = true;
  showAll = false;

  // Toggle active state on filters
  filters.filter(f => f !== filter).forEach(f => f.classList.remove("is-active"));
  filter.classList.toggle("is-active");
  currentFilter = filter.classList.contains("is-active") ? filter.dataset.filter : null;

  // Snapshot starting positions
  grid.__start = grid.offsetHeight;
  allProducts.forEach(p => {
    p.__start = {
      hidden: p.classList.contains("is-hidden"),
      left: p.offsetLeft,
      top: p.offsetTop
    };
  });

  // Apply new visibility
  applyVisibility();

  // Snapshot ending positions
  allProducts.forEach(p => {
    p.__end = {
      hidden: p.classList.contains("is-hidden"),
      left: p.offsetLeft,
      top: p.offsetTop
    };
  });
  grid.__end = grid.offsetHeight;

  // Animate grid height
  const gridAnimation = grid.animate(
    [{ height: `${grid.__start}px` }, { height: `${grid.__end}px` }],
    animationSettings
  );

  // Animate each product card
  const productAnimations = allProducts.map(p => {
    const prev = p.__start.hidden;
    const curr = p.__end.hidden;
    const newlyHidden = !prev && curr;
    const newlyVisible = prev && !curr;
    const persistent = !prev && !curr;

    const x = p.__start.left - p.__end.left;
    const y = p.__start.top - p.__end.top;

    // Pin newly-hidden cards so they don't jump when absolutely positioned
    if (newlyHidden) {
      p.style.left = `${p.__start.left}px`;
      p.style.top = `${p.__start.top}px`;
    }

    // Outer animation: translate (persistent) or scale in/out
    const outer = p.animate([
      { transform: persistent ? `translate(${x}px, ${y}px)` : `scale(${prev ? 0 : 1})` },
      { transform: persistent ? `translate(0, 0)` : `scale(${curr ? 0 : 1})` }
    ], { ...animationSettings });

    // Inner counter-scale so content doesn't squish with the card
    const inner = p.querySelector(".product__inner");
    const innerAnim = inner.animate([
      { transform: `scale(${newlyVisible ? "2, 2" : "1, 1"})` },
      { transform: `scale(${newlyHidden ? "2, 2" : "1, 1"})` }
    ], animationSettings);

    return Promise.all([outer.finished, innerAnim.finished]).then(() => {
      if (newlyHidden) { p.style.left = ""; p.style.top = ""; }
    });
  });

  await Promise.all([...productAnimations, gridAnimation]);
  isAnimating = false;
};

// ── "See More" click ───────────────────────────────────────────────────────
seeMoreCard.addEventListener("click", () => {
  showAll = true;
  applyVisibility();
  seeMoreCard.scrollIntoView({ behavior: "smooth", block: "center" });
});

seeLessBtn.addEventListener("click", () => {
  showAll = false;
  applyVisibility();
  grid.scrollIntoView({ behavior: "smooth", block: "start" });
});
// ── Resize handler (keep explicit card dimensions consistent) ──────────────
const resize = () => {
  allProducts.forEach(p => { p.style.height = ""; p.style.width = ""; });
  const ref = allProducts.find(p => !p.classList.contains("is-hidden"));
  if (!ref) return;
  const { height, width } = getComputedStyle(ref);
  allProducts.forEach(p => { p.style.height = height; p.style.width = width; });
};

// ── Init ───────────────────────────────────────────────────────────────────
applyVisibility();
resize();
window.addEventListener("resize", resize);
filters.forEach(f => f.addEventListener("click", () => makeSelection(f)));