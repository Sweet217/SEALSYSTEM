<script setup>
import FooterComponent from '@/Components/FooterComponent.vue';
import NavbarComponent from '@/Components/NavbarComponent.vue';
import axios from 'axios';
import CryptoJS from 'crypto-js';

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
            crearModalVisible: false,
            generarLicenciaModalVisible: false,
            equipoSeleccionado: {
                equipo_id: null,
                nombre: '',
                numero_licencia: '',
                mac: '',
                server_key: '',
                nombre_usuario: ''
            },
            nuevoEquipo: {
                nombre: '',
                numero_licencia: '',
                nombre_usuario: '',
                mac: '',
            },
            nombreUsuario: '',
            tipoUsuario: '',
            equiposUsuario: [],
            mac: '',
            showGenerarLicencia: false,
            error: null,
        };
    },
    computed: {
        licenciaGenerada() {
            return this.generarLicencia()
        }
    },
    mounted() {
        axios.get('/api/usuario_actual')
            .then(response => {
                this.nuevoEquipo.user_id = response.data.user_id;
                this.nombreUsuario = response.data.nombre;
                this.tipoUsuario = response.data.tipo_usuario;
                this.obtenerEquiposUsuario(response.data.user_id);

                if (this.tipoUsuario === 'Administrador') {
                    return this.showGenerarLicencia = true;
                }
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
        axios.get('/api/get_mac')
            .then(response => {
                this.mac = response.data.mac;
            })
            .catch(error => {
                console.error('Error al obtener la mac:', error);
            })
    },
    methods: {
        obtenerEquiposUsuario(user_id) {
            axios.get(`/equipos_usuario/${user_id}`)
                .then(response => {
                    this.equiposUsuario = response.data.equipos;
                })
                .catch(error => {
                    console.error('Error al obtener los equipos del usuario:', error);
                });
        },
        filtrarEquipos() {
            if (this.tipoUsuario == 'Administrador') {
                // Si el usuario no es un operador, mostrar todos los equipos
                return this.equipos;
            } else {
                // Si el usuario es un operador, filtrar solo los equipos que son suyos
                return this.equipos.filter(equipo => equipo.usuarios.nombre === this.nombreUsuario);
            }
        },

        abrirModal(equipo) {
            this.equipoSeleccionado = {
                equipo_id: equipo.equipo_id,
                nombre: equipo.nombre,
                numero_licencia: equipo.licencias ? equipo.licencias.licencia : '',
                nombre_usuario: equipo.usuarios ? equipo.usuarios.nombre : ''
            };
            this.modalVisible = true;
        },
        cerrarModal() {
            this.modalVisible = false;
            this.equipoSeleccionado = {
                equipo_id: null,
                nombre: '',
                numero_licencia: '',
                nombre_usuario: ''
            };
        },

        abrirCrearModal() {
            this.crearModalVisible = true;
            console.log(this.mac);
            this.nuevoEquipo = {
                nombre: '',
                numero_licencia: '',
                nombre_usuario: this.nombreUsuario, // Asigna el nombre del usuario actual
                mac: this.mac //Asigna la mac del equipo actual (simulado)
            };
        },
        cerrarCrearModal() {
            this.crearModalVisible = false;
            this.nuevoEquipo = {
                nombre: '',
                numero_licencia: '',
                nombre_usuario: this.nombreUsuario
            };
        },
        abrirModalGenerarLicencia(equipo) {
            console.log(equipo)
            this.equipoSeleccionado = {
                equipo_id: equipo.equipo_id,
                mac: equipo.mac,
                server_key: equipo.server_key ? equipo.server_key : this.generarServerKey() //Si ya tiene una server_key pasar esa, si no, generar una.
            };
            this.generarLicenciaModalVisible = true;
        },
        cerrarModalGenerarLicencia() {
            this.generarLicenciaModalVisible = false;
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
            console.log('Numero de licencia:', this.nuevoEquipo.numero_licencia);
            console.log('Equipo ID: ', equipo_id);
            axios.put(`/equiposPUT/${equipo_id}`, {
                nombre: this.equipoSeleccionado.nombre,
                numero_licencia: this.equipoSeleccionado.numero_licencia,
                nombre_usuario: this.equipoSeleccionado.nombre_usuario
            })
                .then(() => {
                    alert('Equipo editado correctamente');
                    this.cerrarModal();
                    window.location.reload();
                })
                .catch(error => {
                    if (error.response && error.response.data.message) {
                        alert(error.response.data.message);
                    } else {
                        console.error('Error al editar el equipo:', error);
                    }
                });
        },
        crearEquipo() {
            const equipoExistente = this.equiposUsuario.some(equipo => equipo.nombre === this.nuevoEquipo.nombre);

            if (equipoExistente) {
                alert('Ya tienes un equipo registrado con este nombre.');
                return;
            }

            console.log(this.nuevoEquipo.mac);
            //La key sera desencriptar
            let key = "desencriptar"
            let mac_encriptada = CryptoJS.AES.encrypt(this.nuevoEquipo.mac, key).toString()

            axios.post('/equiposPOST', {
                nombre: this.nuevoEquipo.nombre,
                numero_licencia: this.nuevoEquipo.numero_licencia,
                nombre_usuario: this.nuevoEquipo.nombre_usuario,
                mac: mac_encriptada
            })
                .then(response => {
                    alert('Equipo creado correctamente');
                    this.cerrarCrearModal();
                    this.equipos.push(response.data.equipo);
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al crear el equipo:', error);
                    alert('Error al crear el equipo');
                });
        },
        guardarServerKey(equipo_id) {
            console.log(this.equipoSeleccionado);
            console.log('Equipo_id: ', equipo_id);
            axios.post(`/equipos/agregarServerKey/${equipo_id}`, {
                server_key: this.equipoSeleccionado.server_key
            })
                .then(response => {
                    alert('Server Key guardada correctamente');
                    window.location.reload()
                })
                .catch(error => {
                    console.error('Error al guardar Server Key:', error);
                    alert('Error al guardar Server Key');
                });
        },
        generarLicencia() {
            let key = 'desencriptar';
            let bytes = CryptoJS.AES.decrypt(this.equipoSeleccionado.mac, key);
            let mac_desencriptada = bytes.toString(CryptoJS.enc.Utf8);
            console.log(mac_desencriptada);
            let key_combined = `${mac_desencriptada}-${this.equipoSeleccionado.server_key}`;
            let hash = CryptoJS.SHA256(key_combined).toString(CryptoJS.enc.Hex);
            let shortHash = hash.substring(0, 16); // Lograr una longitud similar a la de windows.
            let formattedHash = shortHash.match(/.{1,4}/g).join('-').toUpperCase(); //Lograr que la substring sea similar a una licencia de windows XXXX-XXXX-XXXX-XXXX

            return formattedHash;
        },
        generarServerKey() {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let clave = '';
            for (let i = 0; i < 10; i++) {
                clave += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }

            return clave;
        }
    }
}
</script>

<template>
    <div>
        <NavbarComponent></NavbarComponent>

        <div class="container mx-auto mt-4">
            <h1 class="text-2xl font-bold mb-4">Todos los Equipos</h1>
            <button class="btn morado-btn" @click="abrirCrearModal">Crear Nuevo Equipo</button>

            <div v-if="equipos.length === 0" class="text-gray-500">
                No hay equipos disponibles.
            </div>

            <div v-else>
                <ul class="list-disc space-y-2">
                    <li v-for="equipo in filtrarEquipos()" :key="equipo.equipo_id">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3>Equipo: {{ equipo.nombre }}</h3>
                                <p>Licencia: {{ equipo.licencias ? equipo.licencias.licencia : 'Sin Licencia' }}</p>
                                <p>Usuario: {{ equipo.usuarios ? equipo.usuarios.nombre : 'Sin Usuario' }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button v-if="showGenerarLicencia" class="btn generar-licencia-btn"
                                    @click="abrirModalGenerarLicencia(equipo)">Generar
                                    Licencia</button>
                                <button class="btn editar-btn" @click="abrirModal(equipo)">Editar</button>
                                <button class="btn btn-danger btn-trash bi-trash"
                                    @click="eliminarEquipo(equipo.equipo_id)"></button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Modal para editar equipo -->
        <div v-if="modalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarModal">&times;</span>
                <h2>Editar Equipo</h2>
                <form @submit.prevent="editarEquipo(equipoSeleccionado.equipo_id)">
                    <label for="nombre">Nombre del Equipo:</label>
                    <input type="text" v-model="equipoSeleccionado.nombre" id="nombre"
                        class="form-control rounded-pill">
                    <label for="numero_licencia">Número de Licencia:</label>
                    <input type="text" v-model="equipoSeleccionado.numero_licencia" id="numero_licencia"
                        class="form-control rounded-pill">
                    <label for="nombre_usuario">Nombre del Usuario Responsable:</label>
                    <input type="text" v-model="equipoSeleccionado.nombre_usuario" id="nombre_usuario"
                        class="form-control rounded-pill">
                    <div class="text-center">
                        <button type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para crear equipo -->
        <div v-if="crearModalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarCrearModal">&times;</span>
                <h2>Crear Nuevo Equipo</h2>
                <form @submit.prevent="crearEquipo">
                    <label for="nombre">Nombre del Equipo:</label>
                    <input type="text" v-model="nuevoEquipo.nombre" id="nombre" class="form-control rounded-pill">
                    <label for="numero_licencia">Número de Licencia:</label>
                    <input type="text" v-model="nuevoEquipo.numero_licencia" id="numero_licencia"
                        class="form-control rounded-pill">
                    <label for="nombre_usuario">Nombre del Usuario Responsable:</label>
                    <input type="text" v-model="nuevoEquipo.nombre_usuario" id="nombre_usuario"
                        class="form-control rounded-pill" disabled>
                    <div class="text-center">
                        <button type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal para generar licencia -->
        <div v-if="generarLicenciaModalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarModalGenerarLicencia">&times;</span>
                <h2>Generar Licencia</h2>
                <form @submit.prevent="guardarServerKey(equipoSeleccionado.equipo_id)">
                    <div class="campo">
                        <label for="mac">Mac:</label>
                        <input type="text" v-model="equipoSeleccionado.mac" id="mac" class="form-control rounded-pill"
                            disabled>
                    </div>
                    <div class="campo">
                        <label for="serverKey">Server Key:</label>
                        <input class="form-control rounded-pill" type="text" id="serverKey"
                            v-model="equipoSeleccionado.server_key" disabled>
                    </div>
                    <div class="campo">
                        <label for="licencia">Licencia:</label>
                        <input :value="licenciaGenerada" type="text" class="form-control rounded-pill" id="licencia"
                            rows="4" disabled>
                    </div>
                    <div class="text-center">
                        <button type="submit">Guardar Server Key</button>
                    </div>
                </form>
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
    margin-bottom: 10px;
    margin-top: -10px;
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


.generar-licencia-btn {
    background-color: #f78433;
    border: 1px solid #e3671f;
    color: white;
}

.generar-licencia-btn:hover {
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