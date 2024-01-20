<?php include("header.php");
include("connection.php");


$query = "SELECT * FROM Company_information";

$result = mysqli_query($baglanti, $query);

if (!$result) {
    die("Sorgu hatası: " . mysqli_error($baglanti));
}



if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); 
    $company = array(
        'privacy' => $row['privacy_policy'],
    );
}

mysqli_close($baglanti);

?>

		<!-- Start Page title Area -->
		<div class="page-title-area ptb-100">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6 col-md-4">
						<div class="page-title-content">
							<h2>Gizlilik Sözleşmesi</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Page title Area -->

		<!-- End Privacy policy Area -->
		<section class="ptb-100">
			<div class="container">
				<div class="main-content-text">
					<?php echo $company['privacy'] ?>
				</div>
			</div>
		</section>
		<!-- End Privacy policy Area -->

<?php include("footer.php");?>