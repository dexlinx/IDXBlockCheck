<?
ini_set('memory_limit', '256M');
ini_set('max_execution_time', '60S');
ini_set('upload_max_filesize','5M');
ini_set('post_max_size','5M');
set_time_limit(0);

// Get the File Contents
if (!empty($_FILES)){
	
	echo "<pre>";
	//var_dump($_FILES);
	echo "</pre>";
	
	move_uploaded_file($_FILES['uploaded_file_1']['tmp_name'],"clients.json"); //<- Client Export
	move_uploaded_file($_FILES['uploaded_file_2']['tmp_name'], "blocked.csv"); //<- Block Export
}
?>

<?
if (empty($_FILES)){
	?>

 <form enctype="multipart/form-data" action="blockcheck.php" method="POST">
    <p>Upload your files</p>
    IDX Client List: <input type="file" name="uploaded_file_1"></input><br />
	Suppression List: <input type="file" name="uploaded_file_2"></input><br />
    <input type="submit" value="Upload"></input>
</form>

NOTE: Before Submitting Files, do the following:<p>
- Suppression List: Remove All Columns Except Email Address :: Replace \r\n with ","<p>

<? }else{
	
	
	echo "Time to work on the files<p>";
	
$clientList = file_get_contents("clients.json");
$clientListDecoded = json_decode($clientList, true);

//----------------------------------
//Getting the Client List
//----------------------------------
$clientEmails = array();
foreach ($clientListDecoded as $key => $value){
	
	foreach ($value as $valuekey => $valuedata){
		
		if ($valuedata["demo"] == "n"){
		array_push($clientEmails,$valuedata["primaryEmail"]);
		}
	}
}

//----------------------------------
//Getting the Block List
//----------------------------------

$suppressList = file_get_contents("blocked.csv");
$blockList = explode(',',$suppressList);

echo "<pre>";
//var_dump($blockList);
echo "</pre>";


//----------------------------------
//Checking For Blocked Clients
//----------------------------------	
$clientBlockedEmails = array();

foreach($clientEmails as $email){
	
	if(in_array($email,$blockList)){
		array_push($clientBlockedEmails,$email);
	}
}
	
echo "<pre>";
var_dump($clientBlockedEmails);
echo "</pre>";

	
} ?>
