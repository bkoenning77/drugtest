<?php
	$fh = fopen("formulary.txt", 'r') or die("File does not exist or you lack permission to open it");

	$linecount = 0;
	$finalString = "";
	while (! feof($fh)) {
		$str = fgets($fh);


		if ($linecount++ == 0) continue;

		$lineArray = explode(",", $str);

		$arrayCount = 0;

		foreach ($lineArray as $value) {
			$lineArray[$arrayCount] = trim($lineArray[$arrayCount], "\"");
			if ($value == "") {
				$lineArray[$arrayCount] = "\\N";
			}
			$arrayCount++;
		}

		array_pop($lineArray);
		$lineString = "";
		$arrayCount = 0;
		foreach ($lineArray as $value) {
			$lineString .= $value;
			if ($arrayCount != count($lineArray) - 1) {
				$lineString .= "\t";
			}
		}
		$lineString .= "\n";
		$finalString .= $lineString;
	}
	$finalString = trim($finalString, "\n");
	$finalString .= "\n";
	fclose($fh);
	//print($finalString);
	$file = "updated.txt";
	file_put_contents($file, $finalString);

?>