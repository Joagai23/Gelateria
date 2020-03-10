<?php
	
	// Turn off all error reporting (so it doesnt notice us of the GET variables)
	//error_reporting(0);

	class ProductView{

		function getProductView($id, $mysqli){

			//get the information we need using the id
			$productSelect = "SELECT amount_available, image_url, description FROM products WHERE id='$id'";
			$productResult = $mysqli->query($productSelect);

			if(!$productResult)
				die($mysqliReference->error);

			$row = mysqli_fetch_assoc($productResult);

			$amount = $row['amount_available'];
			$image = $row['image_url'];
			$description = $row['description'];

			$view = "
			<div class='productView'>
				<img src='$image' alt='$description' width='400' height='400'>
				<br><br>
				$description
				<br>
				Amount left: $amount
			</div>
			<br>
			<a href='giorgioGelateria.php?id=$id&cart=1'>
				<input class='button' type='submit' value='Add item to cart'>
			</a>
			";

			return $view;
		}
	}
?>