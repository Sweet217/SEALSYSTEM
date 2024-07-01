<script setup>
// Importa los componentes NavbarComponent y FooterComponent
import NavbarComponent from '@/Components/NavbarComponent.vue';
import FooterComponent from '@/Components/FooterComponent.vue';
import Swal from 'sweetalert2';

// Importa las librerías axios y CryptoJS
import axios from 'axios';
import CryptoJS from 'crypto-js';

// Define las props que recibe el componente
const props = defineProps({
    equipos: Object, // Objeto que contiene los equipos del usuario
});
</script>

<script>
export default {// Define el estado del componente
    data() {
        return {
            nombre: '', // Nombre del equipo seleccionado
            modalVisible: false, // Visibilidad del modal principal
            crearModalVisible: false,  // Visibilidad del modal de creación de equipo
            generarLicenciaModalVisible: false, // Visibilidad del modal de generación de licencia
            equipoSeleccionado: {
                equipo_id: null, //Id del equipo
                nombre: '', //Nombre del equipo
                numero_licencia: '', //Numero de licencia del equipo
                mac: '', //Mac del equipo
                server_key: '', //Server key del equipo
                nombre_usuario: '', //Nombre del usuario del equipo
            },
            nuevoEquipo: { // Nuevo equipo a crear
                nombre: '', //Nombre del equipo que se creara
                numero_licencia: '', //Numero de licencia del equipo que se creara (puede ser null en caso de que no se introduzca una licencia)
                nombre_usuario: '', //Nombre del usuario responsable (siempre sera el usuario actual que este utilizando el sistema)
                mac: '', //Mac del equipo se obtendra desde la aplicacion de c# ya que un sistema web no puede sacar la direccion mac
            },
            nombreUsuario: '',  // Nombre del usuario actual
            tipoUsuario: '',  // Tipo de usuario actual (Administrador o Operador)
            equiposUsuario: [], // Equipos del usuario actual
            mac: '', // Dirección MAC del equipo actual
            showGenerarLicencia: false, // Indica si se muestra el botón para generar licencia
            usuariosDisponibles: [],
            error: null, // Almacena el error en caso de que ocurra uno
        };
    },
    computed: {
        licenciaGenerada() {
            return this.generarLicencia() //Para lograr poner el generarLicencia() dentro de un input con v-model
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
        axios.get('/todosusuarios')
            .then(response => {
                this.usuariosDisponibles = response.data;
                console.log(response.data);
            }).catch(error => {
                console.error('Error al botener los usuarios:', error);
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
                // Si el usuario no es un operador, mostrar todos los equipos, se cambio a que de igual el tipo de usuario, solo puedes ver TUS equipos
                return this.equipos.filter(equipo => equipo.usuarios.nombre === this.nombreUsuario);
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
                    Swal.fire({
                        title: 'Dispositivo eliminado',
                        text: 'Dispositivo eliminado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al eliminar el equipo:', error);
                    Swal.fire({
                        title: 'Dispositivo eliminado',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
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
                    Swal.fire({
                        title: 'Dispositivo editado',
                        text: 'Dispositivo editado correctamente',
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
                    if (error.response && error.response.data.message) {
                        console.error('Error al editar el equipo:', error);
                        Swal.fire({
                            title: 'Dispositivo no editado',
                            text: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            }
                        });
                    } else {
                        console.error('Error al editar el dispositivo:', error);
                        Swal.fire({
                            title: 'Dispositivo editado',
                            text: 'Error al editar el dispositivo',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.cerrarModal();
                            }
                        });
                    }
                });
        },
        crearEquipo() {
            const equipoExistente = this.equiposUsuario.some(equipo => equipo.nombre === this.nuevoEquipo.nombre);

            if (equipoExistente) {
                Swal.fire({
                    title: 'Dispositivo encontrado',
                    text: 'Ya tienes un dispositivo registrado con este nombre',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        return;
                    }
                });
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
                    Swal.fire({
                        title: 'Dispositivo creado',
                        text: 'Dispositivo creado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarCrearModal();
                            window.location.reload();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al crear el equipo:', error);
                    Swal.fire({
                        title: 'Dispositivo no creado',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        guardarServerKey(equipo_id) {
            console.log(this.equipoSeleccionado);
            console.log('Equipo_id: ', equipo_id);
            console.log(this.licenciaGenerada);
            axios.post(`/equipos/agregarServerKey/${equipo_id}`, {
                server_key: this.equipoSeleccionado.server_key,
                licencia: this.licenciaGenerada
            })
                .then(response => {
                    Swal.fire({
                        title: 'Server Key',
                        text: 'Server key guardada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModalGenerarLicencia();
                            window.location.reload();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al guardar Server Key:', error);
                    Swal.fire({
                        title: 'Server Key',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModalGenerarLicencia();
                        }
                    });
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
            //Generar una clave en el servidor aleatoria
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let clave = '';
            for (let i = 0; i < 10; i++) {
                clave += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }

            return clave;
        },
        copiarLicencia() {
            // Seleccionar el campo de texto
            const licenciaInput = document.getElementById('licencia');

            try {
                // Intentar copiar el texto al portapapeles
                navigator.clipboard.writeText(licenciaInput.value);

                // Mostrar mensaje de éxito
                Swal.fire({
                    title: 'Copiado!',
                    text: 'Licencia copiada al portapapeles.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            } catch (err) {
                console.error('Error al copiar al portapapeles:', err);
                // Manejar errores si es necesario
                Swal.fire({
                    title: 'Error!',
                    text: 'No se pudo copiar la licencia al portapapeles.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    }
}
</script>

<template>
    <div>
        <NavbarComponent></NavbarComponent>

        <div class="container mx-auto mt-4">
            <h1 class="text-2xl font-bold mb-4">Todos los Dispositivos</h1>
            <button class="btn morado-btn" @click="abrirCrearModal">Crear Nuevo Dispositivo</button>

            <div v-if="equipos.length === 0" class="text-gray-500">
                No hay dispositivos disponibles.
            </div>

            <div v-else>
                <ul class="list-disc space-y-2">
                    <li v-for="equipo in filtrarEquipos()" :key="equipo.equipo_id">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3>Dispositivo: {{ equipo.nombre }}</h3>
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
                <h2>Editar Dispositivo</h2>
                <form @submit.prevent="editarEquipo(equipoSeleccionado.equipo_id)">
                    <label for="nombre">Nombre del Dispositivo:</label>
                    <input type="text" v-model="equipoSeleccionado.nombre" id="nombre"
                        class="form-control rounded-pill">
                    <label for="numero_licencia">Número de Licencia:</label>
                    <input type="text" v-model="equipoSeleccionado.numero_licencia" id="numero_licencia"
                        class="form-control rounded-pill">
                    <label for="nombre_usuario">Nombre del Usuario Responsable:</label>
                    <select v-model="equipoSeleccionado.nombre_usuario" id="nombre_usuario"
                        class="form-control rounded-pill">
                        <option v-for="usuario in usuariosDisponibles" :value="usuario.nombre" :key="usuario.id">
                            {{ usuario.nombre }}
                        </option>
                    </select>
                    <div class="text-center">
                        <button type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para crear equipo -->
        <div v-if="crearModalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarCrearModal">&times;</span>
                <h2>Crear Nuevo Dispositivo</h2>
                <form @submit.prevent="crearEquipo">
                    <label for="nombre">Nombre del Dispositivo:</label>
                    <input type="text" v-model="nuevoEquipo.nombre" id="nombre" class="form-control rounded-pill">
                    <label for="numero_licencia">Número de Licencia:</label>
                    <input type="text" v-model="nuevoEquipo.numero_licencia" id="numero_licencia"
                        class="form-control rounded-pill">
                    <label for="nombre_usuario">Nombre del Usuario Responsable:</label>
                    <input type="text" v-model="nuevoEquipo.nombre_usuario" id="nombre_usuario"
                        class="form-control rounded-pill" disabled>
                    <div class="text-center">
                        <button type="submit">Agregar</button>
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
                        <div class="input-group">
                            <input :value="licenciaGenerada" type="text" class="form-control rounded-pill" id="licencia"
                                rows="4" disabled>
                            <button @click="copiarLicencia" class="btn btn-outline-secondary rounded" type="button">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit">Guardar</button>
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