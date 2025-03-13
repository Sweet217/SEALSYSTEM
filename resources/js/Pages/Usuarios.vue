<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
import axios from 'axios';
import Swal from 'sweetalert2';

// Definir las propiedades que se esperan recibir, en este caso, un objeto de usuarios
const props = defineProps({
    usuarios: Object,
});
</script>

<script>
export default {
    data() {
        return {
            // Variables para controlar la visibilidad de los modales
            modalVisible: false,
            crearModalVisible: false,
            // Objeto para almacenar la información del usuario seleccionado
            usuarioSeleccionado: {
                user_id: '',
                nombre: '',
                email: '',
                telefono: '',
                estado: '',
                tipo_usuario: ''
            },
            // Objeto para almacenar la información del nuevo usuario a crear
            nuevoUsuario: {
                nombre: '',
                email: '',
                password: '',
                confirmPassword: '',
                tipo_usuario: 'Operador',
                telefono: '',
                estado: 'Activo'
            },
            // Variables para almacenar el nombre y tipo del usuario actual y posibles errores
            nombreUsuario: '',
            tipoUsuario: '',
            busqueda: '',
            showPassword: false,
            showConfirmPassword: false,
            error: ''
        };
    },
    mounted() {
        // Al montar el componente, obtener los datos del usuario actual
        axios.get('/api/usuario_actual')
            .then(response => {
                this.nombreUsuario = response.data.nombre;
                this.tipoUsuario = response.data.tipo_usuario;
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
    computed: {
        usuariosFiltrados() {
            const textoBusqueda = this.busqueda.toLowerCase();
            return this.usuarios.filter(usuario =>
                usuario.nombre.toLowerCase().includes(textoBusqueda) ||
                usuario.email.toLowerCase().includes(textoBusqueda) ||
                usuario.telefono.includes(textoBusqueda) ||
                usuario.estado.toLowerCase().includes(textoBusqueda) ||
                usuario.tipo_usuario.toLowerCase().includes(textoBusqueda)
            );
        },
    },

    methods: {
        // Método para abrir el modal de creación de usuario
        abrirCrearModal() {
            this.crearModalVisible = true;
            this.resetForm();
        },
        // Método para cerrar el modal de creación de usuario
        cerrarCrearModal() {
            this.crearModalVisible = false;
            this.resetForm();
        },
        // Método para resetear el formulario de nuevo usuario
        resetForm() {
            this.nuevoUsuario = {
                nombre: '',
                email: '',
                password: '',
                confirmPassword: '',
                tipo_usuario: 'Operador',
                telefono: '',
                estado: 'Activo'
            };
        },
        // Método para abrir el modal de edición de usuario
        abrirModal(usuario) {
            this.usuarioSeleccionado = { ...usuario };
            this.modalVisible = true;
        },
        // Método para cerrar el modal de edición de usuario
        cerrarModal() {
            this.modalVisible = false;
            this.usuarioSeleccionado = {
                user_id: null,
                nombre: '',
                email: '',
                telefono: '',
                estado: '',
                tipo_usuario: ''
            };
        },
        // Método para ver los dispositivos de un usuario
        verDispositivosUsuario(user_id) {
            window.location.href = `/equipos_usuario_pagina/${user_id}`;
        },
        // Método para eliminar un usuario
        eliminarUsuario(user_id) {
            axios.delete(`/usuariosDELETE/${user_id}`)
                .then(() => {
                    Swal.fire({
                        title: 'Usuario eliminado correctamente',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // Recarga la página
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al eliminar el usuario:', error);
                    Swal.fire({
                        title: 'Error al eliminar el usuario',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        // Método para editar un usuario
        editarUsuario(user_id) {
            if (!this.usuarioSeleccionado.nombre || !this.usuarioSeleccionado.email || !this.usuarioSeleccionado.telefono || !this.usuarioSeleccionado.estado || !this.usuarioSeleccionado.tipo_usuario) {
                Swal.fire({
                    title: 'Error al editar el usuario',
                    text: 'Por favor, complete todos los campos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            axios.put(`/usuariosPUT/${user_id}`, {
                nombre: this.usuarioSeleccionado.nombre,
                email: this.usuarioSeleccionado.email,
                telefono: this.usuarioSeleccionado.telefono,
                estado: this.usuarioSeleccionado.estado,
                tipo_usuario: this.usuarioSeleccionado.tipo_usuario
            })
                .then(() => {
                    Swal.fire({
                        title: 'Usuario editado',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModal();
                            window.location.reload(); // Recarga la página
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al editar el usuario:', error);
                    Swal.fire({
                        title: 'Error al editar el usuario',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        // Método para crear un nuevo usuario
        async crearUsuario() {
            // Validar que todos los campos estén completos
            if (
                !this.nuevoUsuario.nombre ||
                !this.nuevoUsuario.email ||
                !this.nuevoUsuario.password ||
                !this.nuevoUsuario.confirmPassword ||
                !this.nuevoUsuario.telefono ||
                !this.nuevoUsuario.estado ||
                !this.nuevoUsuario.tipo_usuario
            ) {
                this.error = 'Por favor completa todos los campos.';
                return; // Salimos de la función si algún campo está vacío
            }

            // Verificar que la contraseña coincida con la confirmación de contraseña
            if (this.nuevoUsuario.password !== this.nuevoUsuario.confirmPassword) {
                this.error = 'Las contraseñas no coinciden.';
                return; // Salimos de la función si las contraseñas no coinciden
            }

            // Validar formato del correo electrónico
            if (!/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/.test(this.nuevoUsuario.email)) {
                this.error = 'Por favor ingresa un correo electrónico válido.';
                return; // Salimos de la función si el correo electrónico no es válido
            }

            // Validar que el teléfono contenga solo números y espacios
            if (!/^[\d\s]+$/.test(this.nuevoUsuario.telefono)) {
                this.error = 'El teléfono debe contener solo números y espacios';
                return; // Salimos de la función si el formato del teléfono no es válido
            }

            try {
                // Si pasamos las validaciones, creamos el usuario
                const response = await axios.post('/signupPOST', {
                    nombre: this.nuevoUsuario.nombre,
                    email: this.nuevoUsuario.email,
                    password: this.nuevoUsuario.password,
                    password_confirmation: this.nuevoUsuario.confirmPassword,
                    tipo_usuario: this.nuevoUsuario.tipo_usuario,
                    telefono: this.nuevoUsuario.telefono,
                    estado: this.nuevoUsuario.estado
                });
                Swal.fire({
                    title: 'Usuario agregado',
                    text: '',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.cerrarCrearModal();
                        window.location.reload(); // Recarga la página
                    }
                });
            } catch (error) {
                console.error('Error al crear el usuario:', error.response.data);
                this.error = 'Error al crear el usuario';
                Swal.fire({
                    title: 'Error al crear el usuario',
                    text: error.response.data,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                    }
                });
            }
        },
        togglePasswordVisibility() {
            // Cambia el estado para mostrar u ocultar la contraseña en texto plano
            this.showPassword = !this.showPassword;
        },
        toggleConfirmPasswordVisibility() {
            // Cambia el estado para mostrar u ocultar la contraseña en texto plano
            this.showConfirmPassword = !this.showConfirmPassword;
        }
    }
}
</script>

<template>
    <NavbarComponent></NavbarComponent>

    <div class="container mx-auto mt-4">
        <h1 class="text-2xl font-bold mb-4">Todos los Usuarios</h1>
        <!-- Barra de búsqueda -->
        <div class="input-group my-4">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" v-model="busqueda" class="form-control"
                placeholder="Buscar por nombre, correo, teléfono, estado o rol" />
        </div>
        <button class="btn morado-btn" @click="abrirCrearModal">Crear Nuevo Usuario</button>

        <div v-if="usuarios.length === 0" class="text-gray-500">
            No hay usuarios disponibles.
        </div>

        <div v-else>
            <ul class="list-disc space-y-2">
                <li v-for="usuario in usuariosFiltrados" :key="usuario.user_id">
                    <div class="flex items-center justify-between">
                        <div>
                            <!-- Detalles del usuario -->
                            <h3>Nombre: {{ usuario.nombre }}</h3>
                            <p>Correo Electrónico: {{ usuario.email }}</p>
                            <p>Teléfono: {{ usuario.telefono }}</p>
                            <p>Estado: {{ usuario.estado }}</p>
                            <p>Rol: {{ usuario.tipo_usuario }}</p>
                        </div>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <!-- Contenedor de botones -->
                            <div class="flex flex-col sm:flex-row">
                                <button class="btn ver-dispositivos-btn"
                                    @click="verDispositivosUsuario(usuario.user_id)">Ver Dispositivos</button>
                                <button class="btn editar-btn" @click="abrirModal(usuario)">Editar</button>
                                <button class="btn eliminar-btn btn-danger btn-trash bi-trash"
                                    @click="eliminarUsuario(usuario.user_id)"></button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Modal para editar usuario -->
        <div v-if="modalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarModal">&times;</span>
                <h2>Editar Usuario</h2>
                <form @submit.prevent="editarUsuario(usuarioSeleccionado.user_id)">
                    <label for="edit-nombre">Nombre:</label>
                    <input type="text" v-model="usuarioSeleccionado.nombre" name="edit-nombre"
                        class="form-control rounded-pill" autocomplete="off" />

                    <label for="edit-email">Correo Electrónico:</label>
                    <input type="email" v-model="usuarioSeleccionado.email" name="edit-email"
                        class="form-control rounded-pill" autocomplete="off" />

                    <label for="edit-telefono">Teléfono:</label>
                    <input type="tel" v-model="usuarioSeleccionado.telefono" name="edit-telefono"
                        class="form-control rounded-pill" autocomplete="off" />

                    <label for="edit-estado">Estado:</label>
                    <select v-model="usuarioSeleccionado.estado" name="edit-estado" class="form-select rounded-pill">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>

                    <label for="edit-tipo_usuario">Rol:</label>
                    <select v-model="usuarioSeleccionado.tipo_usuario" name="edit-tipo_usuario"
                        class="form-select rounded-pill">
                        <option value="Administrador">Administrador</option>
                        <option value="Operador">Operador</option>
                    </select>

                    <div class="text-center">
                        <button type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Para Crear Usuario -->
        <div v-if="crearModalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarCrearModal">&times;</span>
                <h2>Crear Nuevo Usuario</h2>

                <div v-if="error" class="error-message">{{ error }}</div>

                <form @submit.prevent="crearUsuario" autocomplete="off">
                    <label for="crear-nombre">Nombre:</label>
                    <input type="text" v-model="nuevoUsuario.nombre" name="crear-nombre"
                        class="form-control rounded-pill" autocomplete="off" />

                    <label for="crear-email">Correo Electrónico:</label>
                    <input type="email" v-model="nuevoUsuario.email" name="crear-email"
                        class="form-control rounded-pill" autocomplete="off" />

                    <label for="crear-password">Contraseña:</label>
                    <div class="password-field">
                        <input :type="showPassword ? 'text' : 'password'" v-model="nuevoUsuario.password"
                            name="crear-password" class="form-control rounded-pill" autocomplete="new-password" />
                        <i :class="showPassword ? 'fas fa-eye-slash eye-icon' : 'fas fa-eye eye-icon'"
                            @click="togglePasswordVisibility"></i>
                    </div>

                    <label for="crear-confirmPassword">Confirmar Contraseña:</label>
                    <div class="password-field">
                        <input :type="showConfirmPassword ? 'text' : 'password'" v-model="nuevoUsuario.confirmPassword"
                            name="crear-confirmPassword" class="form-control rounded-pill"
                            autocomplete="new-password" />
                        <i :class="showConfirmPassword ? 'fas fa-eye-slash eye-icon' : 'fas fa-eye eye-icon'"
                            @click="toggleConfirmPasswordVisibility"></i>
                    </div>

                    <label for="crear-telefono">Teléfono:</label>
                    <input type="tel" v-model="nuevoUsuario.telefono" name="crear-telefono"
                        class="form-control rounded-pill" autocomplete="off" />

                    <label for="crear-estado">Estado:</label>
                    <select v-model="nuevoUsuario.estado" name="crear-estado" class="form-select rounded-pill">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>

                    <label for="crear-tipo_usuario">Rol:</label>
                    <select v-model="nuevoUsuario.tipo_usuario" name="crear-tipo_usuario"
                        class="form-select rounded-pill">
                        <option value="Administrador">Administrador</option>
                        <option value="Operador">Operador</option>
                    </select>

                    <div class="text-center">
                        <button type="submit">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <FooterComponent></FooterComponent>
</template>


<style>
.error-message {
    color: red;
    margin-bottom: 10px;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    font-size: 1rem;
    cursor: pointer;
}

.morado-btn {
    background-color: #00406d;
    border: 1px solid #004271;
    color: white;
    margin-top: -10px;
    margin-bottom: 20px;
}

.morado-btn:hover {
    background-color: #4a4978;
    border-color: #4a4978;
}

.editar-btn {
    background-color: #fdfcfa;
    border: 1px solid #dcdcdc;
    color: #333;
}

.editar-btn:hover {
    background-color: #e6e6e6;
    border-color: #cccccc;
}

.ver-dispositivos-btn {
    background-color: #34a5f8;
    border: 1px solid #1e8edb;
    color: white;
}

.password-field {
    position: relative;
}

.password-field .eye-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}

.ver-dispositivos-btn:hover {
    background-color: #34a5f8;
    border-color:#1e8edb;
}

.ver-dispositivos-btn,
.editar-btn,
.eliminar-btn {
    margin: 5px;
}

/* Estilos para el modal */
/* Modal container */
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
}

/* Modal content */
.modal-content {
    background-color: #fdfcfa;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.3s ease-in-out;
}

/* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.modal-content h2 {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    padding: 20px 0;
}

.close:hover,
.close:focus {
    color: 1px solid #1e8edb;
    text-decoration: none;
    cursor: pointer;
}

/* Responsive design */
@media (max-width: 600px) {
    .modal-content {
        width: 90%;
        padding: 15px;
    }
}

/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

/* Custom button styles (optional) */
.modal-content button {
    background-color: #1e8edb;;
    color: #fdfcfa;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 15px;
    margin-top: 10px;
}

.modal-content button:hover {
    background-color: #34a5f8;
    border-color:#1e8edb;
}

@media (max-width: 600px) {
    .btn {
        padding: 0.3rem 0.8rem;
        /* Reducir el padding para hacer los botones más compactos */
        font-size: 0.8rem;
        /* Reducir el tamaño de fuente para los botones */
    }

    .ver-dispositivos-btn,
    .editar-btn,
    .eliminar-btn {
        min-width: 60px;
        /* Establecer un ancho mínimo para los botones de acción */
    }

    .list-disc li {
        padding: 8px;
    }

    .list-disc li h3,
    .list-disc li p {
        font-size: 0.9rem;
        /* Reducir el tamaño de fuente para los detalles del usuario */
    }

    .modal-content {
        width: 90%;
        max-width: 90%;
        /* Ajustar el ancho máximo del modal para pantallas pequeñas */
        padding: 15px;
    }

    .morado-btn {
        padding: 0.4rem 1rem;
        /* Ajustar el padding del botón de crear usuario */
        margin-top: 20px;
        /* Aumentar el espacio superior para separar del contenido anterior */
    }

    .flex-col {
        flex-direction: column;
    }

    .flex-row {
        flex-direction: row;
    }

    .flex {
        display: flex;
    }

    .space-y-2 {
        margin-bottom: 0.5rem;
    }

    .space-y-0 {
        margin-bottom: 0;
    }

    .space-x-2 {
        margin-right: 0.5rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        /* Mantén el mismo padding que en dispositivos más grandes */
    }

}
</style>