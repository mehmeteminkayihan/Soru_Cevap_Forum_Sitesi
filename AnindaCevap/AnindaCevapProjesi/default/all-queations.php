<?php 
include("header.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("connection.php");


$sorular = array(); 


$query = "SELECT q.*, ud.display_name, ud.user_image , c.category_type, TIMESTAMPDIFF(SECOND, q.question_date, NOW()) AS diff_seconds 
          FROM Questions q 
          INNER JOIN User_details ud ON q.user_id = ud.user_id
          INNER JOIN Category c ON q.category_id = c.category_id
          ORDER BY q.question_date DESC";
$result = mysqli_query($baglanti, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $soru = array(
			'question_id' => $row['questions_id'],
			'user_id' => $row['user_id'],
            'title' => $row['question_title'],
            'content' => $row['questions'],
            'like' => $row['question_like'],
            'dislike' => $row['question_dislike'],
            'views' => $row['number_of_views'],
            'elapsed_time' => formatElapsedTime($row['diff_seconds']),
            'nickname' => $row['display_name'],
            'category' => $row['category_type'],
            'user_image' => $row['user_image'],
			'answer_count' => $row['total_responses_received']
        );

       
        $sorular[] = $soru;
    }
} else {
    echo "Henüz hiç soru yok.";
}


mysqli_close($baglanti);

// Zamanı insan-okunabilir formata dönüştüren fonksiyon
function formatElapsedTime($seconds) {
    $times = array(   
        31536000 => 'yıl',
        2592000 => 'ay',
        604800 => 'hafta',
        86400 => 'gün',
        3600 => 'saat',
        60 => 'dakika',
        1 => 'saniye'
    );

    foreach ($times as $secondsKey => $time) {
        $quotient = floor($seconds / $secondsKey);
        if ($quotient >= 1) {
            $message = $quotient . ' ' . $time;
            return $message;
        }
    }

    return 'şimdi';
}

$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 1;
$sonuclar_sayfada = 10; 

$toplam_sayfa = ceil(count($sorular) / $sonuclar_sayfada);
$baslangic_indeksi = ($sayfa - 1) * $sonuclar_sayfada;

// Sayfa dizinleme için içerikleri parçalayın
$sorular_parcali = array_slice($sorular, $baslangic_indeksi, $sonuclar_sayfada);
?>
<div class="main-content-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="sidebar-menu-wrap">
                    <div class="sidemenu-wrap d-flex justify-content-between align-items-center">
                        <h3>AC Kenar Çubuğu Menüsü</h3>
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                            <i class="ri-menu-line"></i>
                        </button>
                    </div>
                    
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Menü</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div class="left-sidebar">
                                <?php include("sidebar.php"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="middull-content">                                                
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="recent-questions" role="tabpanel" aria-labelledby="recent-questions-tab">
                            <?php foreach ($sorular_parcali as $soru): ?>
                                <div class="single-qa-box like-dislike">
                                    <div class="d-flex">
                                        <div class="link-unlike flex-shrink-0">
                                            <a >
                                            <img src="<?php echo $soru['user_image']; ?>" width="50px" alt="Image">
                                            </a>

                                            <div class="donet-like-list">
                                                <button class="like-unlink-count " disable>
                                                    <i class="ri-thumb-up-fill"></i>
                                                    <span><?php echo $soru['like']; ?></span>
                                                </button>
                                            </div>

                                            <div class="donet-like-list">
                                                <button class="like-unlink-count " disable>
                                                    <i class="ri-thumb-down-fill"></i>
                                                    <span><?php echo $soru['dislike']; ?></span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 ms-3">
                                            <ul class="graphic-design">
                                                <li>
                                                    <a href="user.php?user_id=<?php echo $soru['user_id']; ?>"><?php echo $soru['nickname']; ?></a>
                                                </li>
                                                <li>
                                                    <span><?php echo 'Yayın tarihi : '.$soru['elapsed_time'].' önce'; ?></span>
                                                </li>
                                                <li>
                                                    <span>Kategori : </span>
                                                    <a href="categories.php?category=<?php echo urlencode($soru['category']); ?>">
                                                        <?php echo $soru['category']; ?>
                                                    </a>
                                                </li>
                                            </ul>

                                            <h3>
                                                <a href="queations-details.php?question_id=<?php echo $soru['question_id']; ?>">
                                                    <?php echo $soru['title']; ?>
                                                </a>
                                            </h3>

                                            <p><?php echo substr($soru['content'], 0, 250); ?>...</p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <ul class="anser-list">
                                                    <li>
                                                        <a><?php echo $soru['answer_count']; ?> Cevap</a>
                                                    </li>
                                                    <li>
                                                        <?php echo $soru['views']; ?> Görüntülenme
                                                    </li>
                                                </ul>

                                                <?php if (isset($_SESSION['username'])): ?>
                                                    <a href="reply.php?question_id=<?php echo $soru['question_id']; ?>" class="default-btn">
                                                        Cevapla
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Oturum açılmamışsa, kullanıcıya uyarı mesajını gösteriyoruz -->
                                                    <div class="col-md-4 position-relative">
                                                        <div class="alert alert-warning" role="alert">
                                                            Cevap vermek için oturum aç !
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="pagination-area">
                                <?php if ($sayfa > 1) : ?>
                                    <a href="most-visited.php?sayfa=<?php echo $sayfa - 1; ?>" class="next page-numbers">
                                        <i class="ri-arrow-left-line"></i>
                                    </a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $toplam_sayfa; $i++) : ?>
                                    <a href="most-visited.php?sayfa=<?php echo $i; ?>" class="page-numbers <?php echo $i == $sayfa ? 'current' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($sayfa < $toplam_sayfa) : ?>
                                    <a href="most-visited.php?sayfa=<?php echo $sayfa + 1; ?>" class="next page-numbers">
                                        <i class="ri-arrow-right-line"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include("rightsidebar.php"); ?>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
