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
            usuarioSeleccionado: {
                usuario_id: '',
                nombre: '',
                correo: '',
                telefono: '',
                estado: '',
                tipo_usuario: ''
                // Agrega más campos según sea necesario
            }
        };
    },
    methods: {
        abrirModal(usuario) {
            this.usuarioSeleccionado = { ...usuario };
            this.modalVisible = true;
        },
        cerrarModal() {
            this.modalVisible = false;
            this.usuarioSeleccionado = {
                usuario_id: null,
                nombre: '',
                correo: '',
                telefono: '',
                estado: '',
                tipo_usuario: ''
                // Agrega más campos según sea necesario
            };
        },
        eliminarUsuario(usuario_id) {
            console.log(usuario_id);
            axios.delete(`/usuariosDELETE/${usuario_id}`)
                .then(() => {
                    alert('Usuario eliminado correctamente');
                    window.location.reload(); // Recarga la página después de eliminar
                })
                .catch(error => {
                    console.error('Error al eliminar el usuario:', error);
                });
        },
        editarUsuario(usuario_id) {
            axios.put(`/usuariosPUT/${usuario_id}`, {
                nombre: this.usuarioSeleccionado.nombre,
                correo: this.usuarioSeleccionado.correo,
                telefono: this.usuarioSeleccionado.telefono,
                estado: this.usuarioSeleccionado.estado,
                tipo_usuario: this.usuarioSeleccionado.tipo_usuario
                // Agrega más campos según sea necesario
            })
                .then(() => {
                    alert('Usuario editado correctamente');
                    this.cerrarModal();
                    window.location.reload(); // Recarga la página después de editar
                })
                .catch(error => {
                    console.error('Error al editar el usuario:', error);
                });
        }
    }
};
</script>

<template>
    <NavbarComponent></NavbarComponent>

    <div class="container mx-auto mt-4">
        <h1 class="text-2xl font-bold mb-4">Todos los Usuarios</h1>

        <div v-if="usuarios.length === 0" class="text-gray-500">
            No hay usuarios disponibles.
        </div>

        <div v-else>
            <ul class="list-disc space-y-2">
                <li v-for="usuario in usuarios" :key="usuario.usuario_id">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3>{{ usuario.nombre }}</h3>
                            <p>{{ usuario.correo }}</p>
                            <p>{{ usuario.telefono }}</p>
                            <p>{{ usuario.estado }}</p>
                            <p>{{ usuario.tipo_usuario }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="btn editar-btn" @click="abrirModal(usuario)">Editar</button>
                            <button class="btn eliminar-btn"
                                @click="eliminarUsuario(usuario.usuario_id)">Eliminar</button>

                            <!-- Modal para editar usuario -->
                            <div v-if="modalVisible" class="modal">
                                <div class="modal-content">
                                    <span class="close" @click="cerrarModal">&times;</span>
                                    <h2>Editar Usuario</h2>
                                    <form @submit.prevent="editarUsuario(usuarioSeleccionado.usuario_id)">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" v-model="usuarioSeleccionado.nombre" id="nombre"
                                            class="form-control rounded-pill">

                                        <label for="correo">Correo Electrónico:</label>
                                        <input type="email" v-model="usuarioSeleccionado.correo" id="correo"
                                            class="form-control rounded-pill">

                                        <label for="telefono">Teléfono:</label>
                                        <input type="tel" v-model="usuarioSeleccionado.telefono" id="telefono"
                                            class="form-control rounded-pill">

                                        <label for="estado">Estado:</label>
                                        <input type="tel" v-model="usuarioSeleccionado.estado" id="estado"
                                            class="form-control rounded-pill">

                                        <label for="tipo_usuario">Rol:</label>
                                        <input type="tel" v-model="usuarioSeleccionado.tipo_usuario" id="tipo_usuario"
                                            class="form-control rounded-pill">

                                        <!-- Agrega más campos de formulario según sea necesario -->

                                        <div class="text-center">
                                            <button type="submit">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <FooterComponent></FooterComponent>
</template>

<style>
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

.eliminar-btn {
    background-color: #f78433;
    border: 1px solid #e3671f;
    color: white;
}

.eliminar-btn:hover {
    background-color: #e3671f;
    border-color: #d4551a;
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