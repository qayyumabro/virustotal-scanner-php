<?php  
include('config.php'); 
header('Content-Type: application/json');
if(!isset($_SESSION['email']))
{
	$data = array("error"=>"Not logged in.");
	echo json_encode($data);
	exit;
}
//make sure there are urls
if(isset($_POST['urls']))
{
	$urls = $_POST['urls'];
	if($urls == ""){
		$data = array("error"=>"No url was provided.");
		echo json_encode($data);
		exit;
	}
}

//make sure token is there  and is correct
if(!isset($_SESSION['token']) || !isset($_POST['token']))
{
	$data = array('error'=>'Token missing, refresh page and try again.');
	echo json_encode($data); 
	die();
}

if($_POST['token'] != $_SESSION['token'])
{
	$data = array('error'=>'Wrong token, refresh page and try again.');
	echo json_encode($data); 
	die();
}


//Handle uploads from urls
if(isset($_POST["urls"]))
{

	//Convert bunch of text to url array
	$text = trim($_POST['urls']);
	$textAr = explode("\n", $text);
	$textAr = array_filter($textAr, 'trim');
	$count = 0;

	foreach ($textAr as $url) 
	{
		$url = trim($url);

		//Exit loop if urls count is greater that our default setting $maxFileUploads
	    if($count > $maxFileUploads) break;

	    //Unique hash to save file on our server
		$filehash = $helper->getHash();

		$file = "$upload/$filehash";
		file_put_contents("$file", fopen("$url", 'r'));

		if(file_exists($file))
		{
			//Get file mime/type like image/png, image/gif etc
			$mime = mime_content_type($file);

			//Get filename.ext from /path/to/filname.ext
			$path = parse_url($url, PHP_URL_PATH);
			$filename = basename($path);

			$filesize = filesize($file);
			
			//Make sure file does not alreadu exist with same hash
			$sha256 = hash('sha256', @file_get_contents($file));

			$sql = "SELECT * FROM {$tbl_prefex}files WHERE sha256=:sha256";
			$check = $handler->prepare($sql);
			$check->execute(array(
				':sha256'=>$sha256
			));
			if($row = $check->fetch())
			{
				unlink($file);
				continue;
			}
			else
			{
				$sql = "INSERT INTO {$tbl_prefex}files(filehash, filename, filesize, url, mime, sha256, lastscantime) VALUES(:filehash, :filename, :filesize, :url, :mime, :sha256, :lastscantime)";
				$query = $handler->prepare($sql);
				if($query->execute(array(
					':filehash'=>$filehash,
					':filename'=>$filename,
					':filesize'=>$filesize,
					':url'=>$url,
					':mime'=>$mime,
					':sha256'=>$sha256,
					':lastscantime'=>date("Y-m-d H:i:s")
				)))
				{
		
				}		
			}
		}	
		$count++;
	}

	$data = array('success'=>'Files uploaded successfully.');
	echo json_encode($data); 
	die();		
}


if(isset($_GET['bylocal']))
{
	foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name)
	{
		$incomming_files = count($_FILES['files']['tmp_name']);
		if((int)$incomming_files > (int)$maxFileUploads)
		{
			echo json_encode(array('error'=>"Max $maxFileUploads files are allowed at once. You sent $incomming_files."));
			exit();				
		}

		$file_name=$_FILES["files"]["name"][$key];
		$file_tmp=$_FILES["files"]["tmp_name"][$key];
		
		//Unique hash to save file on our server
		$filehash = $helper->getHash();
		$file = "$upload/$filehash";

		move_uploaded_file($_FILES["files"]["tmp_name"][$key],$file);

		if(file_exists($file))
		{
			$mime = mime_content_type($file);
			$filename = $file_name;
			$filesize = filesize($file);
			$sha256 = hash('sha256', @file_get_contents($file));

			$sql = "SELECT * FROM {$tbl_prefex}files WHERE sha256=:sha256";
			$check = $handler->prepare($sql);
			$check->execute(array(
				':sha256'=>$sha256
			));

			if($row = $check->fetch())
			{
				unlink($file);
				continue;

			}
			else
			{
				$sql = "INSERT INTO {$tbl_prefex}files(filehash, filename, filesize, url, mime, sha256, lastscantime) VALUES(:filehash, :filename, :filesize, :url, :mime, :sha256, :lastscantime)";
				$query = $handler->prepare($sql);
				
				if($query->execute(array(
					':filehash'=>$filehash,
					':filename'=>$filename,
					':filesize'=>$filesize,
					':url'=>"",
					':mime'=>$mime,
					':sha256'=>$sha256,
					':lastscantime'=>date("Y-m-d H:i:s")
				)))
				{
		
				}		
			}
		}
	}

	$data = array('success'=>'Files uploaded successfully.');
}

?>