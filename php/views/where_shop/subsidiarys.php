<style>
    .mapsFrame {
        margin-bottom: 30px !important;
        border: 5px solid #d3e9ff !important;
        /* Aplica un margen de 20px en todos los lados del iframe */
    }
</style>

<div class="blog-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="section-title">Nuestras Sucursales</h2>
                <h4>Puedes acudir a cualquiera de nuestras sucursales para encontrar los productos que mas necesitas, al mejor precio y con el mejor servicio</h4>
            </div>
        </div>

        <div class="row">
            <?php foreach ($getAllSubsidiary as $subs): ?>
                <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 mapsFrame">
                    <div class="post-entry">
                        <?= $subs->url_google_maps ?>
                        <div class="post-content-entry">
                            <h3><a href="#"><?=$subs->subsidiary_name?></a></h3>
                            <div class="meta">
                                <span><?=$subs->address_subs?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>