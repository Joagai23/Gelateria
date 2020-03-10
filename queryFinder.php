<?php

	include "productCreator.php";

	class QueryFinder{

		private $itemsPerPage = 10;
		private $numberOfPages;
		private $currentPage;
		private $sizeDictionary = array("small", "middle-sized", "big");
		private	$flavourDictionary = array("vanilla", "strawberry", "mint", "chocolate", "coconut", "stracciatella", "fudge", "yogurt", "lemon", "tangerine");
		private	$toppingDictionary = array("hot fudge", "caramel sauce", "strawberry sauce", "whipped cream", "sprinkles", "m&ms", "peanuts", "almonds", "walnuts", "oreo cookies");
		private $productCreatorReference;

		function __construct(){

			$this->productCreatorReference = new ProductCreator();
		}

		function findProducts($size, $flavour, $topping, $mysqliReference, $search){

			$content = "";
			$topContent = "";
			$bottomContent = "";

			if($search == ""){

				$topContent = $this->getItems($size, $flavour, $topping, $mysqliReference);
				
			}else{

				$topContent = $this->getSearchValue($search, $mysqliReference);
			}


			$bottomContent = $this->getBottomOfPage();

			$content .= "
			<div id='centerTop'>
				{$topContent}
			</div>
			<div id='centerBottom'>
				{$bottomContent}
		    </div>";

		    return $content;
		}

		function getItems($size, $flavour, $topping, $mysqliReference){

			$size = $this->stringSizeToID($size);
			$flavour = $this->stringFlavourToID($flavour);
			$topping = $this->stringToppingToID($topping);

			$products = array();

			//arrange order
			if(isset($_GET["order"])){

				$order = $_GET["order"];
			}
			else{

				$order = "s";
			}

			$order = $this->charOrderToString($order);

			//select depending on the choices

			$productSelect = "";
			
			if ($size == -1 && $flavour == -1 && $topping != -1){

				$productSelect = "SELECT * FROM products WHERE id_topping='$topping' ORDER BY $order";
			}
			else if($size == -1 && $flavour != -1 && $topping == -1){

				$productSelect = "SELECT * FROM products WHERE id_flavour='$flavour' ORDER BY $order";
			}
			else if($size == -1 && $flavour != -1 && $topping != -1){
				
				$productSelect = "SELECT * FROM products WHERE id_topping='$topping' AND id_flavour='$flavour' ORDER BY $order";
			}
			else if($size != -1 && $flavour == -1 && $topping == -1){
				
				$productSelect = "SELECT * FROM products WHERE size='$size' ORDER BY $order";
			}
			else if($size != -1 && $flavour == -1 && $topping != -1){
				
				$productSelect = "SELECT * FROM products WHERE id_topping='$topping' AND size='$size' ORDER BY $order";
			}
			else if($size != -1 && $flavour != -1 && $topping == -1){
				
				$productSelect = "SELECT * FROM products WHERE id_flavour='$flavour' AND size='$size' ORDER BY $order";
			}
			else if($size != -1 && $flavour != -1 && $topping != -1){

				$productSelect = "SELECT * FROM products WHERE id_topping='$topping' AND size='$size' AND id_flavour='$flavour' ORDER BY $order";
			}
			else{

				$productSelect = "SELECT * FROM products ORDER BY $order";
			}

			$productResult = $mysqliReference->query($productSelect);

			if(!$productResult)
				die($mysqliReference->error);

			$counter = 0;
			
			while ($rows = mysqli_fetch_assoc($productResult)) {

				$products[$counter]['id'] = $rows['id'];
				$products[$counter]['size'] = $rows['size'];
				$products[$counter]['id_flavour'] = $rows['id_flavour'];
				$products[$counter]['id_topping'] = $rows['id_topping'];
				$products[$counter]['amount_available'] = $rows['amount_available'];
				$products[$counter]['image_url'] = $rows['image_url'];
				$products[$counter]['description'] = $rows['description'];

				++$counter;
			}

			$content = "";

			$this->calculateNumberOfPages($counter);

			if(isset($_GET["page"])){

				$this->currentPage = $_GET["page"];
			}
			else{

				$this->currentPage = 1;
			}

			foreach ($products as $key => $value) {
				//check that key is between min and max per page
				if($key >= ($this->currentPage - 1) * $this->itemsPerPage && $key < $this->currentPage * $this->itemsPerPage){

					$content .= $this->productCreatorReference->createProduct($products[$key]);
				}
			}

			return $content;
		}

		function stringSizeToID($size){

			if($size == "small"){

				return 0;
			}
			else if($size == 'middle-sized'){

				return 1;
			}
			else if($size == 'big'){

				return 2;
			}
			else{

				return -1;
			}
		}

		function stringFlavourToID($flavour){

			switch ($flavour) {
				case 'vanilla':
					return 0;
					break;
				case 'strawberry':
					return 1;
					break;
				case 'mint':
					return 2;
					break;
				case 'chocolate':
					return 3;
					break;
				case 'coconut':
					return 4;
					break;
				case 'stracciatella':
					return 5;
					break;
				case 'fudge':
					return 6;
					break;
				case 'yogurt':
					return 7;
					break;
				case 'lemon':
					return 8;
					break;
				case 'tangerine':
					return 9;
					break;
				default:
					return -1;
			}
		}

		function stringToppingToID($flavour){

			switch ($flavour) {
				case 'hot fudge':
					return 0;
					break;
				case 'caramel sauce':
					return 1;
					break;
				case 'strawberry sauce':
					return 2;
					break;
				case 'whipped cream':
					return 3;
					break;
				case 'sprinkles':
					return 4;
					break;
				case 'm&ms':
					return 5;
					break;
				case 'peanuts':
					return 6;
					break;
				case 'almonds':
					return 7;
					break;
				case 'walnuts':
					return 8;
					break;
				case 'oreo cookies':
					return 9;
					break;
				default:
					return -1;
			}
		}

		function charOrderToString($c){

			if($c == "s"){

				return "size";
			}
			else if($c == "t"){

				return "id_topping";
			}
			else if($c == "f"){

				return "id_flavour";
			}
		}

		function calculateNumberOfPages(int $numberItems){

			$this->numberOfPages = $numberItems / $this->itemsPerPage;

			if($this->numberOfPages < 1)
				$this->numberOfPages = 1;
		}

		function getBottomOfPage(){

			$size = -1;
			$flavour = -1;
			$topping = -1;
			$order = "s";

			//get form values so the page never updates as new
			if(isset($_GET["size"])){

				$size = $_GET["size"];
			}
			if(isset($_GET["flavour"])){

				$flavour = $_GET["flavour"];
			}
			if(isset($_GET["topping"])){

				$topping = $_GET["topping"];
			}
			if(isset($_GET["order"])){

				$order = $_GET["order"];
			}

			$content = "";

			//show the page buttons
			$pagination = "<br>";

			for($i = 1; $i <= $this->numberOfPages; ++$i){

				$pagination .= "<form id='pagination' action='giorgioGelateria.php?size=$size&flavour=$flavour&topping=$topping&page=$i&order=$order' method='post'>"
				."<input id='pag'name='page' type='submit' value='$i'>"
				."</form>";
			}

			//show order buttons
			$ord = "";
			$ord .= "<form id='pagination' action='giorgioGelateria.php?size=$size&flavour=$flavour&topping=$topping&page=$this->currentPage&order=s' method='post'>"
				 ."<input id='pag'name='page' type='submit' value='Size'>"
				 ."</form>";
			$ord .= "<form id='pagination' action='giorgioGelateria.php?size=$size&flavour=$flavour&topping=$topping&page=$this->currentPage&order=f' method='post'>"
				 ."<input id='pag'name='page' type='submit' value='Flavour'>"
				 ."</form>";
			$ord .= "<form id='pagination' action='giorgioGelateria.php?size=$size&flavour=$flavour&topping=$topping&page=$this->currentPage&order=t' method='post'>"
				 ."<input id='pag'name='page' type='submit' value='Topping'>"
				 ."</form>";

			$content .= $pagination;
			$content .= "<br>".$ord;

			return $content;
		}

		function getSearchValue($search, $mysqliReference){

			$wordArray = str_word_count($search, 2);
			$size = "null";
			$flavour = "null";
			$topping = "null";
			$content = "null";

			foreach ($wordArray as $key => $value) {
				
				$searchValue = $this->searchDictionary($value);
				$word = strtolower($value);

				if($searchValue == 0){

					foreach ($this->sizeDictionary as $key => $value0) {
				
						if($value0 == $word){

							$size = $word;
						}
					}
				}
				else if($searchValue == 1){
					
					foreach ($this->flavourDictionary as $key => $value0) {
				
						if($value0 == $word){

							$flavour = $word;
						}
					}
				}
				else if($searchValue == 2){
					
					foreach ($this->toppingDictionary as $key => $value0) {
				
						if($value0 == $word){

							$topping = $word;
						}
					}
				}
			}

			$this->setForms($size, $flavour, $topping);
			$content = $this->getItems($size, $flavour, $topping, $mysqliReference);

			return $content;
		}

		function searchDictionary($word){

			$isSizeTerm = false;
			$isFlavourTerm = false;
			$isToppingTerm = false;

			$word = strtolower($word);
			

			foreach ($this->sizeDictionary as $key => $value) {
				
				if($value == $word){
					$isSizeTerm = true;
				}
			}

			foreach ($this->flavourDictionary as $key => $value) {
				
				if($value == $word){
					$isFlavourTerm = true;
				}
			}

			foreach ($this->toppingDictionary as $key => $value) {
				
				if($value == $word){
					$isToppingTerm = true;
				}
			}

			if($isSizeTerm){

				return 0;
			}
			else if($isFlavourTerm){

				return 1;
			}
			else if($isToppingTerm){

				return 2;
			}
			else{

				return -1;
			}
		}

		function setForms($size, $flavour, $topping){

			if($size != "null"){

				$_GET["size"] = $size;
			}

			if($flavour != "null"){

				$_GET["flavour"] = $flavour;
			}

			if($topping != "null"){

				$_GET["topping"] = $topping;
			}
		}
	}
?>