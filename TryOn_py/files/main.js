// ── Drop zone preview ──────────────────────────────────────────
function setupDropZone(zoneId, inputId) {
  const zone  = document.getElementById(zoneId);
  const input = document.getElementById(inputId);

  input.addEventListener('change', () => {
    const file = input.files[0];
    if (!file) return;

    const url = URL.createObjectURL(file);
    zone.querySelectorAll('.drop-icon, .drop-text').forEach(el => el.remove());

    let img = zone.querySelector('img');
    if (!img) { img = document.createElement('img'); zone.prepend(img); }
    img.src = url;
    img.alt = 'preview';
    zone.classList.add('has-file');
  });
}

setupDropZone('zone-bg',   'bg-file');
setupDropZone('zone-garm', 'garm-file');

// ── Helpers ────────────────────────────────────────────────────
function setLoading(on) {
  document.getElementById('btn-run').disabled = on;
  document.getElementById('spinner').classList.toggle('active', on);
  if (on) document.getElementById('result-section').classList.remove('visible');
}

function showStatus(msg, type) {
  const el = document.getElementById('status-msg');
  el.textContent = msg;
  el.className   = type;
}

function clearStatus() {
  const el = document.getElementById('status-msg');
  el.className   = '';
  el.textContent = '';
}

function showResult(url) {
  document.getElementById('result-img').src      = url;
  document.getElementById('download-link').href  = url;
  document.getElementById('result-section').classList.add('visible');
}

// ── Submit ─────────────────────────────────────────────────────
document.getElementById('btn-run').addEventListener('click', async () => {
  const bgFile   = document.getElementById('bg-file').files[0];
  const garmFile = document.getElementById('garm-file').files[0];

  if (!bgFile || !garmFile) {
    showStatus('Veuillez sélectionner les deux images.', 'error');
    return;
  }

  const fd = new FormData();
  fd.append('bg_img',          bgFile);
  fd.append('garm_img',        garmFile);
  fd.append('garment_des',     document.getElementById('garment-desc').value  || 'Un vêtement');
  fd.append('denoise_steps',   document.getElementById('denoise-steps').value);
  fd.append('seed',            document.getElementById('seed').value);
  fd.append('is_checked',      document.getElementById('is-checked').checked      ? '1' : '0');
  fd.append('is_checked_crop', document.getElementById('is-checked-crop').checked ? '1' : '0');

  setLoading(true);
  clearStatus();

  try {
    const res  = await fetch('http://localhost:5000/tryon', { method: 'POST', body: fd });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Erreur inconnue');
    showResult(data.url);
    showStatus('Image générée avec succès !', 'success');
  } catch (err) {
    showStatus('Erreur : ' + err.message, 'error');
  } finally {
    setLoading(false);
  }
});
