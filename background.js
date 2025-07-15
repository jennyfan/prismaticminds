document.addEventListener('DOMContentLoaded', function() {
  // Simplified feature detection: SVG or box-shadow fallback
  function getBlurSupport() {
    // Test for SVG filter support
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
    const blur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
    
    try {
      filter.appendChild(blur);
      svg.appendChild(filter);
      document.body.appendChild(svg);
      
      const supportsSVGFilters = typeof blur.stdDeviationX !== 'undefined';
      
      document.body.removeChild(svg);
      
      if (supportsSVGFilters) {
        return 'svg';
      }
    } catch (e) {
      if (svg.parentNode) document.body.removeChild(svg);
    }
    
    // Default to box-shadow fallback for everything else
    return 'shadow';
  }

  const blurSupport = getBlurSupport();

  if (blurSupport === 'svg') {
    // Full animated background with SVG filters
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
  } else {
    // Box-shadow blur fallback for maximum compatibility
    const backgroundHTML = `
      <div class="gradient-bg gradient-bg-shadow-blur">
        <div class="gradients-container gradients-shadow-blur">
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