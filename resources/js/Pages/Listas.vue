<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
// import CrearListaComponent from '@/Components/CrearListaComponent.vue'
import axios from 'axios';

const props = defineProps({
  listas: Object,
  equipos: Object
});

</script>

<script>
export default {
  data() {
    return {
      nombre: '',
      modalVisible: false,
      modalCrearVisible: false,
      listaSeleccionada: {
        id_lista: null,
        nombre: '',
      },
      equipoSeleccionado: {
        equipo_id: null,
        nombre: '',
        numero_licencia: '',
        nombre_usuario: ''
      },
      error: null,
      user_id: this.user_id,
      nombre_usuario: '',
      tipo_usuario: '',
      nuevoNombre: '',
      currentListId: Number,
    };
  },
  mounted() {
    axios.get('/api/usuario_actual')
      .then(response => {
        this.user_id = response.data.user_id;
        this.nombreUsuario = response.data.nombre;
        this.tipoUsuario = response.data.tipo_usuario;
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
  },
  computed: {
    equiposDisponibles() {
      if (this.tipoUsuario == 'Administrador') {
        return this.equipos.filter(equipo => equipo.user_id === this.user_id);
      } else {
        return this.equipos.filter(equipo => equipo.user_id === this.user_id);
      }
    },
  },
  methods: {
    filtrarListas() {
      if (this.tipoUsuario == 'Administrador') {
        return this.listas.filter(lista => lista.equipo.user_id === this.user_id);
      } else {
        return this.listas.filter(lista => lista.equipo.user_id === this.user_id);
      }
    },
    abrirModal(lista) {
      this.listaSeleccionada = { ...lista };
      this.modalVisible = true;
    },
    cerrarModal() {
      this.modalVisible = false;
      this.listaSeleccionada = {
        id_lista: null,
        nombre: '',
      };
      this.error = null;
    },
    abrirModalCrear() {
      this.modalCrearVisible = true;
      console.log('Tipo usuario', this.tipoUsuario);
    },
    cerrarModalCrear() {
      this.modalCrearVisible = false;
      this.nuevoNombre = '';
      this.error = null;
    },
    eliminarLista(id_lista) {
      axios.delete(`/listasDELETE/${id_lista}`, {
        //id_lista: this.id,
      })
        .then(() => {
          window.location.reload()
          alert('Lista eliminada correctamente');
        })
        .catch(error => {
          console.error('Error al eliminar la lista:', error);
        });
    },
    editarLista(id_lista) {
      axios.put(`/listasPUT/${id_lista}`, {
        nombre: this.listaSeleccionada.nombre,
      })
        .then(() => {
          alert('Lista editada correctamente');
          this.cerrarModal();
          window.location.reload()
        })
        .catch(error => {
          console.error('Error al editar la lista:', error)
        });
    },
    crearLista() {
      axios.post('/listasPOST', {
        nombre: this.nuevoNombre,
        equipo_id: this.equipoSeleccionado.equipo_id
      })
        .then(() => {
          alert('Lista creada correctamente');
          this.cerrarModalCrear();
          window.location.reload();
        })
        .catch(error => {
          console.error('Error al crear la lista:', error);
        });
    },
    redirectToListaContent(listId) {
      this.currentListId = listId;
      window.location.href = `/listas/${listId}/multimedia`;
    },
  }
};

</script>

<template>
  <NavbarComponent></NavbarComponent>

  <div class="container mx-auto mt-4">
    <h1 class="text-2xl font-bold mb-4">Todas las Listas</h1>

    <button class="btn morado-btn" @click="abrirModalCrear">Crear Nueva Lista</button>


    <div v-if="listas.length === 0" class="text-gray-500">
      No hay listas disponibles.
    </div>

    <div v-else>
      <ul class="list-disc space-y-2">
        <li v-for="lista in filtrarListas()" :key="lista.id_lista">
          <div class="flex items-center justify-between">
            <button class="btn eliminar-btn" @click="redirectToListaContent(lista.id_lista)">
              {{ lista.nombre }}
            </button>
            <div class="flex space-x-2">
              <button class="btn editar-btn" @click="abrirModal(lista)">Editar</button>
              <button class="btn btn-danger btn-trash bi-trash" @click="eliminarLista(lista.id_lista)">
              </button>


              <div v-if="modalVisible" class="modal">
                <div class="modal-content">
                  <span class="close" @click="cerrarModal">&times;</span>
                  <h2>Editar Lista</h2>
                  <form @submit.prevent="editarLista(listaSeleccionada.id_lista)">
                    <label for="nombre">Nombre:</label>
                    <input type="text" v-model="listaSeleccionada.nombre" id="nombre" class="form-control rounded-pill">
                    <div class="text-center">
                      <button type="submit">Guardar Cambios</button>
                    </div>
                  </form>
                </div>
              </div>

              <div v-if="modalCrearVisible" class="modal">
                <div class="modal-content">
                  <span class="close" @click="cerrarModalCrear">&times;</span>
                  <h2>Crear Lista</h2>
                  <form @submit.prevent="crearLista">
                    <div class="mb-3">
                      <label for="nuevoNombre">Nombre de la Lista:</label>
                      <input type="text" v-model="nuevoNombre" id="nuevoNombre" class="form-control rounded-pill">
                    </div>
                    <div class="mb-3">
                      <label for="equipoSeleccionado">Equipo:</label>
                      <select v-model="equipoSeleccionado.equipo_id" id="equipoSeleccionado"
                        class="form-control rounded-pill">
                        <option v-for="equipo in equiposDisponibles" :value="equipo.equipo_id" :key="equipo.equipo_id">
                          {{ equipo.nombre }}
                        </option>
                      </select>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary rounded-pill">Crear</button>
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