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
                licencia_inicio: '',
                licencia_final: '',
                periodo: ''
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
            busqueda: '',
            periodoSeleccionado: 'PRUEBA',
            error: null, // Almacena el error en caso de que ocurra uno
        };
    },
    computed: {
        licenciaGenerada() {
            return this.generarLicencia() //Para lograr poner el generarLicencia() dentro de un input con v-model
        },
        macDesencriptada: {
            get() {
                return this.equipoSeleccionado.mac || '';
            },
            set(value) {
                this.equipoSeleccionado.mac = value;
            }
        },
        formattedLicensePeriod() {
            const { licencia_inicio, licencia_final } = this.equipoSeleccionado;

            // Verifica si ambas fechas están presentes
            if (!licencia_inicio || !licencia_final) {
                return '';
            }

            // Función para formatear las fechas en formato Día/Mes/Año
            function formatDateToDMY(dateString) {
                const date = new Date(dateString);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Meses son 0-indexados
                const year = date.getFullYear();

                return `${day}/${month}/${year}`;
            }

            const inicioFormatted = formatDateToDMY(licencia_inicio);
            const finalFormatted = formatDateToDMY(licencia_final);

            return `${inicioFormatted} al ${finalFormatted}`;
        },
        usuariosResponsables() {
            const usuarios = this.equipos.map(equipo => equipo.usuarios.nombre);
            return [...new Set(usuarios)]; // Eliminar duplicados
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
        axios.get('/todosusuarios')
            .then(response => {
                this.usuariosDisponibles = response.data;

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
                return this.equipos
            } else {
                return this.equipos
            }
        },
        abrirModal(equipo) {
            this.equipoSeleccionado = {
                equipo_id: equipo.equipo_id,
                nombre: equipo.nombre,
                numero_licencia: equipo.licencias ? equipo.licencias.licencia : '',
                nombre_usuario: equipo.usuarios ? equipo.usuarios.nombre : '',
                licencia_inicio: equipo.licencias ? equipo.licencias.licencia_inicio : '',
                licencia_final: equipo.licencias ? equipo.licencias.licencia_final : '',
                periodo: equipo.licencias ? equipo.licencias.periodo : '',
            };
            this.modalVisible = true;
            // console.log(this.equipoSeleccionado.licencia_inicio);
        },
        cerrarModal() {
            this.modalVisible = false;
            this.equipoSeleccionado = {
                equipo_id: null,
                nombre: '',
                numero_licencia: '',
                nombre_usuario: '',
                licencia_inicio: '',
                licencia_final: ''
            };
        },

        abrirCrearModal() {
            this.crearModalVisible = true;
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
            this.equipoSeleccionado = {
                equipo_id: equipo.equipo_id,
                mac: equipo.mac || '',
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
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload()
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
            if (!this.equipoSeleccionado.nombre || !this.equipoSeleccionado.nombre_usuario) {
                Swal.fire({
                    title: 'Error al editar el dispositivo',
                    text: 'Por favor, complete los campos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            axios.put(`/equiposPUT/${equipo_id}`, {
                nombre: this.equipoSeleccionado.nombre,
                numero_licencia: this.equipoSeleccionado.numero_licencia,
                nombre_usuario: this.equipoSeleccionado.nombre_usuario
            })
                .then(() => {
                    Swal.fire({
                        title: 'Dispositivo editado',
                        text: this.formattedLicensePeriod,
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

                        Swal.fire({
                            title: 'Error al editar el dispositivo',
                            text: '',
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
            if (!this.nuevoEquipo.nombre) {
                Swal.fire({
                    title: 'Error al crear el dispositivo',
                    text: 'Por favor, nombre el dispositivo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            const equipoExistente = this.equiposUsuario.some(equipo => equipo.nombre === this.nuevoEquipo.nombre);

            if (equipoExistente) {
                Swal.fire({
                    title: 'Error al crear el dispositivo',
                    text: 'Ya tienes un dispositivo registrado con este nombre',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            let mac_encriptada = null;

            axios.post('/equiposPOST', {
                nombre: this.nuevoEquipo.nombre,
                numero_licencia: this.nuevoEquipo.numero_licencia,
                nombre_usuario: this.nuevoEquipo.nombre_usuario,
                mac: mac_encriptada
            })
                .then(response => {
                    Swal.fire({
                        title: 'Dispositivo agregado',
                        text: '',
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
                    let errorMessage = 'Ocurrió un error al procesar la solicitud.';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }

                    Swal.fire({
                        title: 'Error al crear el dispositivo',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
        },
        guardarServerKey(equipo_id) {
            console.log(this.desencriptarMac(this.equipoSeleccionado.mac))
            console.log(this.equipoSeleccionado.mac)
            if (!this.validarFormatoMAC(this.desencriptarMac(this.equipoSeleccionado.mac))) {
                Swal.fire({
                    title: 'MAC inválida',
                    text: 'El formato de la dirección MAC es incorrecto. Debe tener el formato XX:XX:XX:XX:XX:XX.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.cerrarModalGenerarLicencia();
                    }
                });
                return; // Detener la función si el formato de MAC es incorrecto
            }
            axios.post(`/equipos/agregarServerKey/${equipo_id}`, {
                server_key: this.equipoSeleccionado.server_key,
                licencia: this.licenciaGenerada,
                mac: this.equipoSeleccionado.mac,
                periodo: this.periodoSeleccionado
            })
                .then(response => {
                    Swal.fire({
                        title: 'Licencia Generada Correctamente',
                        text: this.licenciaGenerada,
                        html: `${this.licenciaGenerada} <button id="copyButton" class="btn btn-outline-secondary rounded" type="button">
                    <i class="bi bi-clipboard"></i>
                </button>`,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cerrarModalGenerarLicencia();
                            window.location.reload();
                        }
                    });
                    Swal.getHtmlContainer().querySelector('#copyButton').addEventListener('click', (event) => {
                        navigator.clipboard.writeText(this.licenciaGenerada).then(() => {
                            const button = event.target.closest('button');
                            if (button) {
                                button.innerHTML = '<i class="bi bi-check"></i>';
                                button.classList.remove('btn-outline-secondary');
                                button.classList.add('btn-outline-success');
                            }
                        }).catch(err => {
                            Swal.fire('Error', 'No se pudo copiar la licencia', 'error');
                        });
                    });
                })
                .catch(error => {

                    Swal.fire({
                        title: 'Server Key',
                        text: error.response.data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
        },
        generarLicencia() {
            if (!this.equipoSeleccionado.mac) {
                return null;  // O devuelve un mensaje de error o licencia inválida
            }
            // Obtener la fecha y la hora actuales
            let fechaHoy = new Date();
            let fecha = fechaHoy.toLocaleDateString(); // Obtiene la fecha en formato local
            let hora = fechaHoy.toLocaleTimeString(); // Obtiene la hora en formato local
            let key = 'desencriptar';
            let bytes = CryptoJS.AES.decrypt(this.equipoSeleccionado.mac, key);
            let mac_desencriptada = bytes.toString(CryptoJS.enc.Utf8);

            let key_combined = `${mac_desencriptada}-${this.equipoSeleccionado.server_key} - ${this.periodoSeleccionado} - ${fecha} -${hora}`;
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
            } catch (err) {
                console.error('Error al copiar al portapapeles:', err);
            }
        },
        desencriptarMac(mac) {
            if (!mac) return null;

            // Function to decode URL-safe base64 to standard base64
            function urlSafeBase64Decode(base64) {
                let base64Url = base64.replace(/-/g, '+').replace(/_/g, '/');
                return base64Url;
            }

            try {
                // Decode the URL-safe base64 string
                let macBase64 = urlSafeBase64Decode(mac);

                // Prepare the decryption key
                let key = CryptoJS.enc.Utf8.parse('desencriptar1234'); // Ensure key is correctly formatted

                // Decrypt the base64-encoded string
                let decrypted = CryptoJS.AES.decrypt(macBase64, key, { mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7 });

                // Convert the decrypted data to UTF-8 string
                let result = decrypted.toString(CryptoJS.enc.Utf8);

                // Check if decryption was successful
                if (!result) throw new Error("Decryption failed, resulting data is empty.");

                return result;
            } catch (error) {
                console.error("Decryption error:", error);
                return null;
            }
        },
        encriptarMac(mac) {
            let key = 'desencriptar1234';
            return CryptoJS.AES.encrypt(mac, key).toString();
        },
        validarFormatoMAC(mac) {
            // Expresión regular para validar el formato de una dirección MAC XX:XX:XX:XX:XX:XX
            const regexMAC = /^([0-9A-Fa-f]{2}[:-]?){5}([0-9A-Fa-f]{2})$/;
            return regexMAC.test(mac);
        },
    }
}
</script>

<template>
    <div>
        <NavbarComponent></NavbarComponent>

        <div class="container mx-auto mt-4">
            <div class="input-group my-4">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" v-model="busqueda" class="form-control" placeholder="Buscar nombre de dispositivo" />
            </div>
            <h1 class="text-2xl font-bold mb-4" v-for="usuario in usuariosResponsables" :key="usuario">
                Dispositivos de {{ usuario }}
            </h1>
            <button class="btn morado-btn" @click="abrirCrearModal">Crear Nuevo Dispositivo</button>

            <div v-if="equipos.length === 0" class="text-gray-500">
                No hay dispositivos disponibles.
            </div>

            <div v-else>
                <ul class="list-disc space-y-2">
                    <li v-for="equipo in filtrarEquipos()" :key="equipo.equipo_id">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3>{{ equipo.nombre }}</h3>
                                <p v-show="tipoUsuario == 'Administrador'">Licencia: {{ equipo.licencias ?
                    equipo.licencias.licencia : 'Sin Licencia' }}</p>
                                <!-- <p v-show="tipoUsuario == 'Administrador'">Mac: {{ desencriptarMac(equipo.mac) }} </p> -->
                                <!-- <p v-show="tipoUsuario == 'Administrador'">Usuario: {{ equipo.usuarios ?
                    equipo.usuarios.nombre : 'Sin Usuario' }}</p> -->
                                <!-- <p>Vigencia: {{ equipo.licencias && equipo.licencias.licencia_inicio ?
                    equipo.licencias.licencia_inicio : 'N/A' }} - {{ equipo.licencias &&
                    equipo.licencias.licencia_final ? equipo.licencias.licencia_final : 'N/A' }}</p>
                                <p>
                                    Periodo:
                                    {{ equipo.licencias && equipo.licencias.periodo ? equipo.licencias.periodo : 'N/A'
                                    }}
                                </p> -->
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
                    <!-- <label for="numero_licencia">Número de Licencia:</label>
                    <input type="text" v-model="equipoSeleccionado.numero_licencia" id="numero_licencia"
                        class="form-control rounded-pill"> -->
                    <label for="periodo">Periodo:</label>
                    <input type="text" :value="equipoSeleccionado.periodo" id="periodo"
                        class="form-control rounded-pill" disabled />
                    <label for="Vigencia">Vigencia:</label>
                    <input type="text" :value="formattedLicensePeriod" id="vigencia" class="form-control rounded-pill"
                        disabled />
                    <label for="nombre_usuario" v-show="tipoUsuario == 'Administrador'">Nombre del Usuario
                        Responsable:</label>
                    <select v-model="equipoSeleccionado.nombre_usuario" id="nombre_usuario"
                        class="form-control rounded-pill" v-show="tipoUsuario == 'Administrador'">
                        <option v-for=" usuario  in  usuariosDisponibles " :value="usuario.nombre" :key="usuario.id">
                            {{ usuario.nombre }}
                        </option>
                    </select>
                    <div class="text-center">
                        <button type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para agregar equipo -->
        <div v-if="crearModalVisible" class="modal">
            <div class="modal-content">
                <span class="close" @click="cerrarCrearModal">&times;</span>
                <h2>Agregar Nuevo Dispositivo</h2>
                <form @submit.prevent="crearEquipo">
                    <label for="nombre">Nombre del Dispositivo:</label>
                    <input type="text" v-model="nuevoEquipo.nombre" id="nombre" class="form-control rounded-pill">
                    <!-- <label for="numero_licencia">Número de Licencia:</label>
                    <input type="text" v-model="nuevoEquipo.numero_licencia" id="numero_licencia"
                        class="form-control rounded-pill"> -->
                    <label for="nombre_usuario" v-show="tipoUsuario == 'Administrador'">Nombre del Usuario
                        Responsable:</label>
                    <select v-model="nuevoEquipo.nombre_usuario" id="nombre_usuario" class="form-control rounded-pill"
                        v-show="tipoUsuario == 'Administrador'">
                        <option v-for=" usuario  in  usuariosDisponibles " :value="usuario.nombre" :key="usuario.id">
                            {{ usuario.nombre }}
                        </option>
                    </select>
                    <div class=" text-center">
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
                        <label for="mac">Device Key:</label>
                        <input type="text" v-model="macDesencriptada" id="mac" class="form-control rounded-pill">
                    </div>
                    <div class=" campo">
                        <label for="serverKey">Server Key:</label>
                        <input class="form-control rounded-pill" type="text" id="serverKey"
                            v-model="equipoSeleccionado.server_key" disabled>
                    </div>
                    <div class="campo">
                        <label for="periodo">Periodo:</label>
                        <select v-model="periodoSeleccionado" id="periodo" class="form-control rounded-pill">
                            <option value="PRUEBA">Prueba</option>
                            <option value="MENSUAL">Mensual</option>
                            <option value="TRIMESTRAL">Trimestral</option>
                            <option value="SEMESTRAL">Semestral</option>
                            <option value="ANUAL">Anual</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit">Generar licencia</button>
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