<?php 
ob_start(); // Çıktıyı geciktirir

include("connection.php");

$username_err = $password_err = "";

if (isset($_POST["login"])) { 
    $name = $email = $password = "";

    // Kullanıcı adı doğrulaması
    if (empty($_POST["loginname"])) {
        $username_err = "Kullanıcı adı boş geçilemez.";
    } else {
        $name = $_POST["loginname"];
    }

    // Şifre doğrulaması
    if (empty($_POST["loginpassword"])) {
        $password_err = "Şifre boş bırakılamaz.";
    } else {
        $password = $_POST["loginpassword"];
    }

    // Eğer hata yoksa kullanıcı adı ve şifre veritabanında doğrulanır
    if (empty($username_err) && empty($password_err)) {
        $secim = "SELECT * FROM Users WHERE user_nickname = '$name' OR user_email= '$name' ";
        $calistir = mysqli_query($baglanti, $secim);
        $kayitsayisi = mysqli_num_rows($calistir);
        
        if ($kayitsayisi > 0) {
            // Eğer kullanıcı varsa, şifre kontrol edilir
            $ilgilikayit = mysqli_fetch_assoc($calistir);    
            $hashlisifre = $ilgilikayit["user_password"];

            if (password_verify($password, $hashlisifre)) {
                // Eğer şifre doğruysa, oturum başlatılır ve kullanıcı bilgileri saklanır
                session_start();
				$_SESSION["user_id"] = $ilgilikayit["user_id"];
                $_SESSION["username"] = $ilgilikayit["user_nickname"];
                $_SESSION["email"] = $ilgilikayit["user_email"];
                $_SESSION["role"] = $ilgilikayit["authorization_id"];
                echo '<script>window.location.href = "user-profile.php";</script>'; 
                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert"> Şifre yanlış. </div>'; 
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Kullanıcı adı veya E-posta yanlış.</div>'; 
        }
    } else {
        echo "hata";
    }

    $baglanti->close(); 
}
?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Giriş Yap</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form class="user-form" method="POST">
							<div class="row">
								
		
								<div class="col-12">
									<div class="form-group">
										<label>Kullanıcı Adı veya Email</label>
										<input class="form-control" type="text" name="loginname">
									</div>
								</div>
		
								<div class="col-12">
									<div class="form-group">
										<label>Şifre</label>
										<input class="form-control" type="password" name="loginpassword">
									</div>
								</div>
		
								<div class="col-12">
									<div class="login-action">
										<span class="forgot-login">
											<a href="forgot.php">Şifremi unuttum?</a>
										</span>
									</div>
								</div>
		
								<div class="col-12">
									<button class="default-btn" type="submit" name="login">
										Giriş Yap
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>