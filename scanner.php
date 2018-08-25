<?php
//This file sends scan request to virustotal api
include('config.php');
require_once('VirusTotalApiV2.php');  

$datetime = date("Y-m-d H:i:s");

$sql = "SELECT t1.id, t1.filehash, t1.filename, t1.filesize, t1.datetime, t1.mime,  t1.sha256, t1.url,t1.status, t2.permalink 
		FROM {$tbl_prefex}files t1 
		LEFT OUTER JOIN {$tbl_prefex}scans t2 ON t2.file_id = t1.id AND t2.id = (SELECT MAX(id) FROM {$tbl_prefex}scans WHERE file_id=t1.id)
		WHERE HOUR(TIMEDIFF(NOW(), t1.lastscantime)) > 24 OR t1.status='Not Scanned' AND (t1.status!='Oversize' || t1.status!='Skip')
		LIMIT $vtScannerThreshold";

$querys = $handler->prepare($sql);
$querys->execute();
while($row = $querys->fetch())
{	
	$id = $row["id"];
	$filesize = $row["filesize"];
	$sha256 = $row["sha256"];
	$filesize = $row["filesize"];
	$filehash = $row["filehash"];
	$status = $row["status"];
	$permalink = $row["permalink"];
	$mime = $row["mime"];
	$url = $row["url"];
	$file = "$upload/$filehash";
	$ip = $_SERVER['REMOTE_ADDR'];
	$timestamp = time() + 3600;
	$h = md5($salt . $timestamp . $filehash); 
    $downloadLink = "$baseUrl/file.php?sig={$filehash}&h={$h}&t={$timestamp}&download=1";

	//fail-fail situation
	$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status WHERE id=:id";
	$query_fileerror = $handler->prepare($sql_fileerror);
	$query_fileerror->execute(array(
	':status'=> 'Error',
	':id'=>$id
	));


	//API cannot handle files baove 32 MB
	if($filesize/1024/1024 > 32)
	{
		//oversize file
		$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
		$query_fileerror = $handler->prepare($sql_fileerror);
		$query_fileerror->execute(array(
		':status'=> 'Oversize',
		':id'=>$id,
		':lastscantime'=>$datetime
		));		

		continue;
	}
	
	$api = new VirusTotalAPIV2($virustotalApiKey);

	//ReScan file
	if($status == "Completed" && $permalink != '')
	{
			$result = $api->rescan($sha256);			
			if(isset($result->response_code))
			{
				if($result->response_code==1)
				{
						$permalink = $result->permalink;
						//$scan_id = $result->scan_id;

						$sql_rescan = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
						$query_rescan = $handler->prepare($sql_rescan);
						if($query_rescan->execute(array(
						':status'=>'Queued',
						':id'=>$id,
						':lastscantime'=>$datetime
						)))
						{
							//echo $url." : success rescan<br />";
						}						
				}	
				else
				{
					echo $result->response_code."response_code rescan<br />";
					//fail-fail situation
					$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
					$query_fileerror = $handler->prepare($sql_fileerror);
					$query_fileerror->execute(array(
					':status'=> 'Error',
					':id'=>$id,
					':lastscantime'=>$datetime
					));	
				}				
			}
			else
			{
				//fail-fail situation
				$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
				$query_fileerror = $handler->prepare($sql_fileerror);
				$query_fileerror->execute(array(
				':status'=> 'Error',
				':id'=>$id,
				':lastscantime'=>$datetime
				));	
			}
	}
	else
	{
		//First scan
		//$result = $api->scanUrl($downloadLink);
		$result = $api->scanFile($file, $mime);
		$scanId = $api->getScanID($result); // Can be used to check for the report later on.

		//var_dump($result);

		if(isset($result->response_code))
		{
			if($result->response_code==1)
			{
				if($result->verbose_msg=="Scan request successfully queued, come back later for the report")
				{
					$permalink = $result->permalink;
					$sha256 = $result->sha256;

					$sql_oversized = "UPDATE {$tbl_prefex}files set status=:status, sha256=:sha256, lastscantime=:lastscantime WHERE id=:id";
					$query_oversized = $handler->prepare($sql_oversized);
					if($query_oversized->execute(array(
					':status'=>'Queued',
					':id'=>$id,
					':sha256'=>$sha256,
					':lastscantime'=>$datetime

					)))
					{
						//echo $url." : success scan<br />";
					}
				}
			}
			else
			{
				//fail-fail situation
				$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
				$query_fileerror = $handler->prepare($sql_fileerror);
				$query_fileerror->execute(array(
				':status'=> 'Error',
				':id'=>$id,
				':lastscantime'=>$datetime
				));	
			}
		}	
		else
		{
			//fail-fail situation
			$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
			$query_fileerror = $handler->prepare($sql_fileerror);
			$query_fileerror->execute(array(
			':status'=> 'Error',
			':id'=>$id,
			':lastscantime'=>$datetime
			));				
		}
	}

}

?>
