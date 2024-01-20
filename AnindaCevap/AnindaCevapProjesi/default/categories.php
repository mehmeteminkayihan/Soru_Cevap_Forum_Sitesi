<?php include("header.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("connection.php");


if (isset($_GET['category'])) {
    $selected_category = $_GET['category'];
    $sorular = array(); 

    
    $query = "SELECT q.*, ud.display_name, ud.user_image , c.category_type, TIMESTAMPDIFF(SECOND, q.question_date, NOW()) AS diff_seconds 
              FROM Questions q 
              INNER JOIN User_details ud ON q.user_id = ud.user_id
              INNER JOIN Category c ON q.category_id = c.category_id
              WHERE c.category_type = ?
              ORDER BY q.question_date DESC";

    
    $stmt = mysqli_prepare($baglanti, $query);

    
    mysqli_stmt_bind_param($stmt, "s", $selected_category);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $soru = array(
				'user_id' => $row['user_id'],
				'question_id' => $row['questions_id'],
                'title' => $row['question_title'],
                'content' => $row['questions'],
                'like' => $row['question_like'],
                'dislike' => $row['question_dislike'],
                'views' => $row['number_of_views'],
                'elapsed_time' => formatElapsedTime($row['diff_seconds']),
                'nickname' => $row['display_name'],
                'category' => $row['category_type'],
				'user_image' => $row['user_image'],
                'answer_count' => $row['total_responses_received']
            );

            
            $sorular[] = $soru;
        }

        
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Bu kategoride henüz hiç soru yok.
                </div>';
      
    }

    
    mysqli_stmt_close($stmt);
    mysqli_close($baglanti);
} else {
    echo "Lütfen bir kategori seçin.";
}

function formatElapsedTime($seconds) {
    $times = array(   
        31536000 => 'yıl',
        2592000 => 'ay',
        604800 => 'hafta',
        86400 => 'gün',
        3600 => 'saat',
        60 => 'dakika',
        1 => 'saniye'
    );

    foreach ($times as $secondsKey => $time) {
        $quotient = floor($seconds / $secondsKey);
        if ($quotient >= 1) {
            $message = $quotient . ' ' . $time;
            return $message;
        }
    }

    return 'şimdi';
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

					<div class="col-lg-6">
						<div class="middull-content">												
							
							<div class="tab-content" id="myTabContent">
							    <div class="tab-pane fade show active" id="recent-questions" role="tabpanel" aria-labelledby="recent-questions-tab">
								  <?php foreach ($sorular as $soru): ?>
									<div class="single-qa-box like-dislike">
										<div class="d-flex">
											<div class="link-unlike flex-shrink-0">
												<a >
												<img src="<?php echo $soru['user_image']; ?>" width="50px" alt="Image">
												</a>
											</div>

											<div class="flex-grow-1 ms-3">
												<ul class="graphic-design">
													<li>
													<a href="user.php?user_id=<?php echo $soru['user_id']; ?>"><?php echo $soru['nickname']; ?></a>
													</li>
													<li>
														<span><?php echo 'Yayın tarihi : '.$soru['elapsed_time'].' önce'; ?></span>
													</li>
													<li>
														<span>Kategori : </span>
														<a href="categories.php?category=<?php echo urlencode($soru['category']); ?>">
                                                              <?php echo $soru['category']; ?>
                                                        </a>
													</li>
												</ul>

												<h3>
												    <a href="queations-details.php?question_id=<?php echo $soru['question_id']; ?>">
													   <?php echo $soru['title']; ?>
													</a>
												</h3>

												<p><?php echo $soru['content']; ?></p>
	
												<div class="d-flex justify-content-between align-items-center">
													<ul class="anser-list">
													
														<li>
															<a >
															<?php echo $soru['answer_count']; ?> Cevap
															</a>
														</li>
														<li>
															
															<?php echo $soru['views']; ?> Görüntülenme
															
														</li>
														
													</ul>

													<?php if (isset($_SESSION['username'])): ?>
                                                        <a href="reply.php?question_id=<?php echo $favorite['question_id']; ?>" class="default-btn">
                                                           Cevapla
                                                        </a>
                                                       <?php else: ?>
                                                            <div class="col-md-4 position-relative">
                                                                <div class="alert alert-warning" role="alert">
                                                                  Cevap vermek için oturum aç !
                                                                </div>
                                                            </div>
                                                    <?php endif; ?>
												</div>
											</div>
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

<?php include("footer.php"); ?>
