<?php
	$file_string = file_get_contents('formulary.txt');
	$str_length = strlen($file_string);
	$finalString = "";
	$i = 0;

	while ($i < $str_length) {
		if ($file_string[$i] == ",") {
			$finalString .= "?";
			$i++;
		}
		elseif ($file_string[$i] == "\"") {
			$finalString .= $file_string[$i];
			$i++;
			while($file_string[$i] != "\"") {
				$finalString .= $file_string[$i];
				$i++;
			}
			$finalString .= $file_string[$i];
			$i++;
		}
		else {
			$finalString .= $file_string[$i];
			$i++;
		}
	}

	$file = "modded.txt";
	file_put_contents($file, $finalString);


	$fh = fopen("modded.txt", 'r') or die("File does not exist or you lack permission to open it");

	$first_line = fgets($fh);
	$keys = explode("?", $first_line);

	$final = "";

	$pack_size_types = array();
	$generic_names = array();
	$ndc_first_five = array();

	while (! feof($fh)) {
		$strLine = fgets($fh);

		$lineArray = explode("?", $strLine);

		$arrayCount = 0;
		$data = array();

		foreach ($lineArray as $value) {
			$data[trim($keys[$arrayCount++], "\"")] = trim($value, "\"");
		}

		foreach ($data as $key => $value) {
			$data[$key] = trim($value);
		}



		$selectedItems  = array('generic_name' => $data['Drug_Generic'], 
								'NDC' => $data['Drug_NDC'],
								'drug_description' => $data['Drug_Descr'],
								'pack_size' => $data['Drug_Pkg'],
								'unit' => $data['Drug_Unit'],
								'price_340B' => $data['Drug_340B'],
								'add_date' => $data['Drug_med_EffDate'],
								'end_date' => $data['Drug_med_EndDate'],
								'pdl_pa_required' => $data['Drug_PDL_pa_required'],
								'clinical_pa_required' => $data['Drug_Clinical_pa_required'],
								'retail_price' => $data['Drug_Retail'],
								'specialty_price' => $data['Drug_SPC'],
								'ltc_price' => $data['Drug_LTC'],
								'va_cost' => $data['Drug_VAC'],
								'drug_class' => $data['Drug_MKID_Desc']);

		$date_mods = array('add_date', 'end_date');


		/* modify dates to mysql format yyyy-mm-dd */
		foreach ($date_mods as $value) {
			if ($selectedItems[$value] != "") {
				$date_split = explode("/", $selectedItems[$value]);
				$selectedItems[$value] = $date_split[2] . "-" . $date_split[0] . "-" . $date_split[1];
			}
		}
		/* end date modification */

		/* modify ndc to format #####-####-## */
		$a = substr($selectedItems['NDC'], 0, 5);
		$b = substr($selectedItems['NDC'], 5, 4);
		$c = substr($selectedItems['NDC'], 9, 2);
		$selectedItems['NDC'] = $a . "-" . $b . "-" . $c;



		$pack_size_types[$selectedItems['unit']]++;
		$generic_names[$selectedItems['generic_name']]++;
		$ndc_first_five[substr($selectedItems['NDC'], 0, 5)]++;

		$outputString = "";	

		$itemCount = 0;

		foreach ($selectedItems as $value) {
			if ($value != "") {
				$outputString .= $value;
			}
			else {
				$outputString .= "\\N";
			}
			if ($itemCount++ != count($selectedItems) - 1) {
				$outputString .= "\t";
			}
			else {
				$outputString .= "\n";
			}
		}
		$final .= $outputString;
	}
	
	fclose($fh);

	$file = "finisheddata.txt";
	file_put_contents($file, $final);

	foreach ($pack_size_types as $key => $value) {
		print($key);
		print("\t");
		print($value);
		print("\n");
	}


/*
		foreach ($ndc_first_five as $key => $value) {
		print($key);
		print("\t");
		print($value);
		print("\n");
	}
	*/

	print(count($ndc_first_five));
?>