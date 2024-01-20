    <?php
    session_start();
    include("connection.php");
    if(isset($_GET['Search'])){
        $searchTerm = $_GET['Search'];

        $sql = "SELECT * FROM Questions WHERE question_title LIKE '%$searchTerm%' OR questions LIKE '%$searchTerm%'";

        
        $result = mysqli_query($baglanti, $sql);

        // Eğer sonuçlar varsa
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
               
                echo "Başlık: " . $row['question_title'] . "<br>";
                echo "Soru: " . $row['questions'] . "<br><br>";
            }
        } else {
            echo "Arama sonucunda eşleşen bir şey bulunamadı.";
        }

        
        mysqli_close($baglanti);
    }
    
    if (isset($_GET['logout'])) {
        unset($_SESSION["username"]);
        unset($_SESSION["email"]);
        unset($_SESSION["role"]);
        session_destroy();
        
        echo '<script>alert("Başarıyla çıkış yaptınız.");</script>';
    }

    $username = $email = $role = '';

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $role = $_SESSION['role'];
    }
    ?>

    <!doctype html>
    <html lang="zxx">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Links Of CSS File -->
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
            <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
            <link rel="stylesheet" href="assets/css/flaticon.css">
            <link rel="stylesheet" href="assets/css/remixicon.css">
            <link rel="stylesheet" href="assets/css/meanmenu.min.css">
            <link rel="stylesheet" href="assets/css/animate.min.css">
            <link rel="stylesheet" href="assets/css/metismenu.min.css">
            <link rel="stylesheet" href="assets/css/font-awesome.min.css">
            <link rel="stylesheet" href="assets/css/editor.css">
            <link rel="stylesheet" href="assets/css/style.css">
            <link rel="stylesheet" href="assets/css/responsive.css">
            
            <!-- Favicon -->
            <link rel="icon" type="image/png" href="assets/images/favicon.png">
            <!-- Title -->
            <title>Anında Cevap Forum-Sitesi</title>
        </head>

        <body>
        
            <div class="preloader" id="loader-style">
                <div class="preloader-wrap">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        
            
            
            <!-- End Preloader Area -->
            
            <!-- Start Navbar Area --> 
            <div class="navbar-area">
              
                <div class="desktop-nav">
             <div class="container">
                <nav class="navbar navbar-expand-md navbar-light">
                    <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                        <ul class="navbar-nav m-auto">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link active">
                                    Ana Sayfa
                                </a>
                            </li>
                            <?php if(isset($_SESSION['username'])) { ?>
                                <li class="nav-item">
                                    <a href="user-profile.php" class="nav-link">
                                    Faaliyetler
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="nav-item">
                                <a href="about.php" class="nav-link">
                                    Hakkımızda
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="contact-us.php" class="nav-link">
                                    İletişime Geç
                                </a>
                            </li>
                        </ul>
                        <div class="others-options">
                            <ul>
                                <li>
                                    <form action="search.php" method="post" class="search-box">
                                        <input type="text" name="Search" placeholder="Arama Yap..." class="form-control">
                                        <button type="submit" class="search-btn">
                                            <i class="ri-search-line"></i>
                                        </button>
                                    </form>
                                </li>
                                <?php
                            
                                $username = '';
                                if (isset($_SESSION['username'])) {
                                    $username = $_SESSION['username'];
                                }
                                ?>
                                <li>
                                    <?php if ($username != '') { ?>
                                        <a href="logout.php" class="nav-link">
                                            Çıkış Yap
                                        </a>
                                    <?php } else { ?>
                                        <a href="log-in.php" data-bs-toggle="modal" data-bs-target="#exampleModal" class="active">
                                            Giriş Yap
                                        </a>
                                    <?php } ?>
                                </li>
                                <?php if ($username == '') { ?>
                                    <li>
                                        <a data-bs-toggle="modal" data-bs-target="#exampleModal-2">
                                            Kayıt Ol
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>


                <div class="others-option-for-responsive">
                    <div class="container">
                        <div class="dot-menu">
                            <div class="inner">
                                <div class="circle circle-one"></div>
                                <div class="circle circle-two"></div>
                                <div class="circle circle-three"></div>
                            </div>
                        </div>
                        
                        <div class="container">
                            <div class="option-inner">
                                <div class="others-options justify-content-center d-flex align-items-center">
                                    <ul>
                                        <li>
                                            <form class="search-box">
                                                <input type="text" name="Search" placeholder="Search for..." class="form-control">
                                        
                                                <button type="submit" class="search-btn">
                                                    <i class="ri-search-line"></i>
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <a href="log-in.php" data-bs-toggle="modal" data-bs-target="#exampleModal-3" class="active">
                                                Giriş Yap
                                            </a>
                                        </li>
                                        <li>
                                            <a  data-bs-toggle="modal" data-bs-target="#exampleModal-4">
                                                Kayıt Ol
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Navbar Area -->

    <?php include("register.php"); ?>
    <?php include("login.php"); ?>
