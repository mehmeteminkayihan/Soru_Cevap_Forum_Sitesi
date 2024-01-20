<?php

include("header.php");
include("connection.php");


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {	
}


if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];

    $query = "SELECT q.questions_id, q.question_title, q.questions, q.question_like, q.question_dislike, q.number_of_views, q.user_id, ud.display_name, ud.user_image , c.category_type, TIMESTAMPDIFF(SECOND, q.question_date, NOW()) AS diff_seconds, q.total_responses_received
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
            
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Sorgu hazırlanamadı.";
       
    }
} else {
   
}

$queryAnswers = "SELECT a.user_id, a.answer_id, ud.user_image , a.answer, a.answer_date, a.answer_like, a.answer_dislike, TIMESTAMPDIFF(SECOND, a.answer_date, NOW()) AS diff_answer, ud.display_name
                FROM Answers a
                INNER JOIN User_details ud ON a.user_id = ud.user_id
                WHERE a.questions_id = ?";

$stmtAnswers = mysqli_stmt_init($baglanti);

if (mysqli_stmt_prepare($stmtAnswers, $queryAnswers)) {
    mysqli_stmt_bind_param($stmtAnswers, 'i', $question_id);
    mysqli_stmt_execute($stmtAnswers);

    $resultAnswers = mysqli_stmt_get_result($stmtAnswers);
    
    $allAnswers = array(); 

    
    if (mysqli_num_rows($resultAnswers) > 0) {
        while ($rowAnswer = mysqli_fetch_assoc($resultAnswers)) {
            $answerDetails = array(
                'user_id' => $rowAnswer['user_id'],
                'answer_id' => $rowAnswer['answer_id'],
                'answer' => $rowAnswer['answer'],
                'answer_date' => formatElapsedTime($rowAnswer['diff_answer']),
                'answer_like' => $rowAnswer['answer_like'],
                'answer_dislike' => $rowAnswer['answer_dislike'],
                'display_name' => $rowAnswer['display_name']
            );

            $allAnswers[] = $answerDetails; 
        }
    } else {
       
    } 
    mysqli_stmt_close($stmtAnswers);
} else {
    echo "Cevapları getirme sorgusu hazırlanamadı.";
    
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
    // Oturum kontrolü ve diğer gerekli işlemler burada

   
    $answer_content = $_POST['answer'];

    if (isset($_GET['question_id'])) {
        $question_id = $_GET['question_id'];

       
        $check_query = "SELECT COUNT(*) AS count FROM Answers WHERE questions_id = ? AND user_id = ?";
        $check_stmt = mysqli_stmt_init($baglanti);

        if (mysqli_stmt_prepare($check_stmt, $check_query)) {
            mysqli_stmt_bind_param($check_stmt, 'ii', $question_id, $user_id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            $row = mysqli_fetch_assoc($check_result);

            if ($row['count'] > 0) {
                
                echo '<div class="alert alert-danger" role="alert">
                        Bu soruya zaten cevap verdiniz.
                      </div>';
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
                } else {
                    echo "Sorgu hazırlanamadı.";
                }

                mysqli_stmt_close($insert_stmt);

          
                $update_query = "UPDATE Questions SET total_responses_received = total_responses_received + 1 WHERE questions_id = ?";

                $update_stmt = mysqli_stmt_init($baglanti);

                if (mysqli_stmt_prepare($update_stmt, $update_query)) {
                    mysqli_stmt_bind_param($update_stmt, 'i', $question_id);
                    mysqli_stmt_execute($update_stmt);
                }
                mysqli_stmt_close($update_stmt);
            }
        } else {
            echo "Sorgu hazırlanamadı.";
            exit();
        }
    } else {
        echo "Soru ID'si bulunamadı.";
        exit();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['answer_like']) || isset($_POST['answer_dislike'])) {
       // Kullanıcı cevaba bir tepki verdiği durumu kontrol et
       $answer_id = $_POST['answer_id'];
       $user_id = $_SESSION['user_id'];
   
       $reaction_type = "";
       if (isset($_POST['answer_like'])) {
           $reaction_type = "like";
       } elseif (isset($_POST['answer_dislike'])) {
           $reaction_type = "dislike";
       }
   
     
       $check_reaction_query = "SELECT COUNT(*) AS count FROM Answer_Reaction_Log WHERE user_id = ? AND answer_id = ?";
       $check_reaction_stmt = mysqli_stmt_init($baglanti);
   
       if (mysqli_stmt_prepare($check_reaction_stmt, $check_reaction_query)) {
           mysqli_stmt_bind_param($check_reaction_stmt, 'ii', $user_id, $answer_id);
           mysqli_stmt_execute($check_reaction_stmt);
           $check_reaction_result = mysqli_stmt_get_result($check_reaction_stmt);
           $row_reaction = mysqli_fetch_assoc($check_reaction_result);
   
           if ($row_reaction['count'] > 0) {
           } else {
   
               $insert_reaction_query = "INSERT INTO Answer_Reaction_Log (user_id, answer_id, reaction_type) VALUES (?, ?, ?)";
               $insert_reaction_stmt = mysqli_stmt_init($baglanti);
       
               if (mysqli_stmt_prepare($insert_reaction_stmt, $insert_reaction_query)) {
                   mysqli_stmt_bind_param($insert_reaction_stmt, 'iis', $user_id, $answer_id, $reaction_type);
                   mysqli_stmt_execute($insert_reaction_stmt);
                   mysqli_stmt_close($insert_reaction_stmt);
       
                  
                   $update_answer_query = "UPDATE Answers SET ";
                   if ($reaction_type == "like") {
                       $update_answer_query .= "answer_like = answer_like + 1";
                   } elseif ($reaction_type == "dislike") {
                       $update_answer_query .= "answer_dislike = answer_dislike + 1";
                   }
                   $update_answer_query .= " WHERE answer_id = ?";
                   
                   $update_answer_stmt = mysqli_stmt_init($baglanti);
                   if (mysqli_stmt_prepare($update_answer_stmt, $update_answer_query)) {
                       mysqli_stmt_bind_param($update_answer_stmt, 'i', $answer_id);
                       mysqli_stmt_execute($update_answer_stmt);
                       mysqli_stmt_close($update_answer_stmt);
                   }
               }
             
           }
       } else {
           echo "Tepki kontrol sorgusu hazırlanamadı.";
       }
   
       mysqli_stmt_close($check_reaction_stmt);
    }
   }

