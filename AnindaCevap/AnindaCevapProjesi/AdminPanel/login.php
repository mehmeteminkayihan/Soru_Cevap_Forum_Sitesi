<?php
include("connection.php");
session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $username = $_POST['loginname'];
    $password = $_POST['loginpassword'];

    $query = "SELECT user_id, user_nickname, user_password, authorization_id FROM Users WHERE user_nickname = ?";
    

    $stmt = mysqli_prepare($baglanti, $query);

    // Bağlantıyı kontrol et ve sorguyu çalıştır
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            // Kullanıcı bulunduysa
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                // Veritabanındaki hashlenmiş şifre ile gelen şifreyi kontrol et
                if (password_verify($password, $row['user_password'])) {

                    if ($row['authorization_id'] == 1 || $row['authorization_id'] == 2 ) {
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_nickname'] = $row['user_nickname'];
                        $_SESSION['authorization_id'] = $row['authorization_id'];

                        header("Location: index.php");
                        exit();
                    } else {
                        
                        $error_message = "Yetkiniz bulunmamaktadır.";
                    }
                } else {
                    
                    $error_message = "Hatalı şifre girdiniz.";
                }
            } else {
                
                $error_message = "Kullanıcı bulunamadı.";
            }
        } else {
           
            $error_message = "Sorgu hatası: " . mysqli_error($baglanti);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($baglanti);
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AC Admin-Panel-Login</title>

    <!-- Bootstrap CSS -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Dış Sıra -->
        <div class="row justify-content-center align-items-center" style="height: 100vh;">

            <div class="col-xl-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Anında Cevap Admin Panel</h1>
                        </div>
                        <?php
                            // Hata mesajı varsa alert ile göster
                            if ($error_message !== "") {
                                echo "<script>window.onload = function() { alert('$error_message'); }</script>";
                            }
                        ?>
                        <form class="user" method="POST" action="">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="loginname" placeholder="Kullanıcı Adınızın" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" name="loginpassword" placeholder="Şifre" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                               Giriş Yap
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
