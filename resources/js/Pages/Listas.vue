<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
import CrearListaComponent from '@/Components/CrearListaComponent.vue'

const props = defineProps({
  listas: Object,
});

let currentListId = null;
</script>

<script>
export default {
  data() {
    return {
      nombre: '',
    };
  },
  methods: {
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
  }
};

</script>

<template>
  <NavbarComponent></NavbarComponent>

  <div class="container mx-auto mt-4">
    <h1 class="text-2xl font-bold mb-4">Todas las Listas</h1>

    <div v-if="listas.length === 0" class="text-gray-500">
      No hay listas disponibles.
    </div>

    <div v-else>
      <ul class="list-disc space-y-2">
        <li v-for="lista in listas" :key="lista.id_lista">
          <div class="flex items-center justify-between">
            <button class="btn morado-btn"
              @click="$router.push({ name: 'list-multimedia', params: { listId: lista.id_lista } })">
              {{ lista.nombre }}
            </button>
            <div class="flex space-x-2">
              <button class="btn editar-btn" @click="handleEditList(lista.id_lista)">
                Editar
              </button>
              <button class="btn eliminar-btn" @click="eliminarLista(lista.id_lista)">
                Eliminar
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <CrearListaComponent></CrearListaComponent>

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
</style>