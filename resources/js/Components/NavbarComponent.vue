<script>
export default {
  name: 'NavbarComponent',
  data() {
    return {
      nombreUsuario: '',
      tipoUsuario: '',
      showUsuarios: false,
    };
  },
  mounted() {
    axios.get('/api/usuario_actual')
      .then(response => {
        this.nombreUsuario = response.data.nombre;
        this.tipoUsuario = response.data.tipo_usuario;
        // console.log(this.tipoUsuario)
        if (this.tipoUsuario === 'Administrador') {
          return this.showUsuarios = true;
        }
      })
      .catch(error => {
        console.error('Error al obtener el usuario actual:', error);
        // Redirigir a la página de inicio de sesión si no está autenticado
        if (error.response && error.response.status === 401) {
          window.location.href = '/';
        } else {
          this.error = 'Error al obtener el usuario actual.';
        }
      });
  },
  methods: {
    redirectUsuarios() {
      window.location.href = '/usuarios';
    },
    redirectListas() {
      window.location.href = '/listas';
    },
    redirectEquipos() {
      window.location.href = '/equipos';
    }
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
        <div class="flex2"></div>
        <div class="flex3 text-center" id="siteBrand">
          <div class="logo-container">
            <img class="solytec-logo text-center" src="@/images/SOLYTEC LOGO.jpg">
          </div>
        </div>
        <div class="button-container flexMain">
          <!-- Agregar v-if para mostrar el botón de usuarios solo si el usuario es administrador -->
          <button v-if="showUsuarios" @click="redirectUsuarios" class="siteLink usuarios">USUARIOS</button>
          <button class="siteLink listas" @click="redirectListas">LISTAS</button>
          <button class="siteLink equipos" @click="redirectEquipos">EQUIPOS</button>
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
    width: auto;
    height: auto;
    /* Ajusta la altura automáticamente */
    max-width: 100%;
    scale: auto;
    /* Asegura que el logo no exceda el ancho del contenedor */
  }

  .logo-container {
    margin-bottom: 20px;
    /* Espacio entre el logo y las opciones */
  }

  .siteLink {
    padding: 12px;
    font-size: 14px;
    /* Reducir el relleno y el tamaño del texto para hacer los botones más compactos */
  }
}

/* Para pantallas con un ancho máximo de 480px (por ejemplo, smartphones): */
@media (max-width: 480px) {
  .solytec-logo {
    margin: 0 auto;
    width: auto;
    height: auto;
    /* Ajusta la altura automáticamente */
    max-width: 100%;
    scale: auto;
  }

  .siteLink {
    padding: 8px;
    font-size: 12px;
    /* Reducir aún más el relleno y el tamaño del texto para pantallas más pequeñas */
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
