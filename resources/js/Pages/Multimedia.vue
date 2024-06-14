<script setup>
import FooterComponent from '@/Components/FooterComponent.vue';
import NavbarComponent from '@/Components/NavbarComponent.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    multimedia: Object,
    listaData: Object,
});

</script>

<script>
export default {
    components: {
        draggable
    },
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
            nuevaMultimedia: {
                tipo: '',
                id_lista: this.listaData.id_lista,
            },
            nuevaImagen: {
                nombre_archivo: '',
                tiempo: 0,
                archivo: null,
            },
            nuevoVideo: {
                nombre_archivo: '',
                archivo: null,
            },
            nuevoEnlace: {
                enlace: '',
            },
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
        abrirModal() {
            this.modalVisible = true;
            console.log('abierto')
        },
        cerrarModal() {
            this.modalVisible = false;
            this.error = null;
        },
        validarDuracion(item) {
            item.data.tiempo = item.data.tiempo.replace(/\D/g, '');
        },
        editarImagen(imagen_id, tiempo) {
            tiempo = tiempo.replace(/\D/g, '');
            axios.put(`/imagenPUT/${imagen_id}`, { tiempo })
                .then(() => {
                    // alert('Imagen editada correctamente');
                    this.cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al editar la imagen:', error);
                });
        },
        crearMultimedia() {
            if (this.nuevaMultimedia.tipo === 'imagen') {
                this.crearImagen();
            } else if (this.nuevaMultimedia.tipo === 'video') {
                this.crearVideo();
            } else if (this.nuevaMultimedia.tipo === 'enlace') {
                this.crearEnlace();
            } else {
                console.error('Tipo de multimedia no válido');
            }
        },
        crearImagen() {
            const formData = new FormData();
            formData.append('nombre_archivo', this.nuevaImagen.nombre_archivo);
            formData.append('tiempo', this.nuevaImagen.tiempo);
            formData.append('archivo', this.nuevaImagen.archivo);
            formData.append('id_lista', this.nuevaMultimedia.id_lista);

            axios.post('/imagenesPOST', formData)
                .then(response => {
                    this.cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al crear la imagen:', error);
                });
        },
        crearVideo() {
            const formData = new FormData();
            formData.append('nombre_archivo', this.nuevoVideo.nombre_archivo);
            formData.append('archivo', this.nuevoVideo.archivo);
            formData.append('id_lista', this.nuevaMultimedia.id_lista);

            axios.post('/videosPOST', formData)
                .then(response => {
                    this.cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al crear el video:', error);
                });
        },
        crearEnlace() {
            const formData = new FormData();
            formData.append('data', this.nuevoEnlace.enlace);
            formData.append('id_lista', this.nuevaMultimedia.id_lista);

            axios.post('/enlacesPOST', formData)
                .then(response => {
                    this.cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al crear el enlace:', error);
                });
        },
        handleImagenSeleccionada(event) {
            this.nuevaImagen.archivo = event.target.files[0];
        },
        handleVideoSeleccionado(event) {
            this.nuevoVideo.archivo = event.target.files[0];
        },

        // Método para eliminar una imagen
        eliminarImagen(multimedia_id, imagen_id) {
            axios.delete(`/imagenesDELETE/${multimedia_id}/${imagen_id}`, {
                data: {
                    multimedia_id: multimedia_id,
                    imagen_id: imagen_id
                }
            })
                .then(response => {
                    console.log(response.data.message);
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al eliminar la imagen:', error);
                });
        },

        // Método para eliminar un video
        eliminarVideo(multimedia_id, video_id) {
            axios.delete(`/videosDELETE/${multimedia_id}/${video_id}`, {
                data: {
                    multimedia_id: multimedia_id,
                    video_id: video_id
                }
            })
                .then(response => {
                    console.log(response.data.message);
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al eliminar el video:', error);
                });
        },

        // Método para eliminar un enlace
        eliminarEnlace(multimedia_id, enlace_id) {
            axios.delete(`/enlacesDELETE/${multimedia_id}/${enlace_id}`, {
                data: {
                    multimedia_id: multimedia_id,
                    enlace_id: enlace_id
                }
            })
                .then(response => {
                    console.log(response.data.message);
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al eliminar el enlace:', error);
                });
        },
        handleDragEnd(event) {
            // Handle drag end event to update positions in backend
            const reorderedMultimedia = event.newIndex.map(index => this.multimedia[index]);
            const updatedMultimedia = reorderedMultimedia.map((item, index) => ({
                ...item,
                position: index + 1 // Assuming position starts from 1
            }));

            axios.put('/api/updateMultimediaOrder', updatedMultimedia)
                .then(response => {
                    console.log('Updated positions successfully');
                })
                .catch(error => {
                    console.error('Error updating positions:', error);
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
            <button class="btn morado-btn" @click="abrirModal">Crear Nueva Multimedia</button>

            <div v-if="multimedia.length">
                <draggable :list="multimedia" :item-key="item => item.data.id" @end="handleDragEnd">
                    <template #item="{ element }">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <template v-if="element.tipo === 'video'">
                                    <div class="video">
                                        <h2 class="text-2l font-bold mb-4">Video</h2>
                                        <video :src="`/storage/${element.data.data}`" controls></video>
                                    </div>
                                </template>

                                <template v-else-if="element.tipo === 'imagen'">
                                    <div class="image">
                                        <h2 class="text-2l font-bold mb-4">Imagen</h2>
                                        <img :src="`/storage/${element.data.data}`"
                                            :alt="element.data.nombre_archivo" />
                                    </div>
                                </template>

                                <template v-else-if="element.tipo === 'enlace'">
                                    <div class="link">
                                        <h2 class="text-2l font-bold mb-4">Enlace</h2>
                                        <a :href="element.data.data" target="_blank" class="youtube-link">{{
                element.data.data }}</a>
                                    </div>
                                </template>
                            </div>

                            <div class="col-md-6">
                                <template v-if="element.tipo === 'video'">
                                    <!-- Aquí puedes añadir cualquier contenido adicional específico para videos si es necesario -->
                                </template>

                                <template v-else-if="element.tipo === 'imagen'">
                                    <div class="input-container">
                                        <label>Duración:</label>
                                        <div class="input-wrapper">
                                            <input type="text" v-model="element.data.tiempo"
                                                @input="validarDuracion(element)"
                                                @focusout="editarImagen(element.data.imagen_id, element.data.tiempo)"
                                                class="text-center input-duracion-imagen">
                                            <span>Segundos</span>
                                        </div>
                                    </div>
                                </template>

                                <!-- Puedes añadir más columnas aquí para otros elementos si es necesario -->
                            </div>

                            <div class="col-md-3">
                                <button
                                    @click="eliminarMultimedia(element.data.multimedia_id, element.data.video_id, element.data.imagen_id, element.data.enlace_id)"
                                    class="btn btn-danger btn-sm mt-2">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>


            <div v-else>
                <p>No hay multimedia disponible para esta lista.</p>
            </div>
        </div>

        <!-- Modal para crear nueva multimedia -->
        <div v-if="modalVisible" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear Nueva Multimedia</h5>
                        <button type="button" class="close" @click="cerrarModal">&times;</button>
                    </div>
                    <form @submit.prevent="crearMultimedia">
                        <div class="modal-body">
                            <!-- Campos para la multimedia -->
                            <div class="form-group">
                                <label for="tipo">Tipo:</label>
                                <select class="form-control" v-model="nuevaMultimedia.tipo" id="tipo">
                                    <option value="imagen">Imagen</option>
                                    <option value="video">Video</option>
                                    <option value="enlace">Enlace</option>
                                </select>
                            </div>

                            <!-- Dependiendo del tipo de multimedia, mostrar campos específicos -->
                            <template v-if="nuevaMultimedia.tipo === 'imagen'">
                                <!-- Campos para cargar imagen -->
                                <div class="form-group">
                                    <label for="nombreArchivoImagen">Nombre de archivo:</label>
                                    <input type="text" class="form-control rounded-pill"
                                        v-model="nuevaImagen.nombre_archivo" id="nombreArchivoImagen">
                                </div>
                                <div class="form-group">
                                    <label for="tiempoImagen">Tiempo (segundos):</label>
                                    <input type="number" class="form-control rounded-pill" v-model="nuevaImagen.tiempo"
                                        id="tiempoImagen">
                                </div>
                                <!-- Input para subir archivo de imagen -->
                                <div class="form-group">
                                    <label for="subirImagen">Seleccionar imagen:</label>
                                    <input type="file" class="form-control-file" @change="handleImagenSeleccionada"
                                        id="subirImagen">
                                </div>
                            </template>

                            <template v-if="nuevaMultimedia.tipo === 'video'">
                                <!-- Campos para cargar video -->
                                <div class="form-group">
                                    <label for="nombreArchivoVideo">Nombre de archivo:</label>
                                    <input type="text" class="form-control rounded-pill"
                                        v-model="nuevoVideo.nombre_archivo" id="nombreArchivoVideo">
                                </div>
                                <!-- Input para subir archivo de video -->
                                <div class="form-group">
                                    <label for="subirVideo">Seleccionar video:</label>
                                    <input type="file" class="form-control-file" @change="handleVideoSeleccionado"
                                        id="subirVideo">
                                </div>
                            </template>

                            <template v-if="nuevaMultimedia.tipo === 'enlace'">
                                <!-- Campos para cargar enlace -->
                                <div class="form-group">
                                    <label for="enlace">Enlace:</label>
                                    <input type="text" class="form-control rounded-pill" v-model="nuevoEnlace.enlace"
                                        id="enlace">
                                </div>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Crear Multimedia</button>
                        </div>
                    </form>
                </div>
            </div>
            <FooterComponent />
        </div>
    </div>
</template>

<style scoped>
.content {
    padding: 20px;
}

.input-container {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    /* O center, dependiendo de tu necesidad */
    flex-wrap: wrap;
    /* Para manejar mejor la responsividad */
    gap: 5px;
    /* Margen alrededor del contenedor */
    top: -100px;
    left: 110px;
}

.input-wrapper {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    /* O center, dependiendo de tu necesidad */
}

.input-duracion-imagen {
    width: 50px;
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
    text-align: center;
}

.btn-trash-video {
    position: relative;
    top: -50px;
    left: 100px;
    margin: 10px;
}

.btn-trash-imagen {
    position: relative;
    top: -86px;
    left: 100px;
    margin: 10px;
}

.btn-trash-enlace {
    position: relative;
    margin: 10px;
    top: -35px;
}

.youtube-link {
    top: -15px;
    position: relative;
}

.video,
.image,
.link {
    margin: 10px;
}

.content .video video,
.content .image img {
    width: 100px;
    height: 100px;
}

h2 {
    margin-bottom: 10px;
}

a {
    color: #0800ff;
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
    min-width: 500px;
    min-height: 500px;
    max-height: 500px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.3s ease-in-out;
    margin: 0 auto;
    /* Centrar el modal horizontalmente */
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

.modal-content button {
    background-color: #f78433;
    color: #fdfcfa;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 180px;
    margin-top: 10px;
    position: static;
}

.modal-content button:hover {
    background-color: #e3671f;
    border-color: #d4551a;
}
</style>
