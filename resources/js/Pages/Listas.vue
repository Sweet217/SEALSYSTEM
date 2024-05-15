<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'

const props = defineProps({
    listas: Object,
});

</script>

<script>

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
          <li v-for="lista in listas" :key="lista.id">
            <div class="flex items-center justify-between">
              <button class="btn btn-primary" @click="handleListClick(lista.id)">
                {{ lista.nombre }}
              </button>
              <div class="flex space-x-2">
                <button class="btn btn-sm btn-light" @click="handleEditList(lista.id)">
                  Editar
                </button>
                <button class="btn btn-sm btn-danger" @click="handleDeleteList(lista.id)">
                  Eliminar
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  
    <FooterComponent></FooterComponent>
  </template>