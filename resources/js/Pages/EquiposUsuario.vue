<template>
    <div>
        <h1>Equipos del Usuario</h1>
        <div v-if="loading">Cargando equipos...</div>
        <div v-else>
            <ul>
                <li v-for="equipo in equiposUsuario" :key="equipo.id">
                    {{ equipo.nombre }} - {{ equipo.numero_licencia }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['userId'],
    data() {
        return {
            equiposUsuario: [],
            loading: true,
        };
    },
    mounted() {
        this.verDispositivosUsuario(this.userId);
    },
    methods: {
        verDispositivosUsuario(user_id) {
            axios.get(`/api/equipos_usuario/${user_id}`)
                .then(response => {
                    this.equiposUsuario = response.data.equipos;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error al obtener los equipos del usuario:', error);
                    this.loading = false;
                });
        }
    }
};
</script>