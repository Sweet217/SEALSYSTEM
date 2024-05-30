<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
//import CrearEquiposComponent from '@Components/CrearEquiposComponent.vue'
import axios from 'axios';

const props = defineProps({
    equipos: Object
});
</script>

<script>
export default {
    data() {
        return {
            nombre: '',
            modalVisible: false,
            equipoSeleccionado: {
                id_equipo: null,
                nombre: ''
            },
            error: null
        };
    },

    methods: {
        abrirModal(equipo) {
            console.log('clicleando')
            equipoSeleccionado = { ...equipo };
            modalVisible = true;
        },

        cerrarModal() {
            modalVisible = false;
            equipoSeleccionado = {
                equipo_id: null,
                nombre: ''
                // Reinicia las demás propiedades según sea necesario
            };
        },

        eliminarEquipo(equipo_id) {
            axios.delete(`/equiposDELETE/${equipo_id}`)
                .then(() => {
                    alert('Equipo eliminado correctamente');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al eliminar el equipo:', error);
                });
        },

        editarEquipo(equipo_id) {
            axios.put(`/equiposPUT/${equipo_id}`, {
                nombre: equipoSeleccionado.nombre
                // Agrega más propiedades según sea necesario
            })
                .then(() => {
                    alert('Equipo editado correctamente');
                    cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al editar el equipo:', error);
                });
        },
    }
}

</script>
<template>
    <div>
        <NavbarComponent></NavbarComponent>

        <div class="container mx-auto mt-4">
            <h1 class="text-2xl font-bold mb-4">Todos los Equipos</h1>

            <div v-if="equipos.length === 0" class="text-gray-500">
                No hay equipos disponibles.
            </div>

            <div v-else>
                <ul class="list-disc space-y-2">
                    <li v-for="equipo in equipos" :key="equipo.equipo_id">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3>{{ equipo.nombre }}</h3>
                                <p>{{ equipo.licencia_id }}</p>
                                <p>{{ equipo.usuario_id }}</p>

                            </div>
                            <div class="flex space-x-2">
                                <button class="btn editar-btn" @click="abrirModal(equipo)">Editar</button>
                                <button class="btn eliminar-btn"
                                    @click="eliminarEquipo(equipo.equipo_id)">Eliminar</button>

                                <!-- Modal para editar equipo -->
                                <div v-if="modalVisible" class="modal">
                                    <div class="modal-content">
                                        <span class="close" @click="cerrarModal">&times;</span>
                                        <h2>Editar Equipo</h2>
                                        <form @submit.prevent="editarEquipo(equipoSeleccionado.equipo_id)">
                                            <label for="nombre">Nombre:</label>
                                            <input type="text" v-model="equipoSeleccionado.nombre" id="nombre"
                                                class="form-control rounded-pill">
                                            <!-- Agrega más campos del formulario según sea necesario -->

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
    </div>
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