<?php
include("connection.php");


$query = "SELECT * FROM Contact ORDER BY contact_date DESC";

$result = mysqli_query($baglanti, $query);


$company_info = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
      
        while ($row = mysqli_fetch_assoc($result)) {
            $company_info[] = array(
                'name' => $row["contact_nick"],
                'email' => $row["contact_email"],
                'title' => $row["contact_title"],
                'message' => $row["contact_message"],
                'date' => $row["contact_date"],
            );
        }
    } else {
        echo "Tabloda herhangi bir kayıt bulunamadı.";
    }
} else {
    echo "Sorgu hatası: " . mysqli_error($baglanti);
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
                                    <th>İsim</th>
                                    <th>E-posta</th>
                                    <th>Konu</th>
                                    <th>Mesaj</th>
                                    <th>Gönderilme tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($company_info as $company) : ?>
                                    <tr>
                                        <td class="sorting_asc" style="width: 150px;"><?php echo $company['name']; ?></td>
                                        <td class="sorting_asc" style="width: 150px;"><?php echo $company['email']; ?></td>
                                        <td class="sorting_asc" style="max-width: 150px"><?php echo $company['title']; ?></td>
                                        <td class="sorting_asc" style="max-width: 150px"><?php echo $company['message']; ?></td>
                                        <td class="sorting_asc" style="width: 200px;"><?php echo $company['date']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
</body>
