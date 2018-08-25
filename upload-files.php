<?php 
include('config.php'); 
if(!isset($_SESSION['email']))
{
    header("Location: $baseUrl/login.php");
    exit();
}

$data['token'] = md5(uniqid(rand(), true));
$_SESSION['token'] = $data['token'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Upload Files  - <?php echo $baseTitle; ?></title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet"/> 
  <link href="css/style.css?v=10" rel="stylesheet"/>
  <link href="css/font-awesome.min.css" rel="stylesheet"/>
  <link href="css/dropzone.css" rel="stylesheet"/>
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
            <h2>Upload Files</h2>
            <div class="row">
            <div class="col-md-3"></div>
              <div class="col-md-6">
              <p>Upload from your computer. (max: <?php echo $maxFileUploads; ?>)</p>
                <form action="upload.php?bylocal" method="post" class="dropzone" id="dropzoneFileUpload">
                  <p class="text-center  my-auto tools-form">Drop file or click to browse</p>
                  <input type="hidden" name="token" value="<?php echo $data['token']; ?>">
                </form> 
                <div id="files"></div>
                <hr>
                 <p>One url per line (max: <?php echo $maxFileUploads; ?>)</p>                        

                 <form id="urlsform">
                  <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>" />
                  <textarea name="urls" rows="10" class="form-control"></textarea>
                </form>                      

                <br>
                <button class="btn btn-success" id="upload" >Upload Files</button>           
              </div>
              <div class="col-md-3"></div>
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
  <script src="js/dropzone.js"></script>
  <script type="text/javascript">
    var urls;
    var total=0;
    var index=0;
    Dropzone.autoDiscover = false;
    var fileIndex = 0;    
    var filename = "";
    var token = "<?php echo $data["token"]; ?>";
    Dropzone.options.dropzoneFileUpload = 
    {
        url: "upload.php?bylocal",
        maxFiles: <?php echo $maxFileUploads; ?>,
        uploadMultiple: true,
        addRemoveLinks: true,
        paramName: "files",
        maxFilesize: 100,
        previewsContainer: false,
        params: {
            _token: token
        },
        init: function() {
            this.on("addedfile", function(file) {
              filename = file.name;
            }),
            
            this.on("sending", function(file) {
              $('#files').html("<i class='fa fa-spinner fa-spin'></i>");

            });

            this.on("successmultiple", function(file, responseText) {
                if (responseText.success) 
                {
                  $('#files').css("display", "block");
                  $('#files').html('<p>'+responseText.success+'</p><br />');
                }
                if (responseText.error) 
                {
                  $('#files').css("display", "block");
                  $('#files').html('<p>'+responseText.error+'</p><br />');
                }
            });

            this.on("queuecomplete", function(file) { 
              $('#files').html('');
               alert("Files uploaded.");
            });
        }
    }

    $('#dropzoneFileUpload').dropzone();


    $('#upload').on('click', function(e)
    {
      
      $('#upload').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Uploading');

        var datastring = $("#urlsform").serialize();

        $.ajax(
        {
          type: "POST",
          url: "upload.php",
          data: datastring,
          dataType: "json",
          success: function(json) 
          {
            var data='';
            $.each(json,function()
            {
                if(json.success)
                {                           
                  alert(json.success);
                }

                else if(json.error)
                {
                  alert(json.error);
                }
                else
                {
                  alert("Unknown error occured");
                }
            });    
          },
          fail: function(xhr, textStatus, errorThrown){
              alert("Upload request failed, please try again");
          },
             complete: function(){
               $('#upload').html('Upload Files');
             }
        });
    });

    function ValidURL(str) {
      var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
      '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name and extension
      '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
      '(\\:\\d+)?'+ // port
      '(\\/[-a-z\\d%@_.~+&:]*)*'+ // path
      '(\\?[;&a-z\\d%@_.,~+&:=-]*)?'+ // query string
      '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
      return pattern.test(str);
    }

    function sleep (time) {
      return new Promise((resolve) => setTimeout(resolve, time));
    }
  </script>
</body>
</html>