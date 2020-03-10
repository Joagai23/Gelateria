<?php 
	
	//sends the database to filters needed to print the data
	class CategorySearcher{

		function outputList(){

			$content = "";
			$content .= "
		  	<form action='giorgioGelateria.php' method='get'>
				<ul>
				  <li>Size</li>
				  	  <input type='radio' name='size' value='small' > Small<br>
					  <input type='radio' name='size' value='middle-sized'> Middle-sized<br>
					  <input type='radio' name='size' value='big'> Big<br>
			  		<li>Flavour</li>
					  <input type='radio' name='flavour' value='vanilla'> Vanilla<br>
					  <input type='radio' name='flavour' value='strawberry'> Strawberry<br>
					  <input type='radio' name='flavour' value='mint'> Mint<br>
					  <input type='radio' name='flavour' value='chocolate'> Chocolate<br>
					  <input type='radio' name='flavour' value='coconut'> Coconut<br>
					  <input type='radio' name='flavour' value='stracciatella'> Stracciatella<br>
					  <input type='radio' name='flavour' value='fudge'> Fudge<br>
					  <input type='radio' name='flavour' value='yogurt'> Yogurt<br>
					  <input type='radio' name='flavour' value='lemon'> Lemon<br>
					  <input type='radio' name='flavour' value='tangerine'> Tangerine<br>
				    <li>Topping</li>
					  <input type='radio' name='topping' value='hot fudge'> Hot fudge<br>
					  <input type='radio' name='topping' value='caramel sauce'> Caramel sauce<br>
					  <input type='radio' name='topping' value='strawberry sauce'> Strawberry sauce<br>
					  <input type='radio' name='topping' value='whipped cream'> Whipped cream<br>
					  <input type='radio' name='topping' value='sprinkles'> Sprinkles<br>
					  <input type='radio' name='topping' value='m&ms'> M&Ms<br>
					  <input type='radio' name='topping' value='peanuts'> Peanuts<br>
					  <input type='radio' name='topping' value='almonds'> Almonds<br>
					  <input type='radio' name='topping' value='walnuts'> Walnuts<br>
					  <input type='radio' name='topping' value='oreo cookies'> Oreo cookies
				</ul>
				<input class='button' type='submit' value='Search ice cream'>
			</form>
			";

			return $content;
		}
	}
?>