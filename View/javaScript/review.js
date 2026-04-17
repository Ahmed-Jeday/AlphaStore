// ========================
// WRITE A REVIEW - COMPLETE JS MODULE
// ========================

let reviewsData = [];
let ratingCounts = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
let totalRatings = 0;
let totalSum = 0;

// ---------- API CALLS ----------

async function getAllReview() {
    const params = new URLSearchParams(window.location.search);
    const productId = params.get('id');
    if (!productId) return;

    try {
        // Going through index.php as requested by user
        const response = await fetch(`../../index.php?action=getAllReview&productId=${productId}`);
        const data = await response.json();
        if (data.success) {
            reviewsData = data.data || [];
            refreshAllReviews();
        }
    } catch(err) { console.error("Error loading reviews:", err); }
}

async function addReviewToDB(rating, title, body) {
    const params = new URLSearchParams(window.location.search);
    let productId = params.get('id');
    const hiddenProductId = document.getElementById('reviewProductId');
    if (!productId && hiddenProductId) {
        productId = hiddenProductId.value;
    }

    if (!productId) {
        alert('Impossible de déterminer le produit. Rechargez la page et réessayez.');
        return;
    }

    try {
        const response = await fetch(`../../index.php?action=addReview`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId, rating, title, body })
        });

        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (parseErr) {
            console.error('Invalid JSON response from review API:', text);
            alert('A server error occurred. Please try again.');
            return;
        }

        if (!response.ok) {
            console.error('Review API returned HTTP error:', response.status, data);
            alert('A server error occurred. Please try again.');
            return;
        }

        if (data.success) {
            alert('Review submitted successfully! ✨');
            await getAllReview();
            closeReviewModal();
        } else {
            alert('Error: ' + (data.message || 'Could not save review. Please sign in first.'));
        }
    } catch(err) {
        console.error('Error saving review:', err);
        alert('A server error occurred. Please try again.');
    }
}

// ---------- CALCULATIONS ----------

function computeStatistics() {
    totalRatings = reviewsData.length;
    totalSum = reviewsData.reduce((sum, review) => sum + parseInt(review.rating), 0);
    
    ratingCounts = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
    reviewsData.forEach(review => {
        ratingCounts[review.rating]++;
    });
    
    const average = totalRatings === 0 ? 0 : totalSum / totalRatings;
    return { total: totalRatings, average, counts: ratingCounts };
}

// ---------- DISPLAY FUNCTIONS ----------

function generateStars(rating, size = 16) {
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHtml += `<svg class="star" width="${size}" height="${size}" viewBox="0 0 16 16">
                <polygon points="8,1 10.2,5.8 15.5,6.3 11.6,9.9 12.8,15.1 8,12.4 3.2,15.1 4.4,9.9 0.5,6.3 5.8,5.8" fill="var(--black)"/>
            </svg>`;
        } else {
            starsHtml += `<svg class="star star-empty" width="${size}" height="${size}" viewBox="0 0 16 16">
                <polygon points="8,1 10.2,5.8 15.5,6.3 11.6,9.9 12.8,15.1 8,12.4 3.2,15.1 4.4,9.9 0.5,6.3 5.8,5.8"/>
            </svg>`;
        }
    }
    return starsHtml;
}

function renderAverageStars(average, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.innerHTML = '';
    const fullStars = Math.floor(average);
    const hasHalfStar = (average - fullStars) >= 0.5;
    
    for (let i = 1; i <= 5; i++) {
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("viewBox", "0 0 16 16");
        svg.classList.add("star");
        svg.style.width = "16px";
        svg.style.height = "16px";
        
        if (i <= fullStars) {
            const polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
            polygon.setAttribute("points", "8,1 10.2,5.8 15.5,6.3 11.6,9.9 12.8,15.1 8,12.4 3.2,15.1 4.4,9.9 0.5,6.3 5.8,5.8");
            polygon.setAttribute("fill", "var(--black)");
            svg.appendChild(polygon);
        } else if (i === fullStars + 1 && hasHalfStar) {
            // Half star simulation
            const poly = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
            poly.setAttribute("points", "8,1 10.2,5.8 15.5,6.3 11.6,9.9 12.8,15.1 8,12.4 3.2,15.1 4.4,9.9 0.5,6.3 5.8,5.8");
            poly.setAttribute("fill", "var(--black)");
            svg.appendChild(poly);
        } else {
            const polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
            polygon.setAttribute("points", "8,1 10.2,5.8 15.5,6.3 11.6,9.9 12.8,15.1 8,12.4 3.2,15.1 4.4,9.9 0.5,6.3 5.8,5.8");
            polygon.setAttribute("fill", "none");
            polygon.setAttribute("stroke", "var(--mid-gray)");
            polygon.setAttribute("stroke-width", "1.5");
            svg.appendChild(polygon);
        }
        container.appendChild(svg);
    }
}

