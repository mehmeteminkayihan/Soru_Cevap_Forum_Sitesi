<?php 

include("connection.php");

$sonuclar_sayfada = 10; 

if (!isset($_GET['sayfa'])) {
    $sayfa = 1; 
} else {
    $sayfa = $_GET['sayfa'];
}

$count_query = "SELECT COUNT(*) AS toplam FROM Users";
$count_result = $baglanti->query($count_query);
$count_row = $count_result->fetch_assoc();
$toplam_kayitlar = $count_row['toplam'];

$toplam_sayfalar = ceil($toplam_kayitlar / $sonuclar_sayfada);

$baslangic = ($sayfa - 1) * $sonuclar_sayfada; 


 $query = "SELECT u.* , a.authorization_name FROM Users u JOIN Authorizations a ON u.authorization_id = a.authorization_id LIMIT ?, ?";

 $stmt = $baglanti->prepare($query);
    $stmt->bind_param("ii", $baslangic, $sonuclar_sayfada);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_users_role']) && isset($_POST['user_id'])) {
        // Yetki atama işlemleri
        $userId = $_POST['user_id'];
        $newUserRole = $_POST['new_users_role'];

        switch ($newUserRole) {
            case '1':
                $authorizationId = 1; 
                break;
            case '2':
                $authorizationId = 2; 
                break;
            case '3':
                $authorizationId = 3;  
                break;
            default:
                $authorizationId = 3; 
                break;
        }

        
        $query = "UPDATE Users SET authorization_id = ? WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("ii", $authorizationId, $userId);
        $stmt->execute();

        
        header("Location: users.php"); 
        exit();
    }

    if (isset($_POST['deleteUser']) && isset($_POST['user_id'])) {
        $deleteUser = $_POST['deleteUser'];
        $userId = $_POST['user_id'];

        if ($deleteUser == '2') { 

        $query = "DELETE FROM View_Log WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $query = "DELETE FROM Reaction_Log WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        $query = "DELETE FROM Answers WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        $query = "DELETE FROM Questions WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $query = "DELETE FROM User_details WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $query = "DELETE FROM Users WHERE user_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $baglanti->close();
        }

        header("Location: users.php"); 
        exit();
    }
}



?>

<body id="page-top">
    <div id="wrapper">
        <?php include("sidebar.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
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
                        <table class="table table-striped border table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kullanıcı Adı</th>
                                    <th>E-posta</th>
                                    <th>Kayıt Tarihi</th>
                                    <th>Yetki Türü</th>
                                    <th class="text-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $index => $user): ?>
                                    <tr>
                                        <td class="sorting_asc"><?php echo $user['user_id']; ?></td>
                                        <td class="sorting_asc"><?php echo $user['user_nickname']; ?></td>
                                        <td class="sorting_asc"><?php echo $user['user_email']; ?></td>
                                        <td class="sorting_asc"><?php echo $user['registration_date']; ?></td>
                                        <td class="sorting_asc"><?php echo $user['authorization_name']; ?></td>
                                        <td class="sorting_asc text-center">
                                         <?php if ($authorization_id == 1): ?>
                                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?php echo $index; ?>" data-whatever="@mdo">Yetki Ver</button>
                                         <?php endif; ?>
                                          <div class="modal fade" id="exampleModal<?php echo $index; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $index; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel<?php echo $index; ?>">Yetki Düzenle :</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" id="editForm<?php echo $index; ?>">
                                                            <div class="form-group">
                                                              <label for="new-role-select" class="col-form-label">Yeni yetki seçin:</label>
                                                               <select class="custom-select" id="new-role-select" name="new_users_role">
                                                                 <option value="1">Admin</option>
                                                                 <option value="2">Moderatör</option>
                                                                 <option value="3">Kullanıcı</option>
                                                                  <!-- Diğer yetki seçeneklerini buraya ekleyebilirsiniz -->
                                                                </select>
                                                            </div>
                                                            <input type="hidden" id="category-id" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" form="editForm<?php echo $index; ?>" class="btn btn-primary">Değiştir</button>
                                                    </div>
                                                </div>
                                            </div>
                                         </div>
                                          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalDelete<?php echo $index; ?>" data-whatever="@mdo">Sil</button>
                                         <div class="modal fade" id="exampleModalDelete<?php echo $index; ?>" tabindex="-1" aria-labelledby="exampleModalLabelDelete<?php echo $index; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel<?php echo $index; ?>">Kullanıcı Sil:</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" id="deleteForm<?php echo $index; ?>">
                                                            <div class="form-group">
                                                               <label for="delete-user-select" class="col-form-label">Kullanıcı silinsin mi ?</label>
                                                               <select class="custom-select" id="delete-user-select" name="deleteUser">
                                                                 <option value="1">Hayır</option>
                                                                 <option value="2">Evet</option>
                                                                  <!-- Diğer yetki seçeneklerini buraya ekleyebilirsiniz -->
                                                                </select>
                                                            </div>
                                                            <input type="hidden" id="user-id" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                            <button type="submit" form="deleteForm<?php echo $index; ?>" class="btn btn-primary">Sil</button>
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
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
</body>

