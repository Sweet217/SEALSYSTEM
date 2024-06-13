<script setup>
import FooterComponent from '@/Components/FooterComponent.vue';
import NavbarComponent from '@/Components/NavbarComponent.vue';

const props = defineProps({
    multimedia: Object,
    listaData: Object,
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
            axios.post('/enlacesPOST', {
                data: this.nuevoEnlace.enlace,
            })
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
                            <!-- Hacer que la duración sea editable -->
                            <label>Duración:</label>
                            <input type="text" v-model="item.data.tiempo" @input="validarDuracion(item)"
                                @focusout="editarImagen(item.data.imagen_id, item.data.tiempo)" class="text-center"
                                style="max-width: 50px; height: 30px;"> Segundos
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
