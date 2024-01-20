<?php
include("header.php");
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {	
    exit();
}

if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];

    $query = "SELECT q.questions_id, q.question_title, q.questions, q.question_like, q.question_dislike, q.number_of_views, ud.display_name, ud.user_image ,c.category_type, TIMESTAMPDIFF(SECOND, q.question_date, NOW()) AS diff_seconds, q.total_responses_received, q.user_id
            FROM Questions q 
            INNER JOIN User_details ud ON q.user_id = ud.user_id
            INNER JOIN Category c ON q.category_id = c.category_id
            WHERE q.questions_id = ?";

    $stmt = mysqli_stmt_init($baglanti);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $question_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $selected_question = array(
                'question_id' => $row['questions_id'],
                'user_id' => $row['user_id'],
                'title' => $row['question_title'],
                'content' => $row['questions'],
                'like' => $row['question_like'],
                'dislike' => $row['question_dislike'],
                'views' => $row['number_of_views'],
                'nickname' => $row['display_name'],
                'category' => $row['category_type'],
                'user_image' => $row['user_image'],
                'elapsed_time' => formatElapsedTime($row['diff_seconds']),
                'answer_count' => $row['total_responses_received']
            );
        } else {
            echo "Soru bulunamadı.";
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Sorgu hazırlanamadı.";
        exit();
    }
} else {
    echo "Soru ID'si bulunamadı.";
    exit();
}

$show_reply_button = true;

$check_query = "SELECT COUNT(*) AS count FROM Answers WHERE questions_id = ? AND user_id = ?";
$check_stmt = mysqli_stmt_init($baglanti);

if (mysqli_stmt_prepare($check_stmt, $check_query)) {
    mysqli_stmt_bind_param($check_stmt, 'ii', $question_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($check_result);

    if ($row['count'] > 0) {
        $show_reply_button = false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $answer_content = $_POST['answer'];

    $check_query = "SELECT COUNT(*) AS count FROM Answers WHERE questions_id = ? AND user_id = ?";
    $check_stmt = mysqli_stmt_init($baglanti);

    if (mysqli_stmt_prepare($check_stmt, $check_query)) {
        mysqli_stmt_bind_param($check_stmt, 'ii', $question_id, $user_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        $row = mysqli_fetch_assoc($check_result);

        if ($row['count'] > 0) {
            exit();
        } else {
            $insert_query = "INSERT INTO Answers (questions_id, user_id, answer, answer_date)
                      VALUES (?, ?, ?, NOW())";

            $insert_stmt = mysqli_stmt_init($baglanti);

            if (mysqli_stmt_prepare($insert_stmt, $insert_query)) {
                mysqli_stmt_bind_param($insert_stmt, 'iis', $question_id, $user_id, $answer_content);
                mysqli_stmt_execute($insert_stmt);
                
                echo '<div class="alert alert-success" role="alert">
                        Cevap başarıyla gönderildi.
                      </div>';
                
              
                $update_question_query = "UPDATE Questions SET total_responses_received = total_responses_received + 1 WHERE questions_id = ?";
                $update_question_stmt = mysqli_stmt_init($baglanti);

                if (mysqli_stmt_prepare($update_question_stmt, $update_question_query)) {
                    mysqli_stmt_bind_param($update_question_stmt, 'i', $question_id);
                    mysqli_stmt_execute($update_question_stmt);
                }
                mysqli_stmt_close($update_question_stmt);

               
                $update_user_query = "UPDATE User_details SET number_of_answers = number_of_answers + 1 WHERE user_id = ?";
                $update_user_stmt = mysqli_stmt_init($baglanti);

                if (mysqli_stmt_prepare($update_user_stmt, $update_user_query)) {
                    mysqli_stmt_bind_param($update_user_stmt, 'i', $user_id);
                    mysqli_stmt_execute($update_user_stmt);
                }
                mysqli_stmt_close($update_user_stmt);
            } else {
                echo "Sorgu hazırlanamadı.";
            }

            mysqli_stmt_close($insert_stmt);
        }
    } else {
        echo "Sorgu hazırlanamadı.";
        exit();
    }
}

mysqli_close($baglanti);

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
		
							<div class="most-answered-details">
								<div class="most-answered-details-content">
									<div class="d-flex">
										<div class="link-unlike flex-shrink-0">
											<a >
                                                <img src="<?php echo $selected_question['user_image']; ?>" width="50px" alt="Image">
											</a>
										</div>

										<div class="flex-grow-1 ms-3">
											<ul class="graphic-design">
												<li>
													<a href="user.php?user_id=<?php echo $selected_question['user_id']; ?>"><?php echo $selected_question['nickname']; ?></a>
												</li>
												<li>
												  <span><?php echo 'Yayın tarihi : '.$selected_question['elapsed_time'].' önce'; ?></span>
												</li>
												<li>
													<span>Kategori : </span>
													<a href="categories.php?category=<?php echo urlencode($selected_question['category']); ?>">
                                                         <?php echo $selected_question['category']; ?>
                                                    </a>
												</li>
											</ul>

											<h3>
												<a>
												<?php echo $selected_question['title']; ?>
												</a>
											</h3>

											<p><?php echo $selected_question['content']; ?></p>

											<div class="d-flex justify-content-between align-items-center">
												<ul class="anser-list">
												    <li>
													  <?php echo $selected_question['answer_count'] ; ?> Cevap
													</li>
													<li>
													  <?php echo $selected_question['views'] ; ?> Görüntülenme
													</li>													
												</ul>
											</div>
										</div>
									</div>
								</div>
								   <?php if ($show_reply_button): ?>
                                        <form class="your-answer-form" method="POST">
                                            <div class="form-group">
                                              <label>Cevabınız</label>
                                                 <textarea class="form-control" name="answer"></textarea>
                                            </div>
                                                <div class="button">
                                                    <div class="col-12">
                                                         <div class="form-group mb-0">
                                                             <button class="default-btn" name="reply">Cevabı Gönder</button>
                                                         </div>
                                                     </div>
                                                </div>
                                        </form>
                                     <?php else: ?>
                                        <div class="alert alert-info" role="alert">
                                               Bu soruya zaten cevap verdiniz.
                                        </div>
                                    <?php endif; ?>
							    </div>
						</div>
					</div>

					<?php include("rightsidebar.php");?>
				</div>
			</div>
		</div>
		<!-- End Mail Content Area -->

		<?php include("footer.php");?>