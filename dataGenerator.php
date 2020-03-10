<?php
	
	include "pagePrinter.php";

	//connect to the database
	$pagePrinter = new PagePrinter();
	$pagePrinter->connect_to_database();
	$mysqli = $pagePrinter->getMySqli();

	//attributes for database inputs
	$toppingsArray = array("hot fudge", "caramel sauce", "strawberry sauce", "whipped cream", "sprinkles", 
			"M&Ms", "peanuts", "almonds", "walnuts", "oreo cookies");

	$flavoursArray = array("vanilla", "strawberry", "mint", "chocolate", "coconut", "stracciatella", "fudge", "yogurt", "lemon", "tangerine");

	$sizeArray = array("small", "middle-sized", "big");

	$imageArray = array("Images/blue.png", "Images/orange.png", "Images/purple.png");

	$minAmount = 10;
	$maxAmount = 30;

	//insert different data in the database
	// 1. insert different toppings (apply counter to set id)	

	for ($i = 0; $i < sizeof($toppingsArray); ++$i) { 
		
		$queryInsert = "INSERT INTO toppings(id, name) VALUES ('$i', '$toppingsArray[$i]')";
		$result = $mysqli->query($queryInsert);
			
		if(!$result){
			echo "Topping {$toppingsArray[$i]} with id.{$i} could not be inserted.<br>";
			die($mysqli->error);
		}
		else
			echo "<br> Topping {$toppingsArray[$i]} with id.{$i} was successfully inserted.";
	}

	// 2. insert different flavours (apply counter to set id)

	for ($i = 0; $i < sizeof($flavoursArray); ++$i) { 
		
		$queryInsert = "INSERT INTO flavours(id, name) VALUES ('$i', '$flavoursArray[$i]')";
		$result = $mysqli->query($queryInsert);
			
		if(!$result){
			echo "Flavour {$flavoursArray[$i]} with id.{$i} could not be inserted.<br>";
			die($mysqli->error);
		}
		else
			echo "<br> Flavour {$flavoursArray[$i]} with id.{$i} was successfully inserted.";
	}

	// 3. "randomize" products with different flavours and toppings

	for($i = 0; $i < sizeof($sizeArray); ++$i){									//create products of every size

		for($j = 0; $j < sizeof($flavoursArray); ++$j){							// create products of every flavours

			for($k = 0; $k < sizeof($toppingsArray); ++$k){						// create products with every topping

				$amount = random_int($minAmount, $maxAmount);					//randomize amount of each product [10, 30]
				$image = random_int(0, sizeof($imageArray) - 1);    			//randomize type of picture [0, 2]
				$description = $sizeArray[$i]." ".$flavoursArray[$j]			//create description with the data generated
				." ice cream with ". $toppingsArray[$k].".";

				$queryInsert = "INSERT INTO products(size, id_flavour, id_topping, amount_available, image_url, description) 
					VALUES ('$i', '$j', '$k', '$amount', '$imageArray[$image]', '$description')";
				$result = $mysqli->query($queryInsert);
					
				if(!$result){
					echo "$description could not be inserted.<br>";
					die($mysqli->error);
				}
				else
					echo "<br> $description was successfully inserted.";
			}
		}
	}

?>