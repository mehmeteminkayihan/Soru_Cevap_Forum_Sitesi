<?php
include("connection.php"); 

$username_err = $email_err = $password_err = ""; 

if (isset($_POST["signup"])) { 

    $name = $email = $password = ""; 

    // Kullanıcı adı doğrulaması yapılıyor
    if (empty($_POST["name"])) {
        $username_err = "Kullanıcı adı boş geçilemez.";
    } elseif (strlen($_POST["name"]) < 4) {
        $username_err = "Kullanıcı adı en az 4 karakterden oluşmalıdır.";
    } elseif (!preg_match('/^[a-zA-Z\d_]{4,20}$/', $_POST["name"])) {
        $username_err = "Kullanıcı adı büyük küçük harf ve rakamdan oluşmalıdır.";
    } else {
        $name = $_POST["name"];
    }

    // E-posta doğrulaması yapılıyor
    if (empty($_POST["email"])) {
        $email_err = "E-posta alanı boş geçilemez.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Geçersiz E-posta formatı.";
    } else {
        $email = $_POST["email"];
    }

    // Şifre doğrulaması yapılıyor
    if (empty($_POST["password"])) {
        $password_err = "Şifre boş bırakılamaz.";
    } elseif (strlen($_POST["password"]) < 8) {
        $password_err = "Şifre en az 8 karakter olmalıdır.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $_POST["password"])) {
        $password_err = "Şifre en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.";
    } else {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Şifre hashleniyor
    }
    
    $registration_date = date("Y-m-d H:i:s"); 
    $accept = isset($_POST["accept"]) ? 1 : 0; 
    $authorization_id = 3; 

    // Eğer hata yoksa, kullanıcı veritabanında var mı kontrol ediliyor
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $check_query = "SELECT COUNT(*) AS count_user FROM Users WHERE user_nickname = ? OR user_email = ?";
        $check_stmt = $baglanti->prepare($check_query);

        if ($check_stmt) {
            $check_stmt->bind_param("ss", $name, $email);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count_user'] > 0) { 
                echo '<div class="alert alert-danger" role="alert">
                Kullanıcı adı veya e-posta adresi zaten kullanımda.
                </div>';
            } else { 
                $insert = $baglanti->prepare("INSERT INTO Users (user_nickname, user_email, user_password, user_contract, authorization_id, registration_date) VALUES (?, ?, ?, ?, ?, ?)");

                if ($insert) {
                    $insert->bind_param("sssiss", $name, $email, $password, $accept, $authorization_id, $registration_date);
                    $execute = $insert->execute();

                    if ($execute) { // Kullanıcı başarıyla eklendiyse
                        $user_id = $insert->insert_id; 

                        
                        $insert_details_query = "INSERT INTO user_details (user_id, display_name) VALUES (?, ?)";
                        $insert_details_stmt = $baglanti->prepare($insert_details_query);

                        if ($insert_details_stmt) {
                            $insert_details_stmt->bind_param("is", $user_id, $name);
                            $insert_details_result = $insert_details_stmt->execute();

                            if ($insert_details_result) {
                                echo '<div class="alert alert-success" role="alert">
                                Kayıt Başarılı
                                </div>';
                            } else {
                                echo '<div class="alert alert-danger" role="alert">
                                Kullanıcı detayları eklenemedi.
                                </div>';
                            }

                            $insert_details_stmt->close();
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                        Kayıt başarısız.
                        </div>';
                    }

                    $insert->close();
                }
            }

            $check_stmt->close();
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Formda hatalar var, lütfen gerekli alanları düzeltin.
        </div>';
    }

    $baglanti->close();
}
?>


<div class="modal fade" id="exampleModal-2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kayıt Ol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="user-form" method="POST" name="userForm" onsubmit="return validateForm()" >
                    <div class="row">
                        
                        <!-- Kullanıcı Adı girişi -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Kullanıcı Adı</label>
                                <input class="form-control <?php if(!empty($username_err)) {echo "is-invalid"; } ?>" type="text" name="name" oninput="validateForm()">
                                <div id="usernameErr" class="invalid-feedback"><?php echo $username_err; ?></div>
                            </div>
                        </div>

                        <!-- E-posta girişi -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control <?php if(!empty($email_err)) {echo "is-invalid"; } ?>" type="text" name="email" oninput="validateForm()">
                                <div id="emailErr" class="invalid-feedback"><?php echo $email_err; ?></div>
                            </div>
                        </div>

                        <!-- Şifre girişi -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Şifre</label>
                                <input class="form-control <?php if(!empty($password_err)) {echo "is-invalid"; } ?>" type="password" name="password" oninput="validateForm()">
                                <div id="passwordErr" class="invalid-feedback"><?php echo $password_err; ?></div>
                            </div>
                        </div>

                        <!-- Kullanıcı sözleşmesi kabulü -->
                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="accept" required>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Okudum Kabul Ediyorum  <a href="privacy-policy.php">Gizlilik Politikası</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kayıt Ol Butonu -->
                        <div class="col-12">
                            <button class="default-btn" type="submit" name="signup" onclick="preventModalClose(event); validateForm()">
                                Kayıt Ol
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript kodları -->
<script>
    function validateForm() {
        var username = document.forms["userForm"]["name"].value;
        var email = document.forms["userForm"]["email"].value;
        var password = document.forms["userForm"]["password"].value;
        var accept = document.forms["userForm"]["accept"].checked;

        var error = false;

        // Kullanıcı adı doğrulama
        if (username === "" || username.length < 4 || !username.match(/^[a-zA-Z\d_]{4,20}$/)) {
            document.getElementById("usernameErr").textContent = "Kullanıcı adı uygun değil.";
            error = true;
        } else {
            document.getElementById("usernameErr").textContent = "";
        }

        // Email doğrulama
        if (email === "" || !email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
            document.getElementById("emailErr").textContent = "Geçerli bir e-posta adresi girin.";
            error = true;
        } else {
            document.getElementById("emailErr").textContent = "";
        }

        // Şifre doğrulama
        if (password === "" || password.length < 8 || !password.match(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)) {
            document.getElementById("passwordErr").textContent = "Geçerli bir şifre girin.";
            error = true;
        } else {
            document.getElementById("passwordErr").textContent = "";
        }

        // Kullanıcı sözleşmesi kabulü doğrulama
        if (!accept) {
            document.getElementById("acceptErr").textContent = "Kullanıcı sözleşmesini kabul edin.";
            error = true;
        } else {
            document.getElementById("acceptErr").textContent = "";
        }

        if (error) {
			function preventModalClose(event) {
                 event.preventDefault(); // Varsayılan işlemi (modalın kapanmasını) engeller
              }
            return false; // Formun gönderilmesini engeller
        } else {
            // Tüm alanlar doğrulandı, formu gönder
            return true;
        }
    }
</script>