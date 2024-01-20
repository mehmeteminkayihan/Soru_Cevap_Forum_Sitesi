<?php include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['Search'])){
        $searchTerm = $_POST['Search'];

      
        include("connection.php");

      
        $sql = "SELECT q.questions_id, u.user_id, u.display_name, c.category_type, q.question_title, q.questions, q.question_date, q.question_like, q.question_dislike, q.number_of_views, q.total_responses_received, TIMESTAMPDIFF(SECOND, q.question_date, NOW()) AS diff_seconds
        FROM Questions q
        INNER JOIN User_details u ON q.user_id = u.user_id
        INNER JOIN Category c ON q.category_id = c.category_id
        WHERE q.question_title LIKE '%$searchTerm%' OR q.questions LIKE '%$searchTerm%'";

        $result = mysqli_query($baglanti, $sql);

        $searchResults = array();

        if(mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)) {
				$searchResults[] = array(
					'question_id' => $row['questions_id'],
					'user_id' => $row['user_id'],
					'title' => $row['question_title'],
					'content' => $row['questions'],
					'question_like' => $row['question_like'],
					'question_dislike' => $row['question_dislike'],
					'views' => $row['number_of_views'],
                    'nickname' => $row['display_name'],
                    'category' => $row['category_type'],
					'elapsed_time' => formatElapsedTime($row['diff_seconds']),
					'question_date' => $row['question_date'],
					'answer_count' => $row['total_responses_received']
				);
            }
        } else {
            echo 'Arama sonucunda eşleşen bir şey bulunamadı.';
        }

        mysqli_close($baglanti);
    }
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
		<!-- Start Mail Content Area -->
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
                            <div class="search-content">
							<?php
    // Eğer arama yapıldıysa ve sonuçlar varsa, bu sonuçları kullanabiliriz
    if(isset($searchResults) && !empty($searchResults)) {
        foreach ($searchResults as $search) {
            ?>
            <div class="single-qa-box like-dislike">
                <div class="d-flex">
                    <div class="link-unlike flex-shrink-0">
                        <a href="user.php">
                            <img src="assets/images/user/user-1.jpg" alt="Image">
                        </a>
                    </div>

                    <div class="flex-grow-1 ms-3">
                        <ul class="graphic-design">
                            <li>
                                <a href="user.php?user_id=<?php echo $search['user_id']; ?>"><?php echo $search['nickname']; ?></a>
                            </li>
                            <li>
                                <span><?php echo 'Yayın tarihi : '.$search['elapsed_time'].' önce'; ?></span>
                            </li>
                            <li>
                                <span>Kategori : </span>
                                <a href="categories.php?category=<?php echo urlencode($search['category']); ?>">
                                 <?php echo $search['category']; ?>
                                </a>
                            </li>
                        </ul>

                        <h3>
                            <a href="queations-details.php?question_id=<?php echo $search['question_id']; ?>">
                                <?php echo $search['title']; ?>
                            </a>
                        </h3>

                        <p><?php echo substr($search['content'], 0, 250); ?>...</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <ul class="anser-list">
                                <li>
                                    <a><?php echo $search['answer_count']; ?> Cevap</a>
                                </li>
                                <li>
                                    <?php echo $search['views']; ?> Görüntülenme
                                </li>
                            </ul>

                            <?php if (isset($_SESSION['username'])): ?>
                                <a href="reply.php?question_id=<?php echo $search['question_id']; ?>" class="default-btn">
                                    Cevapla
                                </a>
                            <?php else: ?>
                                <!-- Oturum açılmamışsa, kullanıcıya uyarı mesajını gösteriyoruz -->
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
            <?php
        }
    }
    ?>
                            </div>
                        </div>
					</div>
					<?php include("rightsidebar.php");?>
				</div>
			</div>
		</div>
		<!-- End Mail Content Area -->

		<?php include("footer.php");?>