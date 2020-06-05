<?php
	$fh = fopen("formulary.txt", 'r') or die("File does not exist or you lack permission to open it");
	//$line = fgets($fh);
	//fclose($fh);
	//echo $line;

	//echo file_get_contents('formulary.txt');	
	$linecount = 0;
	while (! feof($fh) && $linecount < 2) {
		$str = fgets($fh);
		$linecount++;
		//echo $str;
	}
	fclose($fh);
	//print($linecount);
	//print(gettype($str));
	//echo $str;
	$fieldarray = explode(",", $str);
	//print($fieldarray);
	$count = 0;
	foreach ($fieldarray as $value) {
		//print($value);
		$fieldarray[$count] = trim($fieldarray[$count], "\"");
		$fieldarray[$count] = trim($fieldarray[$count], "\n");
		if ($value == "") {
			$fieldarray[$count] = "\\N";
		}
		$count++;
		//print("\n");
	}
	foreach ($fieldarray as $value) {
		print($value);
		//if (strpos($value, "\n")) print('contains newline');
		print("\n");
	}

	print($fieldarray[count($fieldarray) - 1]);



?>