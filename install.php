<?php

  $not_installed = "";
  $installed = "";

  if (!defined('PDO::ATTR_DRIVER_NAME')) {
    $not_installed .= '<p>PDO not installed</p>';
  }
  else
  {
    $installed .= '<p>PDO is installed</p>';
  }

  if (function_exists('mysqli_connect')) {
    $installed .= '<p>MySQLi is installed</p>';
  }
  else
  {
    $not_installed .= '<p>MySQLi not installed</p>';
  }

  $tbl_prefex = "vt_";
  $dbhost = 'localhost';
  $dbname = '';
  $dbuser = '';
  $dbpass = '';
  $baseUrl  = (( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
  $message = "";

  if(!empty($_POST) && $not_installed =="")
  {
      $tbl_prefex = $_POST["tbl_prefex"];
      $dbhost = $_POST["dbhost"];
      $dbname = $_POST["dbname"];
      $dbuser = $_POST["dbuser"];
      $dbpass = $_POST["dbpass"];

      try
      {
        $handler = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e) 
      {
        $message = '<p class="error">Unable to connect to database ' . $e->getMessage().", please make sure provided info is correct.</p>";
      }

      try
      {
        $dbfile = "<?php".PHP_EOL;
        $dbfile .= '$tbl_prefex = "'.$tbl_prefex.'";'.PHP_EOL;
        $dbfile .= '$dbhost = "'.$dbhost.'";'.PHP_EOL;
        $dbfile .= '$dbname = "'.$dbname.'";'.PHP_EOL;
        $dbfile .= '$dbuser = "'.$dbuser.'";'.PHP_EOL;
        $dbfile .= '$dbpass = "'.$dbpass.'";'.PHP_EOL;
        $dbfile .= '$baseUrl = "'.$baseUrl.'";'.PHP_EOL;

        $dbfile .= "try{".PHP_EOL;
        $dbfile .= '$handler = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);'.PHP_EOL;
        $dbfile .= '$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);'.PHP_EOL;
        $dbfile .= "}".PHP_EOL;
        $dbfile .= 'catch (PDOException $e)'.PHP_EOL;
        $dbfile .= "{".PHP_EOL;
        $dbfile .= 'die("unable to connect to database " . $e->getMessage());'.PHP_EOL;
        $dbfile .= "}".PHP_EOL;
        $dbfile .= "?>";

        $fp=fopen('db.php','w');
        fwrite($fp, $dbfile);
        fclose($fp);
      }

      catch(Exception $e)
      {
        $message = '<p class="error">Couldn\'t create <b>db.php file, please rename db.config.php file to db.php and edit database info manually.</b></p>';
      }

      try
      {
        $sql = file_get_contents('db.sql');
        $sql = str_replace('{{prefix}}', $tbl_prefex, $sql);

        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if (mysqli_connect_errno()) { /* check connection */
            $message = "<p class='error'>Connect failed for MySQLi</p>";
        }

        /* execute multi query */
        if ($mysqli->multi_query($sql)) {
            //echo "success";
        } else {
           //echo "error";
        }
      }
      catch(Exception $e)
      {
        $message = '<p class="error">Couldn\'t import databse, please use db.config.sql file to import it manually.</b></p>';
      }

     if($message =="") $message = "<p class='success'>Script installed successfully.</p>";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>VirusTotal Scanner Installation</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet"/> 
  <link href="css/style.css?v=10" rel="stylesheet"/>
  <link href="css/font-awesome.min.css" rel="stylesheet"/>
  <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
  <![endif]-->
  <link rel="icon" type="image/x-icon" sizes="16x16" href="icons/16x16.ico">
  <link rel="icon" type="image/x-icon" sizes="32x32" href="icons/32x32.ico">
  <link rel="apple-touch-icon" sizes="57x57" href="icons/57x57.ico">
  <link rel="apple-touch-icon" sizes="60x60" href="icons/60x60.ico">
  <link rel="apple-touch-icon" sizes="72x72" href="icons/72x72.ico">
  <link rel="apple-touch-icon" sizes="76x76" href="icons/76x76.ico">
  <link rel="icon" type="image/x-icon" sizes="96x96" href="icons/96x96.ico">
  <link rel="apple-touch-icon" sizes="114x114" href="icons/114x114.ico">
  <link rel="apple-touch-icon" sizes="120x120" href="icons/120x120.ico">
  <link rel="apple-touch-icon" sizes="144x144" href="icons/144x144.ico">
  <meta name="msapplication-TileColor" content="#ffffff">
  <link name="msapplication-TileImage" content="icons/144x144.ico">
  <meta name="theme-color" content="#ffffff">
  <link rel="apple-touch-icon" sizes="152x152" href="icons/152x152.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="icons/180x180.ico">
  <link rel="icon" type="image/x-icon" sizes="192x192" href="icons/192x192.ico">
  <link rel="icon" type="image/x-icon" sizes="128x128" href="icons/128x128.ico">
  <link rel="icon" type="image/x-icon" sizes="256x256" href="icons/256x256.ico">
  <link rel="icon" type="image/x-icon" sizes="512x512" href="icons/512x512.ico">

</head>
<body>

    <main role="main">

      <div class="container-fluid">
        
      <div class="row">
        <div class="col-md-3">
          
        </div>
        <div class="col-md-3">
              <div class="card">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Installation settings</h5>
                    <?php 
                    if($not_installed ==""){
                    ?>
                    <?php if($message!="") echo "<div class='msg'><p>$message</p></div>"; ?>

                    <form method="post" action="install.php">
                       <label>Database name</label>
                      <input type="text" name="dbname" class="form-control" placeholder="Database Name" value="<?php echo $dbname; ?>">

                      <br>
                      <label>Database host</label>
                      <input type="text" name="dbhost" class="form-control" placeholder="Database host" value="<?php echo $dbhost; ?>">

                      <br>
                      <label>Database user</label>
                      <input type="text" name="dbuser" class="form-control" placeholder="Database user" value="<?php echo $dbuser; ?>">


                      <br>
                      <label>Database password</label>
                      <input type="text" name="dbpass" class="form-control" placeholder="Database password" value="<?php echo $dbpass; ?>">

                      <br>
                      <label>Table prefix (optional)</label>
                      <input type="text" name="tbl_prefex" class="form-control" placeholder="Table prefix" value="<?php echo $tbl_prefex; ?>">

                      <br>
                      <label><b>Base Url</b> (for example http://mysite.com/vt-scanner)</label>
                      <p><i>This is important as link system in software is based on this. Don't include trailing slash.</i></p>
                      <input type="text" name="baseUrl" class="form-control" placeholder="Base url" value="<?php echo $baseUrl; ?>">

                      <hr>               
                    
                      <input type="submit" class="btn btn-success pull-right" name="Submit" value="Install">                     
                    </form>

                    <?php }else{

                      echo "This script needs PDO and MySQLi extensions installed to run.";

                      } ?>

                  </div>
                </div>
              </div>

              <p class="text-center">VirusTotal Scanner Installation</p>


        </div>
        <div class="col-md-3">

          <?php 
            if($not_installed!="")
            {
              ?>
              <div class="alert alert-danger">
              <?php echo $not_installed; ?>
              </div>
              <?php
            }
           ?>

          <?php 
            if($installed!="")
            {
              ?>
              <div class="alert alert-success">
              <?php echo $installed; ?>
              </div>
              <?php
            }
           ?>
          <div class="alert alert-info">
            <ul>
              <li><p>Default account login is</p>
              <ul>
                <li><i>Email: </i>admin@admin.com</li>
                <li><i>Password: </i>admin</li>
              </ul>
              </li>
              <hr>
              <li>You need an API Key from <a href="" target="_blank">virustotal.com</a>. You have to set this in software settings after installation.</li>
              <hr>
              <li>Remove <b>install.php</b>, <b>db.config.php</b>, <b>db.config.sql</b> and <b>db.sql</b> files after installation.</li>
            </ul>
          </div>

          
        
        </div>
        <div class="col-md-3">
          
        </div>
      </div>

      </div> <!-- /container-fluid -->

    </main>

  <script src="js/jquery-3.1.1.min.js"></script>   
  <script src="js/tether.min.js"></script>
  <!--script src="js/popper.min.js"></script-->
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">

  </script>
</body>
</html>