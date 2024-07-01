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
                      <img class="solytec-logo" src="@/images/SOLYTEC LOGO.jpg">
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

.letra-naranja {
  color: #f78433;
  /* Cambia "orange" al color deseado */
}

.color-boton {
  background-color: #c4bccc;
  color: #302f51;
  transition: transform 0.3s ease-in-out;
}

.login-boton:hover {
  background-color: #302f51;
  color: #fdfcfa;
  transform: scale(1.1);
  transition: transform 0.3s ease-in-out;
}

.main-section {
  animation: gradient-animation 2s infinite alternate;
  background-color: #dba075;
}

.solytec-logo {
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
  .solytec-logo {
    margin-left: -40px;
    margin-top: 0px;
    width: 50%;
    height: 50%;
  }
}

@media (max-width: 480px) {

  /* Media query para smartphones */
  .solytec-logo {
    margin-left: -40px;
    margin-top: 0px;
    width: 50%;
    height: 50%;
  }
}

@keyframes gradient-animation {
  0% {
    background: radial-gradient(circle at center, #dba075, #f78433);
  }

  1% {
    background: radial-gradient(circle at center, #dba074, #f78434);
  }

  2% {
    background: radial-gradient(circle at center, #dba073, #f78435);
  }

  3% {
    background: radial-gradient(circle at center, #dba072, #f78436);
  }

  4% {
    background: radial-gradient(circle at center, #dba071, #f78437);
  }

  5% {
    background: radial-gradient(circle at center, #dba070, #f78438);
  }

  6% {
    background: radial-gradient(circle at center, #dba06f, #f78439);
  }

  7% {
    background: radial-gradient(circle at center, #dba06e, #f7843a);
  }

  8% {
    background: radial-gradient(circle at center, #dba06d, #f7843b);
  }

  9% {
    background: radial-gradient(circle at center, #dba06c, #f7843c);
  }

  10% {
    background: radial-gradient(circle at center, #dba06b, #f7843d);
  }

  11% {
    background: radial-gradient(circle at center, #dba06a, #f7843e);
  }

  12% {
    background: radial-gradient(circle at center, #dba069, #f7843f);
  }

  13% {
    background: radial-gradient(circle at center, #dba068, #f78440);
  }

  14% {
    background: radial-gradient(circle at center, #dba067, #f78441);
  }

  15% {
    background: radial-gradient(circle at center, #dba066, #f78442);
  }

  16% {
    background: radial-gradient(circle at center, #dba065, #f78443);
  }

  17% {
    background: radial-gradient(circle at center, #dba064, #f78444);
  }

  18% {
    background: radial-gradient(circle at center, #dba063, #f78445);
  }

  19% {
    background: radial-gradient(circle at center, #dba062, #f78446);
  }

  20% {
    background: radial-gradient(circle at center, #dba061, #f78447);
  }

  21% {
    background: radial-gradient(circle at center, #dba060, #f78448);
  }

  22% {
    background: radial-gradient(circle at center, #dba05f, #f78449);
  }

  23% {
    background: radial-gradient(circle at center, #dba05e, #f7844a);
  }

  24% {
    background: radial-gradient(circle at center, #dba05d, #f7844b);
  }

  25% {
    background: radial-gradient(circle at center, #dba05c, #f7844c);
  }

  26% {
    background: radial-gradient(circle at center, #dba05b, #f7844d);
  }

  27% {
    background: radial-gradient(circle at center, #dba05a, #f7844e);
  }

  28% {
    background: radial-gradient(circle at center, #dba059, #f7844f);
  }

  29% {
    background: radial-gradient(circle at center, #dba058, #f78450);
  }

  30% {
    background: radial-gradient(circle at center, #dba057, #f78451);
  }

  31% {
    background: radial-gradient(circle at center, #dba056, #f78452);
  }

  32% {
    background: radial-gradient(circle at center, #dba055, #f78453);
  }

  33% {
    background: radial-gradient(circle at center, #dba054, #f78454);
  }

  34% {
    background: radial-gradient(circle at center, #dba053, #f78455);
  }

  35% {
    background: radial-gradient(circle at center, #dba052, #f78456);
  }

  36% {
    background: radial-gradient(circle at center, #dba051, #f78457);
  }

  37% {
    background: radial-gradient(circle at center, #dba050, #f78458);
  }

  38% {
    background: radial-gradient(circle at center, #dba04f, #f78459);
  }

  39% {
    background: radial-gradient(circle at center, #dba04e, #f7845a);
  }

  40% {
    background: radial-gradient(circle at center, #dba04d, #f7845b);
  }

  41% {
    background: radial-gradient(circle at center, #dba04c, #f7845c);
  }

  42% {
    background: radial-gradient(circle at center, #dba04b, #f7845d);
  }

  43% {
    background: radial-gradient(circle at center, #dba04a, #f7845e);
  }

  44% {
    background: radial-gradient(circle at center, #dba049, #f7845f);
  }

  45% {
    background: radial-gradient(circle at center, #dba048, #f78460);
  }

  46% {
    background: radial-gradient(circle at center, #dba047, #f78461);
  }

  47% {
    background: radial-gradient(circle at center, #dba046, #f78462);
  }

  48% {
    background: radial-gradient(circle at center, #dba045, #f78463);
  }

  49% {
    background: radial-gradient(circle at center, #dba044, #f78464);
  }

  50% {
    background: radial-gradient(circle at center, #dba043, #f78465);
  }

  51% {
    background: radial-gradient(circle at center, #dba043, #f78465);
    /* Mismo color del 50% */
  }

  52% {
    background: radial-gradient(circle at center, #dba044, #f78464);
  }

  53% {
    background: radial-gradient(circle at center, #dba045, #f78463);
  }

  54% {
    background: radial-gradient(circle at center, #dba046, #f78462);
  }

  55% {
    background: radial-gradient(circle at center, #dba047, #f78461);
  }

  56% {
    background: radial-gradient(circle at center, #dba048, #f78460);
  }

  57% {
    background: radial-gradient(circle at center, #dba049, #f7845f);
  }

  58% {
    background: radial-gradient(circle at center, #dba04a, #f7845e);
  }

  59% {
    background: radial-gradient(circle at center, #dba04b, #f7845d);
  }

  60% {
    background: radial-gradient(circle at center, #dba04c, #f7845c);
  }

  61% {
    background: radial-gradient(circle at center, #dba04d, #f7845b);
  }

  62% {
    background: radial-gradient(circle at center, #dba04e, #f7845a);
  }

  63% {
    background: radial-gradient(circle at center, #dba04f, #f78459);
  }

  64% {
    background: radial-gradient(circle at center, #dba050, #f78458);
  }

  65% {
    background: radial-gradient(circle at center, #dba051, #f78457);
  }

  66% {
    background: radial-gradient(circle at center, #dba052, #f78456);
  }

  67% {
    background: radial-gradient(circle at center, #dba053, #f78455);
  }

  68% {
    background: radial-gradient(circle at center, #dba054, #f78454);
  }

  69% {
    background: radial-gradient(circle at center, #dba055, #f78453);
  }

  70% {
    background: radial-gradient(circle at center, #dba056, #f78452);
  }

  71% {
    background: radial-gradient(circle at center, #dba057, #f78451);
  }

  72% {
    background: radial-gradient(circle at center, #dba058, #f78450);
  }

  73% {
    background: radial-gradient(circle at center, #dba059, #f7844f);
  }

  74% {
    background: radial-gradient(circle at center, #dba05a, #f7844e);
  }

  75% {
    background: radial-gradient(circle at center, #dba05b, #f7844d);
  }

  76% {
    background: radial-gradient(circle at center, #dba05c, #f7844c);
  }

  77% {
    background: radial-gradient(circle at center, #dba05d, #f7844b);
  }

  78% {
    background: radial-gradient(circle at center, #dba05e, #f7844a);
  }

  79% {
    background: radial-gradient(circle at center, #dba05f, #f78449);
  }

  80% {
    background: radial-gradient(circle at center, #dba060, #f78448);
  }

  81% {
    background: radial-gradient(circle at center, #dba061, #f78447);
  }

  82% {
    background: radial-gradient(circle at center, #dba062, #f78446);
  }

  83% {
    background: radial-gradient(circle at center, #dba063, #f78445);
  }

  84% {
    background: radial-gradient(circle at center, #dba064, #f78444);
  }

  85% {
    background: radial-gradient(circle at center, #dba065, #f78443);
  }

  86% {
    background: radial-gradient(circle at center, #dba066, #f78442);
  }

  87% {
    background: radial-gradient(circle at center, #dba067, #f78441);
  }

  88% {
    background: radial-gradient(circle at center, #dba068, #f78440);
  }

  89% {
    background: radial-gradient(circle at center, #dba069, #f7843f);
  }

  90% {
    background: radial-gradient(circle at center, #dba06a, #f7843e);
  }

  91% {
    background: radial-gradient(circle at center, #dba06b, #f7843d);
  }

  92% {
    background: radial-gradient(circle at center, #dba06c, #f7843c);
  }

  93% {
    background: radial-gradient(circle at center, #dba06d, #f7843b);
  }

  94% {
    background: radial-gradient(circle at center, #dba06e, #f7843a);
  }

  95% {
    background: radial-gradient(circle at center, #dba06f, #f78439);
  }

  96% {
    background: radial-gradient(circle at center, #dba070, #f78438);
  }

  97% {
    background: radial-gradient(circle at center, #dba072, #f78436);
  }

  98% {
    background: radial-gradient(circle at center, #dba073, #f78435);
  }

  99% {
    background: radial-gradient(circle at center, #dba074, #f78434);
  }

  100% {
    background: radial-gradient(circle at center, #dba075, #f78433);
    /* Regresa al color inicial */
  }
}
</style>
