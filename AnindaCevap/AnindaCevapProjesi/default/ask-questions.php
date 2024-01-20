<?php
include("header.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("connection.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM category";
$result = mysqli_query($baglanti, $sql);

$categories = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[$row['category_id']] = $row['category_type'];
    }
} else {
    echo "Veri bulunamadı";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (empty($_POST['Title']) || empty($_POST['Category']) || empty($_POST['Description'])) {
      
    } else {
        $title = mysqli_real_escape_string($baglanti, $_POST['Title']);
        $category = mysqli_real_escape_string($baglanti, $_POST['Category']);
        $description = mysqli_real_escape_string($baglanti, $_POST['Description']);

        $selectedCategoryId = array_search($category, $categories);

        if ($selectedCategoryId !== false) {
            $insertQuery = "INSERT INTO Questions (user_id, category_id, question_title, questions, question_date) VALUES (?, ?, ?, ?, NOW())";

            if ($stmt = mysqli_prepare($baglanti, $insertQuery)) {
                mysqli_stmt_bind_param($stmt, "iiss", $user_id, $selectedCategoryId, $title, $description);

                if (mysqli_stmt_execute($stmt)) {
                    
                    $updateQuery = "UPDATE user_details SET number_of_questions = number_of_questions + 1 WHERE user_id = ?";
                    
                    if ($stmtUpdate = mysqli_prepare($baglanti, $updateQuery)) {
                        mysqli_stmt_bind_param($stmtUpdate, "i", $user_id);
                        
                        if (mysqli_stmt_execute($stmtUpdate)) {
                            echo '<div class="alert alert-success" role="alert">
                                Sorunuzu sordunuz.
                            </div>';
                        } else {
                            echo "Soru sayısı güncellenirken hata oluştu: " . mysqli_stmt_error($stmtUpdate);
                        }
                        
                        mysqli_stmt_close($stmtUpdate);
                    } else {
                        echo "Hazırlık hatası: " . mysqli_error($baglanti);
                    }
                } else {
                    echo "Veri eklenirken hata oluştu: " . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Hazırlık hatası: " . mysqli_error($baglanti);
            }
        } else {
            echo "Geçersiz kategori seçildi";
        }
    }
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
                    <form class="your-answer-form" method="POST">
                        <div class="form-group">
                            <h3>Bir Soru Oluşturun</h3>
                        </div>

                        <div class="form-group">
                            <label>Başlık</label>
                            <input type="text" class="form-control <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST['Title'])) echo 'is-invalid'; ?>" name="Title" id="Title" >
                            <div class="invalid-feedback">Başlık alanı boş bırakılamaz.</div>
                        </div>

                        <div class="form-group">
                            <label for="Category">Kategori</label>
                            <select class="form-select form-control <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST['Category'])) echo 'is-invalid'; ?>" name="Category" id="Category" aria-label="Default select example" required>
                                <option selected disabled>Kategori Seç</option>
                                <?php
                                foreach ($categories as $categoryId => $category) {
                                    echo "<option value='" . htmlspecialchars($category) . "'>" . htmlspecialchars($category) . "</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Lütfen bir kategori seçin.</div>
                        </div>

                        <div class="form-group">
                            <label>Açıklama</label>
                            <textarea class="form-control <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST['Description'])) echo 'is-invalid'; ?>" name="Description" id="Description" ></textarea>
                            <div class="invalid-feedback">Açıklama alanı boş bırakılamaz.</div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="default-btn" name="submit">Yanıtınızı Gönderin</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php include("rightsidebar.php");?>
        </div>
    </div>
</div>


<!-- End Mail Content Area -->

<?php include("footer.php");?>

