<?php
include('config.php');
$message = "";

if(isset($_SESSION['email']))
{
    header("Location: $baseUrl");
    exit();
}

if(!empty($_POST))
{
    if(isset($_POST['email']) && isset($_POST['password']))
    {
        $email = $_POST['email'];
        $password = sha1($_POST['password']);

        $sql = "SELECT * from {$tbl_prefex}settings WHERE email=:email AND password=:password";

        $query = $handler->prepare($sql);
        $query->execute(array(
            ':email' => $email,
            ':password' => $password
        ));
        if($row = $query->fetch())
        {
            $email = $row["email"]; 
            $_SESSION["email"] = $email;
            header("Location: $baseUrl");
            exit();
        }
        else
        {
          $message = "<span class='error'> Email/Password error.</span>";
        }
    }
    else
    {
        $message = "<span class='error'>Request token missing.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login - <?php echo $baseTitle; ?></title>
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
        
            <h2>Login</h2>
            <div class="row">
              <div class="col-md-4"></div>
              <div class="col-md-4">
                <div class="card">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Login into dashboard</h5>
                    <?php if($message!="") echo "<div class='msg'><p>$message</p></div>"; ?>

                    <form method="post" action="login.php">
                       <label>Email</label>
                      <input type="email" name="email" class="form-control" placeholder="Email">

                      <br>
                      <label>Password</label>
                      <input type="password" name="password" class="form-control" placeholder="Password">
            
                      <br>
                      <input type="submit" class="btn btn-success pull-right" name="Submit" value="Login">                     
                    </form>



                  </div>
                </div>
              </div></div>
              <div class="col-md-4"></div>
            
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