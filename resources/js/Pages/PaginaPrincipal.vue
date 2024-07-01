<script setup>
import FooterComponent from '@/Components/FooterComponent.vue'
import NavbarComponent from '@/Components/NavbarComponent.vue'
import HeaderComponent from '@/Components/HeaderComponent.vue'
// Importación de los componentes FooterComponent, NavbarComponent y HeaderComponent
</script>

<script>
//Sección de script vacía, puede usarse para lógica adicional en el futuro
</script>

<template>
    <!DOCTYPE html>
    <html data-bs-theme="light" lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>Home - SOLYTEC</title>
        <!-- Configuración inicial del documento HTML -->
    </head>

    <body>
        <!-- Componentes principales de la página -->
        <NavbarComponent></NavbarComponent>
        <!-- Componente de la barra de navegación -->

        <HeaderComponent></HeaderComponent>
        <!-- Componente del encabezado -->

        <FooterComponent></FooterComponent>
        <!-- Componente del pie de página -->
    </body>

    </html>
</template>