<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("connection.php");


$sql = "SELECT * FROM category";


$result = mysqli_query($baglanti, $sql);


$categories = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category_type'];
    }
} else {
    echo "Veri bulunamadı";
}
?>


<nav class="sidebar-nav" data-simplebar="">
	<ul id="sidebar-menu" class="sidebar-menu">
	    <li>
			<a href="index.php" class="box-style active">
				<span class="menu-title">
				 <i class="ri-home-8-line"></i>
					Ana Sayfa
				</span>
			</a>
		</li>

		<?php if(isset($_SESSION['username'])) { ?>
		<li>
			<a href="ask-questions.php" class="box-style">
				<span class="menu-title">
				 <i class="ri-checkbox-circle-line"></i>
					Soru Sor
				</span>
			</a>
		</li>
		<?php } ?>

		<li>
            <a href="#" class="has-arrow box-style">
                <i class="ri-price-tag-3-line"></i>
                <span class="menu-title">
                    Kategoriler
                </span>
            </a>
            <ul class="sidemenu-nav-second-level">
                <?php
                // $categories dizisindeki her kategori için bir <li> oluştur
                foreach ($categories as $category) {
					echo '<li>
							<a href="categories.php?category=' . urlencode($category) . '">
								<span class="menu-title">' . $category . '</span>
							</a>
						  </li>';
				}
                ?>
            </ul>
        </li>											
		<li>
			<a href="all-queations.php" class="box-style">
				<span class="menu-title">
				<i class="ri-question-line"></i>
				 Sorular
				</span>
			</a>
		</li>
																							
		<li>
			<a href="most-visited.php" class="box-style">
				 <span class="menu-title">
					 <i class="ri-eye-line"></i>
					    En çok ziyaret edilenler
				 </span>
			</a>
		</li>

		<li>
			<a href="all-users.php" class="box-style">
				<span class="menu-title">
				<i class="ri-user-line"></i>
					Kullanıcılar
				</span>
			</a>
		</li>
	</ul>
</nav>							