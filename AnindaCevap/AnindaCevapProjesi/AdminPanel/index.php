<?php
include("connection.php");


$sonuclar_sayfada = 10;

if (!isset($_GET['sayfa'])) {
    $sayfa = 1; 
} else {
    $sayfa = $_GET['sayfa'];
}

$count_query = "SELECT COUNT(*) AS toplam FROM Questions";
$count_result = $baglanti->query($count_query);
$count_row = $count_result->fetch_assoc();
$toplam_kayitlar = $count_row['toplam'];

$toplam_sayfalar = ceil($toplam_kayitlar / $sonuclar_sayfada);

$baslangic = ($sayfa - 1) * $sonuclar_sayfada;

$query = "SELECT q.*, display_name, category_type FROM Questions q 
          JOIN User_details u ON q.user_id = u.user_id 
          JOIN Category c ON q.category_id = c.category_id 
          LIMIT ?, ?"; 

$stmt = $baglanti->prepare($query);
$stmt->bind_param("ii", $baslangic, $sonuclar_sayfada);
$stmt->execute();

$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteQuestion']) && isset($_POST['questions_id'])) {
        $deleteQuestion = $_POST['deleteQuestion'];
        $questionId = $_POST['questions_id'];

            
            $query = "DELETE FROM View_Log WHERE questions_id = ?";
            $stmt = $baglanti->prepare($query);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();

            $query = "DELETE FROM Reaction_Log WHERE questions_id = ?";
            $stmt = $baglanti->prepare($query);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();

            $query = "DELETE FROM Answers WHERE questions_id = ?";
            $stmt = $baglanti->prepare($query);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();

            $query = "DELETE FROM Questions WHERE questions_id = ?";
            $stmt = $baglanti->prepare($query);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();
        
        header("Location: index.php");
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
                <div id="content">
                    <div class="container-fluid">
                        <table class="table table-striped border table-bordered">
                            <thead>
                                <tr>
                                    <th>Kullanıcı Adı</th>
                                    <th>Kategori Türü</th>
                                    <th>Başlık</th>
                                    <th>Soru</th>
                                    <th>Soru Tarihi</th>
                                    <th class="text-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($questions as $index => $question) : ?>
                                    <tr>
                                        <td class="sorting_asc" style="width: 150px;"><?php echo $question['display_name']; ?></td>
                                        <td class="sorting_asc" style="width: 150px;"><?php echo $question['category_type']; ?></td>
                                        <td class="sorting_asc" style="max-width: 150px"><?php echo $question['question_title']; ?></td>
                                        <td class="sorting_asc" style="max-width: 150px"><?php echo $question['questions']; ?></td>
                                        <td class="sorting_asc" style="width: 200px;"><?php echo $question['question_date']; ?></td>
                                        <td class="sorting_asc text-center" style="width: 150px;">
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalDelete<?php echo $index; ?>" data-whatever="@mdo">Sil</button>
                                            <div class="modal fade" id="exampleModalDelete<?php echo $index; ?>" tabindex="-1" aria-labelledby="exampleModalLabelDelete<?php echo $index; ?>" aria-hidden="true">
                                             <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel<?php echo $index; ?>">Soru Sil:</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                       Soru silinsin mi ?
                                                    </div>
                                                    <div class="modal-footer">
                                                      <form method="POST" id="deleteForm<?php echo $index; ?>">
                                                        <input type="hidden" name="deleteQuestion" value="true">
                                                        <input type="hidden" name="questions_id" value="<?php echo $question['questions_id']; ?>">
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
                        <!-- Sayfalama bağlantıları -->
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