$check_reaction_query = "SELECT COUNT(*) AS count FROM Reaction_Log WHERE user_id = ? AND questions_id = ?";
$check_reaction_stmt = mysqli_stmt_init($baglanti);

if (mysqli_stmt_prepare($check_reaction_stmt, $check_reaction_query)) {
    mysqli_stmt_bind_param($check_reaction_stmt, 'ii', $user_id, $question_id);
    mysqli_stmt_execute($check_reaction_stmt);
    $check_reaction_result = mysqli_stmt_get_result($check_reaction_stmt);
    $row_reaction = mysqli_fetch_assoc($check_reaction_result);

    if ($row_reaction['count'] > 0) {
    } else {
        // Kullanıcı daha önce bu soruya bir tepki vermemiş, bu durumda yeni tepkiyi ekleyebiliriz
        $reaction_type = "";
        
        if (isset($_POST['user_like'])) {
            $reaction_type = "like"; 
        } elseif (isset($_POST['user_dislike'])) {
            $reaction_type = "dislike"; 
        }

        if (!empty($reaction_type)) {
            // Eğer bir tepki türü belirlenmişse
            $insert_reaction_query = "INSERT INTO Reaction_Log (user_id, questions_id, reaction_type) VALUES (?, ?, ?)";
            $insert_reaction_stmt = mysqli_stmt_init($baglanti);

            if (mysqli_stmt_prepare($insert_reaction_stmt, $insert_reaction_query)) {
                mysqli_stmt_bind_param($insert_reaction_stmt, 'iis', $user_id, $question_id, $reaction_type);
                mysqli_stmt_execute($insert_reaction_stmt);

                
                $update_question_query = "UPDATE Questions SET ";

                if ($reaction_type == "like") {
                    $update_question_query .= "question_like = question_like + 1";
                } else if ($reaction_type == "dislike") {
                    $update_question_query .= "question_dislike = question_dislike + 1";
                }

                $update_question_query .= " WHERE questions_id = ?";
                
                $update_question_stmt = mysqli_stmt_init($baglanti);

                if (mysqli_stmt_prepare($update_question_stmt, $update_question_query)) {
                    mysqli_stmt_bind_param($update_question_stmt, 'i', $question_id);
                    mysqli_stmt_execute($update_question_stmt);
                    mysqli_stmt_close($update_question_stmt);
                }
            } else {
                echo "Tepki ekleme sorgusu hazırlanamadı.";
            }

            mysqli_stmt_close($insert_reaction_stmt);
        }
    }
} else {
    echo "Tepki kontrol sorgusu hazırlanamadı.";
}

