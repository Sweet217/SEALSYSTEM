<template>
  <nav id="mainNavigation" class="navbar">
    <div class="navbar-container d-flex justify-content-between align-items-center">
      <div class="navbar-brand d-flex align-items-center">
        <img class="seal-logo rounded" src="@/images/51Qqh-JOmGL._AC_UF1000,1000_QL80_.jpg" @click="redirectPantallaPrincipal" />
        <span class="nombre-usuario rounded ml-2" @mouseover="showDetails = true" @mouseleave="showDetails = false">
          {{ nombreUsuario }}
        </span>
        <transition name="fade">
          <div v-if="showDetails" class="user-details">
            <p>User: {{ tipoUsuario }}</p>
            <p>Email: {{ correoUsuario }}</p>
          </div>
        </transition>
      </div>
      <button class="navbar-toggler ml-auto" @click="toggleMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div :class="{ 'navbar-menu': true, 'is-active': menuOpen }">
        <a class="navbar-item rounded font-weight-light" @click="redirectUsuarios"
          v-if="tipoUsuario === 'Administrador'">Users</a>
        <a class="navbar-item rounded font-weight-light" @click="redirectListas">Lists</a>
        <a class="navbar-item rounded font-weight-light" @click="redirectEquipos">Devices</a>
        <a @click="cerrarSesion" class="navbar-item rounded font-weight-light">Sign Out</a>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'NavbarComponent',
  data() {
    return {
      nombreUsuario: '',
      tipoUsuario: '',
      correoUsuario: '',
      userId: '',
      showUsuarios: false,
      menuOpen: false,
      showDetails: false,
    };
  },
  mounted() {
    axios.get('/api/usuario_actual')
      .then(response => {
        this.nombreUsuario = response.data.nombre;
        this.userId = response.data.user_id;
        this.tipoUsuario = response.data.tipo_usuario;
        this.correoUsuario = response.data.email;
        if (this.tipoUsuario === 'Administrador') {
          this.showUsuarios = true;
        }
      })
      .catch(error => {
        console.error('Error al obtener el usuario actual:', error);
        if (error.response && error.response.status === 401) {
          window.location.href = '/';
        } else {
          this.error = 'Error al obtener el usuario actual.';
        }
      });

    this.waitForElement('#mainNavigation').then((element) => {
      element.style.display = 'block';
      element.style.animationName = 'bounce';
      element.style.animationDuration = '1s';
      element.style.animationFillMode = 'forwards';
    }).catch((error) => {
      console.error('Element not found:', error);
    });
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    },
    redirectUsuarios() {
      window.location.href = '/usuarios';
    },
    redirectListas() {
      window.location.href = '/listas';
    },
    redirectEquipos() {
      const userId = this.userId;
      // Redireccionar a la ruta /equipos con el userId como parámetro query
      window.location.href = `/equipos?user_id=${userId}`;
    },
    cerrarSesion() {
      window.location.href = '/';
    },
    redirectPantallaPrincipal() {
      window.location.href = '/pantallaprincipal';
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
        }, 5000);
      });
    },
  }
};
</script>

<style scoped>
.nombre-usuario {
  padding: 5px;
  transition: transform 0.3s, border-color 0.3s;
  color: #465c6c
}

.nombre-usuario:hover {
  transform: scale(1.1);
  border: 1px solid #34a5f8;
  cursor: pointer;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.user-details {
  background-color: #ffffff;
  padding: 10px;
  border: 1px solid #34a5f8;
  border-radius: 5px;
  position: absolute;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 999;
  color: #465c6c;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #ffffff;
  padding: 1rem;
  color: white;
  border: 1px;
  border-color: #34a5f8;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.navbar-brand {
  display: flex;
  align-items: center;
}

.navbar-toggler {
  display: none;
  background-color: transparent;
  border: none;
  cursor: pointer;
}

.navbar-toggler-icon {
  display: inline-block;
  width: 1.25em;
  height: 1.25em;
  background-color: white;
}

.navbar-menu {
  display: flex;
  flex-direction: row;
}

.navbar-item {
  margin: 0rem;
  padding: 0 1rem;
  cursor: pointer;
  color: #465c6c;
  text-decoration: none;
  font-size: 20px;
  transition: transform 0.3s, border-color 0.3s;
}

.navbar-item:hover {
  transform: scale(1.1);
  background: #34a5f8;
}

.navbar-end {
  display: flex;
  align-items: center;
}

.user-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.cerrar_sesion {
  background: none;
  border: none;
  color: rgb(0, 0, 0);
  cursor: pointer;
}

@media (max-width: 768px) {
  .navbar-menu {
    flex-direction: column;
    display: none;
    width: 100%;
  }

  .navbar-menu.is-active {
    display: flex;
  }

  .navbar-toggler {
    display: block;
  }

  .navbar-toggler-icon {
    display: inline-block;
    width: 1.25em;
    height: 1.25em;
    background-color: white;
  }

  .navbar-item {
    text-align: center;
    padding: 1rem;
    width: 100%;
  }

  .navbar-end {
    flex-direction: column;
    align-items: center;
  }

  .navbar-container {
    flex-direction: column;
  }

  .navbar-brand {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }
}

.seal-logo {
  width: 100px;
  cursor: pointer;
  margin-right: 15px;
  transition: transform 0.3s, border-color 0.3s;
}

.seal-logo:hover {
  transform: scale(1.1);
  cursor: pointer;
}
</style>
