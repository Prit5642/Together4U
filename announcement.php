<?php
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$response = array('success' => false, 'message' => '');
$clubname = $_SESSION['clubname'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $announcement = $_POST['announcement'];
    $clubName = $_SESSION['clubname'];
    
    // Insert announcement into database
    $sql = "INSERT INTO announcements (announcement_text, club_name) VALUES ('$announcement', '$clubName')";
    if (mysqli_query($conn, $sql)) {
        $response['success'] = true;
        $response['message'] = 'Message sent successfully!';
    } else {
        $response['message'] = 'Error: ' . $conn->error;
    }
}

$sql = "SELECT * FROM announcements where club_name='$clubname' ORDER BY announcement_id DESC";
$result = mysqli_query($conn, $sql);
?>


<?php include('includes/header.php');?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>

  <?php include('includes/sidebar.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
  <?php include('includes/pagetitle.php');?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <?php if ($response['success']): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success</h5>
                    <?php echo $response['message']; ?>
                </div>
            <?php elseif (!empty($response['message'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error</h5>
                    <?php echo $response['message']; ?>
                </div>
            <?php endif; ?>

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-keyboard"></i> Make an Announcement</h3>
              </div>

              <form method="post" action="">

                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <textarea name="announcement" rows="4" placeholder="Enter your announcement here" style="width: 100%;"></textarea>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Send Annoucement</button>
                </div>
              </form>
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-keyboard"></i> All Announcements</h3>
              </div>

              <!-- <form method="post" action="">

                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <textarea name="announcement" rows="4" placeholder="Enter your announcement here" style="width: 100%;"></textarea>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Send Annoucement</button>
                </div>
              </form> -->


              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th>Description</th>
                          <th>Time</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                      $counter = 1;
                      $clubname = $_SESSION['clubname'];
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>{$row['announcement_id']}</td>";
                          echo "<td>{$row['announcement_text']}</td>";
                          echo "<td>{$row['created_at']}</td>";
                          echo "</tr>";
                          $counter++;
                      }
                      ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong> &copy; <?php echo date('Y');?> Together4U</a> -</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Developed By</b> <a href="#">Prit Shah</a> and <a href="#">Vedant Patel</a>
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<?php include('includes/footer.php');?>
</body>
</html>