<script>
export default {
  name: 'HeaderComponent',
};
</script>

<template>
  <header class="div-naranja pt-5" style="border-color: #f78433;">
    <div class="container pt-4 pt-xl-5">
      <div class="row pt-5">
        <div class="col-md-8 text-center text-md-start mx-auto">
          <div class="text-center">
            <h1 class="text-publicidad display-4 fw-bold mb-5">SEALS!!</h1>
            <p class="text-publicidad fs-5 mb-5">SEAL SYSTEM</p>
            <!--Titulo principal que se muestra junto con el background animado.-->
            <form class="d-flex justify-content-center flex-wrap" method="post" data-bs-theme="light">
              <div class="shadow-lg mb-3"></div>
              <div class="shadow-lg mb-3"></div>
            </form>
          </div>
        </div>
        <div class="col-12 col-lg-10 mx-auto">
          <div class="text-center position-relative"><img class="img-fluid" src="" style="width: 800px;"></div>
        </div>
      </div>
    </div>
  </header>
</template>
<!--Este header se utiliza solamente en la pagina principal pero es posible se termine usando en distintas paginas por eso es un componente-->
<style>
.div-naranja {
  padding: 0px;
  /* Ajusta el valor de padding según tus necesidades */
  border-radius: 0px;
  /* Ajusta el valor de border-radius para el redondeo */
  background: radial-gradient(circle at center, #f78433, #dba075);
  /* Color de fondo naranja gradient */
  opacity: 0;
  /* Inicialmente el fondo es transparente */
  animation: gradient-reveal 2s ease-in-out forwards, gradient-animation 10s linear infinite alternate;
  /* Animaciones combinadas */
}

@keyframes gradient-reveal {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

/* Animacion del background de distintos tipos de naranja*/
@keyframes gradient-animation {
  0% {
    background: radial-gradient(circle at center, #75d1db, #33a4f7);
  }

  1% {
    background: radial-gradient(circle at center, #74d1da, #34a5f8);
  }

  2% {
    background: radial-gradient(circle at center, #73d1d9, #35a6f9);
  }

  3% {
    background: radial-gradient(circle at center, #72d1d8, #36a7fa);
  }

  4% {
    background: radial-gradient(circle at center, #71d1d7, #37a8fb);
  }

  5% {
    background: radial-gradient(circle at center, #70d1d6, #38a9fc);
  }

  6% {
    background: radial-gradient(circle at center, #6fd1d5, #39aafd);
  }

  7% {
    background: radial-gradient(circle at center, #6ed1d4, #3aabfe);
  }

  8% {
    background: radial-gradient(circle at center, #6dd1d3, #3bacff);
  }

  9% {
    background: radial-gradient(circle at center, #6cd1d2, #3cadff);
  }

  10% {
    background: radial-gradient(circle at center, #6bd1d1, #3daeff);
  }

  11% {
    background: radial-gradient(circle at center, #6ad1d0, #3eafff);
  }

  12% {
    background: radial-gradient(circle at center, #69d1cf, #3fb0ff);
  }

  13% {
    background: radial-gradient(circle at center, #68d1ce, #40b1ff);
  }

  14% {
    background: radial-gradient(circle at center, #67d1cd, #41b2ff);
  }

  15% {
    background: radial-gradient(circle at center, #66d1cc, #42b3ff);
  }

  16% {
    background: radial-gradient(circle at center, #65d1cb, #43b4ff);
  }

  17% {
    background: radial-gradient(circle at center, #64d1ca, #44b5ff);
  }

  18% {
    background: radial-gradient(circle at center, #63d1c9, #45b6ff);
  }

  19% {
    background: radial-gradient(circle at center, #62d1c8, #46b7ff);
  }

  20% {
    background: radial-gradient(circle at center, #61d1c7, #47b8ff);
  }

  21% {
    background: radial-gradient(circle at center, #60d1c6, #48b9ff);
  }

  22% {
    background: radial-gradient(circle at center, #5fd1c5, #49baff);
  }

  23% {
    background: radial-gradient(circle at center, #5ed1c4, #4abbff);
  }

  24% {
    background: radial-gradient(circle at center, #5dd1c3, #4bbcff);
  }

  25% {
    background: radial-gradient(circle at center, #5cd1c2, #4cbcff);
  }

  26% {
    background: radial-gradient(circle at center, #5bd1c1, #4dbdff);
  }

  27% {
    background: radial-gradient(circle at center, #5ad1c0, #4ebeff);
  }

  28% {
    background: radial-gradient(circle at center, #59d1bf, #4fbfff);
  }

  29% {
    background: radial-gradient(circle at center, #58d1be, #50c0ff);
  }

  30% {
    background: radial-gradient(circle at center, #57d1bd, #51c1ff);
  }

  31% {
    background: radial-gradient(circle at center, #56d1bc, #52c2ff);
  }

  32% {
    background: radial-gradient(circle at center, #55d1bb, #53c3ff);
  }

  33% {
    background: radial-gradient(circle at center, #54d1ba, #54c4ff);
  }

  34% {
    background: radial-gradient(circle at center, #53d1b9, #55c5ff);
  }

  35% {
    background: radial-gradient(circle at center, #52d1b8, #56c6ff);
  }

  36% {
    background: radial-gradient(circle at center, #51d1b7, #57c7ff);
  }

  37% {
    background: radial-gradient(circle at center, #50d1b6, #58c8ff);
  }

  38% {
    background: radial-gradient(circle at center, #4fd1b5, #59c9ff);
  }

  39% {
    background: radial-gradient(circle at center, #4ed1b4, #5acaff);
  }

  40% {
    background: radial-gradient(circle at center, #4dd1b3, #5bcbff);
  }

  41% {
    background: radial-gradient(circle at center, #4cd1b2, #5cccff);
  }

  42% {
    background: radial-gradient(circle at center, #4bd1b1, #5dcdff);
  }

  43% {
    background: radial-gradient(circle at center, #4ad1b0, #5ecfff);
  }

  44% {
    background: radial-gradient(circle at center, #49d1af, #5fd0ff);
  }

  45% {
    background: radial-gradient(circle at center, #48d1ae, #60d1ff);
  }

  46% {
    background: radial-gradient(circle at center, #47d1ad, #61d2ff);
  }

  47% {
    background: radial-gradient(circle at center, #46d1ac, #62d3ff);
  }

  48% {
    background: radial-gradient(circle at center, #45d1ab, #63d4ff);
  }

  49% {
    background: radial-gradient(circle at center, #44d1aa, #64d5ff);
  }

  50% {
    background: radial-gradient(circle at center, #43d1a9, #65d6ff);
  }

  51% {
    background: radial-gradient(circle at center, #43d1a9, #65d6ff);
  }

  52% {
    background: radial-gradient(circle at center, #44d1aa, #64d5ff);
  }

  53% {
    background: radial-gradient(circle at center, #45d1ab, #63d4ff);
  }

  54% {
    background: radial-gradient(circle at center, #46d1ac, #62d3ff);
  }

  55% {
    background: radial-gradient(circle at center, #47d1ad, #61d2ff);
  }

  56% {
    background: radial-gradient(circle at center, #48d1ae, #60d1ff);
  }

  57% {
    background: radial-gradient(circle at center, #49d1af, #5fd0ff);
  }

  58% {
    background: radial-gradient(circle at center, #4ad1b0, #5ecfff);
  }

  59% {
    background: radial-gradient(circle at center, #4bd1b1, #5dcdff);
  }

  60% {
    background: radial-gradient(circle at center, #4cd1b2, #5cccff);
  }

  61% {
    background: radial-gradient(circle at center, #4dd1b3, #5bcbff);
  }

  62% {
    background: radial-gradient(circle at center, #4ed1b4, #5acaff);
  }

  63% {
    background: radial-gradient(circle at center, #4fd1b5, #59c9ff);
  }

  64% {
    background: radial-gradient(circle at center, #50d1b6, #58c8ff);
  }

  65% {
    background: radial-gradient(circle at center, #51d1b7, #57c7ff);
  }

  66% {
    background: radial-gradient(circle at center, #52d1b8, #56c6ff);
  }

  67% {
    background: radial-gradient(circle at center, #53d1b9, #55c5ff);
  }

  68% {
    background: radial-gradient(circle at center, #54d1ba, #54c4ff);
  }

  69% {
    background: radial-gradient(circle at center, #55d1bb, #53c3ff);
  }

  70% {
    background: radial-gradient(circle at center, #56d1bc, #52c2ff);
  }

  71% {
    background: radial-gradient(circle at center, #57d1bd, #51c1ff);
  }

  72% {
    background: radial-gradient(circle at center, #58d1be, #50c0ff);
  }

  73% {
    background: radial-gradient(circle at center, #59d1bf, #4fbfff);
  }

  74% {
    background: radial-gradient(circle at center, #5ad1c0, #4ebeff);
  }

  75% {
    background: radial-gradient(circle at center, #5bd1c1, #4dbdff);
  }

  76% {
    background: radial-gradient(circle at center, #5cd1c2, #4cbcff);
  }

  77% {
    background: radial-gradient(circle at center, #5dd1c3, #4bbcff);
  }

  78% {
    background: radial-gradient(circle at center, #5ed1c4, #4abbff);
  }

  79% {
    background: radial-gradient(circle at center, #5fd1c5, #49baff);
  }

  80% {
    background: radial-gradient(circle at center, #60d1c6, #48b9ff);
  }

  81% {
    background: radial-gradient(circle at center, #61d1c7, #47b8ff);
  }

  82% {
    background: radial-gradient(circle at center, #62d1c8, #46b7ff);
  }

  83% {
    background: radial-gradient(circle at center, #63d1c9, #45b6ff);
  }

  84% {
    background: radial-gradient(circle at center, #64d1ca, #44b5ff);
  }

  85% {
    background: radial-gradient(circle at center, #65d1cb, #43b4ff);
  }

  86% {
    background: radial-gradient(circle at center, #66d1cc, #42b3ff);
  }

  87% {
    background: radial-gradient(circle at center, #67d1cd, #41b2ff);
  }

  88% {
    background: radial-gradient(circle at center, #68d1ce, #40b1ff);
  }

  89% {
    background: radial-gradient(circle at center, #69d1cf, #3fb0ff);
  }

  90% {
    background: radial-gradient(circle at center, #6ad1d0, #3eafff);
  }

  91% {
    background: radial-gradient(circle at center, #6bd1d1, #3daeff);
  }

  92% {
    background: radial-gradient(circle at center, #6cd1d2, #3cadff);
  }

  93% {
    background: radial-gradient(circle at center, #6dd1d3, #3bacff);
  }

  94% {
    background: radial-gradient(circle at center, #6ed1d4, #3aabfe);
  }

  95% {
    background: radial-gradient(circle at center, #6fd1d5, #39aafd);
  }

  96% {
    background: radial-gradient(circle at center, #70d1d6, #38a9fc);
  }

  97% {
    background: radial-gradient(circle at center, #72d1d8, #36a7fa);
  }

  98% {
    background: radial-gradient(circle at center, #73d1d9, #35a6f9);
  }

  99% {
    background: radial-gradient(circle at center, #74d1da, #34a5f8);
  }

  100% {
    background: radial-gradient(circle at center, #75d1db, #33a4f7);
  }
}

.text-publicidad {
  color: #22313C;
  /* Color del texto de publicidad */
}

</style>