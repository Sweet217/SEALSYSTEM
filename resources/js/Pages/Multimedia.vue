<script setup>
import FooterComponent from '@/Components/FooterComponent.vue';
import NavbarComponent from '@/Components/NavbarComponent.vue';

const props = defineProps({
    multimedia: Object,
});
</script>

<script>
export default {
    data() {
        return {
            nombreUsuario: '',
            tipoUsuario: '',
            error: '',
            modalVisible: false,
            imagenSeleccionada: {
                imagen_id: null,
                tiempo: '',
            },
            error: null
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
                if (error.response && error.response.status === 401) {
                    window.location.href = '/';
                } else {
                    this.error = 'Error al obtener el usuario actual.';
                }
            });
    },
    methods: {
        abrirModal(imagen) {
            this.imagenSeleccionada = { ...imagen };
            this.modalVisible = true;
        },
        cerrarModal() {
            this.modalVisible = false;
            this.imagenSeleccionada = {
                id_imagen: null,
                tiempo: '',
            };
            this.error = null;
        },
        editarImagen(imagen_id) {
            axios.put(`/imagenPUT/${imagenSeleccionada.imagen_id}`, {
                duracion: imagenSeleccionada.duracion,
            })
                .then(() => {
                    alert('Imagen editada correctamente');
                    cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al editar la imagen:', error);
                });
        },
    }
}
</script>

<template>
    <div>
        <NavbarComponent />
        <div class="content container mx-auto mt-4">
            <h1 class="text-3xl font-bold mb-4">Multimedia</h1>

            <div v-if="multimedia.length">
                <div v-for="item in multimedia" :key="item.data.id">
                    <template v-if="item.tipo === 'video'">
                        <div class="video">
                            <h2 class="text-2xl font-bold mb-4">Video(s)</h2>
                            <video :src="`/storage/${item.data.data}`" controls></video>
                            <!-- <p>{{ item.data.nombre_archivo }}</p> -->
                        </div>
                    </template>

                    <template v-if="item.tipo === 'imagen'">
                        <div class="image">
                            <h2 class="text-2xl font-bold mb-4">Imagen(es)</h2>
                            <img :src="`/storage/${item.data.data}`" :alt="item.data.nombre_archivo" />
                            <p> Duracion: <br>{{ item.data.tiempo }} Segundos</p>
                            <button class="btn editar-btn" @click="abrirModal(item.data)">
                                Editar Duración
                            </button>
                            <!-- <p>{{ item.data.nombre_archivo }}</p> -->
                        </div>
                    </template>

                    <template v-if="item.tipo === 'enlace'">
                        <div class="link">
                            <h2 class="text-2xl font-bold mb-4">Enlace(s)</h2>
                            <a :href="item.data.data" target="_blank">{{ item.data.data }}</a>
                        </div>
                    </template>
                </div>
            </div>
            <div v-else>
                <p>No hay multimedia disponible para esta lista.</p>
            </div>
        </div>

        <div v-if="modalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarModal">&times;</span>
                <h2>Editar Duracion de Imagen</h2>
                <form @submit.prevent="editarImagen(imagenSeleccionada.imagen_id)">
                    <label for="nombre">Tiempo:</label>
                    <input type="text" v-model="imagenSeleccionada.tiempo" id="nombre"
                        class="form-control rounded-pill">
                    <div class="text-center">
                        <button type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
        <FooterComponent />
    </div>
</template>

<style scoped>
.content {
    padding: 20px;
}

.video,
.image,
.link {
    margin-bottom: 20px;
}

.video video,
.image img {
    width: 100px;
    height: 100px;
    /* Ajusta la altura automáticamente para mantener la relación de aspecto */
}

h2 {
    margin-bottom: 10px;
}

a {
    color: #302f51;
    /* Cambié el color para que coincida con los estilos de los botones */
    text-decoration: none;
    /* Quité la subrayado del enlace */
}

/* Estilos para los botones */
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
    margin-bottom: 15px;
}

.morado-btn:hover {
    background-color: #4a4978;
    border-color: #4a4978;
}

.editar-btn {
    background-color: #fdfcfa;
    border: 1px solid #dcdcdc;
    color: #333;
    max-width: ;
    width: auto;
    height: auto;
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
</style>
