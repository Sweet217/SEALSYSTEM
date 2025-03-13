<script>
import axios from 'axios';
export default {
    name: 'SignupComponent',
    data() {
        return {
            nombre: '',
            email: '',
            password: '',
            confirmPassword: '',
            tipo_usuario: 'Operador',
            telefono: '',
            estado: 'Activo'
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await axios.post('/signupPOST', {
                    nombre: this.nombre,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.confirmPassword,
                    tipo_usuario: this.tipo_usuario,
                    telefono: this.telefono,
                    estado: this.estado
                });
                console.log('User created successfully:', response.data);
                alert('User created successfully!');
            } catch (error) {
                console.error('Error creating user:', error.response.data);
                alert('Error creating user. Please try again.');
            }
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
                                <!-- Image content here -->
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center" autocomplete="off">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <form @submit.prevent="submitForm" autocomplete="off">
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                            <img class="seal-logo" src="@/images/imagen_ejemplo.jpg">
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">
                                            Registrar Usuario
                                        </h5>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="text" id="nombre" v-model="nombre"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="nombre">Nombre completo</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="text" id="telefono" v-model="telefono"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="telefono">Num celular</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="email" id="email" v-model="email"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="email" autocomplete="new-email">Direccion de
                                                correo
                                                electronico</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="password" id="password" v-model="password"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="password">Contrasena</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="password" id="confirmPassword" v-model="confirmPassword"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="confirmPassword">Confirmar Contrasena</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <select class="form-control form-control-lg" id="tipo_usuario"
                                                v-model="tipo_usuario" aria-label="Tipo de Usuario">
                                                <option value="Operador">Operador</option>
                                                <option value="Administrador">Administrador</option>
                                            </select>
                                            <label class="form-label" for="tipo_usuario">Tipo de Usuario</label>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button data-mdb-button-init data-mdb-ripple-init
                                                class="color-boton login-boton btn btn-lg btn-block" type="submit">
                                                Registrate
                                            </button>
                                        </div>
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
    background-image: url('@/images/kioskogobierno.jpg');
    /* Ruta de la imagen */
    background-size: cover;
    background-repeat: no-repeat;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
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
    background-color: #1e8edb;
}

.seal-logo {
    margin-left: -55px;
    margin-top: -48px;
    margin-bottom: -40px;
    width: 50%;
    height: 50%;
    object-fit: contain;
}

@media (max-width: 768px) {
    .seal-logo {
        margin-left: -40px;
        margin-top: 0px;
        width: 50%;
        height: 50%;
        /* Reduce el ancho a 30% */
    }
}

/* Para pantallas con un ancho máximo de 480px (por ejemplo, smartphones): */
@media (max-width: 480px) {
    .seal-logo {
        margin-left: -40px;
        margin-top: 0px;
        width: 50%;
        height: 50%;
        /* Reduce el ancho a 20% */
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