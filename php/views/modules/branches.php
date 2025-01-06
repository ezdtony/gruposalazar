<?php $getAllSubsidiary = $prods_model->getAllSubsidiaryAdress();   ?>
<link rel="stylesheet" href="css/maps_style.css">
<section class="py-5 bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bootstrap-tabs product-tabs">
                    <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                        <h3 class="text-dark">Nuestras Sucursales</h3>
                    </div>
                    <div class="row">
                        <?php foreach ($getAllSubsidiary as $subs): ?>
                            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 mapsFrame">
                                <div class="post-entry shadow-lg rounded bg-white p-4">
                                    <?= $subs->url_google_maps ?> <!-- Aquí se incluye el iframe de Google Maps -->
                                    <div class="post-content-entry mt-3">
                                        <h3 class="text-dark"><?= $subs->subsidiary_name ?></h3>
                                        <p class="text-muted"><?= $subs->address_subs ?></p>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href='<?= $subs->see_on_gmaps ?>' class="btn btn-primary" target="_blank">
                                            Ver en Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
