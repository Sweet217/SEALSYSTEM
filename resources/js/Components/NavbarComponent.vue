<script>
export default {
  name: 'NavbarComponent',
  data() {
    return {
      nombreUsuario: '',
      tipoUsuario: '',
      correoUsuario: '',
      showUsuarios: false,
    };
  },
  mounted() {
    axios.get('/api/usuario_actual') //Hacer una peticion a la api para obtener los datos del usuario que esta utilizando el sistema
      .then(response => {
        this.nombreUsuario = response.data.nombre;
        this.tipoUsuario = response.data.tipo_usuario;
        this.correoUsuario = response.data.email;
        // console.log(this.tipoUsuario)
        if (this.tipoUsuario === 'Administrador') {
          return this.showUsuarios = true; //Solo se mostrara el apartado "Usuarios" a los administradores
        }
      })
      .catch(error => {
        console.error('Error al obtener el usuario actual:', error);
        // Redirigir a la página de inicio de sesión si el usuario no está autenticado
        if (error.response && error.response.status === 401) {
          window.location.href = '/';
        } else {
          this.error = 'Error al obtener el usuario actual.';
        }
      });

    this.waitForElement('#mainNavigation').then((element) => {
      // Unhide the navbar element
      element.style.display = 'block';
      // Apply the bounce animation
      element.style.animationName = 'bounce';
      element.style.animationDuration = '1s';
      element.style.animationFillMode = 'forwards';
    }).catch((error) => {
      console.error('Element not found:', error);
    });
  },
  methods: {
    redirectUsuarios() {
      window.location.href = '/usuarios';  //Redireccionar a /usuarios
    },
    redirectListas() {
      window.location.href = '/listas'; //Redireccionar a /listas
    },
    redirectEquipos() {
      window.location.href = '/equipos'; //Redireccionar a /equipos
    },
    cerrarSesion() {
      window.location.href = '/'
    },
    waitForElement(selector) {
      return new Promise((resolve, reject) => {
        const interval = setInterval(() => {
          const element = document.querySelector(selector);
          if (element) {
            clearInterval(interval);
            resolve(element);
          }
        }, 100);

        setTimeout(() => {
          clearInterval(interval);
          reject(new Error('Element not found'));
        }, 5000); // Timeout after 5 seconds
      });
    },
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
    <div role="navigation" class="sticky-top" id="mainNavigation">
      <div class="flexMain">
        <div class="flex2"></div>
        <div class="cerrarsesion-container">
          <button @click="cerrarSesion" class="cerrar_sesion">
            <i class="bi bi-box-arrow-left"></i>
          </button>
          <h1 class="correo_usuario"> Usuario: {{ this.nombreUsuario }} <br> Correo: {{ this.correoUsuario }}</h1>
          <div class="logo-container">
            <img class="solytec-logo text-center" src="@/images/SOLYTEC LOGO.jpg">
          </div>
        </div>
        <div class="flex3 text-center" id="siteBrand">
        </div>
        <div class="button-container flexMain">
          <!-- Agregar v-if para mostrar el botón de usuarios solo si el usuario es administrador -->
          <button v-if="showUsuarios" @click="redirectUsuarios" class="siteLink usuarios">USUARIOS</button>
          <button class="siteLink listas" @click="redirectListas">LISTAS</button>
          <button class="siteLink equipos" @click="redirectEquipos">DISPOSITIVOS</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
#menuHolder {
  padding: 10px;
}

.solytec-logo {
  width: 30%;
  height: 30%;
  object-fit: contain;
  margin-left: 10px;
}

.cerrarsesion-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1em;
  background-color: #ffffff;
  border: 1px solid #ffffff;
  border-radius: 5px;
  padding-bottom: 0px;
  margin-bottom: -10px;
  margin-top: -60px;
  /* Añadido para ajuste visual */
}

.correo_usuario {
  font-size: 1em;
  margin: 0;
  flex: 1;
}

.cerrar_sesion {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 0.5em 1em;
  font-size: 1em;
  border-radius: 5px;
  cursor: pointer;
  margin-right: 10px;
}

.cerrar_sesion i {
  font-size: 1em;
}

.button-container {
  display: flex;
  gap: 10px;
}

/* Estilos responsivos */
@media (max-width: 822px) {
  .cerrarsesion-container {
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 10px;
    position: absolute;
    scale: .7;
    padding-left: 0px;
    margin-right: 200px;
    margin-top: 20px;
  }

  .correo_usuario {
    font-size: 1em;
    margin-bottom: 0.5em;
  }

  .cerrar_sesion {
    width: 65%;
    padding: 0.5em;
  }

  .solytec-logo {
    width: 50%;
    height: auto;
    margin-left: 50px;
  }

  .button-container {
    flex-direction: column;
    align-items: center;
  }

  .siteLink {
    width: 100%;
    padding: 8px;
    font-size: 14px;
  }

  .logo-container {
    margin-bottom: 20px;
  }
}

@media (max-width: 566px) {
  .cerrarsesion-container {
    align-items: flex-start;
    position: relative;
    padding-bottom: 0px;
    margin-right: 50px;
    margin-top: -50px;
  }

  .correo_usuario {
    margin-top: 10px;
    font-size: 0.9em;
  }

  .cerrar_sesion {
    font-size: 0.9em;
  }

  .cerrar_sesion i {
    font-size: 1em;
  }

  .solytec-logo {
    width: 100%;
    padding-top: 0px;
  }

  .siteLink {
    padding: 6px;
    font-size: 12px;
  }

  .logo-container {
    padding-top: 0px;
    margin-bottom: -50px;
    margin-left: -90px;
    margin-right: 40px;
    scale: 100%;
  }
}

.letra-naranja {
  color: #f78433;
  /* la letra o de sOlytec */
}

.flexMain {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.flex2 {
  flex: 2;
}

.flex3 {
  flex: 3;
}

/* Button Styles */
button.siteLink {
  border: none;
  padding: 24px;
  display: inline-block;
  min-width: 115px;
  text-align: center;
  color: black;
  transition: all 300ms linear;
  cursor: pointer;
  border-radius: 5px;
}

button.siteLink:hover {
  background: #f78433;
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
  background: #ffffff;
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