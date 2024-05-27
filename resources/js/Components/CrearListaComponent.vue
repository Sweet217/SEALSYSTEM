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
                    window.location.reload()
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
            <button type="submit" class="btn color-btn">Crear</button>
        </form>
    </div>
</template>

<style>
.registro-lista h2 {
    text-align: center;
    font-weight: bold;
    font-size: 1.7rem;
}

.registro-lista {
    margin-left: 5px;
    padding-top: 15px;
    padding-left: 10px;
}

.registro-lista h2 {
    padding-top: 15px;
    margin-bottom: 10px;
}

.registro-lista button.color-btn {
    background-color: #302f51;
    border-color: #302f51;
    color: white;
    padding-top: 10px;
    padding-bottom: 10px;
    /* Color del texto */
}

.registro-lista button.color-btn:hover {
    background-color: #4a4978;
    border-color: #4a4978;
}

.registro-lista button.color-btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(48, 47, 81, 0.5);
}

.registro-lista input {
    margin-bottom: 10px;
    margin-top: 10px;
}
</style>