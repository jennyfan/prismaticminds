document.addEventListener('DOMContentLoaded', function() {
  // Enhanced feature detection for blur support
  function getBlurSupport() {
    // Check for SVG filter support (best quality)
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
    const blur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
    
    try {
      filter.appendChild(blur);
      svg.appendChild(filter);
      document.body.appendChild(svg);
      
      const supportsSVGFilters = typeof blur.stdDeviationX !== 'undefined' && 
                                !(/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
      
      document.body.removeChild(svg);
      
      if (supportsSVGFilters) {
        return 'svg';
      }
    } catch (e) {
      if (svg.parentNode) document.body.removeChild(svg);
    }
    
    // Check for CSS filter support (good fallback for modern mobile)
    const testElement = document.createElement('div');
    testElement.style.filter = 'blur(1px)';
    const supportsCSSBlur = testElement.style.filter === 'blur(1px)';
    
    if (supportsCSSBlur) {
      return 'css';
    }
    
    // Fallback to box-shadow blur effect
    return 'shadow';
  }

  const blurSupport = getBlurSupport();

  if (blurSupport === 'svg') {
    // Full animated background with SVG filters (desktop)
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
  } else if (blurSupport === 'css') {
    // CSS blur filters for modern mobile devices
    const backgroundHTML = `
      <div class="gradient-bg gradient-bg-css-blur">
        <div class="gradients-container gradients-css-blur">
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
    // Box-shadow blur fallback for older devices
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