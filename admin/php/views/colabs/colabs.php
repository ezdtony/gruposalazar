<?php
$getCollaborators = $colabs_model->getAllColabs();
$getSates = $colabs_model->getAllStates();
$getSubsidiary = $colabs_model->getSubsidiary();
$getPositions = $colabs_model->getPositions();
?>
<h1 class="h2">Colaboradores</h1>

<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Colaboradores registrados
                </h2>

                <!-- Link -->
                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#newColabModal">
                    Registrar usuario
                </a>
            </div>
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0" id="tableColabs">
                    <thead class="thead-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Contraseña acceso</th>
                            <th>Activo</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>

                    <tbody id="tbodyColabs">
                        <?php foreach ($getCollaborators as $colab) : ?>
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
                                <td><?= $colab->colaborator_code ?></td>
                                <td><?= $colab->name ?> <?= $colab->lastname ?></td>
                                <td><?= $colab->business_mail ?></td>
                                <td>
                                    <div class="fw-bold"><?= $colab->password_access ?></div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input <?= $colab->status ? 'checked' : '' ?> class="form-check-input activeColab" data-id-colab="<?= $colab->id_colaborator ?>" type="checkbox" role="switch" data-id-colab="<?= $colab->id_colaborator ?> ">
                                        <label class="form-check-label" for="activeColab<?= $colab->id_colaborator ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn text-bg-primary-soft btnEditColab" data-bs-toggle="modal" data-id-colab="<?= $colab->id_colaborator ?>" data-bs-target="#editColabModal"><i class="fa-solid fa-pen-to-square"></i></i></button>
                                </td>
                                <td>
                                    <button type="button" class="btn text-bg-danger-soft deleteColab" data-id-colab="<?= $colab->id_colaborator ?>"><i class="fa-solid fa-trash"></i></button>
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
<script src="js/functions/colabs.js"></script>
<?php
include 'modals/modalNewColab.php';
include 'modals/editColab.php';
?>