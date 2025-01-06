<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                    <h2 class="section-title">Categorías</h2>

                    <div class="d-flex align-items-center">
                        <div class="swiper-buttons">
                            <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
                            <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="category-carousel swiper">
                    <div class="swiper-wrapper">
                        <?php
                        $all_categories = $prods_model->getAllCategories();
                        ?>
                        <?php foreach ($all_categories as $category): ?>
                            <a href="product_categories.php?type=<?=$category->id_categories; ?>" class="nav-link category-item swiper-slide">
                                <img src="<?=$category->image_url; ?>" alt="" style="max-width: 50% !important; max-height: 50% !important; height: auto;">
                                <br>
                                <strong style="size: 20px !important"><?=$category->categories_description; ?></strong>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>