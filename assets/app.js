// ================= CAROUSEL =================
function initCarousel(id) {
  const root = document.getElementById(id);
  if (!root) return;

  const slides = root.querySelector('.slides');
  if (!slides) return;
  
  const slideElements = slides.children;
  const total = slideElements.length;
  
  if (total === 0) return;
  
  let idx = 0;
  let autoInterval;

  function go(i) {
    idx = (i + total) % total;
    slides.style.transform = `translateX(${-idx * 100}%)`;
  }

  const nextBtn = root.querySelector('.carousel-next');
  const prevBtn = root.querySelector('.carousel-prev');

  if (nextBtn) {
    nextBtn.onclick = () => go(idx + 1);
  }
  
  if (prevBtn) {
    prevBtn.onclick = () => go(idx - 1);
  }

  function startAuto() {
    autoInterval = setInterval(() => go(idx + 1), 5000);
  }

  function stopAuto() {
    if (autoInterval) {
      clearInterval(autoInterval);
    }
  }

  startAuto();
  
  root.onmouseenter = stopAuto;
  root.onmouseleave = startAuto;
}

// ================= VIDEO ACTIONS =================
function attachCopyHandlers() {
  document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.onclick = async (e) => {
      e.preventDefault();
      const url = btn.dataset.url;
      
      if (!url) return;
      
      try {
        await navigator.clipboard.writeText(url);
        showNotification('Link gekopieerd!', 'success');
      } catch (err) {
        const fallback = prompt('Kopieer handmatig:', url);
      }
    };
  });
}

function attachPlayHandlers() {
  document.querySelectorAll('.play-btn').forEach(btn => {
    btn.onclick = (e) => {
      e.preventDefault();
      const url = btn.dataset.url;
      
      if (!url) return;
      
      window.open(url, '_blank', 'noopener,noreferrer');
    };
  });
}

// ================= NOTIFICATIONS =================
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `notification ${type}`;
  notification.textContent = message;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    background: rgba(26, 26, 46, 0.95);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    color: var(--text);
    z-index: 1000;
    animation: slideIn 0.3s ease;
  `;
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// ================= FORM VALIDATION =================
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return;
  
  form.addEventListener('submit', (e) => {
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
      if (!input.value.trim()) {
        isValid = false;
        input.style.borderColor = 'var(--danger)';
      } else {
        input.style.borderColor = '';
      }
    });
    
    if (!isValid) {
      e.preventDefault();
      showNotification('Vul alle verplichte velden in', 'error');
    }
  });
}

// ================= INITIALIZATION =================
document.addEventListener('DOMContentLoaded', () => {
  initCarousel('carousel');
  attachCopyHandlers();
  attachPlayHandlers();
  
  // Add CSS animations if not present
  if (!document.querySelector('#dynamic-animations')) {
    const style = document.createElement('style');
    style.id = 'dynamic-animations';
    style.textContent = `
      @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
      }
      @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  }
});