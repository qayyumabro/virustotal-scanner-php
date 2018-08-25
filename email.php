<?php  
include('config.php');
//Exit if email-notifications are off
if($emailNotify == 0) die();

//Loading required librarries
require 'libs/phpspreadsheet/vendor/autoload.php';
require_once 'libs/phpmailer/phpmailer/Exception.php';
require_once 'libs/phpmailer/phpmailer/PHPMailer.php';
require_once 'libs/phpmailer/phpmailer/SMTP.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$datetime = date("Y-m-d");

//Starting mail object
$mail = new PHPMailer(true);

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);	

if(file_exists("libs/email-template.html"))
{
	//Load email template into $body
	$body = file_get_contents("libs/email-template.html");

	//Get detections information/calculations 
	$sql = "SELECT
			COUNT(*) as total,
			(SELECT  COUNT( DISTINCT file_id) FROM {$tbl_prefex}scans WHERE positives > 0) as infected,
			(SELECT COUNT(DISTINCT file_id) FROM {$tbl_prefex}scans WHERE positives = 0) as safe
			FROM `{$tbl_prefex}files` t1";

	$query  = $handler->prepare($sql);
	$query->execute();

	if($row = $query->fetch())
	{
		$total = $row["total"];
		$infected = $row["infected"];
		$safe = $row["safe"];

		$body = str_replace("{{date}}", "$datetime", $body);
		$body = str_replace("{{total}}", "$total", $body);
		$body = str_replace("{{infected}}", "$infected", $body);
		$body = str_replace("{{safe}}", "$safe", $body);
		$body = str_replace("{{baseUrl}}", "$baseUrl", $body);
		$body = str_replace("{{baseTitle}}", "$baseTitle", $body);

		$mail->SMTPDebug  = 4;                     
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host       = $stmp;
		$mail->Port       = $port;
		$mail->Username   = $emailEmail;
		$mail->Password   = $emailPassword;
		$mail->From = $emailEmail;
		$mail->FromName = "Scan Report - $baseTitle";
		$mail->AddReplyTo($emailEmail, $emailEmail);
		$mail->AddAddress($emailEmail);
		$mail->Subject = "There were $infected detections today - $baseTitle";
		$mail->MsgHTML($body);

		//Excel sheet path
		$sheetPath = "$upload/scan-report-$datetime.xlsx";

		//Delete if it already exists
		if(file_exists($sheetPath))
			unlink($sheetPath);

		$styleArray = [
		    'font' => [
		        'bold' => true,
		    ],
		    'alignment' => [
		        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		    ],
		    'borders' => [
		        'top' => [
		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		        ],
		    ],
		    'fill' => [
		        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
		        'rotation' => 90,
		        'startColor' => [
		            'argb' => 'FFA0A0A0',
		        ],
		        'endColor' => [
		            'argb' => 'FFFFFFFF',
		        ],
		    ],
		];

		$styleArrayInfected = [
		    'font' => [
		        'bold' => false,
		    ],
		    'fill' => [
		        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
		        'rotation' => 90,
		        'startColor' => [
		            'argb' => 'f44336',
		        ],
		        'endColor' => [
		            'argb' => 'f44336',
		        ],
		    ],
		];
		
		//Create new spreadsheet object
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		//Sheet's header row
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'FileHash');
		$sheet->setCellValue('C1', 'FileName');
		$sheet->setCellValue('D1', 'Url');
		$sheet->setCellValue('E1', 'Report');
		$sheet->setCellValue('F1', 'Detections');
		$sheet->setCellValue('G1', 'Total AVs');

		//Apply custom design on just header row
		$spreadsheet->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);

		//Reading report data from database
        $sql = "SELECT t1.filehash, t1.filename, t1.filesize, t1.datetime, t1.sha256, t1.url, t2.positives, t2.total, t2.permalink,t1.status,  t2.datetime as scantime, t1.lastscantime  
                FROM {$tbl_prefex}files t1
                LEFT OUTER JOIN {$tbl_prefex}scans t2 ON t2.file_id = t1.id AND t2.id = (SELECT MAX(id) FROM {$tbl_prefex}scans WHERE file_id=t1.id)

                ORDER BY t1.id";
        $query = $handler->prepare($sql);
        $query->execute();
        $rows = $query->rowCount();
        if($rows > 0)
        {
          $count = 1;
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

            $index = $count+1;

            //Setting cell values
            $sheet->setCellValue("A$index", $count);
            $sheet->setCellValue("B$index", $filehash);
            $sheet->setCellValue("C$index", $filename);
            $sheet->setCellValue("D$index", $url);
            $sheet->setCellValue("E$index", $permalink);
            $sheet->setCellValue("F$index", $positives);
            $sheet->setCellValue("G$index", $total);

            //Change bg of row to red if there was a positive detection
            if($positives>0)
            	$spreadsheet->getActiveSheet()->getStyle("A$index:F$index")->applyFromArray($styleArrayInfected);
            
            $count++;
          }                  
        }
		
        //Autosize all the columns
        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);


        //Write xlsx file for attatchment
		$writer = new Xlsx($spreadsheet);
		$writer->save($sheetPath);

		//Attatch xlsx file in email
		$mail->addAttachment($sheetPath);
		$mail->Send();	

		//Delete file after sending email to save disk
		unlink($sheetPath);
	}
}
?>




