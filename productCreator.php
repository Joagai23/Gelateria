<?php

	class ProductCreator{

		function createProduct($productData){

			$id = 0;
			$content = "";
			$size = "";
			$flavour = "";
			$topping = "";
			$amountAvailable = 0;
			$imageUrl = "";
			$description = "";

			foreach ($productData as $key => $value) {

				if($key == "size"){

					$size = $this->sizeIdToString($value);
				}

				switch ($key) {
					case 'id':
						$id = $value;
						break;
					case 'size':
						$size = $this->sizeIdToString($value);
						break;
					case 'id_flavour':
						$flavour = $this->flavourIdToString($value);
						break;
					case 'id_topping':
						$topping = $this->toppingIdToString($value);
						break;
					case 'amount_available':
						$amountAvailable = $value;
						break;
					case 'image_url':
						$imageUrl = $value;
						break;
					case 'description':
						$description = $value;
						break;
				}

				$content .= $key.": ".$value.", ";
			}

			$content ="
				<div class='product'>
					<form method='post' action='giorgioGelateria.php?typeOfPage=3&id=$id'>
						<input type='image' src='$imageUrl' alt='$id' width='175' height='175'>
						<br><br>$size $flavour $topping	
					</form>				
				</div>";

			return $content;
		}

		function sizeIdToString($i){

			switch ($i) {
				case 0:
					return "Small";		
				case 1:
					return "Middle-sized";
				case 2:
					return "Big";
			}
		}

		function flavourIdToString($i){

			switch ($i) {
				case 0:
					return "Vanilla";			
				case 1:
					return "Strawberry";
				case 2:
					return "Mint";
				case 3:
					return "Chocolate";
				case 4:
					return "Coconut";
				case 5:
					return "Stracciatella";
				case 6:
					return "Fudge";
				case 7:
					return "Yogurt";
				case 8:
					return "Lemon";
				case 9:
					return "Tangerine";
			}
		}

		function toppingIdToString($i){

			switch ($i) {
				case 0:
					return "Hot fudge";		
				case 1:
					return "Caramel sauce";
				case 2:
					return "Strawberry sauce";
				case 3:
					return "Whipped cream";;
				case 4:
					return "Sprinkles";;
				case 5:
					return "M&Ms";
				case 6:
					return "Peanuts";
				case 7:
					return "Almonds";
				case 8:
					return "Walnuts";
				case 9:
					return "Oreo cookies";
			}
		}
	}
?>