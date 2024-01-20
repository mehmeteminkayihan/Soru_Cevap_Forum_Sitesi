<?php
include("header.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("connection.php");

// Kullanıcı oturumu açıksa
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    
    $info = "SELECT U.registration_date, UD.display_name, UD.user_about, UD.number_of_questions, UD.number_of_answers, UD.user_image
        FROM Users U
        INNER JOIN User_details UD ON U.user_id = UD.user_id
        WHERE U.user_id = ?";
    $user_info = $baglanti->prepare($info);

    
    if (!$user_info) {
        die("Sorgu hazırlanamadı: " . $baglanti->error);
    }

    $user_info->bind_param("i", $user_id);
    $user_info->execute();
    $info_result = $user_info->get_result();

    // Kullanıcı bilgilerini işle
    if ($info_result->num_rows > 0) {
        $info_row = $info_result->fetch_assoc();
        $display_name = $info_row['display_name'] ?? null;
        $registration_date = $info_row['registration_date'];
        $user_about = $info_row['user_about'];
        $user_question = $info_row['number_of_questions'];
        $user_answer = $info_row['number_of_answers'];
        $user_image = $info_row['user_image'];

       
        $registration_date = strtotime($registration_date);
        $current_date = time();
        $membership_duration = $current_date - $registration_date;

        $formatted_membership_duration = '';
        if ($membership_duration >= 365 * 24 * 60 * 60) {
            $years = floor($membership_duration / (365 * 24 * 60 * 60));
            $formatted_membership_duration = $years . ' yıl';
        } elseif ($membership_duration >= 30 * 24 * 60 * 60) {
            $months = floor($membership_duration / (30 * 24 * 60 * 60));
            $formatted_membership_duration = $months . ' ay';
        } else {
            $days = floor($membership_duration / (24 * 60 * 60));
            $formatted_membership_duration = $days . ' gün';
        }

        
        $total_views_query = "SELECT SUM(number_of_views) AS total_views FROM Questions WHERE user_id = ?";
        $total_views_stmt = $baglanti->prepare($total_views_query);

        if (!$total_views_stmt) {
            die("Sorgu hazırlanamadı: " . $baglanti->error);
        }

        $total_views_stmt->bind_param("i", $user_id);
        $total_views_stmt->execute();
        $total_views_result = $total_views_stmt->get_result();

        if ($total_views_result->num_rows > 0) {
            $total_views_row = $total_views_result->fetch_assoc();
            $total_views = $total_views_row['total_views'] ?? 0;
        } else {
            echo '<div class="alert alert-danger" role="alert">
                        Görüntülenme sayıları alınamadı.
                    </div>';
            $total_views = 0;
        }

        $total_views_stmt->close();
    } else {
        
        $display_name = "Bilgi yok";
        $formatted_membership_duration = "Yeni kayıtlı üye";
        $total_questions = 0;
        $total_views = 0;
    }

    $user_info->close();
} else {
    echo '<div class="alert alert-danger" role="alert">
                Oturum açılmamış.
            </div>';
    $display_name = "Oturum açılmamış";
    $formatted_membership_duration = "Oturum açılmamış";
    $total_questions = 0;
    $total_views = 0;
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

            <div class="col-lg-9">
                <div class="user-profile-area">
                    <div class="profile-content d-flex justify-content-between align-items-center">
                        <div class="profile-img">
                            <img src="<?php echo $user_image; ?>" alt="Image">
                            <h3><?php echo $display_name; ?></h3>
                            <p><strong>Üyelik Süresi:</strong> <?php echo $formatted_membership_duration; ?></p>
                        </div>
                        <div class="edit-btn">
                            <a href="edit-profile.php" class="default-btn">Profilini Düzenle</a>
                        </div>
                    </div>

                    <div class="profile-achive">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6">
                                <div class="single-achive">
                                    <h2><?php echo  $user_answer; ?></h2>
                                    <span>Cevapların</span>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-6">
                                <div class="single-achive">
                                    <h2><?php echo  $user_question; ?></h2>
                                    <span>Soruların</span>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-6">
                                <div class="single-achive">
                                    <h2><?php echo  $total_views; ?></h2>
                                    <span>Görüntülenmen</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about">
                        <h3>Hakkımda</h3>
                        <p><?php echo isset($user_about) && !empty($user_about) ? $user_about : 'Herhangi bir bilgi girmediniz.'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php");?>