<?php  		
//Download file link
if(isset($_GET['sig']) && isset($_GET['h']) && isset($_GET['t']) && isset($_GET['download']))
{
	include_once('config.php');
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$signature = $_GET['sig'];
	$hashGiven = $_GET['h'];
	$timestamp = $_GET['t'];

	$hash = md5($salt . $timestamp . $signature);

	if($hashGiven == $hash && $timestamp >= time()) 
	{
		$filehash = $signature;

		$sql = "SELECT * FROM {$tbl_prefex}files WHERE filehash=:filehash LIMIT 1";
		$query = $handler->prepare($sql);
		$query->execute(array(':filehash'=>$filehash));
		if ($row=$query->fetch()) 
		{
		  	$filehash = $row["filehash"];

		  	$filename = $row["filename"];

		  	$filesize = $row["filesize"];

		  	$mime = $row["mime"];

		  	if(file_exists("$upload/$filehash"))
		  	{
		  		$filePath = "$upload/$filehash";

                header("Content-Description: File Transfer");
                header("Content-Type: $mime");
                header("Content-Length: ".$filesize);
                header("Content-disposition: attachment; filename=\"" . basename($filename) . "\""); 
                readfile($filePath);     		  		
		  	}

		}
	}
}

//Delete file link
else if(isset($_GET['sig']) && isset($_GET['h']) && isset($_GET['t']) && isset($_GET['delete']))
{
	include_once('config.php');
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$signature = $_GET['sig'];
	$hashGiven = $_GET['h'];
	$timestamp = $_GET['t'];

	$hash = md5($salt . $timestamp . $signature);

	if($hashGiven == $hash && $timestamp >= time()) 
	{
		$filehash = $signature;

		$sql = "SELECT * FROM {$tbl_prefex}files WHERE filehash=:filehash LIMIT 1";
		$query = $handler->prepare($sql);
		$query->execute(array(':filehash'=>$filehash));
		if ($row=$query->fetch()) 
		{
			$id = $row["id"];
		  	$filehash = $row["filehash"];
		  	$filename = $row["filename"];
		  	$filesize = $row["filesize"];
		  	$mime = $row["mime"];

		  	if(file_exists("$upload/$filehash"))
		  	{
		  		$filePath = "$upload/$filehash";
		  		unlink($filePath);

		  		$sql = "DELETE FROM {$tbl_prefex}files WHERE id=:id LIMIT 1";
		  		$delete_file = $handler->prepare($sql);
		  		$delete_file->execute(array(':id'=>$id));

		  		$sql = "DELETE FROM {$tbl_prefex}scans WHERE file_id=:id";
		  		$delete_file_scans = $handler->prepare($sql);
		  		$delete_file_scans->execute(array(':id'=>$id));

		  		header('Content-Type: application/json');
		  		$data = array('success'=>'File deleted.');
				echo json_encode($data); 
				die();
 		  		
		  	}

		}
	}
	
	$data = array('error'=>'File not deleted.');
	echo json_encode($data); 
	die();
}
else
{
    exit("file doesn't exist (c)");             
}
?>