<?php
include("connection.php");

$company_about = '';
$company_phonenumber = '';
$company_email = '';
$company_address = '';
$linkedin = '';
$privacy_policy = '';


$query = "SELECT * FROM Company_information WHERE authorization_id = 1"; 
$result = mysqli_query($baglanti, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $company_about = $row['company_about'];
    $company_phonenumber = $row['company_phonenumber'];
    $company_email = $row['company_email'];
    $company_address = $row['company_address'];
    $linkedin = $row['Linkedin'];
    $privacy_policy = $row['privacy_policy'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $company_about = nl2br($_POST['company_about']);
    $company_phonenumber = $_POST['company_phonenumber'];
    $company_email = $_POST['company_email'];
    $company_address = $_POST['company_address'];
    $linkedin = $_POST['linkedin'];
    $privacy_policy = nl2br($_POST['privacy_policy']);

    
    $check_query = "SELECT * FROM Company_information WHERE authorization_id = 1";
    $check_result = mysqli_query($baglanti, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      
        $update_query = "UPDATE Company_information SET 
                        company_about = '$company_about', 
                        company_phonenumber = '$company_phonenumber', 
                        company_email = '$company_email', 
                        company_address = '$company_address', 
                        Linkedin = '$linkedin', 
                        privacy_policy = '$privacy_policy' 
                        WHERE authorization_id = 1"; 

        if (mysqli_query($baglanti, $update_query)) {

            header("Location: company.php");
            exit(); 
        } else {
            echo "Güncelleme işlemi başarısız oldu: " . mysqli_error($baglanti);
        }
    } else {
      
        $insert_query = "INSERT INTO Company_information (authorization_id, company_about, company_phonenumber, company_email, company_address, Linkedin, privacy_policy) 
                        VALUES (1, '$company_about', '$company_phonenumber', '$company_email', '$company_address', '$linkedin', '$privacy_policy')";

        if (mysqli_query($baglanti, $insert_query)) {
            header("Location: company.php");
            exit();
        } else {
            echo "Ekleme işlemi başarısız oldu: " . mysqli_error($baglanti);
        }
    }

    mysqli_close($baglanti);
}
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include("sidebar.php"); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include("navbar.php");
                if (isset($_SESSION['user_id'])) {
                    $authorization_id = $_SESSION['authorization_id'];
                } else {	
                    exit();
                }
                ?>
                <div id="content">
                    <div class="container-fluid">
                        <form method="POST">
                         <?php if ($authorization_id == 1): ?>
                          <button type="submit" class="btn btn-success">Güncelle</button>
                         <?php endif; ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Telefon numarası :</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="company_phonenumber" value="<?php echo $company_phonenumber ; ?>">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">E-posta adresi :</span>
                                </div>
                                <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="company_email" value="<?php echo $company_email ; ?>">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Şirket adresi :</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="company_address" value="<?php echo $company_address ; ?>">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Linkedln :</span>
                                </div>
                                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" name="linkedin" value="<?php echo $linkedin ; ?>">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Şirket hakkında :</span>
                                </div>
                                <textarea class="form-control" aria-label="With textarea" name="company_about" style="resize: vertical;  min-height: 500px;" ><?php echo $company_about; ?></textarea> 
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Gizlilik Sözleşmesi :</span>
                                </div>
                                <textarea class="form-control" aria-label="With textarea" name="privacy_policy" style="resize: vertical;  min-height: 500px; "  ><?php echo $company_about; ?></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
</body>

