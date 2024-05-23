<script>
export default {
    name: 'CrearListaComponent',

    data() {
        return {
            nombre: '',
        };
    },
    methods: {
        crearLista() {
            axios.post('/listasPOST', {
                nombre: this.nombre,
            })
                .then(() => {
                    this.nombre = ''; // Limpiar el campo de nombre
                    alert('Lista creada correctamente');
                })
                .catch(error => {
                    console.error('Error al crear la lista:', error);
                });
        },
    },
};


</script>

<template>
    <div class="registro-lista">
        <h2>Crear lista</h2>
        <form @submit.prevent="crearLista">
            <div class="form-group">
                <label for="nombre">Nombre de la lista:</label>
                <input type="text" id="nombre" class="form-control" v-model="nombre" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
    </div>
</template>