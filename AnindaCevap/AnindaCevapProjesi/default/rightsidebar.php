<?php include("connection.php");

$sql = "SELECT * FROM Questions ORDER BY total_responses_received DESC LIMIT 4";


$result = mysqli_query($baglanti, $sql);


$questionsArray = array();


if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $questionsArray[] = $row;
    }
} else {
    echo "Veri bulunamadı.";
}

$sql = "SELECT q.*, u.display_name AS nickname 
        FROM Questions q 
        INNER JOIN User_details u ON q.user_id = u.user_id 
        ORDER BY q.question_date DESC LIMIT 4";


$result = mysqli_query($baglanti, $sql);


$recentQuestionsArray = array();


if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $recentQuestionsArray[] = $row;
    }
} else {
    echo "Veri bulunamadı.";
}

$sql = "SELECT SUM(number_of_views) AS TotalViews FROM Questions";
$result = mysqli_query($baglanti, $sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $toplamGoruntuleme = $row['TotalViews'];
} else {
    echo "Sorgu sonucu bulunamadı";
}

$sql = "SELECT 
COUNT(DISTINCT q.questions_id) AS ToplamSoruSayisi,
COUNT(DISTINCT a.questions_id) AS ToplamCevapSayisi,
COUNT(DISTINCT u.user_id) AS ToplamUyeSayisi
FROM Users u
LEFT JOIN Questions q ON u.user_id = q.user_id
LEFT JOIN Answers a ON u.user_id = a.user_id";

$result = mysqli_query($baglanti, $sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $toplamSoru = $row["ToplamSoruSayisi"];
    $toplamCevap = $row["ToplamCevapSayisi"];
    $toplamUye = $row["ToplamUyeSayisi"];
} else {
    echo "Sorgu sonucu bulunamadı";
}

mysqli_close($baglanti);

?>
<div class="col-lg-3">
    <div class="right-siderbar">
        <div class="right-siderbar-common">
            <div class="discussions">
                <h3><i class="ri-speaker-line"></i>Popüler Tartışmalar</h3>
                <ul>
                    <?php foreach ($questionsArray as $question): ?>
                        <li>
                            <a href="queations-details.php?question_id=<?php echo $question['questions_id']; ?>">
                                <?php echo $question["question_title"] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>	
                </ul>
            </div>
        </div>

        <div class="right-siderbar-common">
            <div class="answer-count">
                <ul class="d-flex flex-wrap">
                    <li><span>Sorular</span><span class="count"><?php echo $toplamSoru ?></span></li>
                    <li><span>Cevaplar</span><span class="count"><?php echo $toplamCevap ?></span></li>
                    <li><span>Kullanıcılar</span><span class="count"><?php echo $toplamUye ?></span></li>
                    <li><span>Görüntülenme</span><span class="count"><?php echo  $toplamGoruntuleme ?></span></li>
                </ul>
            </div>
        </div>

        <div class="right-siderbar-common">
            <div class="recent-post">
                <h3><i class="ri-discuss-line"></i>Son Gönderiler</h3>
                <ul>
                    <?php foreach ($recentQuestionsArray as $recentquestion): ?>
                        <li>
                            <a href="queations-details.php?question_id=<?php echo $recentquestion['questions_id']; ?>">
							  <b> <?php echo $recentquestion["question_title"]; ?> </b>
                            </a>
                            <div>
                                <a href="user.php?user_id=<?php echo $recentquestion['user_id']; ?>">
								   Kullanıcı adı:  <?php echo $recentquestion['nickname']; ?>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>	
                </ul>
            </div>
        </div>
    </div>
</div>
