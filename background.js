document.addEventListener('DOMContentLoaded', function() {
  // Simple mobile detection for compatibility
  function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
           window.innerWidth <= 768;
  }

  const isMobile = isMobileDevice();

  if (isMobile) {
    // Mobile-friendly version with radial gradients (no blur effects)
    const backgroundHTML = `
      <div class="gradient-bg gradient-bg-mobile">
        <div class="gradients-container gradients-mobile">
          <div class="g1"></div>
          <div class="g2"></div>
          <div class="g3"></div>
          <div class="g4"></div>
          <div class="g5"></div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('afterbegin', backgroundHTML);
  } else {
    // Desktop version with SVG filters
    const backgroundHTML = `
      <div class="gradient-bg">
        <svg xmlns="http://www.w3.org/2000/svg">
          <defs>
            <filter id="goo">
              <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
              <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -8" result="goo" />
              <feBlend in="SourceGraphic" in2="goo" />
            </filter>
          </defs>
        </svg>
        <div class="gradients-container">
          <div class="g1"></div>
          <div class="g2"></div>
          <div class="g3"></div>
          <div class="g4"></div>
          <div class="g5"></div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('afterbegin', backgroundHTML);
  }
}); 