<?php  
class helper
{
	//Generating file 20 char length file hashes
	public function getHash($prefix='',$post_fix='')
	{
		include("db.php");
		$indb = true;
		do {
		    $t=time();
		    $id  = ( rand(11111111111,99999999999999).$prefix.$t.$post_fix);
		    $out = strlen($id) > 20 ? substr($id,0,19)."" : $id;	
			
		
			$sql = "SELECT filehash FROM {$tbl_prefex}files WHERE filehash=:filehash LIMIT 1";
			$query = $handler->prepare($sql);
			$query->execute(array(
				':filehash'=>$out
			));
			if(!$row = $query->fetch())
			{
				$indb = false;
			}
		    	
		} while ($indb == true);

	    return $out;
	}

	//Converting datetime to descriptive time, like file was uploaded 3 days ago etc
	function ago($time)
	{
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	       $difference /= $lengths[$j];
	   }

	   $difference = round($difference);

	   if($difference != 1) {
	       $periods[$j].= "s";
	   }

	   return "$difference $periods[$j] ago";
	}

	//Convert 1111111 to readable file size, 10 MB etc
	function size($size, $precision = 1)
	{
	  if($size==null || $size==-1 || $size=="" || $size==0) return "URL";
	  $base = log((float)$size, 1024);
	  $suffixes = array(' B', ' KB', ' MB', ' GB', ' TB');   

	  return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}
}
?>