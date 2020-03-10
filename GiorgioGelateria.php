<?php

	include "index.php";

	if(isset($_GET['formType']))
	{
		$formType = $_GET['formType'];
	}
	else
	{
		$formType = 0;
	}

	if(isset($_GET['typeOfPage']))
	{
		$typeOfPage = $_GET['typeOfPage'];
	}
	else
	{
		$typeOfPage = 0;
	}

	//create the index
	$index = new index();

	//save the output of the index in a variable and echo it
	$code = $index->outputHTML($formType, $typeOfPage);
	echo "$code";
?>