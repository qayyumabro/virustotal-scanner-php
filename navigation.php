  <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
  <div class="container-fluid">
    <a href="<?php echo $baseUrl; ?>" class="navbar-brand"><?php echo $baseTitle; ?><sup><?php echo $version; ?></sup></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav">
                
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Scans Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="files.php"><i class="fa fa-file"></i> Files</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="upload-files.php"><i class="fa fa-plus"></i> Upload Files</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Settings</a>
        </li>
      </ul>

      <?php if(!isset($_SESSION['email'])) { ?>
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
      </ul>
      <?php } else { ?>

      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
      <?php } ?>
    </div>
  </div>
</div>