<?php 
include('config.php'); 
$message ="";

if(!empty($_POST))
{
  //Updating general settings
  if(isset($_GET["general"]))
  {
    if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['timezone']) && isset($_POST['virustotalApiKey']) && isset($_POST['perPageRecords'])  && isset($_POST['vtScannerThreshold']) && isset($_POST['vtReporterThreshold']) && isset($_POST['maxFileUploads']))
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $virustotalApiKey = $_POST['virustotalApiKey'];
        $timezone = $_POST['timezone'];
        $perPageRecords = $_POST['perPageRecords'];
        $maxFileUploads = $_POST['maxFileUploads'];
        $vtScannerThreshold = $_POST['vtScannerThreshold'];
        $vtReporterThreshold = $_POST['vtReporterThreshold'];

        if($password!="")
        {
          $sql = "UPDATE {$tbl_prefex}settings set email=:email, password=:password, virustotalApiKey=:virustotalApiKey, timezone=:timezone, perPageRecords=:perPageRecords, vtScannerThreshold=:vtScannerThreshold, vtReporterThreshold=:vtReporterThreshold, maxFileUploads=:maxFileUploads";

          $params = array(
          ':email'=>$email,
          ':password'=>sha1($password),
          ':virustotalApiKey'=>$virustotalApiKey,
          ':timezone'=>$timezone,
          ':perPageRecords'=>$perPageRecords,
          ':vtScannerThreshold'=>$vtScannerThreshold,
          ':vtReporterThreshold'=>$vtScannerThreshold,
          ':timezone'=>$timezone,
          ':maxFileUploads'=>$maxFileUploads);

          $query = $handler->prepare($sql);
          if($query->execute($params))
          {
            date_default_timezone_set($timezone);
            $dt = new DateTime();
            $offset = $dt->format("P");
            $handler->exec("SET time_zone='$offset';");
            $message = "<span class='success'>Settings changed successfully.</span>";
          }
          else
          {
            $message = "<span class='error'>Settings not saved.</span>";
          }                      
        }

        else
        {
          $sql = "UPDATE {$tbl_prefex}settings set email=:email, virustotalApiKey=:virustotalApiKey, timezone=:timezone, perPageRecords=:perPageRecords, vtScannerThreshold=:vtScannerThreshold, vtReporterThreshold=:vtReporterThreshold, maxFileUploads=:maxFileUploads";

          $params = array(
          ':email'=>$email,
          ':virustotalApiKey'=>$virustotalApiKey,
          ':timezone'=>$timezone,
          ':perPageRecords'=>$perPageRecords,
          ':vtScannerThreshold'=>$vtScannerThreshold,
          ':vtReporterThreshold'=>$vtScannerThreshold,
          ':timezone'=>$timezone,
          ':maxFileUploads'=>$maxFileUploads);   
          $query = $handler->prepare($sql);
          if($query->execute($params))
          {
            date_default_timezone_set($timezone);
            $dt = new DateTime();
            $offset = $dt->format("P");
            $handler->exec("SET time_zone='$offset';");
            $message = "<span class='success'>Settings changed successfully.</span>";
          }
          else
          {
            $message = "<span class='error'>Settings not saved.</span>";
          } 
        }
    }
    else
    {
        $message = "<span class='error'>Important information is missing. All fields are required</span>";        
    }   
  }

  //Updating email related settings
  if(isset($_GET["email"]))
  {
    if(isset($_POST['emailEmail']) && isset($_POST['emailPassword']) && isset($_POST['stmp']) && isset($_POST['port']))
    {
        $emailEmail = $_POST['emailEmail'];
        $emailPassword = $_POST['emailPassword'];
        $stmp = $_POST['stmp'];
        $port = $_POST['port'];

        if(isset($_POST["emailNotify"]))
        {
          $sql = "UPDATE {$tbl_prefex}settings set emailEmail=:emailEmail, emailPassword=:emailPassword, stmp=:stmp, port=:port, emailNotify=:emailNotify";

          $params = array(
          ':emailEmail'=>$emailEmail,
          ':emailPassword'=>$emailPassword,
          ':stmp'=>$stmp,
          ':port'=>$port,
          ':emailNotify'=>1);   
          $query = $handler->prepare($sql);
          if($query->execute($params))
          {
            $message = "<span class='success'>Settings changed successfully.</span>";
          }
          else
          {
            $message = "<span class='error'>Settings not saved.</span>";
          }                      
        }

        else
        {
          $sql = "UPDATE {$tbl_prefex}settings set emailEmail=:emailEmail, emailPassword=:emailPassword, stmp=:stmp, port=:port, emailNotify=:emailNotify";

          $params = array(
          ':emailEmail'=>$emailEmail,
          ':emailPassword'=>$emailPassword,
          ':stmp'=>$stmp,
          ':port'=>$port,
          ':emailNotify'=>0);   
          $query = $handler->prepare($sql);
          if($query->execute($params))
          {
            $message = "<span class='success'>Settings changed successfully.</span>";
          }
          else
          {
            $message = "<span class='error'>Settings not saved.</span>";
          }   
        }
    }
    else
    {
        $message = "<span class='error'>Important information is missing. All fields are required</span>";        
    }   
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Settings - <?php echo $baseTitle; ?></title>
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
 <?php include('navigation.php'); ?>

    <main role="main">

      <div class="container-fluid">
        
        <div class="row">
        
          <div class="col-md-12">
        
            <h2>Settings</h2>
            <div class="row">
              <div class="col-md-4">
                <div class="card">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Change default settings</h5>
                    <?php if($message!="") echo "<div class='msg'><p>$message</p></div>"; ?>

                    <form method="post" action="settings.php?general">
                       <label>Email</label>
                      <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $accountEmail; ?>">

                      <br>
                      <label>Password</label>
                      <input type="password" name="password" class="form-control" placeholder="Password">

                      <br>
                      <label>Virus Total ApiKey</label>
                      <input type="text" name="virustotalApiKey" class="form-control" placeholder="Virus Total ApiKey" value="<?php echo $virustotalApiKey; ?>">


                      <br>
                      <label>Scanner Threshold - Recommended is 2</label>
                      <input type="number" name="vtScannerThreshold" class="form-control" placeholder="Scanner Threshold" value="<?php echo $vtScannerThreshold; ?>">

                      <br>
                      <label>Reporter Threshold - Recommended is 2</label>
                      <input type="number" name="vtReporterThreshold" class="form-control" placeholder="Reporter Threshold" value="<?php echo $vtReporterThreshold; ?>">

                      <br>
                      <label>Per Page Records</label>
                      <input type="number" name="perPageRecords" class="form-control" placeholder="Reporter Threshold" value="<?php echo $perPageRecords; ?>">


                      <br>
                      <label>Max File Uploads</label>
                      <input type="number" name="maxFileUploads" class="form-control" placeholder="Max File Uploads" value="<?php echo $maxFileUploads; ?>">


                      <br>
                      <label>Timezone</label>
                      <select class="form-control" name="timezone">
                         <?php  
                              foreach ($timezones as $key => $value) 
                              {
                                  if($key==$timezone)
                                      echo "<option value='$key' selected>$value</option>";
                                  else
                                      echo "<option value='$key'>$value</option>";
                              }
                          ?>
                      </select>  
                      <hr>               
                    
                      <input type="submit" class="btn btn-success pull-right" name="Submit" value="Save Settings">                     
                    </form>



                  </div>
                </div>
              </div></div>
              <div class="col-md-4">
                
                <div class="card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Email notification settings</h5>                      

                      <form method="post" action="settings.php?email">

                        <label class="custom-control custom-checkbox">
                          <?php if($emailNotify == 0) { ?>
                          <input type="checkbox" class="custom-control-input" name="emailNotify" value="1">
                          <?php } else { ?>
                            <input type="checkbox" class="custom-control-input" name="emailNotify" value="1" checked>
                          <?php } ?>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Send Email Reports</span>
                        </label>
                        <br>
                      <br>
                      <label>Email</label>
                      <input type="email" name="emailEmail" class="form-control" placeholder="Email" value="<?php echo $emailEmail; ?>">

                      <br>
                      <label>Password</label>
                      <input type="password" name="emailPassword" class="form-control" placeholder="Password" value="<?php echo $emailPassword; ?>">

                      <br>
                      <label>STMP Host (Outgoing)</label>
                      <input type="text" name="stmp" class="form-control" placeholder="STMP Host" value="<?php echo $stmp; ?>">

                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" disabled checked>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">SSL</span>
                      </label>

                      <br><br>
                      <label>STMP Port</label>
                      <input type="number" name="port" class="form-control" placeholder="STMP Port" value="<?php echo $port; ?>">
                      <hr>               
                    
                      <input type="submit" class="btn btn-success pull-right" name="Submit" value="Save Settings">     
                      </form> 
                    </div>
                  </div>
                </div>

              </div>

              <div class="col-md-4">
                
                <div class="card">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Run these cron jobs</h5>
                        <?php  
                        $cj1 = "*/5 * * * *  /usr/local/bin/php $upload/reporter.php > /dev/null 2>&1";
                        $cj2 = "*/5 * * * *  /usr/local/bin/php $upload/scanner.php > /dev/null 2>&1";                        
                        $cje1 = "0 0 * * *  /usr/local/bin/php $upload/email.php > /dev/null 2>&1";                        

                        $cj3 = "*/5 * * * *  lynx -source $baseUrl/reporter.php";
                        $cj4 = "*/5 * * * *  lynx -source $baseUrl/scanner.php";
                        $cje2 = "0 0 * * *  lynx -source $baseUrl/email.php";

                        ?>
                        <label>Cron job #1</label>
                        <input type="text"  class="form-control" placeholder="Cron job #1" value="<?php echo $cj1; ?>">

                        <br>
                        <label>Cron job #2</label>
                        <input type="text"  class="form-control" placeholder="Cron job #2" value="<?php echo $cj2; ?>">

                        <br>
                        <label>Cron job #3</label>
                        <input type="text"  class="form-control" placeholder="Cron job #3" value="<?php echo $cje1; ?>">

                        <hr>
                        <p class="text-center">or</p>
                        <hr>
                        <label>Cron job #1</label>
                        <input type="text"  class="form-control" placeholder="Cron job #1" value="<?php echo $cj3; ?>">

                        <br>
                        <label>Cron job #2</label>
                        <input type="text"  class="form-control" placeholder="Cron job #2" value="<?php echo $cj4; ?>">

                        <br>
                        <label>Cron job #3</label>
                        <input type="text"  class="form-control" placeholder="Cron job #3" value="<?php echo $cje2; ?>">
                    </div>
                  </div>
                </div>

              </div>
            
          </div>         
        </div>
      	

      </div> <!-- /container-fluid -->

    </main>

  <footer class="container-fluid">
    <p>&copy; <?php echo $baseTitle; ?></p>
  </footer>

  <script src="js/jquery-3.1.1.min.js"></script>   
  <script src="js/tether.min.js"></script>
  <!--script src="js/popper.min.js"></script-->
  <script src="js/bootstrap.min.js"></script>
</body>
</html>