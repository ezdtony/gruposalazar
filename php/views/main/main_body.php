<div class="hero">
	<!-- Offers Section -->
	<div class="container responsiveBanner">
		<div class="row justify-content-between">
			<div class="col-lg-5">
				<div class="intro-excerpt">
					<h1>Colores que Inspiran <br><span clsas="d-block"></span></h1>
					<h2 class="text-white">Compra desde la comodidad de tu casa</h2>
					<p><a href="" class="btn btn-secondary me-2">Comprar ya!!</a></p>
				</div>
			</div>
			<div class="col-lg-7">
				<br>
				<br>
				<br>
				<div class="hero-img-wrap">
					<img src="images/banner.png" class="img-fluid responsive-image">
				</div>
			</div>
		</div>
	</div>
</div>

<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">

	<!-- Contenido del Carrusel -->
	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img class="d-block w-100" src="images/banner/QueBonitaMiCasa.jpg" alt="First slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="images/banner/slide_2_sayer.jpg" alt="Second slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="images/banner/slide_1_sayer.jpg" alt="Third slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="images/banner/slide_3_sayer.jpg" alt="Third slide">
			</div>

		</div>
		<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>

</div>


<!-- Start Product Section -->
<div class="product-section">
	<div class="container">
		<div class="row">

			<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
				<h2 class="mb-4 section-title">Pinturas de la más alta calidad.</h2>
				<p class="mb-4">En nuestro catálogo encontrarás los productos de la mas alta calidad.</p>
				<p><a href="shop.php" class="btn">Explorar</a></p>
			</div>
			<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
				<a style="background-color:#186788 !important" href="https://www.gruposayer.com/v2/simulador.php" target="_blank" class="product-item cardBanner" href="cart.html">
					<img src="images/vendor/banner_simuladordecolor.jpg" class="img-fluid product-thumbnail">
					<h2 class="text-white">Simulador de Color Sayer</h2>
					<h6 class="text-white">Personaliza tus espacios con nuestra herramienta online, que te ayuda
						a seleccionar la combinación de colores ideal para ti.</h6>
				</a>
			</div>
			<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
				<a style="background-color:#86b301 !important" href="https://www.gruposayer.com/v2/contenidos/donde_comprar_sayer.php" target="_blank" class="product-item cardBanner" href="cart.html">
					<img src="images/vendor/banner_sucursales.jpg" class="img-fluid product-thumbnail">
					<h2 class="text-white">¿Donde Comprar?</h2>
					<h6 class="text-white">Encuentra nuestra sucursal mas cercana.</h6>
				</a>
			</div>
			<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
				<a style="background-color:#b20000 !important" href="shop.php?parms=filtered&filter=METALITE&mod=categories" target="_blank" class="product-item cardBanner" href="cart.html">
					<img src="images/vendor/banner_manosalaobra.jpg" class="img-fluid product-thumbnail">
					<h2 class="text-white">Línea Industrial</h2>
					<h6 class="text-white">Conoce nuestra amplia gama de soluciones para el sector industrial y comercial.</h6>
				</a>
			</div>
			<?php $getThirdProds = $prods_model->getThirdArticles(); ?>
			<?php foreach ($getThirdProds as $prod) : ?>
				<!-- <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
					<a class="product-item">
						<img src="<?= str_replace('../', 'admin/', $prod->image) ?>" class="img-fluid product-thumbnail">
						<h3 class="product-title"><?= $prod->product_name ?></h3>
						<strong class="product-price">$ <?= round($prod->price, 2) ?></strong>

						<span class="icon-cross addCartProd" data-id-product="<?= $prod->id_prducts ?>" data-product-price="<?= round($prod->price, 2) ?>">
							<img src="images/cross.svg" class="img-fluid">
						</span>
					</a>
				</div> -->
			<?php endforeach; ?>

		</div>
	</div>
</div>

<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
	<div id="carouselExampleControls1" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img class="d-block w-100" src="images/banner/banner_gp_slzr.jpg" alt="First slide">
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="images/banner/banner_sayerlamejorpintura.jpg" alt="Second slide">
			</div>

		</div>
		<a class="carousel-control-prev" href="#carouselExampleControls1" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleControls1" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>
