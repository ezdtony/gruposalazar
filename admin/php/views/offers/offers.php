<?php
$getAllOffers = $colabs_model->getAllOffers();
$getAllTags = $colabs_model->getAllTags();
?>
<h1 class="h2">Ofertas</h1>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Ofertas de productos
                </h2>

                <!-- Link -->
                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#newOffer">
                    Nueva oferta
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0" id="tableColabs">
                    <thead class="thead-light">
                        <tr>
                            <th>NOMBRE DE OFERTA</th>
                            <th>PORCENTAJE DESCUENTO</th>
                            <th>EFECTIVO DESCUENTO</th>
                            <th>INICIO DE OFERTA</th>
                            <th>FIN DE OFERTA</th>
                            <th>PRODUCTOS ASOCIADOS</th>
                            <th>EDITAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>

                    <tbody id="tbodyColabs">
                        <?php foreach ($getAllOffers as $offer) : ?>
                            <tr>
                                <!-- <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-circle avatar-xs me-2">
                                        <img src="https://d33wubrfki0l68.cloudfront.net/790b7dd581a3ac4fd0410afad0fb12c6e93c9e7a/b0657/assets/images/profiles/profile-07.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                    </div>

                                    <div class="d-flex flex-column">
                                        <span class="fw-bold d-block">Lester William</span>
                                        <span class="fs-6 text-muted">24 minutes ago</span>
                                    </div>
                                </div>
                            </td> -->
                                <td><?= $offer->offer_name ?> </td>
                                <td><?= $offer->percentage ?>% </td>
                                <td>$ <?= $offer->money_discount ?></td>
                                <td><?= $offer->start_date ?></td>
                                <td><?= $offer->end_date ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm offerAddTags" data-id-offer="<?= $offer->id_offers ?>" data-bs-toggle="modal" data-bs-target="#addTags" title="Asociar productos" style="display:inline-block !important"><i class="fa-solid fa-tags"></i></button>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">
                                        <button type="button" class="btn btn-secondary editOffer" data-id-offer=" <?= $offer->id_offers ?>" data-bs-toggle="modal" data-bs-target="#editOffer"><i class="fa-solid fa-pencil"></i></button>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">
                                        <button type="button" class="btn btn-danger deleteOffer" data-id-offer=" <?= $offer->id_offers ?>"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- / .table-responsive -->
        </div>
    </div>
</div>
<script src="js/functions/offers.js"></script>
<?php
include 'modals/newOffer.php';
include 'modals/addTags.php';
include 'modals/purchaseHistory.php';
include 'modals/editOffer.php';
?>
