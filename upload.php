<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="style.css">
<title>My App</title>
</head>
<body>
<div id="container">
<div id="form">

<?php



//Upload File
if (isset($_POST['submit'])) {
	if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
		
	
	$m = new MongoClient();
	$db = $m->worcester;
	$collection = $db->worcester_products;
	//Import uploaded file to Database
	$handle = fopen($_FILES['filename']['tmp_name'], "r");
    $header = fgetcsv($handle);

	while ($line = fgetcsv($handle)){
        $record = array_combine($header, $line);
        $collection->insert( $record );
       // now record contains an array of fields keyed by the associated key in the header record
  	}

  	//print_r($record);

	fclose($handle);

	print "Import done";
 }
	//view upload form
}
?>
<form enctype='multipart/form-data' method='post'>

	

	<input size='50' type='file' name='filename'>

		<input type='submit' name='submit' value='Upload'>
</form>
</div>
</div>
</body>
</html>
