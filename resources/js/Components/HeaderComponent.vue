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
                        <h1 class="text-publicidad display-4 fw-bold mb-5">Publicidad</h1>
                        <p class="text-publicidad fs-5 mb-5">SIST PUBLICIDAD</p>
                        <form class="d-flex justify-content-center flex-wrap" method="post" data-bs-theme="light">
                            <div class="shadow-lg mb-3"></div>
                            <div class="shadow-lg mb-3"></div>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="text-center position-relative"><img class="img-fluid"
                            src="" style="width: 800px;"></div>
                </div>
            </div>
        </div>
    </header>
</template>

<style>
.div-naranja {
    padding: 0px; /* Ajusta el valor de padding según tus necesidades */
    border-radius: 0px; /* Ajusta el valor de border-radius para el redondeo */
    background: radial-gradient(circle at center, #f78433, #dba075); /* Color de fondo naranja gradient */
    opacity: 0; /* Inicialmente el fondo es transparente */
    animation: gradient-reveal 2s ease-in-out forwards, gradient-animation 10s linear infinite alternate; /* Animaciones combinadas */
  }
  
  @keyframes gradient-reveal {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
  
  @keyframes gradient-animation {
    0% {
        background: radial-gradient(circle at center, #dba075, #f78433);
      }
      1% {
        background: radial-gradient(circle at center, #dba074, #f78434);
      }
      2% {
        background: radial-gradient(circle at center, #dba073, #f78435);
      }
      3% {
        background: radial-gradient(circle at center, #dba072, #f78436);
      }
      4% {
        background: radial-gradient(circle at center, #dba071, #f78437);
      }
      5% {
        background: radial-gradient(circle at center, #dba070, #f78438);
      }
      6% {
        background: radial-gradient(circle at center, #dba06f, #f78439);
      }
      7% {
        background: radial-gradient(circle at center, #dba06e, #f7843a);
      }
      8% {
        background: radial-gradient(circle at center, #dba06d, #f7843b);
      }
      9% {
        background: radial-gradient(circle at center, #dba06c, #f7843c);
      }
      10% {
        background: radial-gradient(circle at center, #dba06b, #f7843d);
      }
      11% {
        background: radial-gradient(circle at center, #dba06a, #f7843e);
      }
      12% {
        background: radial-gradient(circle at center, #dba069, #f7843f);
      }
      13% {
        background: radial-gradient(circle at center, #dba068, #f78440);
      }
      14% {
        background: radial-gradient(circle at center, #dba067, #f78441);
      }
      15% {
        background: radial-gradient(circle at center, #dba066, #f78442);
      }
      16% {
        background: radial-gradient(circle at center, #dba065, #f78443);
      }
      17% {
        background: radial-gradient(circle at center, #dba064, #f78444);
      }
      18% {
        background: radial-gradient(circle at center, #dba063, #f78445);
      }
      19% {
        background: radial-gradient(circle at center, #dba062, #f78446);
      }
      20% {
        background: radial-gradient(circle at center, #dba061, #f78447);
      }
      21% {
        background: radial-gradient(circle at center, #dba060, #f78448);
      }
      22% {
        background: radial-gradient(circle at center, #dba05f, #f78449);
      }
      23% {
        background: radial-gradient(circle at center, #dba05e, #f7844a);
      }
      24% {
        background: radial-gradient(circle at center, #dba05d, #f7844b);
      }
      25% {
        background: radial-gradient(circle at center, #dba05c, #f7844c);
      }
      26% {
        background: radial-gradient(circle at center, #dba05b, #f7844d);
      }
      27% {
        background: radial-gradient(circle at center, #dba05a, #f7844e);
      }
      28% {
        background: radial-gradient(circle at center, #dba059, #f7844f);
      }
      29% {
        background: radial-gradient(circle at center, #dba058, #f78450);
      }
      30% {
        background: radial-gradient(circle at center, #dba057, #f78451);
      }
      31% {
        background: radial-gradient(circle at center, #dba056, #f78452);
      }
      32% {
        background: radial-gradient(circle at center, #dba055, #f78453);
      }
      33% {
        background: radial-gradient(circle at center, #dba054, #f78454);
      }
      34% {
        background: radial-gradient(circle at center, #dba053, #f78455);
      }
      35% {
        background: radial-gradient(circle at center, #dba052, #f78456);
      }
      36% {
        background: radial-gradient(circle at center, #dba051, #f78457);
      }
      37% {
        background: radial-gradient(circle at center, #dba050, #f78458);
      }
      38% {
        background: radial-gradient(circle at center, #dba04f, #f78459);
      }
      39% {
        background: radial-gradient(circle at center, #dba04e, #f7845a);
      }
      40% {
        background: radial-gradient(circle at center, #dba04d, #f7845b);
      }
      41% {
        background: radial-gradient(circle at center, #dba04c, #f7845c);
      }
      42% {
        background: radial-gradient(circle at center, #dba04b, #f7845d);
      }
      43% {
        background: radial-gradient(circle at center, #dba04a, #f7845e);
      }
      44% {
        background: radial-gradient(circle at center, #dba049, #f7845f);
      }
      45% {
        background: radial-gradient(circle at center, #dba048, #f78460);
      }
      46% {
        background: radial-gradient(circle at center, #dba047, #f78461);
      }
      47% {
        background: radial-gradient(circle at center, #dba046, #f78462);
      }
      48% {
        background: radial-gradient(circle at center, #dba045, #f78463);
      }
      49% {
        background: radial-gradient(circle at center, #dba044, #f78464);
      }
      50% {
        background: radial-gradient(circle at center, #dba043, #f78465);
      }
      51% {
        background: radial-gradient(circle at center, #dba043, #f78465); /* Mismo color del 50% */
      }
      52% {
        background: radial-gradient(circle at center, #dba044, #f78464);
      }
      53% {
        background: radial-gradient(circle at center, #dba045, #f78463);
      }
      54% {
        background: radial-gradient(circle at center, #dba046, #f78462);
      }
      55% {
        background: radial-gradient(circle at center, #dba047, #f78461);
      }
      56% {
        background: radial-gradient(circle at center, #dba048, #f78460);
      }
      57% {
        background: radial-gradient(circle at center, #dba049, #f7845f);
      }
      58% {
        background: radial-gradient(circle at center, #dba04a, #f7845e);
      }
      59% {
        background: radial-gradient(circle at center, #dba04b, #f7845d);
      }
      60% {
        background: radial-gradient(circle at center, #dba04c, #f7845c);
      }
      61% {
        background: radial-gradient(circle at center, #dba04d, #f7845b);
      }
      62% {
        background: radial-gradient(circle at center, #dba04e, #f7845a);
      }
      63% {
        background: radial-gradient(circle at center, #dba04f, #f78459);
      }
      64% {
        background: radial-gradient(circle at center, #dba050, #f78458);
      }
      65% {
        background: radial-gradient(circle at center, #dba051, #f78457);
      }
      66% {
        background: radial-gradient(circle at center, #dba052, #f78456);
      }
      67% {
        background: radial-gradient(circle at center, #dba053, #f78455);
      }
      68% {
        background: radial-gradient(circle at center, #dba054, #f78454);
      }
      69% {
        background: radial-gradient(circle at center, #dba055, #f78453);
      }
      70% {
        background: radial-gradient(circle at center, #dba056, #f78452);
      }
      71% {
        background: radial-gradient(circle at center, #dba057, #f78451);
      }
      72% {
        background: radial-gradient(circle at center, #dba058, #f78450);
      }
      73% {
        background: radial-gradient(circle at center, #dba059, #f7844f);
      }
      74% {
        background: radial-gradient(circle at center, #dba05a, #f7844e);
      }
      75% {
        background: radial-gradient(circle at center, #dba05b, #f7844d);
      }
      76% {
        background: radial-gradient(circle at center, #dba05c, #f7844c);
      }
      77% {
        background: radial-gradient(circle at center, #dba05d, #f7844b);
      }
      78% {
        background: radial-gradient(circle at center, #dba05e, #f7844a);
      }
      79% {
        background: radial-gradient(circle at center, #dba05f, #f78449);
      }
      80% {
        background: radial-gradient(circle at center, #dba060, #f78448);
      }
      81% {
        background: radial-gradient(circle at center, #dba061, #f78447);
      }
      82% {
        background: radial-gradient(circle at center, #dba062, #f78446);
      }
      83% {
        background: radial-gradient(circle at center, #dba063, #f78445);
      }
      84% {
        background: radial-gradient(circle at center, #dba064, #f78444);
      }
      85% {
        background: radial-gradient(circle at center, #dba065, #f78443);
      }
      86% {
        background: radial-gradient(circle at center, #dba066, #f78442);
      }
      87% {
        background: radial-gradient(circle at center, #dba067, #f78441);
      }
      88% {
        background: radial-gradient(circle at center, #dba068, #f78440);
      }
      89% {
        background: radial-gradient(circle at center, #dba069, #f7843f);
      }
      90% {
        background: radial-gradient(circle at center, #dba06a, #f7843e);
      }
      91% {
        background: radial-gradient(circle at center, #dba06b, #f7843d);
      }
      92% {
        background: radial-gradient(circle at center, #dba06c, #f7843c);
      }
      93% {
        background: radial-gradient(circle at center, #dba06d, #f7843b);
      }
      94% {
        background: radial-gradient(circle at center, #dba06e, #f7843a);
      }
      95% {
        background: radial-gradient(circle at center, #dba06f, #f78439);
      }
      96% {
        background: radial-gradient(circle at center, #dba070, #f78438);
      }
      97% {
        background: radial-gradient(circle at center, #dba072, #f78436);
      }
      98% {
        background: radial-gradient(circle at center, #dba073, #f78435);
      }
      99% {
        background: radial-gradient(circle at center, #dba074, #f78434);
      }
      100% {
        background: radial-gradient(circle at center, #dba075, #f78433); /* Regresa al color inicial */
      }
  }
  
  .text-publicidad {
    color: #22313C;
  }
</style>

