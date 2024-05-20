import { createRouter, createWebHistory } from 'vue-router';
import DynamicMultimediaComponent from '@/Components/DynamicMultimediaComponent.vue';

const routes = [
    {
        path: '/listas/:listId',
        name: 'list-multimedia',
        component: DynamicMultimediaComponent,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;