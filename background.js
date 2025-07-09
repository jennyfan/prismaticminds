document.addEventListener('DOMContentLoaded', function() {
  // Feature detection for SVG filter support
  function supportsSVGFilters() {
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
    const blur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
    
    try {
      filter.appendChild(blur);
      svg.appendChild(filter);
      document.body.appendChild(svg);
      
      const supportsFilters = typeof blur.stdDeviationX !== 'undefined' && 
                             !(/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
      
      document.body.removeChild(svg);
      return supportsFilters;
    } catch (e) {
      if (svg.parentNode) document.body.removeChild(svg);
      return false;
    }
  }

  if (supportsSVGFilters()) {
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
    // Simple static gradient for devices without SVG filter support
    const backgroundHTML = `
      <div class="gradient-bg gradient-bg-static">
      </div>
    `;
    document.body.insertAdjacentHTML('afterbegin', backgroundHTML);
  }
}); 