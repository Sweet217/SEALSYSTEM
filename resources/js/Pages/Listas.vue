<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  listas: Object,
  equipos: Object
});

</script>

<script>
export default {
  data() {
    return {
      nombre: '', // Nombre de la lista
      modalVisible: false, // Controla la visibilidad del modal de edición
      modalCrearVisible: false, // Controla la visibilidad del modal de creación
      listaSeleccionada: { // Datos de la lista seleccionada
        id_lista: null,
        equipo_id: '',
        nombre: '',
      },
      equipoSeleccionado: { // Datos del equipo seleccionado
        equipo_id: null,
        nombre: '',
        numero_licencia: '',
        nombre_usuario: ''
      },
      error: null, // Variable para almacenar errores
      user_id: this.user_id, // ID del usuario actual
      nombreUsuario: '', // Nombre del usuario actual
      tipoUsuario: '', // Tipo de usuario actual (Administrador o Usuario)
      nuevoNombre: '', // Nombre de la nueva lista a crear
      busqueda: '',
      currentListId: Number, // ID de la lista actual
    };
  },
  mounted() {
    // Se ejecuta cuando el componente es montado
    axios.get('/api/usuario_actual')
      .then(response => {
        this.user_id = response.data.user_id; // Asigna el ID del usuario actual
        this.nombreUsuario = response.data.nombre; // Asigna el nombre del usuario actual
        this.tipoUsuario = response.data.tipo_usuario; // Asigna el tipo de usuario actual
      })
      .catch(error => {
        console.error('Error getting the user:', error); // Muestra un error en la consola
        // Redirige a la página de inicio de sesión si el usuario no está autenticado
        if (error.response && error.response.status === 401) {
          window.location.href = '/';
        } else {
          this.error = 'Error getting the user'; // Asigna un mensaje de error
        }
      });
  },
  computed: {
    // Computed property para obtener los equipos disponibles según el tipo de usuario
    equiposDisponibles() {
      if (this.tipoUsuario == 'Administrador') {
        return this.equipos.filter(equipo => equipo.user_id === this.user_id);
      } else {
        return this.equipos.filter(equipo => equipo.user_id === this.user_id);
      }
    },
    filtrarListas() {
      const busqueda = this.busqueda.trim().toLowerCase();
      return this.listas.filter(lista =>
        (lista.equipo && lista.equipo.user_id === this.user_id || lista.user_id == this.user_id) && lista.nombre.toLowerCase().includes(busqueda)
      );
    },
  },
  methods: {
    // Método para filtrar las listas según el tipo de usuario y su ID de usuario
    // filtrarListas() {
    //   if (this.tipoUsuario == 'Administrador') {
    //     return this.listas.filter(lista => lista.equipo.user_id === this.user_id);

    //   } else {
    //     return this.listas.filter(lista => lista.equipo.user_id === this.user_id);
    //   }
    // },
    // Método para abrir el modal y seleccionar una lista
    abrirModal(lista) {
      this.listaSeleccionada = { ...lista }; // Clona la lista seleccionada
      this.modalVisible = true; // Muestra el modal
    },
    // Método para cerrar el modal y reiniciar la lista seleccionada
    cerrarModal() {
      this.modalVisible = false; // Oculta el modal
      this.listaSeleccionada = {
        id_lista: null,
        nombre: '',
      };
      this.error = null; // Reinicia el error
    },
    // Método para abrir el modal de creación de listas
    abrirModalCrear() {
      this.modalCrearVisible = true; // Muestra el modal de creación
      this.nuevoNombre = '';
      this.equipoSeleccionado = '';
    },
    // Método para cerrar el modal de creación y reiniciar el nombre de la nueva lista
    cerrarModalCrear() {
      this.modalCrearVisible = false; // Oculta el modal de creación
      this.error = null; // Reinicia el error
    },
    // Método para eliminar una lista por su ID
    eliminarLista(id_lista) {
      axios.delete(`/listasDELETE/${id_lista}`, {
        //id_lista: this.id, // Esta línea está comentada
      })
        .then(() => {
          Swal.fire({
            title: 'List deleted',
            text: '',
            icon: 'success',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.reload(); // Recarga la página
            }
          }); // Muestra una alerta de éxito
        })
        .catch(error => {
          console.error('Error deleting the list:', error); // Muestra un error en la consola
          Swal.fire({
            title: 'Error deleting the list',
            text: error.response.data.message,
            icon: 'error',
            confirmButtonText: 'Ok'
          })
        });
    },
    // Método para editar una lista por su ID
    editarLista(id_lista) {
      if (!this.listaSeleccionada.nombre || !this.listaSeleccionada.equipo_id) {
        Swal.fire({
          title: 'Error editing the list',
          text: 'Please fullfill all required fields.',
          icon: 'error',
          confirmButtonText: 'Ok'
        });
        return;
      }
      axios.put(`/listasPUT/${id_lista}`, {
        nombre: this.listaSeleccionada.nombre, // Envía el nuevo nombre de la lista
        equipo_id: this.listaSeleccionada.equipo_id,
        user_id: this.user_id, // Envía el ID del usuario que esta utilizando el sistema
      })
        .then(() => {
          Swal.fire({
            title: 'List edited',
            text: '',
            icon: 'success',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.reload()
            }
          });
        })
        .catch(error => {
          console.error('Error editing the list:', error); // Muestra un error en la consola
          Swal.fire({
            title: 'Error editing the list',
            text: error.response.data.error,
            icon: 'error',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
            }
          });
        });
    },
    // Método para crear una nueva lista
    crearLista() {
      console.log(this.nuevoNombre, this.listaSeleccionada.equipo_id);
      if (!this.nuevoNombre || !this.listaSeleccionada.equipo_id) {
        Swal.fire({
          title: 'Error creating the list',
          text: 'Please fullfill all required fields.',
          icon: 'error',
          confirmButtonText: 'Ok'
        });
        return;
      }
      axios.post('/listasPOST', {
        nombre: this.nuevoNombre, // Envía el nombre de la nueva lista
        equipo_id: this.listaSeleccionada.equipo_id, // Envía el ID del equipo seleccionado
        user_id: this.user_id, // Envía el ID del usuario que esta utilizando el sistema
      })
        .then(() => {
          Swal.fire({
            title: 'List created',
            text: '',
            icon: 'success',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
              this.cerrarModalCrear(); // Cierra el modal de creación
              window.location.reload(); // Recarga la página
            }
          });
        })
        .catch(error => {
          console.error('Error creating the list:', error); // Muestra un error en la consola
          Swal.fire({
            title: 'Error creating the list',
            text: error.response.data.error,
            icon: 'error',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
            }
          });
        });
    },
    // Método para redirigir al contenido multimedia de una lista por su ID
    redirectToListaContent(listId) {
      this.currentListId = listId; // Establece el ID de la lista actual
      window.location.href = `/listas/${listId}/multimedia`; // Redirige a la URL del contenido multimedia de la lista
    },

    seleccionarLista(id_lista) {
      axios.post(`/SelectListas/seleccionar/${id_lista}`, {
        user_id: this.user_id
      })
        .then(response => {
          // Recarga las listas para reflejar el cambio de selección
          this.listas.forEach(lista => {
            lista.seleccionado = lista.id_lista === id_lista;
          });
          Swal.fire({
            title: 'List selected',
            text: response.data.message,
            icon: 'success',
            confirmButtonText: 'Ok'
          });
        })
        .catch(error => {
          console.error('Error selecting the list', error);
          Swal.fire({
            title: 'Error selecting the list',
            text: 'We couldnt select the list, please try again.',
            icon: 'error',
            confirmButtonText: 'Ok'
          });
        });
    }
  }
}

