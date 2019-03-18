<!DOCTYPE html>

<!-- Mohamed Elayat -->

<html lang="en">

<head>
	<title> Plogg PHP test </title>
	<meta charset="UTF-8">
	<meta name = "viewport" content = "width = device-width, initial scale = 1.0">
	<link rel="stylesheet" href="pageCSS.css"> 
</head>


<body id="resultsBody">

<div id="resultsBodyWrapper">

	<header>
		<h1> Results </h1>
	</header>

	
	<?php
	
	/*
	*For a given input, this class instantiates
	*all the necessary values to get the results.
	*/
	class input {
		
		private $startDate;
		private $endDate;
		private $total;
		private $baseline;		//considered a percentage of (total / weekdays)
		
		private $totalDays;		//total days in the range
		private $totalWeekdays;	//total weekdays in the range
		private $newTotal;		//new total value at least 1% below and at most 1% above the original total
		private $minValue;		//minimum value in a range based on total, total weekdays and baseline
		private $whatsLeft;		//$newTotal - $minValue * $totalWeekdays
		private $arr;			//array to store the results
		
		
		/***************************************************************************
		* Constructors
		***************************************************************************/
		
		public function __construct(){
			$this->startDate = $_POST["start_date"];
			$this->endDate = $_POST["end_date"];
			$this->total = $_POST["total"];
			$this->baseline = $_POST["baseline"];
			
			$this->totalDays = $this->getDays();
			$this->totalWeekdays = $this->getWeekdays();
			$this->newTotal = $this->getNewTotal();
			$this->minValue = $this->getMinValue();
			$this->whatsLeft = $this->getWhatsLeft();
			$this->arr = array();		
		}

		
		/***************************************************************************
		* Main functions
		***************************************************************************/	
		
		/*
		*For all Weekdays,Adds the minimum value to all the keys
		* in the array corresponding to weekdays
		*/
		public function initializeArray(){			
			$current = strtotime($this->startDate);
			$end = strtotime($this->endDate);		
			while($current <= $end){			
				if(  $this->isWeekend($current)  ){
					$this->toArray($current, 0);
				}
				else{
					$this->toArray($current, $this->minValue);
				}
				$current += 86400;
			}		
		}
		
		/*
		* Returns a random split of the $WhatsLeft range. 
		* This distribution will then be added to the initialized array.
		*/
		public function getProportions(){			
			$numbOfDelimiters = $this->totalWeekdays - 1; 		
			$intervalDelimeters = array();		
			$proportions = array();
			
			//generates random interval delimiters
			for(  $x = 0; $x < $numbOfDelimiters; $x++  ){				
				$randomVal = fmod(  rand(), $this->whatsLeft  );
				array_push(  $intervalDelimeters, $randomVal  );
			}
			
			//sorts those delimiters in ascending order
			sort($intervalDelimeters);
				
			//gets the difference between the interval delimiters (ie the intervals themselves)
			array_push($proportions, $intervalDelimeters[0]);		
			for(  $x = 1; $x < $numbOfDelimiters; $x++  ){
				array_push(  $proportions, abs(  $intervalDelimeters[$x-1] - $intervalDelimeters[$x]  )  );
			}
			array_push(  $proportions, $this->whatsLeft - $intervalDelimeters[$numbOfDelimiters - 1]  );
					
			return $proportions;				
		}
		
		/*
		* Distributes the return value of getProportions() to the array.
		*/
		public function distributeWhatsLeft(){			
			$proportions = $this->getProportions();			
			$j = 0;				
			foreach(  $this->arr as $x => $x_value  ){			
				if(  !$this->isWeekend(  strtotime($x)  )  ) {
					$this->arr[$x] += $proportions[$j];
					$this->arr[$x] = number_format(  (float)$this->arr[$x], 2, '.', '');
					$j++;
				}
			}			
		}
				
		/***************************************************************************
		* Secondary functions
		***************************************************************************/
		 
		//builds the array
		public function generateArray(){		
			$this->initializeArray();
			$this->distributeWhatsLeft();
		}
		
		//prints the array results
		public function printResults(){		
			$this->introMessage();
			$this->printAssocArray(  $this->arr  );		
		}
		
		public function introMessage(){
			echo "Start Date = " . $this->startDate . "<br>";
			echo "End date = " . $this->endDate . "<br>";
			echo "Total = " . $this->total . "<br>";
			echo "Baseline = " . $this->baseline . "<br> <br>";
			echo "Total days = " . $this->totalDays . "<br>";
			echo "Weekdays = " . $this->totalWeekdays . "<br> <br>";
			echo "Sum of array = " . number_format(array_sum(  $this->arr  ), 2) . "<br>";
			echo "<br> <br>";
		}
		
		//adds a key value pair to the array
		public function toArray(  $date, $value  ){		
			$this->arr += [   date("Y-m-d", $date) => $value  ];
		}
		 
		//checks if a date corresponds to a weekend
		public function isWeekend($date) {
			$weekday = date('N', $date);
			return($weekday == 6 || $weekday == 7);
		}
		
		/***************************************************************************
		* Get functions
		***************************************************************************/			
		
		public function getLowerBound(){		
			return $this->total - $this->total / 100;
		}
		
		public function getUpperBound(){		
			return $this->total + $this->total / 100;
		}
		
		public function getNewTotal(){		
			$min = $this->getLowerBound();
			$max = $this->getUpperBound();
			$diff = $max - $min;			
			return mt_rand ($min*100, $max*100) / 100;	
		}
		
		//returns the total number of days
		public function getDays(){
			$difference = abs( strtotime($this->startDate) - strtotime($this->endDate)  );		
			$days = floor(  ($difference)  / (60*60*24) ) + 1;
			return $days;
		}
		
		//returns the total number of weekdays
		public function getWeekdays(){
			$current = strtotime($this->startDate);
			$end = strtotime($this->endDate);	
			$count = 0;		
			while($current <= $end){
				if(  !$this->isWeekend($current)  ){
					$count++;
				}
				$current += 86400;
			}
			return $count;
		}
		
		public function getMinValue(){			
			$minVal = floor(  ($this->newTotal / $this->totalWeekdays) * ($this->baseline / 100) * 100  ) / 100;
			return $minVal;
		}
		
		public function getWhatsLeft(){
			return $this->newTotal - ($this->minValue * $this->totalWeekdays);
		}
		
		/***************************************************************************
		* Helper functions
		***************************************************************************/
		
		public static function printAssocArray(  $arr  ){			
			foreach($arr as $x => $x_value){
				echo "[" . $x . "]" . " => " . $x_value . "<br>";
			}			
			echo "<br> <br>";
		}
		
		public static function printAssocArrayCumul(  $arr  ){			
			$sum = 0;
			foreach($arr as $x => $x_value){
				$sum += $x_value;
				echo "[" . $x . "]" . " => " . $sum . "<br>";
			}
			echo "<br> <br>";			
		}
				
		public static function printNormalArray(  $arr  ){				
			for(  $x = 0; $x < count($arr); $x++  ){			
				echo $arr[$x] . "<br>";				
			}	
			echo "<br> <br>";		
		}
		
		public static function printNormalArrayCumul(  $arr  ){	
			$sum = 0;		
			for(  $x = 0; $x < count($intervals); $x++  ){
				$sum += $intervals[$x];
				echo $sum . "<br>";				
			}	
			echo "<br> <br>";		
		}
		
	}


	//generates the results when form is validated and submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$input = new input();
		$input->generateArray();
		$input->printResults();
	}

	?> 
</div>

</body>
</html>




