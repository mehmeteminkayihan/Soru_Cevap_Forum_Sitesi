<?php include("connection.php");

$sonuclar_sayfada = 10; 

if (!isset($_GET['sayfa'])) {
    $sayfa = 1; 
} else {
    $sayfa = $_GET['sayfa'];
}

$count_query = "SELECT COUNT(*) AS toplam FROM Category";
$count_result = $baglanti->query($count_query);
$count_row = $count_result->fetch_assoc();
$toplam_kayitlar = $count_row['toplam'];

$toplam_sayfalar = ceil($toplam_kayitlar / $sonuclar_sayfada);

$baslangic = ($sayfa - 1) * $sonuclar_sayfada; 

    $query = "SELECT * FROM Category LIMIT ?, ?";

    $stmt = $baglanti->prepare($query);
    $stmt->bind_param("ii", $baslangic, $sonuclar_sayfada);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $categories = $result->fetch_all(MYSQLI_ASSOC);

    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['new_category_name']) && isset($_POST['category_id'])) {
            $newCategoryName = $_POST['new_category_name'];
            $categoryId = $_POST['category_id'];
    
            $query = "UPDATE Category SET category_type = ? WHERE category_id = ?";
            $stmt = $baglanti->prepare($query);
            $stmt->bind_param("si", $newCategoryName, $categoryId);
            $stmt->execute();
    
           
            header("Location: category.php");
            exit();
        }
   
        
        if (isset($_POST['new_category_name'])) {
            $newCategoryName = $_POST['new_category_name'];
    
            $check_query = "SELECT category_id FROM Category WHERE category_type = ?";
            $check_stmt = $baglanti->prepare($check_query);
            $check_stmt->bind_param("s", $newCategoryName);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
    
            if ($check_result->num_rows === 0) {
                $insert_query = "INSERT INTO Category (category_type) VALUES (?)";
                $insert_stmt = $baglanti->prepare($insert_query);
                $insert_stmt->bind_param("s", $newCategoryName);
                $insert_stmt->execute();
    
                header("Location: category.php");
                exit();
            } else {
                $error_message = '<div class="alert alert-danger" role="alert">Bu kategori zaten var.</div>';
            }
        }
    
        if (isset($_POST['deleteCategory']) && isset($_POST['category_id'])) {
            $deleteCategory = $_POST['deleteCategory'];
            $delCategory = $_POST['category_id'];
    
          
            $update_query = "UPDATE questions SET category_id = 6 WHERE category_id = ?";
            $stmt = $baglanti->prepare($update_query);
            $stmt->bind_param("i", $delCategory);
            $stmt->execute();
    
            
            $delete_query = "DELETE FROM Category WHERE category_id = ?";
            $stmt_delete = $baglanti->prepare($delete_query);
            $stmt_delete->bind_param("i", $delCategory);
            $stmt_delete->execute();
    
            header("Location: category.php");
            exit();
        }
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
              <?php include("navbar.php"); ?>
               <?php echo $error_message; ?>
                <div id="content">
                  <div class="container-fluid">
                       <div style="text-align: right;">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Kategori Ekle</button>
                       </div>
                     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Kategori Ekle:</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="category.php">
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Kategori ismi :</label>
                                            <input type="text" class="form-control" id="recipient-name" name="new_category_name">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Ekle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                     </div>
                     <table class="table table-striped border table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kategori Adı</th>
                                <th class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $index => $category): ?>
                                <tr>
                                    <td class="sorting_asc" style="width: 10px;"><?php echo $category['category_id']; ?></td>
                                    <td class="sorting_asc" style="width: 300px;"><?php echo $category['category_type']; ?></td>
                                    <td class="sorting_asc text-center" style="width: 10px;">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?php echo $index; ?>" data-whatever="@mdo">Düzenle</button>
                                        <div class="modal fade" id="exampleModal<?php echo $index; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $index; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel<?php echo $index; ?>">Kategori Düzenle :</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" id="editForm<?php echo $index; ?>">
                                                            <div class="form-group">
                                                                <label for="recipient-name" class="col-form-label">Yeni isim :</label>
                                                                <input type="text" class="form-control" id="recipient-name" name="new_category_name" value="<?php echo $category['category_type']; ?>">
                                                            </div>
                                                            <input type="hidden" id="category-id" name="category_id" value="<?php echo $category['category_id']; ?>">
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" form="editForm<?php echo $index; ?>" class="btn btn-primary">Değiştir</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalDelete<?php echo $index; ?>">Sil</button>
                                        <div class="modal fade" id="exampleModalDelete<?php echo $index; ?>" tabindex="-1" aria-labelledby="exampleModalLabelDelete<?php echo $index; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLabel<?php echo $index; ?>">Kategori</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Kategori silinsin mi ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        
                                                        <form method="POST" id="deleteForm<?php echo $index; ?>" >
                                                           <input type="hidden" name="deleteCategory" value="true">
                                                           <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                                           <button type="submit" class="btn btn-danger">Sil</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                     </table>
                        <div class="sayfalama">
                            <?php
                            echo "<form method='GET'>";
                            for ($i = 1; $i <= $toplam_sayfalar; $i++) {
                                echo "<button type='submit' name='sayfa' value='" . $i . "' class='btn btn-primary'>" . $i . "</button>";
                            }
                            echo "</form>";                            
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
</body>

</html>