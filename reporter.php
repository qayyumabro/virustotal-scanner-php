<?php 
//This cron job file checkes if any file is queued and is 40 minutes old, it will try to get report back from virus total 
include('config.php');
require_once('VirusTotalApiV2.php');  

$datetime = date("Y-m-d H:i:s");
$sql = "SELECT t1.id, t1.filehash, t1.filename, t1.filesize, t1.datetime, t1.mime,  t1.sha256, t1.url,t1.status, t2.permalink 
		FROM {$tbl_prefex}files t1 
		LEFT OUTER JOIN {$tbl_prefex}scans t2 ON t2.file_id = t1.id AND t2.id = (SELECT MAX(id) FROM {$tbl_prefex}scans WHERE file_id=t1.id)
		WHERE MINUTE(TIMEDIFF(NOW(), t1.lastscantime)) > 40 AND t1.status='Queued' AND t1.status!='Not Scanned'
		LIMIT $vtReporterThreshold";
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


	//fail-fail situation
	$sql_fileerror = "UPDATE {$tbl_prefex}files set status=:status, lastscantime=:lastscantime WHERE id=:id";
	$query_fileerror = $handler->prepare($sql_fileerror);
	$query_fileerror->execute(array(
	':status'=> 'Error',
	':id'=>$id,
	':lastscantime'=>$datetime
	
	));


	$api = new VirusTotalAPIV2($virustotalApiKey);
	$report = $api->getFileReport($sha256);
	//var_dump($report);

	if($report)
	{
		$response = $report->response_code;
		if($response==1)
		{
			if($report->verbose_msg=="Scan finished, information embedded")
			{
				$total = $report->total;
				$positives = $report->positives;
				$permalink = $report->permalink;
				
				$sql_completed = "UPDATE {$tbl_prefex}files set status=:status WHERE id=:id";
				$query_completed = $handler->prepare($sql_completed);
				if($query_completed->execute(array(
					':status'=>'Completed',
					':id'=>$id
					)))
				{

					$sql = "INSERT INTO `{$tbl_prefex}scans`(`file_id`, `positives`, `total`, `permalink`) VALUES (:id, :positives, :total, :permalink)";
					$scan = $handler->prepare($sql);
					$scan->execute(array(
						":id"=>$id, 
						":positives"=>$positives, 
						":total"=>$total, 
						":permalink"=>$permalink
					));

					//echo "success";
				}
			}
		}
	}
}
?>