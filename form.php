<!DOCTYPE html>

<!-- Mohamed Elayat -->

<html lang="en">

<head>
	<title> Plogg PHP test </title>
	<meta charset="UTF-8">
	<meta name = "viewport" content = "width = device-width, initial scale = 1.0">
	<link rel="stylesheet" href="pageCSS.css">	
	<script type="text/javascript" src="ajax.js"></script>	
</head>

<body id="formBody">

<div id = "formBodyWrapper">

	<header>
		<h1> Random distribution </h1>
	</header>
	
	<main>	
	
		<?php
		
			//variables to preserve the input when form is submitted
			$start_date = $end_date = $total = $baseline = "";
			
			//variables to set the error messages if there's errors
			$start_date_error = $end_date_error = $total_error = $baseline_error = "*";
			
			require_once('validation_functions.php');

			//form validation that runs upon form submission
			if ($_SERVER["REQUEST_METHOD"] == "POST") {			
				$valid = false;		//result is only displayed if $valid is true
					
				$start_date = test_input(  $_POST["start_date"]  );		//cleans and strips data of any unwanted characters
				$start_date_error = validateStartDate(  $_POST["start_date"], $_POST["end_date"], ValidationMode::Submit  );
					
				$end_date = test_input(  $_POST["end_date"]  );
				$end_date_error = validateEndDate(  $_POST["start_date"], $_POST["end_date"], ValidationMode::Submit  );
					
				$total = test_input(  $_POST["total"]  );
				$total_error = validateTotal(  $_POST["total"], ValidationMode::Submit   );
	
				$baseline = test_input(  $_POST["baseline"]  );
				$baseline_error = validateBaseline(  $_POST["baseline"], ValidationMode::Submit  );
							
				//if there's no error messages
				if(	$start_date_error == "" && 
					$end_date_error == "" && 
					$total_error == "" && 
					$baseline_error == ""  ){
						$valid = true;
				}					  
			}
		
		?>
	
		<!-- htmlspecialchars() is used to avoid $_SERVER["PHP_SELF"] exploits -->
		
		<form name="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateForm()">
				
			<label> Start date: </label>
			<input type="date" name = "start_date" id="start_date_input" value="<?php echo $start_date;?>" oninput="validate(this.name)"> 
			<span class = "error" id="start_date_error"><?php echo $start_date_error;?></span> <br>
			
			<label> End date: </label>
			<input type = "date" name = "end_date" id="end_date_input" value="<?php echo $end_date;?>" oninput="validate(this.name)"> 
			<span class = "error" id="end_date_error"><?php echo $end_date_error;?></span> <br>
			
			<label> Total: </label>
			<input type = "number" name = "total" id="total_input" value="<?php echo $total;?>" oninput="validate(this.name)"> 
			<span class = "error" id="total_error"><?php echo $total_error;?></span> <br>
			
			<label> Baseline: </label>
			<input type = "number" name = "baseline" id="baseline_input" value="<?php echo $baseline;?>" oninput="validate(this.name)"> 
			<span class = "error" id="baseline_error"><?php echo $baseline_error;?></span> <br>
			
			<input type = "submit" class="submitButton" id="submitButton" value = "Get results">
			
		</form>	

		
		<?php			
			//generates results if form submitted is valid
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if(  $valid  ){
					include('arrayGenerator.php');
				}
			}
		?>

	</main>
	
</div>
	
</body>
</html>




