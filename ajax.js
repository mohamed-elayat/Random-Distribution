
//Mohamed Elayat

/*
* Called to validate an individual
* line asynchronously. It calls the validateEntry function
* accordingly.
*/
function validate(  type  ) {
	
	if(  type === "start_date" || type === "end_date"  ){				
		validateEntry(  "start_date"  );
		validateEntry(  "end_date"  );		
	}
	else{			
		validateEntry(  type  );			
	} 	
	
}


/*
* Sends an asynchronous http request to ajaxValidate.php 
* to validate an individual line. Uses JSON to pass the data.
*/
function validateEntry(  type  ){	

		xmlhttp = new XMLHttpRequest;	
		xmlhttp.onreadystatechange = function(){		
			if (this.readyState == 4 && this.status == 200) {		//verifies the response is ready
				document.getElementById(  type + "_error"  ).innerHTML = this.responseText;				
			}
		}
		
		var start_date = document.getElementById("start_date_input").value;
		var end_date = document.getElementById("end_date_input").value;
		var total = document.getElementById("total_input").value;
		var baseline = document.getElementById("baseline_input").value;	
		var a = [start_date,end_date,total, baseline, type];
		
		
		var dataJSON = JSON.stringify(a);

		xmlhttp.open( "POST", "ajaxValidate.php"  );
		xmlhttp.setRequestHeader(  "Content-Type", "application/x-www-form-urlencoded"  );
		xmlhttp.send(  dataJSON  );	
		
}



/*
* Returns true if there's no error messages 
*/
function validateForm(){
		
	if(		document.getElementById("start_date_error").innerHTML ==  "" &&
			document.getElementById("end_date_error").innerHTML ==  "" &&
			document.getElementById("total_error").innerHTML ==  "" &&
			document.getElementById("baseline_error").innerHTML ==  "" ){
		return true;
	}
	else{
		return false;
	}

}
















