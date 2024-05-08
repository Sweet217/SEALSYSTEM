<script>
export default {
    name: 'NavbarComponent',
};
// Wait for the gradient animation to finish (2 seconds) HEADER COMPONENT EFFECT
setTimeout(() => {
  // Unhide the navbar element
  document.getElementById('mainNavigation').style.display = 'block';

  // Apply the bounce animation
  document.getElementById('mainNavigation').style.animationName = 'bounce';
  document.getElementById('mainNavigation').style.animationDuration = '1s';
  document.getElementById('mainNavigation').style.animationFillMode = 'forwards';
}, 10);
</script>

<template>
    <div id="menuHolder">
      <div role="navigation" class="sticky-top border-bottom border-top" id="mainNavigation">
        <div class="flexMain">
          <div class="flex2">
            <button class="whiteLink siteLink" style="border-right:1px solid #eaeaea" onclick="menuToggle()"><i class="fas fa-bars me-2"></i>  MENU</button>
          </div>
          <div class="flex3 text-center" id="siteBrand">
            <span class="h1 mb-0">S<span class="letra-naranja">O</span>LYTEC</span>
          </div>
          <div class="flex2 text-end d-block d-md-none">
            <button class="whiteLink siteLink"><i class="fas fa-search"></i></button>
          </div>
          <div class="flex2 text-end d-none d-md-block">
            <button class="whiteLink siteLink">REGISTER</button>
            <button class="blackLink siteLink">Login</button>
          </div>
        </div>
      </div>
  
      <div id="menuDrawer">
        <div class="p-4 border-bottom">
          <div class='row'>
            <div class="col">
              <select class="noStyle">
                <option value="english">English</option>
                <option value="spanish">Spanish</option>
                <option value="french">French</option>
                <option value="italian">Italian</option>
                <option value="hebrew">Hebrew</option>
              </select>
            </div>
            <div class="col text-end ">
              <i class="fas fa-times" role="btn" onclick="menuToggle()"></i>
            </div>
          </div>
        </div>
        <div>
          <a href="#" class="nav-menu-item"><i class="fas fa-home me-3"></i>Home</a>
          <a href="#" class="nav-menu-item"><i class="fab fa-product-hunt me-3"></i>Products</a>
          <a href="#" class="nav-menu-item"><i class="fas fa-search me-3"></i>Explore</a>
          <a href="#" class="nav-menu-item"><i class="fas fa-wrench me-3"></i>Services</a>
          <a href="#" class="nav-menu-item"><i class="fas fa-dollar-sign me-3"></i>Pricing</a>
          <a href="#" class="nav-menu-item"><i class="fas fa-file-alt me-3"></i>Blog</a>
          <a href="#" class="nav-menu-item"><i class="fas fa-building me-3"></i>About Us</a>
        </div>
      </div>
    </div>
  </template>
  

<style> 
/* General Styles */
.letra-naranja {
  color: #f78433; /* la letra o de sOlytec */
}

.flexMain {
    display: flex;
    align-items: center;
  }
  
  .flex1 { flex: 1; }
  .flex2 { flex: 2; }
  .flex3 { flex: 3; }
  
  /* Button Styles */
  button.siteLink {
    margin-left: -5px;
    border: none;
    padding: 24px;
    display: inline-block;
    min-width: 115px;
  }
  
  .whiteLink {
    background: #fdfcfa;
  }
  
  .whiteLink:active {
    background: #c4bccc;
    color: #fdfcfa;
  }
  
  .blackLink {
    color: #fdfcfa;
    background: #302f51;
    transition: all 300ms linear;
  }
  
  .blackLink:active {
    color: #000;
    background: #fff;
  }
  
  /* Site Brand Styles */
  #siteBrand {
    font-family: impact;
    letter-spacing: -1px;
    font-size: 32px;
    color: #302f51;
    line-height: 1em;
  }
  
  /* Menu Drawer Styles */
  #menuDrawer {
    background: #fdfcfa;
    position: fixed;
    height: 100vh;
    overflow: auto;
    z-index: 12312;
    top: 0;
    left: 0;
    min-width: 25%;
    max-width: 320px;
    width: 100%;
    transform: translateX(-100%);
    transition: transform 200ms linear;
  }
  
  /* Navigation Styles */
  #mainNavigation {
    background: #fdfcfa;
    transform: translateX(25%); /* Initially hidden offscreen */
  }
  
  /* Bounce Animation Styles */
  @keyframes bounce {
    0% {
      transform: translateX(10px); /* Start with a slight offset */
    }
    20% {
      transform: translateX(-10px);
    }
    40% {
      transform: translateX(5px);
    }
    60% {
      transform: translateX(-5px);
    }
    80% {
      transform: translateX(2px);
    }
    100% {
      transform: translateX(0); /* End in original position */
    }
  }
  
</style>
