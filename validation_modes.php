<?php

//Mohamed Elayat

	/*
	 * Class that serves as an enumeration.
	 * The error message that we output partially
	 * depends on whether it's a submit validation
	 * or an asynchronous validation.
	 */
	abstract class ValidationMode	{
		const Submit = 0;
		const Ajax = 1;
	} 
?>