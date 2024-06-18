<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
import axios from 'axios';

const props = defineProps({
    usuarios: Object,
});
</script>

<script>
export default {
    data() {
        return {
            modalVisible: false,
            crearModalVisible: false,
            usuarioSeleccionado: {
                user_id: '',
                nombre: '',
                email: '',
                telefono: '',
                estado: '',
                tipo_usuario: ''
            },
            nuevoUsuario: {
                nombre: '',
                email: '',
                password: '',
                confirmPassword: '',
                tipo_usuario: 'Operador',
                telefono: '',
                estado: 'Activo'
            },
            nombreUsuario: '',
            tipoUsuario: '',
            error: ''
        };
    },
    mounted() {
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
    methods: {
        abrirCrearModal() {
            this.crearModalVisible = true;
            this.resetForm();
        },
        cerrarCrearModal() {
            this.crearModalVisible = false;
            this.resetForm();
        },
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
        abrirModal(usuario) {
            this.usuarioSeleccionado = { ...usuario };
            this.modalVisible = true;
        },
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
        eliminarUsuario(user_id) {
            axios.delete(`/usuariosDELETE/${user_id}`)
                .then(() => {
                    alert('Usuario eliminado correctamente');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al eliminar el usuario:', error);
                });
        },
        editarUsuario(user_id) {
            axios.put(`/usuariosPUT/${user_id}`, {
                nombre: this.usuarioSeleccionado.nombre,
                email: this.usuarioSeleccionado.email,
                telefono: this.usuarioSeleccionado.telefono,
                estado: this.usuarioSeleccionado.estado,
                tipo_usuario: this.usuarioSeleccionado.tipo_usuario
            })
                .then(() => {
                    alert('Usuario editado correctamente');
                    this.cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al editar el usuario:', error);
                });
        },
        async crearUsuario() {
            // Verificamos que todos los campos estén completos
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

            // Verificamos que la contraseña coincida con la confirmación de contraseña
            if (this.nuevoUsuario.password !== this.nuevoUsuario.confirmPassword) {
                this.error = 'Las contraseñas no coinciden.';
                return; // Salimos de la función si las contraseñas no coinciden
            }

            if (!/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/.test(this.nuevoUsuario.email)) {
                this.error = 'Por favor ingresa un correo electrónico válido.';
                return; // Salimos de la función si el correo electrónico no es válido
            }

            if (!/^\d+(\s\d+)*$/.test(this.nuevoUsuario.telefono)) {
                this.error = 'El teléfono debe contener solo números y espacios';
                return; // Salimos de la función si el formato del teléfono no es válido
            }

            if (!/^\d{3}\s\d{3}\s\d{4}$/.test(this.nuevoUsuario.telefono)) {
                this.error = 'El teléfono debe tener el formato 000 000 0000.';
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
                alert('Usuario creado correctamente');
                this.cerrarCrearModal();
                window.location.reload();
            } catch (error) {
                console.error('Error al crear el usuario:', error.response.data);
                this.error = 'Error al crear el usuario';
            }
        }
    }
};
</script>

<template>
    <NavbarComponent></NavbarComponent>

    <div class="container mx-auto mt-4">
        <h1 class="text-2xl font-bold mb-4">Todos los Usuarios</h1>
        <button class="btn morado-btn" @click="abrirCrearModal">Crear Nuevo Usuario</button>

        <div v-if="usuarios.length === 0" class="text-gray-500">
            No hay usuarios disponibles.
        </div>

        <div v-else>
            <ul class="list-disc space-y-2">
                <li v-for="usuario in usuarios" :key="usuario.user_id">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3>Nombre: {{ usuario.nombre }}</h3>
                            <p>Correo Electrónico: {{ usuario.email }}</p>
                            <p>Teléfono: {{ usuario.telefono }}</p>
                            <p>Estado: {{ usuario.estado }}</p>
                            <p>Rol: {{ usuario.tipo_usuario }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="btn editar-btn" @click="abrirModal(usuario)">Editar</button>
                            <button class="btn eliminar-btn btn-danger btn-trash bi-trash"
                                @click="eliminarUsuario(usuario.user_id)"></button>
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
                        <button type="submit">Guardar Cambios</button>
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
                    <input type="password" v-model="nuevoUsuario.password" name="crear-password"
                        class="form-control rounded-pill" autocomplete="new-password" />

                    <label for="crear-confirmPassword">Confirmar Contraseña:</label>
                    <input type="password" v-model="nuevoUsuario.confirmPassword" name="crear-confirmPassword"
                        class="form-control rounded-pill" autocomplete="new-password" />

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
                        <button type="submit">Guardar Cambios</button>
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
    background-color: #302f51;
    border: 1px solid #302f51;
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
    color: #f78433;
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
    background-color: #f78433;
    color: #fdfcfa;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 15px;
    margin-top: 10px;
}

.modal-content button:hover {
    background-color: #e3671f;
    border-color: #d4551a;
}
</style>