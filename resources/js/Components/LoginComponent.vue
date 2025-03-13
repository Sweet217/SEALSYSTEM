<script>
import axios from 'axios'; // Importa la librería axios para realizar peticiones HTTP 


export default {
  name: 'LoginComponent', //Nombre del componente 

  mounted() { //Nada por ahora.
  },

  data() {
    return {
      email: '', // Email del usuario para el login
      password: '', // Contraseña del usuario para el login
      errors: null, // Almacena los errores de autenticación (si existen)
      token: '', // Token de autenticación recibido tras el login exitoso
      showPassword: false, // Indica si se muestra el campo contraseña en texto plano
    }
  },

  methods: {
    async submitLogin() {
      // Intenta realizar el login del usuario
      try {
        const response = await axios.post('/loginPOST', {
          email: this.email, // Envía el email del usuario
          password: this.password, // Envía la contraseña del usuario
          token: this.token // **Revisar si 'token' es necesario enviarlo**
        });

        const isAuthenticated = response.data && response.data.autenticacion_correcta; // Verifica si la autenticación fue exitosa

        if (isAuthenticated) {
          // Login exitoso
          sessionStorage.clear(); // Limpia el almacenamiento de sesión
          console.log('¡Inicio de sesión exitoso!');
          this.$emit('login-success'); // Emite el evento 'login-success'

          // Guarda el token de autenticación
          this.token = response.data.token;
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`; // Agrega el token al header de autorización por defecto de axios
          sessionStorage.setItem('token', this.token); // Almacena el token en sessionStorage

          // Redirecciona a la pantalla principal tras un login exitoso
          axios.get('/api/pantallaprincipal')
            .then(response => {
              window.location.href = '/pantallaprincipal';
            })
            .catch(error => {
              console.error('Error al acceder a pantallaprincipal:', error);
            });
        } else {
          // Login fallido
          this.errors = response.data.errors || ['Credenciales incorrectas']; // Muestra los errores de autenticación (si existen)
        }
      } catch (error) {
        // Manejo de errores durante la petición
        if (error.response) {
          if (error.response.status === 401) {
            this.errors = ['Datos Invalidos']; // Error 401: credenciales no válidas
          } else if (error.response.data && error.response.data.errors) {
            this.errors = error.response.data.errors; // Muestra los errores devueltos por el servidor
          } else {
            console.error('Error en el inicio de sesión:', error.message); // Error inesperado
          }
        } else {
          console.error('Error en el inicio de sesión:', error.message); // Error de red u otro tipo
        }
      }
    },
    togglePasswordVisibility() {
      // Cambia el estado para mostrar u ocultar la contraseña en texto plano
      this.showPassword = !this.showPassword;
    }
  }
};
</script>

<template>
  <section class="vh-100 main-section">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="contenedor-imagen col-md-6 col-lg-5 d-none d-md-block">
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form @submit.prevent="submitLogin">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <img class="seal-logo" src="@/images/51Qqh-JOmGL._AC_UF1000,1000_QL80_.jpg" />
                    </div>

                    <div class="text-danger" v-if="errors">
                      <ul>
                        <li v-for="error in errors" :key="error">{{ error }}</li>
                      </ul>
                    </div>

                    <!-- <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Entra a tu cuenta</h5> -->
                    <br>

                    <div class="form-outline mb-4">
                      <input type="email" v-model="email" id="form2Example17" class="form-control form-control-lg"
                        required />
                      <label class="form-label" for="form2Example17">Direccion de correo electronico</label>
                    </div>

                    <div class="form-outline mb-4 position-relative">
                      <input :type="showPassword ? 'text' : 'password'" v-model="password" id="form2Example27"
                        class="form-control form-control-lg" required />
                      <label class="form-label" for="form2Example27">Contraseña</label>
                      <i :class="showPassword ? 'fas fa-eye-slash eye-icon' : 'fas fa-eye eye-icon'"
                        @click="togglePasswordVisibility"></i>
                    </div>

                    <div class="pt-1 mb-4">
                      <button type="submit" class="color-boton login-boton btn btn-lg btn-block">Inicia sesión</button>
                    </div>

                    <!-- <a class="small text-muted" href="#!">Olvidate tu contraseña?</a>
                    <br> -->
                    <a href="#!" class="small text-muted">Terminos de uso y Política de privacidad</a>
                    <!-- <br>
                    <a href="#!" class="small text-muted">Politica de privacidad</a> -->

                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>


<style>
.contenedor-imagen {
  width: 400px;
  /* Ajusta el ancho según tus necesidades */
  height: 600px;
  /* Ajusta la altura según tus necesidades */
  background-image: url('@/images/imagen_ejemplo.jpg');
  /* Ruta de la imagen */
  background-size: cover;
  background-repeat: no-repeat;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
}

.hide-image .contenedor-imagen {
  display: none;
}

.color-boton {
  background-color: #37abda;
  color: #302f51;
  transition: transform 0.3s ease-in-out;
}

.login-boton:hover {
  background-color: #0094c5;
  color: #fdfcfa;
  transform: scale(1.1);
  transition: transform 0.3s ease-in-out;
}

.main-section {
  animation: gradient-animation 5s infinite alternate;
  background-color: #75cadb;
}

.seal-logo {
  margin-left: -55px;
  margin-top: -48px;
  margin-bottom: -40px;
  width: 50%;
  height: 50%;
  object-fit: contain;
}

.eye-icon {
  position: absolute;
  top: 30%;
  right: 20px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #6c757d;
  /* Color del icono */
}

@media (max-width: 1024px) {

  /* Oculta la imagen si las dimensiones son menores o iguales a 1029x800 */
  .contenedor-imagen {
    width: 300px;
    /* Ajusta el ancho según tus necesidades */
    height: 500px;
  }
}

@media (max-width: 768px) {

  /* Media query para dispositivos móviles */
  .seal-logo {
    margin-left: -40px;
    margin-top: 0px;
    width: 50%;
    height: 50%;
  }
}

@media (max-width: 480px) {

  /* Media query para smartphones */
  .seal-logo {
    margin-left: -40px;
    margin-top: 0px;
    width: 50%;
    height: 50%;
  }
}

@keyframes gradient-animation {
  0% {
    background: radial-gradient(circle at center, #75d1db, #33a4f7);
  }

  1% {
    background: radial-gradient(circle at center, #74d1da, #34a5f8);
  }

  2% {
    background: radial-gradient(circle at center, #73d1d9, #35a6f9);
  }

  3% {
    background: radial-gradient(circle at center, #72d1d8, #36a7fa);
  }

  4% {
    background: radial-gradient(circle at center, #71d1d7, #37a8fb);
  }

  5% {
    background: radial-gradient(circle at center, #70d1d6, #38a9fc);
  }

  6% {
    background: radial-gradient(circle at center, #6fd1d5, #39aafd);
  }

  7% {
    background: radial-gradient(circle at center, #6ed1d4, #3aabfe);
  }

  8% {
    background: radial-gradient(circle at center, #6dd1d3, #3bacff);
  }

  9% {
    background: radial-gradient(circle at center, #6cd1d2, #3cadff);
  }

  10% {
    background: radial-gradient(circle at center, #6bd1d1, #3daeff);
  }

  11% {
    background: radial-gradient(circle at center, #6ad1d0, #3eafff);
  }

  12% {
    background: radial-gradient(circle at center, #69d1cf, #3fb0ff);
  }

  13% {
    background: radial-gradient(circle at center, #68d1ce, #40b1ff);
  }

  14% {
    background: radial-gradient(circle at center, #67d1cd, #41b2ff);
  }

  15% {
    background: radial-gradient(circle at center, #66d1cc, #42b3ff);
  }

  16% {
    background: radial-gradient(circle at center, #65d1cb, #43b4ff);
  }

  17% {
    background: radial-gradient(circle at center, #64d1ca, #44b5ff);
  }

  18% {
    background: radial-gradient(circle at center, #63d1c9, #45b6ff);
  }

  19% {
    background: radial-gradient(circle at center, #62d1c8, #46b7ff);
  }

  20% {
    background: radial-gradient(circle at center, #61d1c7, #47b8ff);
  }

  21% {
    background: radial-gradient(circle at center, #60d1c6, #48b9ff);
  }

  22% {
    background: radial-gradient(circle at center, #5fd1c5, #49baff);
  }

  23% {
    background: radial-gradient(circle at center, #5ed1c4, #4abbff);
  }

  24% {
    background: radial-gradient(circle at center, #5dd1c3, #4bbcff);
  }

  25% {
    background: radial-gradient(circle at center, #5cd1c2, #4cbcff);
  }

  26% {
    background: radial-gradient(circle at center, #5bd1c1, #4dbdff);
  }

  27% {
    background: radial-gradient(circle at center, #5ad1c0, #4ebeff);
  }

  28% {
    background: radial-gradient(circle at center, #59d1bf, #4fbfff);
  }

  29% {
    background: radial-gradient(circle at center, #58d1be, #50c0ff);
  }

  30% {
    background: radial-gradient(circle at center, #57d1bd, #51c1ff);
  }

  31% {
    background: radial-gradient(circle at center, #56d1bc, #52c2ff);
  }

  32% {
    background: radial-gradient(circle at center, #55d1bb, #53c3ff);
  }

  33% {
    background: radial-gradient(circle at center, #54d1ba, #54c4ff);
  }

  34% {
    background: radial-gradient(circle at center, #53d1b9, #55c5ff);
  }

  35% {
    background: radial-gradient(circle at center, #52d1b8, #56c6ff);
  }

  36% {
    background: radial-gradient(circle at center, #51d1b7, #57c7ff);
  }

  37% {
    background: radial-gradient(circle at center, #50d1b6, #58c8ff);
  }

  38% {
    background: radial-gradient(circle at center, #4fd1b5, #59c9ff);
  }

  39% {
    background: radial-gradient(circle at center, #4ed1b4, #5acaff);
  }

  40% {
    background: radial-gradient(circle at center, #4dd1b3, #5bcbff);
  }

  41% {
    background: radial-gradient(circle at center, #4cd1b2, #5cccff);
  }

  42% {
    background: radial-gradient(circle at center, #4bd1b1, #5dcdff);
  }

  43% {
    background: radial-gradient(circle at center, #4ad1b0, #5ecfff);
  }

  44% {
    background: radial-gradient(circle at center, #49d1af, #5fd0ff);
  }

  45% {
    background: radial-gradient(circle at center, #48d1ae, #60d1ff);
  }

  46% {
    background: radial-gradient(circle at center, #47d1ad, #61d2ff);
  }

  47% {
    background: radial-gradient(circle at center, #46d1ac, #62d3ff);
  }

  48% {
    background: radial-gradient(circle at center, #45d1ab, #63d4ff);
  }

  49% {
    background: radial-gradient(circle at center, #44d1aa, #64d5ff);
  }

  50% {
    background: radial-gradient(circle at center, #43d1a9, #65d6ff);
  }

  51% {
    background: radial-gradient(circle at center, #43d1a9, #65d6ff);
  }

  52% {
    background: radial-gradient(circle at center, #44d1aa, #64d5ff);
  }

  53% {
    background: radial-gradient(circle at center, #45d1ab, #63d4ff);
  }

  54% {
    background: radial-gradient(circle at center, #46d1ac, #62d3ff);
  }

  55% {
    background: radial-gradient(circle at center, #47d1ad, #61d2ff);
  }

  56% {
    background: radial-gradient(circle at center, #48d1ae, #60d1ff);
  }

  57% {
    background: radial-gradient(circle at center, #49d1af, #5fd0ff);
  }

  58% {
    background: radial-gradient(circle at center, #4ad1b0, #5ecfff);
  }

  59% {
    background: radial-gradient(circle at center, #4bd1b1, #5dcdff);
  }

  60% {
    background: radial-gradient(circle at center, #4cd1b2, #5cccff);
  }

  61% {
    background: radial-gradient(circle at center, #4dd1b3, #5bcbff);
  }

  62% {
    background: radial-gradient(circle at center, #4ed1b4, #5acaff);
  }

  63% {
    background: radial-gradient(circle at center, #4fd1b5, #59c9ff);
  }

  64% {
    background: radial-gradient(circle at center, #50d1b6, #58c8ff);
  }

  65% {
    background: radial-gradient(circle at center, #51d1b7, #57c7ff);
  }

  66% {
    background: radial-gradient(circle at center, #52d1b8, #56c6ff);
  }

  67% {
    background: radial-gradient(circle at center, #53d1b9, #55c5ff);
  }

  68% {
    background: radial-gradient(circle at center, #54d1ba, #54c4ff);
  }

  69% {
    background: radial-gradient(circle at center, #55d1bb, #53c3ff);
  }

  70% {
    background: radial-gradient(circle at center, #56d1bc, #52c2ff);
  }

  71% {
    background: radial-gradient(circle at center, #57d1bd, #51c1ff);
  }

  72% {
    background: radial-gradient(circle at center, #58d1be, #50c0ff);
  }

  73% {
    background: radial-gradient(circle at center, #59d1bf, #4fbfff);
  }

  74% {
    background: radial-gradient(circle at center, #5ad1c0, #4ebeff);
  }

  75% {
    background: radial-gradient(circle at center, #5bd1c1, #4dbdff);
  }

  76% {
    background: radial-gradient(circle at center, #5cd1c2, #4cbcff);
  }

  77% {
    background: radial-gradient(circle at center, #5dd1c3, #4bbcff);
  }

  78% {
    background: radial-gradient(circle at center, #5ed1c4, #4abbff);
  }

  79% {
    background: radial-gradient(circle at center, #5fd1c5, #49baff);
  }

  80% {
    background: radial-gradient(circle at center, #60d1c6, #48b9ff);
  }

  81% {
    background: radial-gradient(circle at center, #61d1c7, #47b8ff);
  }

  82% {
    background: radial-gradient(circle at center, #62d1c8, #46b7ff);
  }

  83% {
    background: radial-gradient(circle at center, #63d1c9, #45b6ff);
  }

  84% {
    background: radial-gradient(circle at center, #64d1ca, #44b5ff);
  }

  85% {
    background: radial-gradient(circle at center, #65d1cb, #43b4ff);
  }

  86% {
    background: radial-gradient(circle at center, #66d1cc, #42b3ff);
  }

  87% {
    background: radial-gradient(circle at center, #67d1cd, #41b2ff);
  }

  88% {
    background: radial-gradient(circle at center, #68d1ce, #40b1ff);
  }

  89% {
    background: radial-gradient(circle at center, #69d1cf, #3fb0ff);
  }

  90% {
    background: radial-gradient(circle at center, #6ad1d0, #3eafff);
  }

  91% {
    background: radial-gradient(circle at center, #6bd1d1, #3daeff);
  }

  92% {
    background: radial-gradient(circle at center, #6cd1d2, #3cadff);
  }

  93% {
    background: radial-gradient(circle at center, #6dd1d3, #3bacff);
  }

  94% {
    background: radial-gradient(circle at center, #6ed1d4, #3aabfe);
  }

  95% {
    background: radial-gradient(circle at center, #6fd1d5, #39aafd);
  }

  96% {
    background: radial-gradient(circle at center, #70d1d6, #38a9fc);
  }

  97% {
    background: radial-gradient(circle at center, #72d1d8, #36a7fa);
  }

  98% {
    background: radial-gradient(circle at center, #73d1d9, #35a6f9);
  }

  99% {
    background: radial-gradient(circle at center, #74d1da, #34a5f8);
  }

  100% {
    background: radial-gradient(circle at center, #75d1db, #33a4f7);
  }
}

</style>
