<?php

//Mohamed Elayat
	
	require_once('validation_modes.php');
	
	
	/*
	 * Returns the error message for the "total" line.
	 */
	function validateTotal(  $input, $mode  ){			
		if (  empty($input) && !is_numeric($input) ){
				$total_error = "* Total is required";					
		}
		else{				
			$total = test_input(  $input  );
			if (  is_numeric($total) && $total >= 0 && $total <=1000000000000 ){						
				$total_error = "";
			}
			else if(  is_numeric($total) && $total >=1000000000000  ) {
				$total_error = "* Total must be less than 10^12";
			}	
			else{
				$total_error = "* Total must be a positive number";
			}
		}
		return $total_error;
	} 
	
	/*
	 * Returns the error message for the "baseline" line.
	 */		
	function validateBaseline(  $input, $mode  ){	
		if (  empty($input)  && !is_numeric($input)  ){
			$baseline_error = "* Baseline is required";
		} 
		else{
			$baseline = test_input(  $input  );
			if (  is_numeric($baseline) && $baseline >= 0 && $baseline <=100  ){
				$baseline_error = "";
			}
			else{
				$baseline_error = "* Baseline must be between 0 and 100";
			}
		}
		return $baseline_error;	
	} 
	
	/*
	 * Returns the error message for the "Start date" line.
	 */	
	function validateStartDate(  $date1, $date2, $mode  ){			
		if (  empty(  $date1  )  ){			
			if(  $mode === ValidationMode::Submit  ){
				$start_date_error = "* Start date is required";
			}
			else{
				//only star is shown in order not to bother user
				$start_date_error = "*";	
			}			
		} 
		else if(  !(  empty($date1)  ) &&  !(  empty($date2)  )  ){		
			$difference = strtotime(  $date1  ) - strtotime(  $date2  );
			$years = floor(  abs($difference / (365*60*60*24))  );
		
			if(  $difference > 0  ){
				$start_date_error = "* End date cannot be less that start date";
			}					
			else if(  $years > 500  ){
				//limiting range to avoid crashing the program
				$start_date_error = "* Time range cannot be over 500 years. Please choose a smaller range";
			}
			else{
				$start_date_error = "";
			}				
		}		
		else{
			$start_date_error = "";
		}			
		return $start_date_error;	
	}
	
	/*
	 * Returns the error message for the "End date" line.
	 */
	function validateEndDate(  $date1, $date2, $mode  ){			
		if (  empty(  $date2  )  ){			
			if(  $mode === ValidationMode::Submit  ){
				$end_date_error = "* End date is required";
			}
			else{
				//only star is shown in order not to bother user
				$end_date_error = "*";
			}			
		} 
		else if(  !(  empty($date1)  ) &&  !(  empty($date2)  )  ){		
				$difference = strtotime(  $date1  ) - strtotime(  $date2  );
				$years = floor(  abs($difference / (365*60*60*24))  );		//difference in years
			
				if(  $difference > 0  ){
					$end_date_error = "* End date cannot be less that start date";
				}					
				else if(  $years > 500  ){
					//limiting range to avoid crashing the program
					$end_date_error = "* Time range cannot be over 500 years. Please choose a smaller range";
				}
				else{
					$end_date_error = "";
				}						
		}
		else{
			$end_date_error = "";
		}						
		return $end_date_error;		
	}
	
	/*
	 * Strips the input value and returns it.
	 * The return of this function is what's checked in the form validation
	 */
	function test_input($data){			
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}


?>