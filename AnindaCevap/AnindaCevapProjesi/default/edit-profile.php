    <?php
    include("header.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include("connection.php");

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
            
                $nickname = isset($_POST['nickname']) ? mysqli_real_escape_string($baglanti, trim($_POST['nickname'])) : '';
                $location = isset($_POST['location']) ? mysqli_real_escape_string($baglanti, trim($_POST['location'])) : '';
                $about_me = isset($_POST['about']) ? mysqli_real_escape_string($baglanti, trim($_POST['about'])) : '';

                if (!empty($nickname) && !empty($location)) {
                    $check_query = "SELECT * FROM User_details WHERE user_id = ?";
                    $check_stmt = mysqli_prepare($baglanti, $check_query);
                    mysqli_stmt_bind_param($check_stmt, "i", $user_id);
                    mysqli_stmt_execute($check_stmt);
                    $result = mysqli_stmt_get_result($check_stmt);

                    if (mysqli_num_rows($result) > 0) {
                    
                        $query = "UPDATE User_details SET display_name = ?,  Country = ?, user_about = ? WHERE user_id = ?";
                        $stmt = mysqli_prepare($baglanti, $query);
                        mysqli_stmt_bind_param($stmt, "sssi", $nickname, $location, $about_me, $user_id);
                    } else {
                    
                        $query = "INSERT INTO User_details (user_id,  display_name, Country, user_about) VALUES (?,  ?, ?, ?)";
                        $stmt = mysqli_prepare($baglanti, $query);
                        mysqli_stmt_bind_param($stmt, "isss", $user_id,  $nickname, $location, $about_me);
                    }

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<div class="alert alert-success" role="alert">
                            Bilgiler başarıyla güncellendi.
                            </div>';
                    } else {
                        echo "Bilgileri güncellerken bir hata oluştu: " . mysqli_error($baglanti);
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo '<div class="alert alert-danger" role="alert">Lütfen tüm alanları doldurun.</div>';
                }
        }


    if (isset($_POST['image_change'])) {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            

            $image = $_FILES['profile_image']['tmp_name'];

            if (!file_exists($image) || !is_uploaded_file($image)) {
                echo '<div class="alert alert-danger" role="alert">Hata: Dosya yolu geçerli değil veya dosya yüklenemedi.</div>';
                exit;
            }

            // Resmi hedef klasöre yükleme işlemi
            $uploadDirectory = 'assets/images/user/'; // Hedef klasör
            $targetFileName = $uploadDirectory . $_FILES['profile_image']['name'];

            if (move_uploaded_file($image, $targetFileName)) {
                
                $imagePath = $targetFileName; // Resmin yüklendiği yol

                $query = "UPDATE User_details SET user_image = ? WHERE user_id = ?";
                $stmt = mysqli_prepare($baglanti, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $imagePath, $user_id);

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<div class="alert alert-success" role="alert">Resim başarıyla yüklendi ve yol kaydedildi.</div>';
                    } else {
                        echo "Resim yüklenirken bir hata oluştu: " . mysqli_error($baglanti);
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "Sorgu hazırlanırken bir hata oluştu: " . mysqli_error($baglanti);
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Dosya yüklenirken bir hata oluştu.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Lütfen bir dosya seçin ve yükleyin.</div>';
        }
    }

    $check_query = "SELECT user_image FROM User_details WHERE user_id = ?";
    $check_stmt = mysqli_prepare($baglanti, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $user_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $user_image_path = $row['user_image'];
    
    } else {
    
    }

        
        

        if (isset($_POST['change'])) {
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $newPassword = isset($_POST['new-password']) ? $_POST['new-password'] : '';
            $newPasswordAgain = isset($_POST['new-password-again']) ? $_POST['new-password-again'] : '';

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                $check_query = "SELECT user_password FROM Users WHERE user_id = ?";
                $check_stmt = mysqli_prepare($baglanti, $check_query);
                mysqli_stmt_bind_param($check_stmt, "i", $user_id);
                mysqli_stmt_execute($check_stmt);
                $result = mysqli_stmt_get_result($check_stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    $hashed_password = $row['user_password'];

                    if (password_verify($password, $hashed_password)) {
                        if ($newPassword === $newPasswordAgain) {
                            $hashed_new_password = password_hash($newPassword, PASSWORD_DEFAULT);

                            $update_query = "UPDATE Users SET user_password = ? WHERE user_id = ?";
                            $update_stmt = mysqli_prepare($baglanti, $update_query);
                            mysqli_stmt_bind_param($update_stmt, "si", $hashed_new_password, $user_id);

                            if (mysqli_stmt_execute($update_stmt)) {
                                echo '<div class="alert alert-success" role="alert">
                                    Şifre başarıyla değiştirildi.
                                    </div>';
                            } else {
                                echo '<div class="alert alert-danger" role="alert">
                                    Şifre değiştirilirken bir hata oluştu: ' . mysqli_error($baglanti) . '
                                    </div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">
                                Yeni şifreler eşleşmiyor, lütfen aynı şifreyi girin.
                                </div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                            Mevcut şifre hatalı, lütfen doğru şifreyi girin.
                            </div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                        Kullanıcı bulunamadı veya bir hata oluştu.
                        </div>';
                }

                mysqli_stmt_close($check_stmt);
            }  else {
                echo '<div class="alert alert-danger" role="alert">
                    Kullanıcı kimliği bulunamadı.
                    </div>';
                    }
        }
        if ($baglanti->connect_error) {
            die("Bağlantı hatası: " . $baglanti->connect_error);
        }

        $info = "SELECT U.registration_date, UD.display_name,UD.user_about, UD.Country
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

    if ($info_result->num_rows > 0) {
        $user_info_row = $info_result->fetch_assoc();
        $display_name = $user_info_row['display_name'];
        $registration_date = $user_info_row['registration_date'];
        $user_about = $user_info_row['user_about'];
        $user_country = $user_info_row['Country'];

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

        
    } else {
        $display_name = 'Bilgi Yok';
        $formatted_membership_duration = 'Yeni kayıtlı üye';
        
        $user_about='';
        $user_country='';
    
    }


    $user_info->close();

    } 
    else {
        echo '<div class="alert alert-danger" role="alert">Kullanıcı kimliği bulunamadı.</div>';
    }

    mysqli_close($baglanti);
    ?>

    <div class="main-content-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sidebar-menu-wrap">
                        <div class="sidemenu-wrap d-flex justify-content-between align-items-center">
                            <h3>AC Sidebar Menu</h3>
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
                    <div class="edit-profile-area">
                        <div class="profile-content d-flex justify-content-between align-items-center">
                            <div class="profile-img">
                                <img src="<?php echo $user_image_path ;?>" alt="Image">
                                <h3><?php echo  $display_name ;  ?></h3>
                                <p><strong>Üyelik Süresi:</strong> <?php echo $formatted_membership_duration; ?></p>
                            </div>
                        </div>
                        <br><br>

                        <div class="profile-tabs">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab" aria-controls="edit-profile" aria-selected="true">Profili Düzenle</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="change-image-tab" data-bs-toggle="tab" data-bs-target="#change-image" type="button" role="tab" aria-controls="change-image" aria-selected="false">Resmi Değiştir</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">Şifre Değiştir</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active edit-profile" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                                    <div class="public-information">
                                        <h3>Kamuoyunu bilgilendirme</h3>
                                        <form class="edeite-content" method="POST">
                                            <div class="form-group">
                                                <label>Görünen ad</label>
                                                <input type="text" class="form-control" name="nickname" value="<?php echo $display_name ?>" >
                                            </div>
                                            <div class="form-group">
                                                <label>Konum</label>
                                                <input type="text" class="form-control" name="location" value="<?php echo $user_country ?>" >
                                            </div>
                                            <div class="form-group">
                                                <label>Hakkımda</label>
                                                <textarea class="form-control" name="about" ><?php echo $user_about ?></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group mb-0">
                                                        <button class="default-btn" name="save">Değişiklikleri kaydet</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade edit-profile" id="change-image" role="tabpanel" aria-labelledby="change-image-tab">
                                    <div class="public-information">
                                        <h3>Resmi değiştir</h3>
                                        <form class="edeite-content" method="POST" enctype="multipart/form-data">
                                            <div class="information d-flex align-items-center">    
                                                <div class="file-upload-account-info">
                                                    <input type="file" name="profile_image" id="file-2" class="inputfile">
                                                    <label for="file-2" class="upload">
                                                        <i class="ri-link"></i>
                                                        Fotoğraf Yükle
                                                    </label>
                                                    <span>Maksimum dosya boyutu: 10 MB.</span>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <button class="default-btn" name="image_change">Değişikliği kaydet</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade edit-profile" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                                    <div class="public-information">
                                        <h3>Şifreyi değiştir</h3>
                                        <form class="edeite-content" method="POST">
                                            <div class="form-group">
                                                <label>Mevcut Şifre</label>
                                                <input type="password" class="form-control" name="password" id="password">
                                            </div>
                                            <div class="form-group">
                                                <label>Yeni Şifre</label>
                                                <input type="password" class="form-control" name="new-password" id="new-password">
                                            </div>
                                            <div class="form-group">
                                                <label>Yeni Şifre (tekrardan)</label>
                                                <input type="password" class="form-control" name="new-password-again" id="new-password-again">
                                            </div>
                                            <div class="form-group mb-0">
                                                <button class="default-btn" name="change">Değişikliği kaydet</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include("footer.php");?>

            