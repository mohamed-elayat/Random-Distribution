<?php

//Mohamed Elayat

	/*
	 * PHP file that's called asynchronously upon input change in 
	 * the form. Using the validation functions, it outputs
	 * back to the form the appropriate error message.
	 */
	 
	include('validation_functions.php');

	//storing the passed data in an array
	$dataTotal = json_decode(file_get_contents( "php://input" ), true);
	
	$start_date = $dataTotal[0];
	$end_date = $dataTotal[1];
	$total = $dataTotal[2];
	$baseline = $dataTotal[3];
	$type = $dataTotal[4];

	switch ($type) {				
		case "start_date" : 
			echo validateStartDate(  $start_date, $end_date, ValidationMode::Ajax  );				
			break;		
		case "end_date" : 		
			echo validateEndDate(  $start_date, $end_date, ValidationMode::Ajax  );
			break;			
		case "total" :			
			echo validateTotal(  $total, ValidationMode::Ajax  );
			break;
		case "baseline" :			
			echo validateBaseline(  $baseline, ValidationMode::Ajax  );				
			break;
		default:
			echo "";			  
	} 

?>