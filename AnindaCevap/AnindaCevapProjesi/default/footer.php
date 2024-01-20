<?php include("connection.php");

	$query = "SELECT * FROM Company_information";

	$result = mysqli_query($baglanti, $query);

	if (!$result) {
		die("Sorgu hatası: " . mysqli_error($baglanti));
	}

	$company = array(); // Şirket bilgilerini tutmak için boş bir dizi oluşturuyoruz

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result); 
		$company = array(
			'about' => $row['company_about'],
			'phone' => $row['company_phonenumber'],
			'email' => $row['company_email'],
			'address' => $row['company_address'],
			'privacy' => $row['privacy_policy'],
		);
	}

	mysqli_close($baglanti);

	?>
	<!-- Start Footer Area -->
			<div class="footer-area pt-100 pb-70">
				<div class="container">
					<div class="row">
						<div class="col-lg-3 col-sm-6">
							<div class="single-footer-widget style-two">
								<a href="index.php">
									<img src="assets/images/logo.png" width="150px" alt="Image">
								</a>
							</div>
						</div>

						<div class="col-lg-2 col-sm-6">
							<div class="single-footer-widget style-two ml-15">
								<h3>Şirket
								</h3>

								<ul class="import-link">
									<li>
										<a href="about.php">Hakkımızda</a>
									</li>
									<li>
										<a href="contact-us.php">İletişime Geç</a>
									</li>
									
									<li>
										<a href="all-users.php">Kullanıcılar</a>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-2 col-sm-6">
							<div class="single-footer-widget style-two">
								<h3>Keşfedin</h3>

								<ul class="import-link">
									
									
									<li>
										<a href="privacy-policy.php">Gizlilik Politikası</a>
									</li>
								
								</ul>
							</div>
						</div>

						<div class="col-lg-2 col-sm-6">
							<div class="single-footer-widget style-two">
								<h3>Bizi takip edin</h3>

								<ul class="import-link">
								
									<li>
										<a href="" >Linkedin</a>
									</li>
									
								</ul>
							</div>
						</div>

						<div class="col-lg-3 col-sm-6">
							<div class="single-footer-widget style-two">
								<h3>İletişime Geç</h3>

								<ul class="address-link">
									<li>
										<span>Telefon:</span>
										<a href="tel:<?php echo $company['phone']; ?>"><?php echo $company['phone']; ?></a>
									</li>
									<li>
										<span>Email:</span>
										<a href="mailto:<?php echo $company['email']; ?>"><?php echo $company['email']; ?></a>
									</li>
									<li>
									<span>Adres:</span>
									<?php echo $company['address']; ?>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-shape">
					<img src="assets/images/footer-shape.png" alt="Image">
				</div>
			</div>
			<!-- End Footer Area -->

			<!-- Start Go Top Area -->
			<div class="go-top">
				<i class="ri-arrow-up-s-fill"></i>
				<i class="ri-arrow-up-s-fill"></i>
			</div>
			<!-- End Go Top Area -->

			<!-- Links of JS File -->
			<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/bootstrap.bundle.min.js"></script>
			<script src="assets/js/meanmenu.min.js"></script>
			<script src="assets/js/owl.carousel.min.js"></script>
			<script src="assets/js/form-validator.min.js"></script>
			<script src="assets/js/contact-form-script.js"></script>
			<script src="assets/js/ajaxchimp.min.js"></script>
			<script src="assets/js/metismenu.js"></script>
			<script src="assets/js/editor.js"></script>
			<script src="assets/js/like-dislike.min.js"></script>
			<script src="assets/js/custom.js"></script>
		</body>
	</html>