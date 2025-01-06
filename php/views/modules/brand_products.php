<?php

$excluded_brands = [
    6,
    9,
    10,
    11,
    7,
    3,
];


if (isset($_GET['brand'])  && $_GET['brand'] != '') {
    $brand = (int)$_GET['brand']; // Asegúrate de convertir a entero para mayor seguridad
    if (in_array($brand, $excluded_brands)) {
        echo '<script>window.location.href = "index.php";</script>';
        exit; // Detener la ejecución del script después de redirigir
    }
} else {
    echo '<script>window.location.href = "index.php";</script>';

    exit; // Detener la ejecución del script después de redirigir
}
ob_end_flush(); // Libera el contenido del buffer (si es necesario)

$getBrand = $prods_model->getBrand($_GET['brand']);
$brand = "";
if (!empty($getBrand)) {
    $getBrand = $getBrand[0];
    $brand = $getBrand->brand;
}
?>
<section class="py-5">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="bootstrap-tabs product-tabs">
                    <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                        <h3>Productos de la marca <?= $brand ?></h3>
                        <!-- <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a href="#" class="nav-link text-uppercase fs-6 active" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all">All</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-fruits-tab" data-bs-toggle="tab" data-bs-target="#nav-fruits">Fruits & Veges</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-juices-tab" data-bs-toggle="tab" data-bs-target="#nav-juices">Juices</a>
                </div>
              </nav> -->
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">

                            <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="productsContent">

                            </div>
                            <!-- / product-grid -->

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="js/functions/loadBrandArticles.js"></script>