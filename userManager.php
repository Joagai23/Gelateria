<?php

	class UserManager{

		function outputUserInformation($mysqli){

			if(isset($_GET["exit"])){

				//delete cookie
				setcookie("login", "1", time() - 60);

				//refresh main page
				header("Refresh:0; url=giorgioGelateria.php");
			}

			$name = $this->getName($mysqli);
			$email = $_SESSION['user'];
			$prevOrders = $this->getUserOrders($mysqli);

			$content = "<div id='cart'>
				<br><br><br>Name: $name
				<br>Email: $email
				<br>Previous orders:
				<br>{$prevOrders}
				<br>
				<form action='giorgioGelateria.php?typeOfPage=4&exit' method='post'>
					<input class='button' type='submit' value='Log off'>
				</form>
				</div>";	

			return $content;
		}

		function getUserOrders($mysqli){

			$content = "";
			$userId = $this->getUserId($mysqli);
			$userOrders = $this->getOrdersByUserId($userId, $mysqli);

			//start outputing stuff
			foreach ($userOrders as $index => $indexValue) {
				
				$date = "";
				$price = "";
				$id = 0;

				foreach ($userOrders[$index] as $key => $value) {
					
					if($key == 'date'){

						$date .= $value;
					}
					else if($key == 'order_price'){

						$price = $value;
					}
					else if($key == 'id'){

						$id = $value;
					}
				}

				$content .= "&nbsp;&nbsp;&nbsp;&nbsp;- ".$date." --> Total price: ".$price." € <br>";

				$content .= $this->getProductDescriptionsByOrderId($id, $mysqli);

			}

			return $content;
		}

		function getName($mysqli){

			$user = $_SESSION['user'];
			$querySelect = "SELECT name FROM users WHERE mail='$user'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				return mysqli_fetch_assoc($selectResult)['name'];
			}
		}

		function getUserId($mysqli){

			$user = $_SESSION['user'];
			$querySelect = "SELECT id FROM users WHERE mail='$user'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				return mysqli_fetch_assoc($selectResult)['id'];
			}
		}

		function getOrdersByUserId($id, $mysqli){

			$querySelect = "SELECT id, order_price, date FROM orders WHERE user_id='$id'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				$counter = 0;
				$orders = array();
			
				while ($rows = mysqli_fetch_assoc($selectResult)) {

					$orders[$counter]['id'] = $rows['id'];
					$orders[$counter]['order_price'] = $rows['order_price'];
					$orders[$counter]['date'] = $rows['date'];

					++$counter;
				}

				return $orders;
			}
		}

		function getProductDescriptionsByOrderId($idOrder, $mysqli){

			$content = "";

			$querySelect = "SELECT product_id, amount FROM orderproduct WHERE order_id='$idOrder'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				while ($rows = mysqli_fetch_assoc($selectResult)) {

					$amount = $rows['amount'];
					$description = $this->getProductDescriptionById($rows['product_id'], $mysqli);

					$content .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; · ".$amount ."x ".$description."<br>";
				}
			}

			return $content;
		}

		function getProductDescriptionById($productId, $mysqli){

			$querySelect = "SELECT description FROM products WHERE id='$productId'";
			$selectResult = $mysqli->query($querySelect);

			if(!$selectResult){ //something went wrong

				die($mysqli->error);
			}
			else{

				return mysqli_fetch_assoc($selectResult)['description'];
			}
		}
	}
?>