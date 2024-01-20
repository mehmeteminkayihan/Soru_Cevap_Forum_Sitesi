<?php
// user.php dosyası
include("header.php");
include("connection.php");

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $detaylar = array();

   
    $query = "SELECT ud.display_name, ud.user_about, ud.country, ud.user_image, ud.number_of_questions, ud.number_of_answers, u.registration_date
              FROM Users u
              JOIN User_details ud ON u.user_id = ud.user_id
              WHERE u.user_id = $user_id";

    $result = mysqli_query($baglanti, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $detay = array(
                'nickname' => $row['display_name'],
                'country' => $row['country'],
                'about' => $row['user_about'],
                'question' => $row['number_of_questions'],
                'answer' => $row['number_of_answers'],
				'user_image' => $row['user_image']
            );

         
            $detaylar[] = $detay;

            $registration_date = strtotime($row['registration_date']);
            $current_date = time();

            $membership_duration = $current_date - $registration_date;

            $years = floor($membership_duration / (365 * 24 * 60 * 60));
            $months = floor(($membership_duration - ($years * 365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
            $days = floor(($membership_duration - ($years * 365 * 24 * 60 * 60) - ($months * 30 * 24 * 60 * 60)) / (24 * 60 * 60));

            $membership_period = '';

            if ($years > 0) {
                $membership_period .= $years . ' yıl ';
            }

            if ($months > 0) {
                $membership_period .= $months . ' ay ';
            }

            if ($days > 0) {
                $membership_period .= $days . ' gün';
            }

          
            $viewsQuery = "SELECT SUM(number_of_views) AS total_views FROM questions WHERE user_id = $user_id";

          
            $viewsResult = mysqli_query($baglanti, $viewsQuery);

            $totalViews = 0;

            if ($viewsResult) {
              
                $viewsRow = mysqli_fetch_assoc($viewsResult);
                $totalViews = $viewsRow['total_views'];
            } else {
                
                echo "Görüntülenme sayısı alınamadı: " . mysqli_error($baglanti);
            }

        }

    } else {
        echo '<div class="alert alert-danger" role="alert">
        Bu kategoride henüz hiç soru yok.
        </div>';
    }

    mysqli_close($baglanti);
} else {
    echo "Kullanıcı belirtilmedi.";
}


?>

<div class="main-content-area ptb-100">
			<div class="container">
				<div class="row">
					<div class="col-lg-3">
						<div class="sidebar-menu-wrap">
							<div class="sidemenu-wrap d-flex justify-content-between align-items-center">
								<h3>AC Kenar Çubuğu Menüsü </h3>
								<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
									<i class="ri-menu-line"></i>
								</button>
							</div>
							  
							<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
								<div class="offcanvas-header">
									<h5 class="offcanvas-title" id="offcanvasExampleLabel">Menu</h5>
									<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
								</div>
								<div class="offcanvas-body">
									<div class="left-sidebar">
									<?php include("sidebar.php");?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-9">
						<div class="user-profile-area">
							<div class="profile-content d-flex justify-content-between align-items-center">
							    <div class="profile-img">
                                    <img src="<?php echo $detay['user_image']; ?>" alt="Image">
                                    <h4><?php echo $detay['nickname']; ?></h4>
                                    <p> <br> </p>

                                </div>
							</div>

							<div class="profile-achive">
								<div class="row">
									<div class="col-xl-4 col-sm-6">
										<div class="single-achive">
											<h2><?php echo $detay['answer']; ?></h2>
											<span>Cevapları</span>
										</div>
									</div>

									<div class="col-xl-4 col-sm-6">
										<div class="single-achive">
											<h2><?php echo $detay['question']; ?></h2>
											<span>Soruları</span>
										</div>
									</div>
									
									<div class="col-xl-4 col-sm-6">
										<div class="single-achive">
											<h2><?php echo isset($totalViews) ? $totalViews : '0'; ?></h2>
											<span>Görüntülenme</span>
										</div>
									</div>
								</div>
							</div>
							<div class="country">
								<h4>Yaşadığı yer :</h4>
			                    <p style="font-size: 24px;"><?php echo !empty($detay['country']) ? $detay['country'] : 'Bilgi Yok'; ?></p>
								
							</div>

							<div class="about">
								<h4>Hakkımda :</h4>
			                    <p style="font-size: 24px;"><?php echo !empty($detay['about']) ? $detay['about'] : 'Bilgi Yok'; ?></p>
								
							</div>
						</div>
					</div>
				</div>
		</div>
</div>

<?php include("footer.php"); ?>