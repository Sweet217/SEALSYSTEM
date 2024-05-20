<script>
export default {

    name: 'DynamicMultimediaComponent',

    props: {
        listas: Object,
        listId: Number,
    },
    computed: {
        filteredMultimedia() {
            // Filter multimedia based on list ID
            return this.listas.multimedia?.filter(
                (item) => item.id_lista === this.listId
            );
        },
        getMediaUrl() {
            // Implement logic to construct the media URL based on multimedia ID (consider storage location)
            // This example assumes a placeholder implementation
            return (multimediaId) => `media/${multimediaId}.mp4`; // Replace with actual logic
        },
    },
    methods: {
        getYoutubeUrl(url) {
            // Extract the YouTube video ID from the URL
            const youtubeRegexp = /(?:https?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([^\s&]+)/;
            const match = youtubeRegexp.exec(url);
            return match ? `https://www.youtube.com/embed/${match[1]}` : url;
        },
    },
};
</script>

<template>
    <div>
        <h1>{{ listaData.nombre }}</h1>
        <ul>
            <li v-for="item in filteredMultimedia" :key="item.multimedia_id">
                <template v-if="item.tipo === 'imagen'">
                    <img :src="getMediaUrl(item.multimedia_id)" :alt="item.descripcion" />
                </template>
                <template v-else-if="item.tipo === 'video'">
                    <video :src="getMediaUrl(item.multimedia_id)" controls></video>
                </template>
                <template v-else-if="item.tipo === 'enlace_youtube'">
                    <iframe :src="getYoutubeUrl(item.url)" frameborder="0" allowfullscreen></iframe>
                </template>
            </li>
        </ul>
    </div>
</template>
