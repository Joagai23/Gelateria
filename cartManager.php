<?php

	class CartManager{

		function addProduct($idProduct, $mysqli){

			//check if cart is empty
			if (!isset($_SESSION['cart'])) {

    			$_SESSION['cart'] = array();
			}

			//add product to session cart and set cookies
			array_push($_SESSION['cart'], $idProduct);
			setcookie("cart", "2", time() + 60 * 10);

			$this->decrementAmount($idProduct, $mysqli);
		}

		//function to expire cart
		function deleteCart($mysqli){

			if (isset($_SESSION['cart'])) {

    			//increase amount of each product in cart
				foreach ($_SESSION['cart'] as $key => $value) {
					
					$this->incrementAmount($value, $mysqli);
				}

				//unset the variable
				unset ($_SESSION['cart']);
			}
		}

		//function to buy (create order and order-product)
		function buyCart($mysqli, $price){

			$user = $_SESSION['user'];

			//get user id
			$querySelect = "SELECT id FROM users WHERE mail='$user'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				$id =  mysqli_fetch_assoc($selectResult)['id'];
				$dateArray = getdate();
				$date = $dateArray['mday']."/".$dateArray['mon']."/".$dateArray['year'];
				$isPayed = 0;

				//create order
				$queryInsert = "INSERT INTO orders(user_id, order_price, date, is_payed) VALUES ('$id', '$price', '$date', '$isPayed')";
				$insertResult = $mysqli->query($queryInsert);

				if(!$insertResult){

					die($mysqli->error);
				}
				else{

					//create order-product
					//get order id
					$queryOSelect = "SELECT * FROM orders WHERE user_id='$id' AND order_price='$price' AND date='$date'";
					$selectOResult = $mysqli->query($queryOSelect);

					if(!$selectOResult){ //something went wrong

						die($mysqli->error);
					}
					else{

						$orderId = mysqli_fetch_assoc($selectOResult)['id'];
						$productArray = array();

						foreach ($_SESSION['cart'] as $key => $value) {

							$amount = 1;
					
							foreach ($_SESSION['cart'] as $key1 => $value1) {
					
								if($value == $value1 && $key < $key1){

									++$amount;
								}
								else if($value == $value1 && $key > $key1){

									--$amount;
								}
							}

							if($amount > 0){

								$productArray[$value] = $amount;
							}
						}

						//insert order-product
						foreach ($productArray as $productId => $amountOfProduct) {
							
							$queryInsertOP = "INSERT INTO orderproduct(order_id, product_id, amount) VALUES ('$orderId', '$productId', '$amountOfProduct')";
							$insertResultOP = $mysqli->query($queryInsertOP);

							if(!$insertResultOP){

								die($mysqli->error);
							}
							else{

								//unset cart
								unset ($_SESSION['cart']);

								$mailer = new Mailer();
								$mailer->sendMail("$user", "New purchase", 
								"Thanks for purchasing our delicious ice creams! Your delivery will arrive soon.");

								//refresh
								header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=5");
							}
						}
					}
				}
			}
		}

		function incrementAmount($id, $mysqli){

			//select amount by id to get the current amount
			$querySelect = "SELECT amount_available FROM products WHERE id='$id'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{ //we got the amount

				$select = mysqli_fetch_assoc($selectResult);
				$amount =  $select['amount_available'];

				$amount += 1;
				$queryUpdate = "UPDATE products SET amount_available='$amount' WHERE id='$id'";
				$updateResult = $mysqli->query($queryUpdate);

				if(!$updateResult){ //something went wrong

					die($mysqli->error);
				}
			}
		}

		function decrementAmount($id, $mysqli){

			//select amount by id to get the current amount
			$querySelect = "SELECT amount_available FROM products WHERE id='$id'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				//unset id from cart
				if (($key = array_search($id, $_SESSION['cart'])) !== false) {
    				unset($_SESSION['cart'][$key]);
				}

				die($mysqli->error);
			}
			else{ //we got the amount

				$select = mysqli_fetch_assoc($selectResult);
				$amount =  $select['amount_available'];

				$amount -= 1;
				$queryUpdate = "UPDATE products SET amount_available='$amount' WHERE id='$id'";
				$updateResult = $mysqli->query($queryUpdate);

				if(!$updateResult){ //something went wrong

					//unset id from cart
					if (($key = array_search($id, $_SESSION['cart'])) !== false) {
	    				unset($_SESSION['cart'][$key]);
					}
				}
				else{

					//update cookie for cart
					setcookie("cart", "2", time() + 60 * 10);
				}
			}
		}

		function outputCartPage($mysqli){

			$content = "";

			//get number of items in cart
			$numberOfProducts = 0;
			$products = "";

			if (isset($_SESSION['cart'])) {

    			//increase amount of each product in cart
				foreach ($_SESSION['cart'] as $key => $value) {
					
					++$numberOfProducts;

					if(isset($value)){

						$products .= $this->outputProductInfo($value, $mysqli);
					}
				}
			}

			$totalPrice = $numberOfProducts * 3;

			if(isset($_GET["buy"])){

				$content = "<div id='cart'>
				<br><br><br>Thanks for your purchase. <br> We hope to see you soon.
				</div>";

				$this->buyCart($mysqli, $totalPrice);
			}
			else if(isset($_GET["empty"])){

				$content = "Cart successfully emptied.";
				$content = "<div id='cart'>
				<br><br><br> Cart successfully emptied.
				</div>";

				$this->deleteCart($mysqli);
			}
			else{

				$content .= "
				<div id='cart'>
					<br>
					$numberOfProducts products in your cart: $products
					<br>
					Total price: $totalPrice €
				</div>";

				if($totalPrice != 0){ //cant buy

					$content .= "
					<br>
					<form action='giorgioGelateria.php?typeOfPage=1&buy' method='post'>
						<input class='button' type='submit' value='Buy'>
					</form>
					<form action='giorgioGelateria.php?typeOfPage=1&empty' method='post'>
						<input class='button' type='submit' value='Empty cart'>
					</form>
					";
				}
			}

			return $content;
		}

		function outputProductInfo($id, $mysqli){

			$querySelect = "SELECT description FROM products WHERE id='$id'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				$description =  mysqli_fetch_assoc($selectResult)['description'];
				return "<br> $id: $description";
			}
		}
	}
?>