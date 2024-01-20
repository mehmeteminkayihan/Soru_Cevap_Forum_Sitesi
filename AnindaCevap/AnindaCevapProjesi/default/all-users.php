<?php
include("header.php");
include("connection.php");

$kullanıcılar = array();

$query = "SELECT ud.display_name, ud.country, ud.user_image, ud.number_of_questions, u.user_id
          FROM Users u
          JOIN User_details ud ON u.user_id = ud.user_id";
$result = mysqli_query($baglanti, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $kullanıcı = array(
            'user_id' => $row['user_id'],
            'nickname' => $row['display_name'],
            'country' => $row['country'],
            'questions' => $row['number_of_questions'],
            'user_image' => $row['user_image'] 
        );

        $kullanıcılar[] = $kullanıcı;
    }
}

mysqli_close($baglanti);
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

			<div class="col-lg-6">
                <div class="middull-content">
                    <div class="wew-user-area">
                        <div class="row">
                            <?php foreach ($kullanıcılar as $kullanıcı): ?>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="single-new-user">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="<?php echo $kullanıcı['user_image']; ?>" width="75px"alt="Image">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h3>
                                                    <a href="user.php?user_id=<?php echo $kullanıcı['user_id']; ?>"><?php echo $kullanıcı['nickname']; ?></a>
                                                </h3>
                                                <p><h6>Ülkesi : <?php echo $kullanıcı['country']; ?></h6></p>
                                            </div>
                                        </div>
                                        <ul class="d-flex justify-content-between align-items-center">
                                            <li>
                                                <p><span><?php echo $kullanıcı['questions']; ?></span> soru</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

					<?php include("rightsidebar.php");?>
				</div>
			</div>
		</div>

<?php include("footer.php");?>