// Kullanıcının oturumu açık ve question_id parametresi mevcutsa
if (isset($_SESSION['user_id']) && isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    $user_id = $_SESSION['user_id'];

    
    $check_view_query = "SELECT COUNT(*) AS count FROM View_Log WHERE questions_id = ? AND user_id = ?";
    $check_view_stmt = mysqli_stmt_init($baglanti);

    if (mysqli_stmt_prepare($check_view_stmt, $check_view_query)) {
        mysqli_stmt_bind_param($check_view_stmt, 'ii', $question_id, $user_id);
        mysqli_stmt_execute($check_view_stmt);
        $check_view_result = mysqli_stmt_get_result($check_view_stmt);
        $row_view = mysqli_fetch_assoc($check_view_result);

        if ($row_view['count'] == 0) {
           
            $increase_view_query = "UPDATE Questions SET number_of_views = number_of_views + 1 WHERE questions_id = ?";
            $increase_view_stmt = mysqli_stmt_init($baglanti);

            if (mysqli_stmt_prepare($increase_view_stmt, $increase_view_query)) {
                mysqli_stmt_bind_param($increase_view_stmt, 'i', $question_id);
                mysqli_stmt_execute($increase_view_stmt);
                mysqli_stmt_close($increase_view_stmt);

               
                $insert_view_query = "INSERT INTO View_Log (user_id, questions_id) VALUES (?, ?)";
                $insert_view_stmt = mysqli_stmt_init($baglanti);

                if (mysqli_stmt_prepare($insert_view_stmt, $insert_view_query)) {
                    mysqli_stmt_bind_param($insert_view_stmt, 'ii', $user_id, $question_id);
                    mysqli_stmt_execute($insert_view_stmt);
                    mysqli_stmt_close($insert_view_stmt);
                }
            }
        }
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
                                <?php include("sidebar.php"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="middull-content">						
                    <div class="question-details-area">
                        <div class="question-details-content like-dislike">
                            <div class="d-flex">
                                <div class="link-unlike flex-shrink-0">
                                    <a >
                                    <img src="<?php echo $selected_question['user_image']; ?>" width="50px" alt="Image">
                                    </a>

                                    <div class="donet-like-list">
                                        <form method="post"> 
                                            <input type="hidden" name="question_id" value="<?php echo $selected_question['question_id']; ?>">
                                            <button class="like-unlink-count like" name="user_like" value="<?php echo $selected_question['like'];?>"> 
                                                <i class="ri-thumb-up-fill"></i>
                                                <span><?php echo $selected_question['like'];?></span>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="donet-like-list">
                                        <form method="post">
                                            <input type="hidden" name="question_id" value="<?php echo $selected_question['question_id']; ?>">
                                            <button class="like-unlink-count dislike" name="user_dislike" value="<?php echo $selected_question['dislike']; ?>">
                                                <i class="ri-thumb-down-fill"></i>
                                                <span><?php echo $selected_question['dislike']; ?></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex-grow-1 ms-3">
                                    <ul class="graphic-design">
                                        <li>
                                            <a href="user.php?user_id=<?php echo $selected_question['user_id']; ?>"><?php echo $selected_question['nickname'];?></a>
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

                                    <h3><?php echo $selected_question['title']; ?></h3>

                                    <p style="font-size: 16px;"><?php echo $selected_question['content'] ; ?></p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <ul class="anser-list">
                                            <li>
                                                <a><?php echo $selected_question['answer_count']; ?> Cevap</a>
                                            </li>
                                            <li>
                                                <a><?php echo $selected_question['views']; ?> Görüntülenme</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="answerss d-flex justify-content-between align-items-center">
                            <li>
                                <h3><?php echo $selected_question['answer_count']; ?> Cevap</h3>
                            </li>
                        </ul>

                        <?php
                        if (!empty($allAnswers)) {
                            foreach ($allAnswers as $answer) {
                        ?>
                                <div class="answer-question-details like-dislike">
                                    <div class="d-flex">
                                        <div class="link-unlike flex-shrink-0">
                                            <a href="user.php">
                                            
                                            </a>
                                            <div class="donet-like-list">
                                             <form method="post">
                                                <input type="hidden" name="answer_id" value="<?php echo $answer['answer_id']; ?>">
                                                <button class="like-unlink-count like" name="answer_like" value="<?php echo $answer['answer_like']; ?>">
                                                    <i class="ri-thumb-up-fill"></i>
                                                    <span><?php echo $answer['answer_like']; ?></span>
                                                </button>
                                             </form>
                                            </div>

                                            <div class="donet-like-list">
                                             <form method="post">
                                                <input type="hidden" name="answer_id" value="<?php echo $answer['answer_id']; ?>">
                                                <button class="like-unlink-count dislike" name="answer_dislike" value="<?php echo $answer['answer_dislike']; ?>">
                                                    <i class="ri-thumb-down-fill"></i>
                                                    <span><?php echo $answer['answer_dislike']; ?></span>
                                                </button>
                                             </form>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 ms-3">
                                            <ul class="latest-answer-list">
                                                <li>
                                                    <a href="user.php"><?php echo $answer['display_name']; ?></a>
                                                </li>
                                                <li>
                                                    <span><?php echo 'Yayın tarihi: ' . $answer['answer_date'] . ' önce'; ?></span>
                                                </li>
                                            </ul>

                                            <p style="font-size: 16px;"><?php echo $answer['answer']; ?> </p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
    
                        }
                        ?>
                      <?php if (isset($_SESSION['username'])): ?>
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
                     <?php else: ?>
                        <!-- Oturum açılmamışsa, kullanıcıya uyarı mesajını gösteriyoruz -->
                        <div class="alert alert-warning" role="alert">
                         Cevap vermek için oturum aç !
                        </div>
                     <?php endif; ?>


                    </div>
                </div>
            </div>
            <?php include("rightsidebar.php");?>
        </div>
    </div>
</div>


<?php include("footer.php");?>