function updateRatingBars() {
    const { total, counts } = computeStatistics();
    const barGrid = document.getElementById('ratingBars');
    if (!barGrid) return;
    
    barGrid.innerHTML = '';
    for (let stars = 5; stars >= 1; stars--) {
        const count = counts[stars] || 0;
        const percentage = total === 0 ? 0 : (count / total) * 100;
        
        const row = document.createElement('div');
        row.className = 'bar-row';
        row.innerHTML = `
            <span>${stars}</span>
            <div class="bar-track"><div class="bar-fill" style="width: ${percentage}%"></div></div>
            <span class="bar-count">${count}</span>
        `;
        barGrid.appendChild(row);
    }
}

function renderAllReviews() {
    const reviewsContainer = document.getElementById('dynamicReviewsList');
    if (!reviewsContainer) return;
    
    if (reviewsData.length === 0) {
        reviewsContainer.innerHTML = '<p style="text-align:center; color:var(--mid-gray); padding: 40px 0;">No reviews yet. Be the first to write one!</p>';
        return;
    }
    
    reviewsContainer.innerHTML = reviewsData.map(review => {
        const displayDate = review.created_at ? new Date(review.created_at).toLocaleDateString() : "Recently";
        return `
        <div class="review-card">
            <div class="rv-header">
                <div class="stars">${generateStars(review.rating, 13)}</div>
            </div>
            <p class="rv-title">${escapeHtml(review.title)}</p>
            <p class="rv-meta">${escapeHtml(review.author || 'Anonymous')} · ${displayDate}</p>
            <p class="rv-body">${escapeHtml(review.body)}</p>
        </div>
    `;}).join('');
}

function updateReviewsHeader() {
    const { total, average } = computeStatistics();
    const avgDisplay = document.getElementById('avgRatingDisplay');
    const totalText = document.getElementById('totalReviewsText');
    const ratingsCount = document.getElementById('totalRatingsCount');
    
    if (avgDisplay) avgDisplay.textContent = average.toFixed(1);
    if (totalText) totalText.textContent = `${total} Star Ratings`;
    if (ratingsCount) ratingsCount.textContent = `${total} Ratings`;
    
    renderAverageStars(average, 'avgStarsContainer');
    renderAverageStars(average, 'avgStarsLarge');
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
    });
}

function refreshAllReviews() {
    updateReviewsHeader();
    updateRatingBars();
    renderAllReviews();
}

// ---------- MODAL & FORM ----------

function openReviewModal() {
    const modal = document.getElementById('reviewModal');
    if (modal) modal.classList.add('active');
}

function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    if (modal) {
        modal.classList.remove('active');
        const form = document.getElementById('reviewForm');
        if (form) form.reset();
        updateStarSelection(0);
        clearModalErrors();
    }
}

function clearModalErrors() {
    ['ratingError', 'titleError', 'bodyError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '';
    });
}

function updateStarSelection(rating) {
    const starSelects = document.querySelectorAll('#starRatingInput .star-select');
    starSelects.forEach(star => {
        const value = parseInt(star.getAttribute('data-value'));
        star.classList.toggle('selected', value <= rating);
    });
    document.getElementById('selectedRating').value = rating;
}

function initStarRatingModal() {
    const starSelects = document.querySelectorAll('#starRatingInput .star-select');
    starSelects.forEach(star => {
        star.addEventListener('mouseenter', () => {
            const hVal = parseInt(star.getAttribute('data-value'));
            starSelects.forEach(s => s.classList.toggle('hovered', parseInt(s.getAttribute('data-value')) <= hVal));
        });
        star.addEventListener('mouseleave', () => starSelects.forEach(s => s.classList.remove('hovered')));
        star.addEventListener('click', () => updateStarSelection(parseInt(star.getAttribute('data-value'))));
    });
}

function setupReviewForm() {
    const form = document.getElementById('reviewForm');
    if (!form) return;
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const rating = parseInt(document.getElementById('selectedRating').value);
        const title = document.getElementById('reviewTitle').value.trim();
        const body = document.getElementById('reviewBody').value.trim();
        
        let isValid = true;
        clearModalErrors();
        
        if (!rating) { document.getElementById('ratingError').textContent = 'Rating required'; isValid = false; }
        if (!title) { document.getElementById('titleError').textContent = 'Title required'; isValid = false; }
        if (!body) { document.getElementById('bodyError').textContent = 'Review body required'; isValid = false; }
        
        if (isValid) {
            await addReviewToDB(rating, title, body);
        }
    });
}

function initWriteReview() {
    const writeBtn = document.getElementById('writeReviewBtn');
    if (writeBtn) writeBtn.addEventListener('click', openReviewModal);
    
    const closeBtn = document.getElementById('closeModalBtn');
    if (closeBtn) closeBtn.addEventListener('click', closeReviewModal);

    const modalOverlay = document.getElementById('reviewModal');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeReviewModal();
        });
    }

    const params = new URLSearchParams(window.location.search);
    const productId = params.get('id');
    if (productId) {
        const hiddenInput = document.getElementById('reviewProductId');
        if (hiddenInput) hiddenInput.value = productId;
    }
    
    initStarRatingModal();
    setupReviewForm();
    getAllReview();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWriteReview);
} else {
    initWriteReview();
}