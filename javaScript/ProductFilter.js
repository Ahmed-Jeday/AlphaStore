const filters = [...document.querySelectorAll(".filter")];
const grid = document.querySelector(".products");
const products = [...document.querySelectorAll(".product")];

// Get the computed style of the first product
const computedStyle = getComputedStyle(products[0]);
// Get druation and easing from the computed style so the animation settings match the CSS transition
const duration =
	parseFloat(computedStyle.transitionDuration.replace("ms", "")) * 1000;
const easing = computedStyle.transitionTimingFunction;
const animationSettings = { duration, easing };

let isAnimating = false;

const makeSelection = async (filter) => {
	// Do not start a new animation if one is currently running
	if (isAnimating) return;
	// Notate that an animation is currently running
	isAnimating = true;

	// Find the otherFilters (the ones you didn't click on)
	const otherFilters = filters.filter((f) => f != filter);
	// Deselect the otherFilters
	otherFilters.forEach((f) => f.classList.remove("is-active"));
	// Toggle the selection of current filter
	filter.classList.toggle("is-active");

	// Get the value of selected filter
	const selection = filter.classList.contains("is-active")
		? filter.dataset.filter
		: null;

	// Mark non-matched products to be hidden
	const hiddenProducts = products.filter(
		// Products can belong to more than one category. The data-filter attribute can be a list of filter valuse separated by a comma (,)
		// Make sure that there was a selection made, and if so make sure the product's list of filter categories does not include the selection
		(p) => selection != null && !p.dataset.filter.split(",").includes(selection)
	);
	// Mark matched products to be visible
	const activeProducts = products.filter(
		// Products can belong to more than one category. The data-filter attribute can be a list of filter valuse separated by a comma (,)
		// Make sure that there either wasn't a selection made, or if there was make sure the product's list of filter categories includes the selection
		(p) => selection == null || p.dataset.filter.split(",").includes(selection)
	);

	// Get the initial height of the grid to smoothly animate the size
	grid.__start = grid.offsetHeight;

	// Get the initial position data for each product
	products.forEach((p) => {
		p.__start = {
			hidden: p.classList.contains("is-hidden"),
			left: p.offsetLeft,
			top: p.offsetTop
		};
	});

	// Apply the visibility changes to each product
	hiddenProducts.forEach((p) => p.classList.add("is-hidden"));
	activeProducts.forEach((p) => p.classList.remove("is-hidden"));

	// Get the final position data for each product
	products.forEach((p) => {
		p.__end = {
			hidden: p.classList.contains("is-hidden"),
			left: p.offsetLeft,
			top: p.offsetTop
		};
	});

	// Get the final height of the grid to smoothly animate the size
	grid.__end = grid.offsetHeight;

	// Animate the height of the grid from the initial height to the new hight after the products have been filtered
	const gridAnimation = grid.animate(
		[
			{
				height: `${grid.__start}px`
			},
			{
				height: `${grid.__end}px`
			}
		],
		animationSettings
	);

	// Do the product animations, and return an array that contains promises for each of the products collection of animations
	const productAnimations = products.map((p, i) => {
		// Was this product previously hidden?
		const previouslyHidden = p.__start.hidden;
		// Is this product currently hidden?
		const currentlyHidden = p.__end.hidden;

		// Was the product previously hidden and becoming visible?
		const newlyHidden = !previouslyHidden && currentlyHidden;
		// Was the product previously visible and becoming hidden?
		const newlyVisible = previouslyHidden && !currentlyHidden;
		// Was the product perviously visible and staying visible?
		const persistentlyVisible = !previouslyHidden && !currentlyHidden;

		// Get the difference from the starting and ending positions of the product card so we can animate it from where it was to where it's going (only happens if the product is persistentlyVisible)
		const x = p.__start.left - p.__end.left;
		const y = p.__start.top - p.__end.top;

		// If the product is newlyHidden apply left and top style values from the starting offset. This way the element will remain in the same position after it becomes absolutely positioned and is animating out of the grid
		if (newlyHidden) {
			p.style.left = `${p.__start.left}px`;
			p.style.top = `${p.__start.top}px`;
		}

		// Animate the product's position if it's persistentlyVisible, or animate its scale if it's hidden
		const outerAnimation = p.animate(
			[
				{
					...(persistentlyVisible
						? { transform: `translate(${x}px, ${y}px)` }
						: {
								transform: `scale(${previouslyHidden ? 0 : 1}, ${
									previouslyHidden ? 0 : 1
								})`
						  })
				},
				{
					...(persistentlyVisible
						? { transform: `translate(0, 0)` }
						: {
								transform: `scale(${currentlyHidden ? 0 : 1}, ${
									currentlyHidden ? 0 : 1
								})`
						  })
				}
			],
			{ ...animationSettings }
		);

		// Get the inner container
		const inner = p.querySelector(".product__inner");
		// Animate the scale of the inner element to the opposite scale of the outer element. This will make it look like the container is shrinking around the contents of the product card, rather than the contents of the card scaling down with it
		const innerAnimation = inner.animate(
			[
				{
					transform: `scale(${newlyVisible ? "2, 2" : "1, 1"})`
				},
				{
					transform: `scale(${newlyHidden ? "2, 2" : "1, 1"})`
				}
			],
			animationSettings
		);

		// Return a new Promise that resolves once the outer and inner animations have completed, and then cleans up the temp position styles
		return Promise.all([outerAnimation.finished, innerAnimation.finished]).then(
			() => {
				// Clean up the top and left styles we added to newlyHidden products
				if (newlyHidden) {
					p.style.left = "";
					p.style.top = "";
				}
			}
		);
	});

	// Wait for every animation to finish
	await Promise.all([...productAnimations, gridAnimation]);
	// Notate that all current animations have completed
	isAnimating = false;
};

// Set an explicit height/width to the product cards so they dont grow or shrink when they become absolutely positioned and are removed from the page. The Height/Width value is grabbed from the first product on the page that isn't hidden. This way we set everything to the same explicit size. This is recalculated as the screen size changes
const resize = () => {
	products.forEach((p) => {
		p.style.height = "";
		p.style.width = "";
	});
	const { height, width } = getComputedStyle(
		products.find((p) => !p.classList.contains("is-hidden"))
	);
	products.forEach((p) => {
		p.style.height = `${height}px`;
		p.style.width = `${width}px`;
	});
};

// Apply the initial explicit height/width of the product cards
resize();
// Add resize event listener to recalculate explicit height/width
window.addEventListener("resize", resize);
// Add event listener to do the filtering/animation on click
filters.forEach((f) => f.addEventListener("click", () => makeSelection(f)));