</script>

<template>
  <NavbarComponent></NavbarComponent>

  <div class="container mx-auto mt-4">
    <div class="input-group my-4">
      <span class="input-group-text">
        <i class="bi bi-search"></i>
      </span>
      <input type="text" v-model="busqueda" class="form-control" placeholder="Buscar por nombre de lista" />
    </div>
    <h1 class="text-2xl font-bold mb-4">Lists</h1>

    <button class="btn morado-btn" @click="abrirModalCrear">Create list</button>

    <div v-if="listas.length === 0" class="text-gray-500">
      No list available yet.
    </div>

    <div v-else>
      <ul class="list-disc space-y-2">
        <li v-for="lista in filtrarListas" :key="lista.id_lista">
          <div class="flex items-center justify-between">
            <button class="btn eliminar-btn" @click="redirectToListaContent(lista.id_lista)">
              {{ lista.nombre }}
            </button>
            <div class="dispositivo-container">
              <a v-if="lista.equipo_id !== null" class="equipo-text">{{ lista.equipo.nombre }}</a>
              <a v-else class="equipo-text">Global list</a>
            </div>
            <div class="flex space-x-2 items-center">
              <button class="btn editar-btn" @click="abrirModal(lista)">Edit</button>
              <button class="btn btn-danger btn-trash bi-trash" @click="eliminarLista(lista.id_lista)">
              </button>

              <!-- Hidden Radio Button -->
              <input type="radio" id="play-btn-{{ lista.id_lista }}" :value="lista.id_lista"
                :checked="lista.seleccionado" @change="seleccionarLista(lista.id_lista)" class="hidden-radio" />

              <!-- Custom Play Button Style -->
              <label :for="'play-btn-' + lista.id_lista" :class="['play-btn', lista.seleccionado ? 'selected' : '']"
                @click="seleccionarLista(lista.id_lista)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                  <path d="M8 5v14l11-7L8 5z" />
                </svg>
              </label>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Modal de Edición de Lista -->
    <div v-if="modalVisible" class="modal">
      <div class="modal-content">
        <span class="close" @click="cerrarModal">&times;</span>
        <h2>Edit list</h2>
        <form @submit.prevent="editarLista(listaSeleccionada.id_lista)">
          <label for="nombre">Name:</label>
          <input type="text" v-model="listaSeleccionada.nombre" id="nombre" class="form-control rounded-pill">
          <label for="equipo">Device:</label>
          <select v-model="listaSeleccionada.equipo_id" id="equipoSeleccionado" class="form-control rounded-pill">
            <option value="todos">All my devices</option>
            <option v-for="equipo in equiposDisponibles" :value="equipo.equipo_id" :key="equipo.equipo_id">
              {{ equipo.nombre }}
            </option>
          </select>
          <div class="text-center">
            <button type="submit" class="btn btn-primary rounded-pill">Save</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de Creación de Lista -->
    <div v-if="modalCrearVisible" class="modal">
      <div class="modal-content">
        <span class="close" @click="cerrarModalCrear">&times;</span>
        <h2>Create list</h2>
        <form @submit.prevent="crearLista">
          <div class="mb-3">
            <label for="nuevoNombre">List name:</label>
            <input type="text" v-model="nuevoNombre" id="nuevoNombre" class="form-control rounded-pill">
          </div>
          <div class="mb-3">
            <label for="equipoSeleccionado">Dispositivo:</label>
            <select v-model="listaSeleccionada.equipo_id" id="equipoSeleccionado" class="form-control rounded-pill">
              <option value="todos">Device:</option>
              <option v-for="equipo in equiposDisponibles" :value="equipo.equipo_id" :key="equipo.equipo_id">
                {{ equipo.nombre }}
              </option>
            </select>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary rounded-pill">Add</button>
          </div>
        </form>
      </div>
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
  background-color: #00406d;
  border: 1px solid #004271;
  color: white;
  margin-bottom: 15px;
}

.morado-btn:hover {
  background-color: #1e8edb;
  border-color: #1e8edb;
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
  background-color: #34a5f8;
  border: 1px solid #1e8edb;
  color: white;
  width: 180px;
  text-align: left;
}

.eliminar-btn:hover {
  background-color: #1e8edb;
  border-color: #1e8edb;
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
  color: #34a5f8;
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
  background-color: #34a5f8;
  color: #fdfcfa;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  margin-left: 15px;
  margin-top: 10px;
}

.modal-content button:hover {
  background-color: #1e8edb;
  border-color: #1e8edb;
}

.play-btn {
  border: none;
  background: none;
  cursor: pointer;
  padding: 0;
  transition: color 0.3s;
}

.play-btn svg {
  width: 24px;
  height: 24px;
}

.play-btn.selected {
  color: green;
  /* Green if selected */
}

.play-btn:not(.selected) {
  color: gray;
  /* Gray if not selected */
}

.hidden-radio {
  display: none;
}
</style>
