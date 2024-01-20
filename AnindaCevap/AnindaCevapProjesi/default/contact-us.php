<?php include("header.php");
include("connection.php");

$query = "SELECT * FROM Company_information";

$result = mysqli_query($baglanti, $query);

if (!$result) {
    die("Sorgu hatası: " . mysqli_error($baglanti));
}

$company = array(); 

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); 
    $company = array(
        'phone' => $row['company_phonenumber'],
        'email' => $row['company_email'],
        'address' => $row['company_address'],
    );
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $title = $_POST['title'];
    $message = $_POST['message'];
    
   
    $currentDate = date("Y-m-d H:i:s");
    
   
    $stmt = $baglanti->prepare("INSERT INTO Contact (contact_nick,contact_email, contact_title, contact_message, contact_date) VALUES (? , ? , ?, ?, ?)");
    $stmt->bind_param("sssss", $name , $email, $title, $message, $currentDate);
    $stmt->execute(); 

    if ($stmt->affected_rows > 0) {
		echo '<div class="alert alert-success" role="alert">
		Mesaj gönderildi.
		</div>';
    } else {
        echo "Mesaj gönderilirken bir hata oluştu.";
    }
}


mysqli_close($baglanti);
?> 


		<!-- Start Contact Area -->
		<section class="contact-area ptb-100">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="contact-form">
							<h2>İletişime geçiniz</h2>

							<form method="POST">
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label>İsim</label>
											<input type="text" name="name" class="form-control" required="" data-error="Lütfen adınızı girin" placeholder="Anında Cevap">
											<div class="help-block with-errors"></div>
										</div>
									</div>
		
									<div class="col-lg-12">
										<div class="form-group">
											<label>Email</label>
											<input type="email" name="email"  class="form-control" required="" data-error="Lütfen e-posta adresinizi girin" placeholder="Ac@gmail.com">
											<div class="help-block with-errors"></div>
										</div>
									</div>
		
									<div class="col-lg-12">
										<div class="form-group">
											<label>Konu</label>
											<input type="text" name="title"  class="form-control" required="" data-error="Lütfen konuyu giriniz" placeholder="Konu">
											<div class="help-block with-errors"></div>
										</div>
									</div>
		
									<div class="col-lg-12">
										<div class="form-group">
											<label>Mesajınız</label>
											<textarea name="message" class="form-control" id="message" cols="30" rows="6" required="" data-error="Mesajınızı yazın" placeholder="Mesajınızı yazın"></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									
		
									<div class="col-lg-12 col-md-12">
										<button type="submit" class="default-btn">
											Mesajı Gönder
										</button>
										<div id="msgSubmit" class="h3 text-center hidden"></div>
										<div class="clearfix"></div>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="contacts-info">
							<h2>İletişim bilgileri</h2>

							<ul class="address">
								<li>
									<span>Telefon:</span>
									<a href="tel:<?php echo $company['phone']; ?>"><?php echo $company['phone']; ?></a>
								</li>
								<li>
									<span>Email:</span>
									<a href="mailto:<?php echo $company['email']; ?>"><?php echo $company['email']; ?></a>
								</li>
								<li class="location">
									<span>Adres:</span>
									<?php echo $company['address']; ?>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Contact Area -->

		<?php include("footer.php");