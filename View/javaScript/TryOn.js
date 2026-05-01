
    (function() {
      // ---------- Helpers ----------
      function setLoading(on) {
        const btn = document.getElementById('vt-btn-run');
        const spinner = document.getElementById('vt-spinner');
        const resultSection = document.getElementById('vt-result-section');
        if (btn) btn.disabled = on;
        if (spinner) spinner.classList.toggle('active', on);
        if (on && resultSection) resultSection.classList.remove('visible');
      }

      function showStatus(msg, type) {
        const el = document.getElementById('vt-status-msg');
        if (el) {
          el.textContent = msg;
          el.className = 'vt-status-msg ' + type;
          el.style.display = 'block';
        }
      }

      function clearStatus() {
        const el = document.getElementById('vt-status-msg');
        if (el) {
          el.className = 'vt-status-msg';
          el.textContent = '';
          el.style.display = 'none';
        }
      }

      function showResult(url) {
        const mainResult = document.getElementById('vt-result-image');
        if (mainResult) mainResult.src = url;
        const bottomResult = document.getElementById('vt-result-img');
        const downloadLink = document.getElementById('vt-download-link');
        const resultSection = document.getElementById('vt-result-section');
        if (bottomResult) bottomResult.src = url;
        if (downloadLink) downloadLink.href = url;
        if (resultSection) resultSection.classList.add('visible');
      }

      // ---------- Gestion uploads + preview ----------
      function setupPreview(btnId, inputId, previewId) {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (btn && input) {
          btn.addEventListener('click', () => input.click());
          input.addEventListener('change', () => {
            const file = input.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = (e) => { if (preview) preview.src = e.target.result; };
              reader.readAsDataURL(file);
            }
          });
        }
      }

      setupPreview('vt-user-upload-btn', 'vt-user-input', 'vt-user-preview');
      setupPreview('vt-product-upload-btn', 'vt-product-input', 'vt-product-preview');

      // ---------- Appel API ----------
      const runBtn = document.getElementById('vt-btn-run');
      if (runBtn) {
        runBtn.addEventListener('click', async (e) => {
          e.preventDefault();
          const bgFile = document.getElementById('vt-user-input').files[0];
          const garmFile = document.getElementById('vt-product-input').files[0];

          if (!bgFile || !garmFile) {
            showStatus('Veuillez sélectionner les deux images.', 'error');
            return;
          }

          const fd = new FormData();
          fd.append('bg_img', bgFile);
          fd.append('garm_img', garmFile);
          const garmentDes = document.querySelector('.vt-product-name')?.textContent || 'Un vêtement';
          fd.append('garment_des', garmentDes);
          fd.append('denoise_steps', '30');
          fd.append('seed', '42');
          fd.append('is_checked', '1');
          fd.append('is_checked_crop', '0');

          setLoading(true);
          clearStatus();

          try {
            const res = await fetch('http://localhost:5000/tryon', { method: 'POST', body: fd });
            if (!res.ok) {
              const errorData = await res.json();
              throw new Error(errorData.error || 'Erreur lors de la génération');
            }
            const data = await res.json();
            showResult(data.url);
            showStatus('Image générée avec succès !', 'success');
          } catch (err) {
            showStatus('Erreur : ' + err.message, 'error');
          } finally {
            setLoading(false);
          }
        });
      }

      // Animation du point vert
      const style = document.createElement('style');
      style.textContent = `@keyframes pulse { 0% { opacity: 0.4; transform: scale(0.8); } 50% { opacity: 1; transform: scale(1.2); } 100% { opacity: 0.4; transform: scale(0.8); } }`;
      document.head.appendChild(style);

      // ---------- Handle URL parameters ----------
      const urlParams = new URLSearchParams(window.location.search);
      const productImage = urlParams.get('productImage');
      if (productImage) {
          const preview = document.getElementById('vt-product-preview');
          const tryOnSection = document.getElementById('virtual-tryon');
          
          if (preview) {
              preview.src = decodeURIComponent(productImage);
              
              // Load the image into the file input
              fetch(decodeURIComponent(productImage))
                  .then(res => res.blob())
                  .then(blob => {
                      const file = new File([blob], "product.jpg", { type: blob.type });
                      const dataTransfer = new DataTransfer();
                      dataTransfer.items.add(file);
                      const input = document.getElementById('vt-product-input');
                      if (input) {
                          input.files = dataTransfer.files;
                          console.log("Product image loaded from URL into input.");
                      }
                  })
                  .catch(err => console.error("Error loading product image from URL:", err));
          }

          if (tryOnSection) {
              setTimeout(() => {
                  tryOnSection.scrollIntoView({ behavior: 'smooth' });
              }, 500);
          }
      }
      // ---------- Mix & Match Button ----------
      const mixMatchBtn = document.getElementById('vt-btn-mix-match');
      if (mixMatchBtn) {
        mixMatchBtn.addEventListener('click', () => {
          const meteo = document.getElementById('meteo')?.value || 'ete';
          const budget = document.getElementById('budget')?.value || '150';
          const productId = urlParams.get('productId');
          let url = `mix-match.php?meteo=${meteo}&budget=${budget}`;
          if (productId) {
            url += `&product_id=${productId}`;
          }
          window.location.href = url;
        });
      }

    })();