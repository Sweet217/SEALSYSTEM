<script>
export default {
  name: 'NavbarComponent',
  mounted() {
    const usuariosButton = document.querySelector('.usuarios');
    const listasButton = document.querySelector('.listas');
    const equiposButton = document.querySelector('.equipos');

    function redireccionar(ruta) {
      window.location.href = `${window.location.origin}${ruta}`;
    }
    usuariosButton.addEventListener('click', () => {
      redireccionar('/usuarios');
    });
    listasButton.addEventListener('click', () => {
      redireccionar('/listas');
    });
    equiposButton.addEventListener('click', () => {
      redireccionar('/equipos');
    });
  }
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

        </div>
        <div class="flex3 text-center" id="siteBrand">

          <img class="solytec-logo text-center" src="@/images/SOLYTEC LOGO.jpg">
        </div>
        <div class="flex2 text-end d-block d-md-none">
          <button class="whiteLink siteLink"><i class="fas fa-search"></i></button>
        </div>
        <div class="button-container">
          <button class="siteLink usuarios">USUARIOS</button>
          <button class="siteLink listas">LISTAS</button>
          <button class="siteLink equipos">EQUIPOS</button>
        </div>
      </div>
    </div>
  </div>
</template>


<style>
.solytec-logo {
  margin-left: 170px;
  margin-top: -20px;
  margin-bottom: 0px;
  width: 30%;
  height: 30%;
  object-fit: contain;
}

@media (max-width: 768px) {
  .solytec-logo {
    margin: 0 auto;
    width: 50%;
    height: 50%;
    /* Reduce el ancho a 30% */
  }


}

/* Para pantallas con un ancho máximo de 480px (por ejemplo, smartphones): */
@media (max-width: 480px) {
  .solytec-logo {
    margin: 0 auto;
    width: 50%;
    height: 50%;
    /* Reduce el ancho a 20% */
  }
}

.letra-naranja {
  color: #f78433;
  /* la letra o de sOlytec */
}

.flexMain {
  display: flex;
  align-items: center;
}

.flex1 {
  flex: 1;
}

.flex2 {
  flex: 2;
}

.flex3 {
  flex: 3;
}

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

.mobile-menu-button {
  display: none;
  /* Initially hidden */
}

@media (max-width: 768px) {
  .mobile-menu-button {
    display: block;
    /* Show on screens less than 768px */
  }
}

/* Navigation Styles */
#mainNavigation {
  background: #fdfcfa;
  transform: translateX(25%);
  /* Initially hidden offscreen */
}

/* Bounce Animation Styles */
@keyframes bounce {
  0% {
    transform: translateX(10px);
    /* Start with a slight offset */
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
    transform: translateX(0);
    /* End in original position */
  }
}
</style>