<!-- Contenido del Carrusel -->

<!-- End Product Section -->
<!-- <div class="why-choose-section">
	<div class="container">
		<div class="row justify-content-between">
			<div class="col-lg-6">
				<h2 class="section-title">¿Por qué elegirnos?</h2>
				

				<div class="row my-5">
					<div class="col-6 col-md-6">
						<div class="feature">
							<div class="icon">
								<img src="images/truck.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Pickup en Sucursal</h3>
							<p>Has tu pedido en línea y recoge en sucursal.</p>
						</div>
					</div>

					<div class="col-6 col-md-6">
						<div class="feature">
							<div class="icon">
								<img src="images/bag.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Formas de pago</h3>
							<p>Puedes pagar con diferentes métodos de pago, o hacer tu pedido y pagar en sucursal al momento de recoger el pedido.</p>
						</div>
					</div>

					

				</div>
			</div>

			<div class="col-lg-5">
				<div class="img-wrap">
					<img src="images/casher.jpg" alt="Image" class="img-fluid">
				</div>
			</div>

		</div>
	</div>
</div> -->
<!-- Start Why Choose Us Section -->
<!-- <div class="why-choose-section">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-6">
						<h2 class="section-title">Why Choose Us</h2>
						<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>

						<div class="row my-5">
							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/truck.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>Fast &amp; Free Shipping</h3>
									<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/bag.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>Easy to Shop</h3>
									<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/support.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>24/7 Support</h3>
									<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/return.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>Hassle Free Returns</h3>
									<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
								</div>
							</div>

						</div>
					</div>

					<div class="col-lg-5">
						<div class="img-wrap">
							<img src="images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
						</div>
					</div>

				</div>
			</div>
		</div> -->
<!-- End Why Choose Us Section -->

<div class="we-help-section">
	<div class="container">
		<div class="row justify-content-between">
			<div class="col-lg-7 mb-5 mb-lg-0">
				<div class="imgs-grid">
					<div class="grid grid-1"><img src="images/vendor/195919613_3835923163123869_744797643594856586_n.jpg" alt=""></div>
					<div class="grid grid-2"><img src="images/vendor/VS82004L.jpg" alt=""></div>
					<div class="grid grid-3"><img src="images/vendor/banner_tutoriales.jpg" alt=""></div>
				</div>
			</div>
			<div class="col-lg-5 ps-lg-5">
				<h2 class="section-title mb-4">Impermeabiliza fácil con Impersayer</h2>
				<div class="video-container">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/kQDPhZ9t9WM?si=XDHdj6P7CYL5f724" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
				</div>
				<br>
				<p>Te decimos paso a paso como impermeabilizar tu hogar con Imper sayer Fácil el cual es 100% impermeable, de alta reflectividad, de excelente adherencia sobre cualquier sustrato. Gracias al refuerzo de poliuretano tiene excelente resistencia al envejecimiento.</p>

				<br>
				<hr>
				<h2 class="section-title mb-4">Pintura para pizarrón de gis</h2>
				<div class="video-container">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/wVRw6QhzAYo?si=Of27M-dsRn4ckcp_" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
				</div>


				<!-- <ul class="list-unstyled custom-list my-4">
					<li>Donec vitae odio quis nisl dapibus malesuada</li>
					<li>Donec vitae odio quis nisl dapibus malesuada</li>
					<li>Donec vitae odio quis nisl dapibus malesuada</li>
					<li>Donec vitae odio quis nisl dapibus malesuada</li>
				</ul>
				<p><a herf="#" class="btn">Explore</a></p> -->
			</div>
		</div>
	</div>
</div>

