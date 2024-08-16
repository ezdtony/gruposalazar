	<!-- Start Footer Section -->
	<footer class="footer-section text-white"  style="background-color:#186cb4 !important">
		<!-- <div class="container relative">
			<div class="sofa-img">
				<img src="admin/images/logo_fact.png" alt="Image" class="img-fluid" width="200px">
			</div>
		</div> -->

		<div class="row g-5 mb-5">
			<div class="col-lg-4">

				<div class="image-logo-container">
					<img src="admin/images/logo_fact.png">
				</div>
				<!-- <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Grupo Salazar<span></span></a></div> -->
				<!-- <p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p> -->

				<ul class="list-unstyled custom-social">
					<li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
					<!-- <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li> -->
					<!-- <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li> -->
					<!-- <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li> -->
				</ul>
			</div>

			<div class="col-lg-8">
				<div class="row links-wrap">
					<!-- <div class="col-6 col-sm-6 col-md-3">
	                        <ul class="list-unstyled">
	                            <li><a href="#">Tienda</a></li>
	                            <li><a href="#">Contactanos</a></li>
	                        </ul>
	                    </div> -->


					<div class="col-8 col-sm-6 col-md-3">
						<h6>Quejas y Sugerencias</h6>
						<ul class="list-unstyled">
							<li><a href="#" class="text-white">quejasysugerencias@guposalazar.com.mx</a></li>
							<!-- <li><a href="#">Our team</a></li> -->
							<!-- <li><a href="#">Leadership</a></li>
									<li><a href="#">Privacy Policy</a></li> -->
						</ul>
					</div>


				</div>
			</div>

		</div>
		<div class="row g-5 mb-5 text-white">


			<div class="col-lg-12 text-white">
				<div class="row links-wrap">
					<!-- <div class="col-6 col-sm-6 col-md-3">
	                        <ul class="list-unstyled">
	                            <li><a href="#">Tienda</a></li>
	                            <li><a href="#">Contactanos</a></li>
	                        </ul>
	                    </div> -->
					<?php $getSubsidiarys = $prods_model->getSubsidiarys(); ?>
					<?php foreach ($getSubsidiarys as $subs) : ?>
						<div class="col-6 col-sm-6 col-md-3 text-white">
							<h5><?= $subs->subsidiary_name ?></h5>
							<ul class="list-unstyled text-white">
								<li><a href="#" class="text-white"><?= $subs->address_subs ?></a></li>
								<li><a href="#" class="text-white"><?= $subs->subsidiary_phone ?></a></li>
								<li><a href="#" class="text-white"><?= $subs->subsidiary_second_phone ?></a></li>
							</ul>
						</div>
					<?php endforeach; ?>
					<!--
							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">Jobs</a></li>
									<li><a href="#">Our team</a></li>
									<li><a href="#">Leadership</a></li>
									<li><a href="#">Privacy Policy</a></li>
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">Nordic Chair</a></li>
									<li><a href="#">Kruzo Aero</a></li>
									<li><a href="#">Ergonomic Chair</a></li>
								</ul>
							</div> -->
				</div>
			</div>

		</div>

		<div class="border-top copyright">
			<div class="row pt-4">
				<div class="col-lg-6">
					<p class="mb-2 text-center text-lg-start">Copyright &copy;<script>
							document.write(new Date().getFullYear());
						</script>.
					</p>
				</div>

				<div class="col-lg-6 text-center text-lg-end">
					<!-- <ul class="list-unstyled d-inline-flex ms-auto">
								<li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul> -->
				</div>

			</div>
		</div>

		</div>
	</footer>
	<!-- End Footer Section -->




	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/tiny-slider.js"></script>
	<script src="js/functions/getLogos.js"></script>
	<script src="js/custom.js"></script>
	</body>

	</html>