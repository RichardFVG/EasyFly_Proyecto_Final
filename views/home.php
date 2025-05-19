<?php include __DIR__.'/partials/header.php'; ?>

<div class="jumbotron text-center">
    <h1 class="display-5">Bienvenido a EasyFly</h1>
    <p class="lead">Reserva vuelos directos de forma sencilla.</p>

    <a class="btn btn-success" href="default.php?controller=flight&action=list">
        Buscar Vuelos
    </a>

    <!-- Banderas de destinos disponibles ----------------------------------->
    <div class="destinations">
        <img src="https://i.postimg.cc/Y0Sf40SS/Argentina.png"      alt="Argentina"      class="dest-img">
        <img src="https://i.postimg.cc/zfjgnN6H/Brasil.png"         alt="Brasil"         class="dest-img">
        <img src="https://i.postimg.cc/rmwtjb2n/Francia.png"        alt="Francia"        class="dest-img">
        <img src="https://i.postimg.cc/PxVvR4tt/Alemania.png"       alt="Alemania"       class="dest-img">
        <img src="https://i.postimg.cc/3NP4Kx4V/Italia.png"         alt="Italia"         class="dest-img">
        <img src="https://i.postimg.cc/rybtGQnj/Japon.png"          alt="Japón"          class="dest-img">
        <img src="https://i.postimg.cc/9F7wLZsh/Mexico.png"         alt="México"         class="dest-img">
        <img src="https://i.postimg.cc/W1mFvj2Y/Espa-a.png"         alt="España"         class="dest-img">
        <img src="https://i.postimg.cc/FKDYYZkj/Reino-Unido.png"    alt="Reino Unido"    class="dest-img">
        <img src="https://i.postimg.cc/sXxv7h5b/Estados-Unidos.png" alt="Estados Unidos" class="dest-img">
    </div>
</div>

<?php include __DIR__.'/partials/footer.php'; ?>
