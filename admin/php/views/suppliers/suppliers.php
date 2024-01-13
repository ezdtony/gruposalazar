<?php
$getSuppliers = $suppliers_model->getAllSuppliers();
$getAllBrands = $suppliers_model->getAllBrands();
$getSates = $suppliers_model->getAllStates();
?>

<h1 class="h2">Marcas y Proveedores</h1>

<div class="accordion" id="accordionSuppliersAndBrands">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSuppliers">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuppliers" aria-expanded="true" aria-controls="collapseSuppliers">
                Proveedores
            </button>
        </h2>
        <div id="collapseSuppliers" class="accordion-collapse collapse show" aria-labelledby="headingSuppliers" data-bs-parent="#accordionSuppliersAndBrands">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-xxl-12 d-flex">
                        <!-- Card -->
                        <div class="card border-0 flex-fill w-100">
                            <div class="card-header border-0 border-0 card-header-space-between">
                                <!-- Title -->
                                <h2 class="card-header-title h4 text-uppercase">
                                    Todos los proveedores
                                </h2>

                                <!-- Link -->
                                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#modalNewSupplier">
                                    Registrar proveedor
                                </a>
                            </div>
                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table align-middle table-edge" id="tableSuppliers">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                                    Compañía
                                                </a>
                                            </th>
                                            <th>
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                                    Nombre
                                                </a>
                                            </th>
                                            <th>
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                                    Correo
                                                </a>
                                            </th>
                                            <th>
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                                    Teléfono
                                                </a>
                                            </th>
                                            <th tyle="width:30%" class="text-end">
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                                    Dirección
                                                </a>
                                            </th>
                                            <th class="text-end pe-7 min-w-200px">
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="sales">
                                                    Acciones
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php if (!empty($getSuppliers)) : ?>
                                            <?php foreach ($getSuppliers as $suppliers) : ?>
                                                <tr id="trSupplier<?= $suppliers->id_suppliers ?>">
                                                    <td class="name fw-bold" id="td_supplier_<?= $suppliers->id_suppliers ?>">
                                                        <?= $suppliers->supplier ?>
                                                    </td>
                                                    <td class="name " id="td_contact_name_<?= $suppliers->id_suppliers ?>">
                                                        <?= $suppliers->contact_name ?>
                                                    </td>
                                                    <td class="name " id="td_email_contact_<?= $suppliers->id_suppliers ?>">
                                                        <?= $suppliers->email_contact ?>
                                                    </td>
                                                    <td class="name " id="td_cellphone_contact_<?= $suppliers->id_suppliers ?>">
                                                        <?= $suppliers->cellphone_contact ?>
                                                    </td>
                                                    <td class=" tdAddress" id="td_address_supplier_<?= $suppliers->id_suppliers ?>">
                                                        <?= $suppliers->address_supplier ?>
                                                    </td>
                                                    <td class=" text-center">
                                                        <div class="dropdown pull-right" style="float: right !important;">
                                                            <a href="javascript: void(0);" class="dropdown-toggle no-arrow text-secondary" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="14" width="14">
                                                                    <g>
                                                                        <circle cx="12" cy="3.25" r="3.25" style="fill: currentColor"></circle>
                                                                        <circle cx="12" cy="12" r="3.25" style="fill: currentColor"></circle>
                                                                        <circle cx="12" cy="20.75" r="3.25" style="fill: currentColor"></circle>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                <a href="javascript: void(0);" data-id-supplier="<?= $suppliers->id_suppliers ?>" class="dropdown-item editSuppliers" data-bs-toggle="modal" data-bs-target="#editSuppliers">
                                                                    Editar
                                                                </a>
                                                                <!--  <a href="javascript: void(0);" class="dropdown-item">
                                                        Editar stock en sucursales
                                                    </a> -->
                                                                <a href="javascript: void(0);" class="dropdown-item deleteSuppliers" data-id-supplier="<?= $suppliers->id_suppliers ?>" style="color:red !important;">
                                                                    Borrar
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- / .table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingBrands">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrands" aria-expanded="false" aria-controls="collapseBrands">
                Marcas
            </button>
        </h2>
        <div id="collapseBrands" class="accordion-collapse collapse" aria-labelledby="headingBrands" data-bs-parent="#accordionSuppliersAndBrands">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-xxl-12 d-flex">
                        <!-- Card -->
                        <div class="card border-0 flex-fill w-100">
                            <div class="card-header border-0 border-0 card-header-space-between">
                                <!-- Title -->
                                <h2 class="card-header-title h4 text-uppercase">
                                    Marcas
                                </h2>

                                <!-- Link -->
                                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#modalNewSupplier">
                                    Registrar marca
                                </a>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table align-middle table-edge table-nowrap mb-0 table-nowrap" id="tableSuppliers">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                                    Marca
                                                </a>
                                            </th>
                                           <!--  <th class="text-end pe-7 min-w-200px">
                                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="sales">
                                                    Acciones
                                                </a>
                                            </th> -->
                                        </tr>
                                    </thead>

                                    <tbody class="list">
                                        <?php if (!empty($getAllBrands)) : ?>
                                            <?php foreach ($getAllBrands as $brands) : ?>
                                                <tr id="trBrand<?= $brands->id_brands ?>">
                                                    <td class="name fw-bold" id="td_brand_<?= $brands->id_brands ?>">
                                                        <?= $brands->brand ?>
                                                    </td>
                                                   <!--  <td class="fw-bold text-center">
                                                        <div class="dropdown pull-right" style="float: right !important;">
                                                            <a href="javascript: void(0);" class="dropdown-toggle no-arrow text-secondary" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="14" width="14">
                                                                    <g>
                                                                        <circle cx="12" cy="3.25" r="3.25" style="fill: currentColor"></circle>
                                                                        <circle cx="12" cy="12" r="3.25" style="fill: currentColor"></circle>
                                                                        <circle cx="12" cy="20.75" r="3.25" style="fill: currentColor"></circle>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                <a href="javascript: void(0);" data-id-supplier="<?= $suppliers->id_brands ?>" class="dropdown-item editSupplier" data-bs-toggle="modal" data-bs-target="#modalEditSuppliers">
                                                                    Editar
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- / .table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include 'modals/modalNewSupplier.php';
include 'modals/editSuppliers.php';

?>

<script src="js/functions/suppliers.js"></script>