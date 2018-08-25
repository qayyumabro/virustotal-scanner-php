# Virustotal Scanner PHP
Scan your files regularly with virustotal api 

<h2>How to install VirusTotal Scanner</h2>
<ul>
	<li>Upload virustotal-scanner.zip file on your server and extract it.</li>
  	<li>You can alos extract file on your computer and upload those file using FTP program like <b>FileZilla</b>.<br/><img src="https://free-snipping-tool.s3.amazonaws.com/capture_20180802192821.png" alt=""></li>
	<li>Open <i>install.php</i> file in browser.<br /><img src="https://free-snipping-tool.s3.amazonaws.com/capture_20180802235224.png" alt=""></li>
	<li><b>Table Prefix</b> field is recommended to use when you already have tables in your database and avoid any clashes.</li>
  	<li>Base Url  should point to folder you installed on your server.</li>
  	<li>And enter all other database details and click <i>Install</i> button. </li>
  	<li>This will install the VirusTotal Scanner on your server.</li>
  	<li>Open login.php page.</li>
  	<li>Now you can login with this account <b>admin@admin.com</b> using password <b>admin</b>.</li>
</ul>

<h3>Settings</h3>
<ul>
<li>Create account on virustotal.com and get api key.</li>
<li>Change default email and password, as default account is not secure anymore.</li>
<li>You can now add api key from virustotal.com here and change setting as you need and save them.<br /><img src="https://free-snipping-tool.s3.amazonaws.com/capture_20180803000650.png" alt=""></li>
<li>Now open your cPanel <i>Advanced->Cron Jobs</i>, and copy cron job commands from setting and set them in Cron Jobs. <br /><img src="https://free-snipping-tool.s3.amazonaws.com/capture_20180803000829.png" alt=""></li>	
 <li>Now upload files and scanner will start scanning your files.</li>
</ul>