<!-- Start Popular Product -->
<!-- <div class="popular-product">
			<div class="container">
				<div class="row">

					<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
						<div class="product-item-sm d-flex">
							<div class="thumbnail">
								<img src="images/product-1.png" alt="Image" class="img-fluid">
							</div>
							<div class="pt-3">
								<h3>Nordic Chair</h3>
								<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
								<p><a href="#">Read More</a></p>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
						<div class="product-item-sm d-flex">
							<div class="thumbnail">
								<img src="images/product-2.png" alt="Image" class="img-fluid">
							</div>
							<div class="pt-3">
								<h3>Kruzo Aero Chair</h3>
								<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
								<p><a href="#">Read More</a></p>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
						<div class="product-item-sm d-flex">
							<div class="thumbnail">
								<img src="images/product-3.png" alt="Image" class="img-fluid">
							</div>
							<div class="pt-3">
								<h3>Ergonomic Chair</h3>
								<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
								<p><a href="#">Read More</a></p>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div> -->
<!-- End Popular Product -->

<!-- Start Testimonial Slider -->
<!-- <div class="testimonial-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-7 mx-auto text-center">
						<h2 class="section-title">Testimonials</h2>
					</div>
				</div>

				<div class="row justify-content-center">
					<div class="col-lg-12">
						<div class="testimonial-slider-wrap text-center">

							<div id="testimonial-nav">
								<span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
								<span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
							</div>

							<div class="testimonial-slider">

								<div class="item">
									<div class="row justify-content-center">
										<div class="col-lg-8 mx-auto">

											<div class="testimonial-block text-center">
												<blockquote class="mb-5">
													<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
												</blockquote>

												<div class="author-info">
													<div class="author-pic">
														<img src="images/person-1.png" alt="Maria Jones" class="img-fluid">
													</div>
													<h3 class="font-weight-bold">Maria Jones</h3>
													<span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
												</div>
											</div>

										</div>
									</div>
								</div>

								<div class="item">
									<div class="row justify-content-center">
										<div class="col-lg-8 mx-auto">

											<div class="testimonial-block text-center">
												<blockquote class="mb-5">
													<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
												</blockquote>

												<div class="author-info">
													<div class="author-pic">
														<img src="images/person-1.png" alt="Maria Jones" class="img-fluid">
													</div>
													<h3 class="font-weight-bold">Maria Jones</h3>
													<span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
												</div>
											</div>

										</div>
									</div>
								</div>

								<div class="item">
									<div class="row justify-content-center">
										<div class="col-lg-8 mx-auto">

											<div class="testimonial-block text-center">
												<blockquote class="mb-5">
													<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
												</blockquote>

												<div class="author-info">
													<div class="author-pic">
														<img src="images/person-1.png" alt="Maria Jones" class="img-fluid">
													</div>
													<h3 class="font-weight-bold">Maria Jones</h3>
													<span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
												</div>
											</div>

										</div>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div> -->
<!-- End Testimonial Slider -->

<!-- Start Blog Section -->
<!-- 	<div class="blog-section">
			<div class="container">
				<div class="row mb-5">
					<div class="col-md-6">
						<h2 class="section-title">Recent Blog</h2>
					</div>
					<div class="col-md-6 text-start text-md-end">
						<a href="#" class="more">View All Posts</a>
					</div>
				</div>

				<div class="row">

					<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
						<div class="post-entry">
							<a href="#" class="post-thumbnail"><img src="images/post-1.jpg" alt="Image" class="img-fluid"></a>
							<div class="post-content-entry">
								<h3><a href="#">First Time Home Owner Ideas</a></h3>
								<div class="meta">
									<span>by <a href="#">Kristin Watson</a></span> <span>on <a href="#">Dec 19, 2021</a></span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
						<div class="post-entry">
							<a href="#" class="post-thumbnail"><img src="images/post-2.jpg" alt="Image" class="img-fluid"></a>
							<div class="post-content-entry">
								<h3><a href="#">How To Keep Your Furniture Clean</a></h3>
								<div class="meta">
									<span>by <a href="#">Robert Fox</a></span> <span>on <a href="#">Dec 15, 2021</a></span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
						<div class="post-entry">
							<a href="#" class="post-thumbnail"><img src="images/post-3.jpg" alt="Image" class="img-fluid"></a>
							<div class="post-content-entry">
								<h3><a href="#">Small Space Furniture Apartment Ideas</a></h3>
								<div class="meta">
									<span>by <a href="#">Kristin Watson</a></span> <span>on <a href="#">Dec 12, 2021</a></span>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div> -->
<!-- End Blog Section -->