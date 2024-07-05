<script setup>
import FooterComponent from '@/Components/FooterComponent.vue';
import NavbarComponent from '@/Components/NavbarComponent.vue';
import draggable from 'vuedraggable';
import Swal from 'sweetalert2';

const props = defineProps({
    multimedia: Object,
    listaData: Object,
    multimediaData: Object,
});

</script>

<script>
export default {
    components: {
        draggable // Componente draggable utilizado para arrastrar y soltar
    },
    computed: {
        // Propiedad computada para ordenar la multimedia según la posición
        multimediaOrdenada() {
            return this.multimediaData.sort((a, b) => a.posicion - b.posicion);
        }
    },
    data() {
        return {
            nombreUsuario: '', // Nombre del usuario actual
            tipoUsuario: '', // Tipo de usuario actual
            error: '', // Variable para almacenar errores
            modalVisible: false, // Controla la visibilidad del modal
            imagenSeleccionada: { // Datos de la imagen seleccionada
                imagen_id: null,
                tiempo: '',
            },
            nuevaMultimedia: { // Datos de la nueva multimedia
                tipo: '',
                id_lista: this.listaData.id_lista,
            },
            nuevaImagen: { // Datos de la nueva imagen
                nombre_archivo: '',
                tiempo: 0,
                archivo: null,
            },
            nuevoVideo: { // Datos del nuevo video
                nombre_archivo: '',
                archivo: null,
            },
            nuevoEnlace: { // Datos del nuevo enlace
                nombre_enlace: '',
                enlace: '',

            },
            loading: false, // Estado de carga
            uploadProgress: 0, // Progreso de carga
        };
    },
    mounted() {
        // Se ejecuta cuando el componente es montado
        axios.get('/api/usuario_actual')
            .then(response => {
                this.nombreUsuario = response.data.nombre; // Asigna el nombre del usuario actual
                this.tipoUsuario = response.data.tipo_usuario; // Asigna el tipo de usuario actual
            })
            .catch(error => {
                console.error('Error al obtener el usuario actual:', error); // Muestra un error en la consola
                if (error.response && error.response.status === 401) {
                    window.location.href = '/'; // Redirige a la página de inicio de sesión si no está autenticado
                } else {
                    this.error = 'Error al obtener el usuario actual.'; // Asigna un mensaje de error
                }
            });
    },
    watch: {
        'nuevoVideo.nombre_archivo'(newVal) {
            if (newVal.length > 25) {
                this.nuevoVideo.nombre_archivo = newVal.slice(0, 25);
            }
        },
        'nuevaImagen.nombre_archivo'(newVal) {
            if (newVal.length > 25) {
                this.nuevaImagen.nombre_archivo = newVal.slice(0, 25);
            }
        },
        'nuevoEnlace.nombre_enlace'(newVal) {
            if (newVal.length > 25) {
                this.nuevoEnlace.nombre_enlace = newVal.slice(0, 25);
            }
        },
    },
    methods: {
        // Método para abrir el modal
        abrirModal() {
            this.modalVisible = true;
            this.nuevaImagen = {
                nombre_archivo: '',
                tiempo: 0,
                archivo: null,
            };
            this.nuevoVideo = {
                nombre_archivo: '',
                archivo: null,
            };
            this.nuevoEnlace = {
                nombre_enlace: '',
                enlace: '',
            };
            this.nuevaMultimedia = { // Datos de la nueva multimedia
                tipo: '',
                id_lista: this.listaData.id_lista,
            };
        },
        // Método para cerrar el modal
        cerrarModal() {
            this.modalVisible = false;
            this.error = null; // Reinicia el error
        },
        // Método para validar la duración de un item multimedia
        validarDuracion(item) {
            item.data.tiempo = item.data.tiempo.replace(/\D/g, ''); // Remueve caracteres no numéricos
        },
        // Método para editar una imagen por su ID
        editarImagen(imagen_id, tiempo) {
            tiempo = tiempo.replace(/\D/g, ''); // Remueve caracteres no numéricos
            axios.put(`/imagenPUT/${imagen_id}`, { tiempo })
                .then(() => {
                    this.cerrarModal(); // Cierra el modal
                    window.location.reload(); // Recarga la página
                })
                .catch(error => {
                    console.error('Error al editar la imagen:', error); // Muestra un error en la consola
                });
        },
        // Método para crear una nueva multimedia
        crearMultimedia() {
            if (this.nuevaMultimedia.tipo === 'imagen') {
                this.crearImagen(); // Crea una imagen
            } else if (this.nuevaMultimedia.tipo === 'video') {
                this.crearVideo(); // Crea un video
            } else if (this.nuevaMultimedia.tipo === 'enlace') {
                this.crearEnlace(); // Crea un enlace
            } else {
                console.error('Tipo de multimedia no válido'); // Muestra un error en la consola
            }
        },
        // Método para crear una nueva imagen
        crearImagen() {
            const formData = new FormData();
            formData.append('nombre_archivo', this.nuevaImagen.nombre_archivo);
            formData.append('tiempo', this.nuevaImagen.tiempo);
            formData.append('archivo', this.nuevaImagen.archivo);
            formData.append('id_lista', this.nuevaMultimedia.id_lista);

            this.loading = true; // Indicar que ha comenzado la carga

            axios.post('/imagenesPOST', formData, {
                onUploadProgress: (progressEvent) => {
                    this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                }
            })
                .then(response => {
                    this.loading = false; // Indicar que la carga ha terminado
                    this.uploadProgress = 0; // Reiniciar el progreso

                    Swal.fire({
                        title: 'Imagen agregada',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModal();
                            window.location.reload();
                        }
                    });
                })
                .catch(error => {
                    this.loading = false; // Indicar que la carga ha terminado
                    this.uploadProgress = 0; // Reiniciar el progreso
                    console.error('Error al crear la imagen:', error); // Muestra un error en la consola
                    Swal.fire({
                        title: 'Error al crear la imagen',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        // Método para crear un nuevo video
        crearVideo() {
            const formData = new FormData();
            formData.append('nombre_archivo', this.nuevoVideo.nombre_archivo);
            formData.append('archivo', this.nuevoVideo.archivo);
            formData.append('id_lista', this.nuevaMultimedia.id_lista);

            this.loading = true; // Indicar que ha comenzado la carga

            axios.post('/videosPOST', formData, {
                onUploadProgress: (progressEvent) => {
                    this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                }
            })
                .then(response => {
                    this.loading = false; // Indicar que la carga ha terminado
                    this.uploadProgress = 0; // Reiniciar el progreso
                    Swal.fire({
                        title: 'Video agregado',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModal();
                            window.location.reload();
                        }
                    });
                })
                .catch(error => {
                    this.loading = false; // Indicar que la carga ha terminado
                    this.uploadProgress = 0; // Reiniciar el progreso
                    console.error('Error al crear el video:', error); // Muestra un error en la consola
                    Swal.fire({
                        title: 'Error al crear el video',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        // Método para crear un nuevo enlace
        crearEnlace() {
            const formData = new FormData();
            console.log(this.nuevoEnlace.nombre_enlace);
            formData.append('nombre_enlace', this.nuevoEnlace.nombre_enlace);
            formData.append('data', this.nuevoEnlace.enlace);
            formData.append('id_lista', this.nuevaMultimedia.id_lista);

            axios.post('/enlacesPOST', formData)
                .then(response => {
                    Swal.fire({
                        title: 'Enlace agregado',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModal();
                            window.location.reload();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al crear el enlace:', error); // Muestra un error en la consola
                    Swal.fire({
                        title: 'Error al crear el enlace',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        // Maneja la selección de una imagen
        handleImagenSeleccionada(event) {
            this.nuevaImagen.archivo = event.target.files[0];
            const file = event.target.files[0];
            if (file & this.nuevaImagen.nombre_archivo === '') {
                this.nuevaImagen.nombre_archivo = file.name;
            }
        },
        // Maneja la selección de un video
        handleVideoSeleccionado(event) {
            this.nuevoVideo.archivo = event.target.files[0];
            const file = event.target.files[0];
            if (file & this.nuevoVideo.nombre_archivo === '') {
                this.nuevoVideo.nombre_archivo = file.name;
            }
        },
        // Método para eliminar una multimedia
        eliminarMultimedia(multimedia_id, video_id, imagen_id, enlace_id) {
            if (imagen_id) {
                // Eliminar imagen
                axios.delete(`/imagenesDELETE/${multimedia_id}/${imagen_id}`, {
                    data: {
                        multimedia_id: multimedia_id,
                        imagen_id: imagen_id
                    }
                })
                    .then(response => {
                        Swal.fire({
                            title: 'Imagen Eliminada',
                            text: 'Imagen eliminada correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log(response.data.message);
                                window.location.reload(); // Recarga la página
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error al eliminar la imagen:', error); // Muestra un error en la consola
                        Swal.fire({
                            title: 'Error al eliminar la imagen',
                            text: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            }
                        });
                    });
            } else if (video_id) {
                // Eliminar video
                axios.delete(`/videosDELETE/${multimedia_id}/${video_id}`, {
                    data: {
                        multimedia_id: multimedia_id,
                        video_id: video_id
                    }
                })
                    .then(response => {
                        Swal.fire({
                            title: 'Video Eliminado',
                            text: 'Video eliminado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log(response.data.message);
                                window.location.reload(); // Recarga la página
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error al eliminar el video:', error); // Muestra un error en la consola
                        Swal.fire({
                            title: 'Error al eliminar el video',
                            text: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            }
                        });
                    });
            } else if (enlace_id) {
                // Eliminar enlace
                axios.delete(`/enlacesDELETE/${multimedia_id}/${enlace_id}`, {
                    data: {
                        multimedia_id: multimedia_id,
                        enlace_id: enlace_id
                    }
                })
                    .then(response => {
                        Swal.fire({
                            title: 'Enlace Eliminado',
                            text: 'Enlace eliminado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log(response.data.message);
                                window.location.reload(); // Recarga la página
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error al eliminar el enlace:', error); // Muestra un error en la consola
                        Swal.fire({
                            title: 'Error al eliminar el video',
                            text: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            }
                        });
                    });
            }
        },
        // Maneja el fin del arrastre para actualizar la posición de la multimedia
        async handleDragEnd() {
            const positions = this.multimedia.map((item, index) => ({
                multimedia_id: item.data.multimedia_id,
                nueva_posicion: index + 1, // +1 porque las posiciones usualmente empiezan desde 1
            }));
            console.log(positions);

            try {
                const response = await axios.put('/actualizarOrdenMultimedia', { positions });
                console.log('Posiciones actualizadas correctamente:', response.data.message);
            } catch (error) {
                console.error('Error al actualizar posiciones:', error.response.data || error.message);
            }
        },
        // Obtiene la URL de embed de YouTube a partir de una URL estándar
        getEmbedUrl(url) {
            let videoId = this.getVideoId(url);
            return `https://www.youtube.com/embed/${videoId}`;
        },
        // Extrae el ID del video de YouTube a partir de una URL
        getVideoId(url) {
            let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            let match = url.match(regExp);
            if (match && match[2].length === 11) {
                return match[2];
            } else {
                return 'VIDEO_ID';
            }
        },
    }
}
</script>

<template>
    <div>
        <NavbarComponent />
        <div class="content container mx-auto mt-4">
            <h1 class="text-3xl font-bold mb-4">Multimedia {{ listaData.nombre }} </h1>
            <button class="btn morado-btn" @click="abrirModal">Agregar Elemento</button>

            <div v-if="multimedia.length">
                <draggable :list="multimedia" :item-key="item => item.data.id" @end="handleDragEnd">
                    <template #item="{ element }">
                        <div class="row align-items-center justify-content-between mb-4">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <template v-if="element.tipo === 'video'">
                                    <div class="video">
                                        <h2 class="text-2l font-bold mb-4">Video</h2>
                                        <a>{{ element.data.nombre_archivo }} </a>
                                        <video :src="`/storage/${element.data.data}`" controls></video>
                                    </div>
                                </template>

                                <template v-else-if="element.tipo === 'imagen'">
                                    <div class="image">
                                        <h2 class="text-2l font-bold mb-4">Imagen</h2>
                                        <a> {{ element.data.nombre_archivo }} </a>
                                        <img :src="`/storage/${element.data.data}`"
                                            :alt="element.data.nombre_archivo" />
                                    </div>
                                </template>

                                <template v-else-if="element.tipo === 'enlace'">
                                    <div class="link">
                                        <h2 class="text-2l font-bold mb-4">Enlace</h2>
                                        <a class="nombre_enlace">{{ element.data.nombre_enlace }} </a>
                                        <a :href="element.data.data" target="_blank"
                                            class="youtube-link enlace-youtube">{{
                element.data.data }}</a>
                                        <iframe :src="getEmbedUrl(element.data.data)"></iframe>
                                    </div>
                                </template>
                            </div>

                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <template v-if="element.tipo === 'imagen'">
                                    <div class="input-container">
                                        <label>Duración:</label>
                                        <div class="input-wrapper col-12 col-md-3 mb-2 mb-md-0">
                                            <input type="text" v-model="element.data.tiempo"
                                                @input="validarDuracion(element)"
                                                @focusout="editarImagen(element.data.imagen_id, element.data.tiempo)"
                                                class="text-center input-duracion-imagen">
                                            <span>Segundos</span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <button
                                    @click="eliminarMultimedia(element.data.multimedia_id, element.data.video_id, element.data.imagen_id, element.data.enlace_id)"
                                    class="btn btn-danger btn-sm mt-2 btn-trash">
                                    <i class="bi bi-trash"></i>
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
                    <span class="close" @click="cerrarModal">&times;</span>
                    <h2 class="modal-title">Crear Multimedia</h2>
                    <form @submit.prevent="crearMultimedia">
                        <div class="modal-body">
                            <h3>Seleccionar archivo multimedia</h3>
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
                                    <label for="nombreArchivoImagen">Nombre de archivo: </label>
                                    <input type="text" class="form-control rounded-pill"
                                        v-model="nuevaImagen.nombre_archivo" id="nombreArchivoImagen"
                                        placeholder="Maximo 25 caracteres">
                                </div>
                                <div class="form-group">
                                    <label for="tiempoImagen">Tiempo (segundos):</label>
                                    <input type="number" class="form-control rounded-pill" v-model="nuevaImagen.tiempo"
                                        id="tiempoImagen">
                                </div>
                                <!-- Input para subir archivo de imagen -->
                                <div class="form-group">
                                    <label for="subirImagen">Seleccionar imagen:</label>
                                    <input type="file" accept="image/*" class="form-control-file"
                                        @change="handleImagenSeleccionada" id="subirImagen">
                                    <span v-if="fileError" class="error-message">{{ fileError }}</span>
                                </div>

                                <!-- Barra de progreso -->
                                <div v-if="loading" class="form-group">
                                    <label>Subiendo imagen:</label>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" :style="{ width: uploadProgress + '%' }"
                                            :aria-valuenow="uploadProgress" aria-valuemin="0" aria-valuemax="100">
                                            {{ uploadProgress }}%
                                        </div>
                                    </div>
                                    <p>Cargando... {{ uploadProgress }}%</p>
                                </div>
                            </template>

                            <template v-if="nuevaMultimedia.tipo === 'video'">
                                <!-- Campos para cargar video -->
                                <div class="form-group">
                                    <label for="nombreArchivoVideo">Nombre de archivo:</label>
                                    <input type="text" class="form-control rounded-pill"
                                        v-model="nuevoVideo.nombre_archivo" id="nombreArchivoVideo"
                                        placeholder="Maximo 25 caracteres">
                                </div>
                                <!-- Input para subir archivo de video -->
                                <div class="form-group">
                                    <label for="subirVideo">Seleccionar video:</label>
                                    <input type="file" accept="video/*" class="form-control-file"
                                        @change="handleVideoSeleccionado" id="subirVideo">
                                    <span v-if="fileError" class="error-message">{{ fileError }}</span>
                                </div>

                                <!-- Barra de progreso -->
                                <div v-if="loading" class="form-group">
                                    <label>Subiendo video:</label>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" :style="{ width: uploadProgress + '%' }"
                                            :aria-valuenow="uploadProgress" aria-valuemin="0" aria-valuemax="100">
                                            {{ uploadProgress }}%
                                        </div>
                                    </div>
                                    <p>Cargando... {{ uploadProgress }}%</p>
                                </div>
                            </template>

                            <template v-if="nuevaMultimedia.tipo === 'enlace'">
                                <!-- Campos para cargar enlace -->
                                <div class="form-group">
                                    <label for="enlace">Nombre del Enlace:</label>
                                    <input type="text" class="form-control rounded-pill"
                                        v-model="nuevoEnlace.nombre_enlace" id="nombreEnlace"
                                        placeholder="Maximo 25 caracteres">
                                    <label for="enlace">Enlace:</label>
                                    <input type="text" class="form-control rounded-pill" v-model="nuevoEnlace.enlace"
                                        id="enlace" placeholder="Ejemplo: https://www.youtube.com/watch?v=jNQXAC">
                                </div>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary guardar-btn">Agregar</button>
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
    padding: 40px;
}

.btn-trash {
    position: relative;
    display: flex;
    top: 7px;
    left: 60%;
}

.input-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 5px;
}

.input-wrapper {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.input-duracion-imagen {
    width: 50px;
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
    text-align: center;
}

.youtube-link {
    position: relative;
    display: flex;
    left: 70%;
    top: 60px;
}

.video,
.image,
.link {
    margin: 0%;
}

.content .video video,
.content .image img {
    width: 80px;
    height: 80px;
}

h2 {
    margin-bottom: 10px;
}

a.enlace-youtube {
    color: #0800ff;
    /* Cambié el color para que coincida con los estilos de los botones */
    text-decoration: none;
    /* Quité la subrayado del enlace */
}

iframe {
    height: 80px;
    width: 80px;
    margin-top: -20px;
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
    padding: 10px 10;
}

.close:hover,
.close:focus {
    color: #f78433;
    text-decoration: none;
    cursor: pointer;
}

.modal-content .guardar-btn {
    background-color: #f78433;
    color: #fdfcfa;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: -10px;
}

.modal-content .guardar-btn:hover {
    background-color: #e3671f;
    border-color: #d4551a;
}

/* Responsive design */
@media (max-width: 600px) {
    .modal-content {
        width: 90%;
        min-width: unset;
        max-width: unset;
        min-height: unset;
        max-height: unset;
    }

    .btn-trash {
        top: -100px;
    }

    .youtube-link {
        top: 110px;
        left: 0%;
    }

    .video,
    .image,
    .link {
        margin: 0%;
        margin-top: -40px;
    }

    .morado-btn {
        background-color: #302f51;
        border: 1px solid #302f51;
        color: white;
        margin-bottom: 100px;
    }
}
</style>
