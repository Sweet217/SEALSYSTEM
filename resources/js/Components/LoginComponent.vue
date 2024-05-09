<script>
export default {
  name: 'LoginComponent',
};
</script>

<template>
  <section class="vh-100" style="background-color: #dba075;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="contenedor-imagen col-md-6 col-lg-5 d-none d-md-block">
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form>

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">S<span class="letra-naranja">O</span>LYTEC</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Entra a tu cuenta</h5>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="email" id="form2Example17" class="form-control form-control-lg" />
                      <label class="form-label" for="form2Example17">Direccion de correo electronico</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" id="form2Example27" class="form-control form-control-lg" />
                      <label class="form-label" for="form2Example27">Contrasena</label>
                    </div>

                    <div class="pt-1 mb-4">
                      <button data-mdb-button-init data-mdb-ripple-init
                        class=" color-boton login-boton btn btn-lg btn-block" type="button">Logeate</button>
                    </div>

                    <a class="small text-muted" href="#!">Olvidate tu contrasena?</a>
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">No tienes una cuenta? <a href="#!"
                        style="color: #393f81;">Registrate aqui!</a></p>
                    <a href="#!" class="small text-muted">Terminos de uso.</a>
                    <a href="#!" class="small text-muted">Politica de privacidad</a>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style>
.contenedor-imagen {
  width: 400px;
  /* Ajusta el ancho según tus necesidades */
  height: 600px;
  /* Ajusta la altura según tus necesidades */
  background-image: url('@/images/kioskogobierno.jpg');
  /* Ruta de la imagen */
  background-size: cover;
  background-repeat: no-repeat;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
}

.letra-naranja {
  color: #f78433;
  /* Cambia "orange" al color deseado */
}

.color-boton {
  background-color: #c4bccc;
  color: #302f51;
  transition: transform 0.3s ease-in-out;
}

.login-boton:hover {
  background-color: #302f51;
  color: #fdfcfa;
  transform: scale(1.1);
  transition: transform 0.3s ease-in-out;
}
</style>
