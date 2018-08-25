<?php 
include('config.php'); 
if(!isset($_SESSION['email']))
{
    header("Location: $baseUrl/login.php");
    exit();
}

$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = time() + 3600;

////////////////////////////PAGE Stuff///////////////////////////////////////
$page = 1;
if(isset($_GET['page']))
{
  $page=$_GET['page'];
}
$adjacents = 2;
$limit = $perPageRecords;

$sql = "SELECT COUNT(id) as total_pages FROM {$tbl_prefex}scans";

$query = $handler->prepare($sql);
$query->execute();
if ($row=$query->fetch()) 
{
  $total_pages=$row['total_pages'];
}
else
{
  header("Location: $baseUrl/404");
  exit; 
}

if($page) 
  $start = ($page - 1) * $limit;
else
{ 
  $page=1;
  $start=0; 
}
////////////////////////////PAGE Stuff///////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $baseTitle; ?></title>
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
        
          <h2>Scan Reports</h2>

            <table class="table table-responsive table-hover">
              <tr>
                <th style="width: 50px;">#</th>
                <th>File Name</th>
                <th style="width: 500px;">Scan Status</th>
                <th style="width: 100px;">File Size</th>
                <th style="width: 150px;">Download</th>
                <th style="width: 200px;" >Date Uploaded</th>
                <th style="width: 200px;">Delete</th>
              </tr>

          
                  <?php  
                    $sql = "SELECT t1.filehash, t1.filename, t1.filesize, t1.datetime, t1.sha256, t1.url, t2.positives, t2.total, t2.permalink,t1.status,  t2.datetime as scantime, t1.lastscantime 
                    FROM {$tbl_prefex}scans t2, {$tbl_prefex}files t1
                    WHERE t1.id = t2.file_id
                    ORDER BY t2.id desc LIMIT $start, $limit";
                    $query = $handler->prepare($sql);
                    $query->execute();
                    $rows = $query->rowCount();
                    if($rows > 0)
                    {
                      $count = $start;
                      while ($row=$query->fetch()) 
                      {
                        $filehash = $row["filehash"];
                        $filename = $row["filename"];
                        $filesize = $row["filesize"];
                        $url = $row["url"];
                        $datetime = $row["datetime"];
                        $sha256 = $row["sha256"];
                        $scantime = $row["scantime"];

                        $positives = $row["positives"];
                        $total = $row["total"];
                        $permalink = $row["permalink"];


                        $status = $row["status"];


                        $h = md5($salt . $timestamp . $filehash); 
                        $downloadLink = "$baseUrl/file.php?sig={$filehash}&h={$h}&t={$timestamp}&download=1";
                        $deleteLink = "$baseUrl/file.php?sig={$filehash}&h={$h}&t={$timestamp}&delete=1";
                        ?>
                        <tr>
                          <td><?php echo $total_pages - $count; ?></td>
                          <?php if($url!="") {?>
                          <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $filename; ?></a><br /><span class="hash"><b>sha256: </b><?php echo $sha256; ?></span></td>
                          <?php } else { ?>
                              <td><?php echo $filename; ?><br /><span class="hash"><b>sha256: </b><?php echo $sha256; ?></span></td>
                          <?php } ?>

                          <?php if($permalink!=''){ ?>
                            <?php if($positives>0){ ?>
                              <td><span style="color: red;"><i class="fa fa-flag"></i></span> Scanned <?php echo $helper->ago(strtotime($scantime)); ?>, <?php echo "$positives/$total detections"; ?>. <a href="<?php echo $permalink; ?>" target="_blank">Full Report</a></td>
                            <?php } else if($positives==0){ ?>
                              <td><span style="color: green;"><i class="fa fa-flag"></i></span> Scanned <?php echo $helper->ago(strtotime($scantime)); ?>, <?php echo "$positives/$total detections"; ?>. <a href="<?php echo $permalink; ?>" target="_blank">Full Report</a></td>
                            <?php } ?>
                          <?php } 

                                else if($status == "Oversize") echo "<td><p><i class='fa fa-flag'></i> Error: File is above 32MB</p></td>";
                                else if($status == "Error") echo "<td><p><i class='fa fa-flag'></i> Unknown error occurred while scanning this file.</p></td>";
                                else if($status == "Not Scanned") echo "<td><p><i class='fa fa-flag'></i> File not scanned yet.</p></td>";
                                else if($status == "Queued") echo "<td><p><i class='fa fa-flag'></i> File queued for scan <?php echo $helper->ago(strtotime($scantime)); ?>.</p></td>";
                                else { echo "<td>-</td>"; }
                          ?>

                          <td><?php echo $helper->size($filesize); ?></td>
                          <td><i class="fa fa-arrow-circle-down"></i> <a target="_blank" href="<?php echo $downloadLink; ?>">Download</a></td>
                          <td><?php echo $helper->ago(strtotime($datetime)); ?></td>
                          <td>
                          <i class="fa fa-times" aria-hidden="true"></i> <a href="#delete" onclick="deleteFile('<?php echo $deleteLink; ?>')">Delete</a>
                          </td>
                        </tr>
                        <?php

                        $count++;
                      }                  
                    }
                    else
                    {
                      echo '<div class="col-md-12 text-center"><p>No scan reports at this time.</p></div>';
                    }

                  ?>
              
            </table>
            <!--Pagination-->
            <?php
            /* Setup page vars for display. */
            if ($page == 0) $page = 1;          //if no page var is given, default to 1.
            $prev = $page - 1;              //previous page is page - 1
            $next = $page + 1;              //next page is page + 1
            $lastpage = ceil($total_pages/$limit);    //lastpage is = total pages / items per page, rounded up.
            $lpm1 = $lastpage - 1;            //last page minus 1
            
            /* 
              Now we apply our rules and draw the pagination object. 
              We're actually saving the code to a variable in case we want to draw it more than once.
            */

            $pagination = "";
            if($lastpage > 1)
            { 
              $pagination .= "<ul class=\"pagination pull-right\">";
              //previous button
              if ($page > 1) 
                $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$prev\">←</a></li>";
              else
                $pagination.= "<li class='page-item disabled'><a class='page-link'>←</a></li>"; 
              
              //pages 
              if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
              { 
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                  if ($counter == $page)
                    $pagination.= "<li class='page-item disabled'><a class='page-link'>$counter</a></li>";
                  else
                    $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$counter\">$counter</a></li>";
                  
                }
              }
              elseif($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
              {
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2))    
                {
                  for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                  {
                    if ($counter == $page)
                      $pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                    else
                      $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$counter\">$counter</a></li>";         
                  }
                  $pagination.= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$lpm1\">$lpm1</a></li>";
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$lastpage\">$lastpage</a></li>";   
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=1\">1</a></li>";
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=2\">2</a></li>";
                  $pagination.= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                  for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                  {
                    if ($counter == $page)
                      $pagination.= "<li class='page-item disabled'><a class='page-link'>$counter</a></li>";
                    else
                      $pagination.= "<li class='page-item'><a class='page-link'href=\"$baseUrl/index.php?page=$counter\">$counter</a></li>";         
                  }
                  $pagination.= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$lpm1\">$lpm1</a></li>";
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$lastpage\">$lastpage</a></li>";   
                }
                //close to end; only hide early pages
                else
                {
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=1\">1</a></li>";
                  $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=2\">2</a></li>";
                  $pagination.= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                  for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                  {
                    if ($counter == $page)
                      $pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                    else
                      $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$counter\">$counter</a></li>";         
                  }
                }
              }
              
              //next button
              if ($page < $counter - 1) 
                $pagination.= "<li class='page-item'><a class='page-link' href=\"$baseUrl/index.php?page=$next\">→</a></li>";
              else
                $pagination.= "<li class='page-item'><a class='page-link'>→</a></li>";
              $pagination.= "</ul>\n";   
            }

            echo $pagination;

           ?>
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
  <script type="text/javascript">
  function deleteFile(_url) 
  {
    if(confirm("Are you sure you want to delete this file?"))
    {
      $.ajax(
      {
          type: "GET",
          url: _url,
          dataType: "json",
          success: function(json) 
          {
             $.each(json,function()
              {
                  if(json.error)
                  {
                      alert(json.error);
                  }
                  if(json.success)
                  {
                      alert(json.success);
                  }
              });
          }
      });
    }
    else
    {

    }
  }
  </script>
</body>
</html>