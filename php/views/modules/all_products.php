<section class="py-5">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="bootstrap-tabs product-tabs">
                    <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                        <?php if (isset($_GET['search'])): ?>
                            <?php if ($_GET['search'] != ''): ?>
                                <h3>Resultados de la búsqueda para "<?= $_GET['search'] ?>"</h3>
                            <?php else: ?>
                                <h3>Todos los productos</h3>
                            <?php endif; ?>
                        <?php else: ?>
                            <h3>Todos los productos</h3>
                        <?php endif; ?>
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

<script src="js/functions/loadArticlesShop.js"></script>