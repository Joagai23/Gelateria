<?php
	
	session_start();

	include "sendMail.php";
	include "categorySearcher.php";
	include "styles.php";
	include "queryFinder.php";
	include "productView.php";
	include "cartManager.php";
	include "userManager.php";

	class PagePrinter{

		private $dataFormContent = "";
		private $dataFormType = 0;
		private $typeOfPage = 0;
		private $insideMiddleHeight = "";
		private $mysqli;
		private $errorMessage = "";
		private $categories = "";
		private $type0PageStyles = "";
		private $type2PageStyles = "";
		private $queryFinderReference;
		private $productViewReference;
		private $cartManager;
		private $userManager;

		function __construct(){

			$categorySearcher = new CategorySearcher();
			$stylist = new Stylist();

			$this->productViewReference = new ProductView();
			$this->categories = $categorySearcher->outputList();
			$this->type0PageStyles= $stylist->outputTypePageStyles(0);
			$this->type2PageStyles = $stylist->outputTypePageStyles(2);
			$this->queryFinderReference = new QueryFinder();
			$this->cartManager = new CartManager();
			$this->userManager = new UserManager();

			//check cart expiration cookie
			if(!isset($_COOKIE['login']) || !isset($_COOKIE['cart'])){

				//unset cart
				$this->connect_to_database();
				$this->cartManager->deleteCart($this->mysqli);				
			}
		}

		function getMySqli(){

			return $this->mysqli;
		}

		function updateDataFormType(int $dataFormType){

			$this->dataFormType = $dataFormType;
		}

		function updateTypeOfPage(int $typeOfPage){

			$this->typeOfPage = $typeOfPage;
		}

		public function outputHeader(){
			
			$content = "";

			if($this->typeOfPage == 0){

				//print search page styles
				$content .= $this->type0PageStyles;
			}
			else if($this->typeOfPage == 1){

				//print shopping cart styles
				$content .= $this->type0PageStyles;
			}
			else if($this->typeOfPage == 2){

				//print data management styles
				$content .= $this->type2PageStyles;			
			}
			else if($this->typeOfPage == 3){

				//product view
				$content .= $this->type0PageStyles;
			}
			else{
				//user view
				$content .= $this->type0PageStyles;
			}

			return $content;
		}

		public function outputBody(){ //search page
			
			//variables to print info	
			$content = "";
			$center = "";

			//variable for queries
			$size = "";
			$flavour = "";
			$topping = "";

			if($this->typeOfPage == 0){

				if(isset($_POST["search"])){

					//look for products
					$this->connect_to_database();
					$center = $this->queryFinderReference->findProducts($size, $flavour, $topping, $this->mysqli, $_POST["search"]);

				}
				else{ 

					if(isset($_GET["size"])){

						$size = $_GET["size"];
					} 

					if(isset($_GET["flavour"])){

						$flavour = $_GET["flavour"];
					}

					if(isset($_GET["topping"])){

						$topping = $_GET["topping"];
					}

					//look for products
					$this->connect_to_database();
					$center = $this->queryFinderReference->findProducts($size, $flavour, $topping, $this->mysqli, "");
				}

				if(isset($_GET["id"]) && isset($_GET["cart"])){

					//check if user is logged in
					if(isset($_COOKIE['login'])){

						$id = $_GET["id"];
						//add item to session
						$this->connect_to_database();
						$this->cartManager->addProduct($id, $this->mysqli);

					}else{

						//log in
						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=0");
					}

				}

				//print search page body
				$content .= "
				<div id='top'>
					<div id='logo'>
						<a href='?typeOfPage=0'>	
							<img src='Images/giorgioGelateriaFondo.png' alt='logo' width='175' height='105'>		
						</a>
					</div>
					<div id='title'>
						Gelateria di Giorgio
					</div>";

						if(isset($_COOKIE['login'])){
							
							$content .= "<div id='userCart'>
											<a href='?typeOfPage=1'>	
												<img src='Images/cart.png' alt='cart' width='75' height='75'>		
											</a>
										</div>";

							$content .= "<div id='user'>
											<a href='?typeOfPage=4'>	
												<img src='Images/yourAccount.png' alt='yourAccount' width='156' height='50'>	
											</a>
										</div>";
						}else{

							$content .= "<div id='userCart'>
											<a href='?typeOfPage=2'>	
												<img src='Images/cart.png' alt='cart' width='75' height='75'>		
											</a>
										</div>";

							$content .= "<div id='user'>
											<a href='?typeOfPage=2'>	
												<img src='Images/signIn.png' alt='signIn' width='156' height='50'>	
											</a>
										</div>";
						}			

					$content .= "
					<div id = 'search'>
						<form method='post' action='giorgioGelateria.php?typeOfPage=0'>
							<input type='text' name='search' placeholder='Search..'>
						</form>
					</div>
				</div>
				<div id='middle'>
					<div id='left'>  {$this->categories} </div>
					<div id='center'>
						$center 
					</div>
				</div>";
			}
			else if($this->typeOfPage == 1){

				$this->connect_to_database();
				$center = $this->cartManager->outputCartPage($this->mysqli);

				//print shopping cart body
				$content .= "
				<div id='top'>
					<div id='logo'>
						<a href='?typeOfPage=0'>	
							<img src='Images/giorgioGelateriaFondo.png' alt='logo' width='175' height='105'>		
						</a>
					</div>
					<div id='title'>
						Gelateria di Giorgio
					</div>
				</div>
				<div id='middle'>
					<div id='center'>
						{$center}
					</div>
				</div>";
			}
			else if($this->typeOfPage == 2){

				$this->setForm();

				//print data management body
				$content .= "<div id='top'>		
					<a href='?typeOfPage=0'>	
						<img src='Images/giorgioGelateriaFondo.png' alt='logo' width='400' height='240'>		
					</a>
				</div>
				<div id='middle'>
					<div id='insideMiddle'>
						<div id='formContent'>				
							{$this->dataFormContent}
						</div>
					</div>
				</div>";
			}
			else if($this->typeOfPage == 3){ //view item

				if(isset($_GET["id"])){

					//look for products
					$this->connect_to_database();
					$center = $this->productViewReference->getProductView($_GET["id"], $this->mysqli);
				}

				$content .= "
				<div id='top'>
					<div id='logo'>
						<a href='?typeOfPage=0'>	
							<img src='Images/giorgioGelateriaFondo.png' alt='logo' width='175' height='105'>		
						</a>
					</div>
					<div id='title'>
						Gelateria di Giorgio
					</div>
					<div id='userCart'>
						<a href='?typeOfPage=1'>	
							<img src='Images/cart.png' alt='cart' width='75' height='75'>		
						</a>
					</div>
					<div id='user'>";

						if(isset($_COOKIE['login'])){
							
							$content .= "<a href='?typeOfPage=4'>	
											<img src='Images/yourAccount.png' alt='yourAccount' width='156' height='50'>	
										</a>";
						}else{

							$content .= "<a href='?typeOfPage=2'>	
											<img src='Images/signIn.png' alt='signIn' width='156' height='50'>	
										</a>";
						}			

					$content .="
					</div>
					<div id = 'search'>
						<form method='post' action='giorgioGelateria.php?typeOfPage=0'>
							<input type='text' name='search' placeholder='Search..'>
						</form>
					</div>
				</div>
				<div id='middle'>
					{$center}
				</div>";
			}
			else{ //user (data name, mail, previous orders)

				if(isset($_COOKIE['login'])){ //user is logged in
							
					$this->connect_to_database();		
					$center = $this->userManager->outputUserInformation($this->mysqli);

					$content .= "
					<div id='top'>
						<div id='logo'>
							<a href='?typeOfPage=0'>	
								<img src='Images/giorgioGelateriaFondo.png' alt='logo' width='175' height='105'>		
							</a>
						</div>
						<div id='title'>
							Gelateria di Giorgio
						</div>
						<div id='userCart'>
							<a href='?typeOfPage=1'>	
								<img src='Images/cart.png' alt='cart' width='75' height='75'>		
							</a>
						</div>
					</div>
					<div id='middle'>
						<div id='center'>
							{$center}
						</div>
					</div>";
				}
				else{

					header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2");
				}

				
			}

			return $content;
		}

		private function setForm()
		{
			if($this->dataFormType == 0){ //log in

				if(isset($_POST["email"]) && isset($_POST["password"])){

					$this->connect_to_database();

					$email = $this->mysql_fix_string($this->mysqli, $_POST["email"]);
					$pass = $this->mysql_fix_string($this->mysqli, $_POST["password"]);

					$stmt = $this->mysqli->prepare("SELECT * FROM users WHERE password =? AND mail =?");

					$hashedPassword = hash("md5", $pass);

					$stmt->bind_param("ss", $hashedPassword, $email);
					$stmt->execute();
					$result = $stmt->get_result();

					if($result && $result->num_rows == 1){ //account exists
						
						//lets chech if account  has been confirmed
						$querySelect = "SELECT * FROM users WHERE status = '0' AND mail = '$email'";
						$resultOfSelect = $this->mysqli->query($querySelect);

						if(!$resultOfSelect){
							//account wasnt confirmed
							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=3");
						}else{
							
							//successful login
							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=0");
							
							//START SESSION
							$_SESSION["user"] = "$email";
							setcookie("login", "1", time() + 60 * 30);
						}						
					}
					else{ //account doesnt exist

						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=1");
					}

				}else{

					$this->dataFormContent .= "
									<fieldset>
									<legend>
											<div id='title'>
												Sign in
											</div>
										</legend>
									<form method='post' action='giorgioGelateria.php?typeOfPage=2&formType=0'>
										<label> Email </label>
										<br>
										<input type='text' size='45' name='email' style='height:30px;'>
										<br><br>
										<label> Password </label>
										<br>
										<input type='password' size='45' name='password' style='height:30px;'>
										<br><br>
										<input type='submit' value='Sign in' style='height:30px; width:325px'>
										<br>								
									</form>
										<a href='?formType=1&typeOfPage=2'>
											<button type='button' style='height:30px; width:325px'>Create an account</button>
										</a>
									</fieldset>
									<br>
									<a href='?formType=2&typeOfPage=2' style='font-size: 15px; color: black;'>
											Forgot your password?
									</a>";
				}
			}
			else if($this->dataFormType == 1){ //create account

				if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password2"]) && isset($_POST["user"]) 
					&& $_POST["password"] == $_POST["password2"]){

					$this->connect_to_database();

					$email = $this->mysql_fix_string($this->mysqli, $_POST["email"]);
					$pass = $this->mysql_fix_string($this->mysqli, $_POST["password"]);
					$name = $this->mysql_fix_string($this->mysqli, $_POST["user"]);

					//check if email is already in the database
					$select = $this->mysqli->prepare("SELECT * FROM users WHERE mail =?");

					$select->bind_param("s", $email);
					$select->execute();
					$resultOfSelect = $select->get_result();

					if($resultOfSelect && $resultOfSelect->num_rows == 1){
						
						//email already in database
						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=0");
					}
					else{ //new mail

						//user type = 1 (client)
						$userType = 1;
						//status = 1 (not active)
						$status = 1;
						//confirmation code
						$digits = 5;
						$code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

						//hash and salt the password before storing it in the database ("md5")
						$hashedPassword = hash("md5", $pass);

						$queryInsert = "INSERT INTO users(user_type, name, mail, password, status, confirmation_code) 
						VALUES ('$userType', '$name', '$email', '$hashedPassword', '$status', '$code')";
						$result = $this->mysqli->query($queryInsert);

						if(!$result){
							die($this->mysqli->error);
						}
						else{

							//send mail to confirm the account
							$mailer = new Mailer();
							$mailer->sendMail("$email", "Confirm account", 
								"Your account has been successfully created. Please enter the following code {$code} in the link below
								to confirm your account and start shopping. <br>
								<a href='http://127.0.0.1/IA_2/giorgioGelateria.php?typeOfPage=2&formType=3'> Click to confirm. </a>");

							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=5");
						}
					}
				}else{

					$this->dataFormContent .= "								
								<form method='post' action='giorgioGelateria.php?typeOfPage=2&formType=1'>
								<fieldset>
									<legend>
										<div id='title'>
											Create account
										</div>
									</legend>
									<label> Your name </label>
									<br>
									<input type='text' size='45' name='user' style='height:30px;'>
									<br><br>
									<label> Email </label>
									<br>
									<input type='text' size='45' name='email' style='height:30px;'>
									<br><br>
									<label> Password </label>
									<br>
									<input type='password' size='45' name='password' style='height:30px;'>
									<br><br>
									<label> Re-enter password </label>
									<br>
									<input type='password' size='45' name='password2' style='height:30px;'>
									<br><br>
									<input type='submit' value='Create your account' style='height:30px; width:325px'>
								</fieldset>
								</form>";
				}		
			}
			else if($this->dataFormType == 2){ //forgot password

				if(isset($_POST["email"])){

					$this->connect_to_database();

					$email = $this->mysql_fix_string($this->mysqli, $_POST["email"]);

					//check if email is already in the database
					$select = $this->mysqli->prepare("SELECT * FROM users WHERE mail =?");

					$select->bind_param("s", $email);
					$select->execute();
					$resultOfSelect = $select->get_result();

					if($resultOfSelect && $resultOfSelect->num_rows == 1){ //email is in the database
						
						//change status in database to 2 (CHANGING PASSWORD)
						$status = 2;

						$queryUpdate = "UPDATE users SET status='$status' WHERE mail='$email'";
						$result = $this->mysqli->query($queryUpdate);

						if(!$result){ //something went wrong

							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=2");
						}
						else{ //change was done

							//now send email
							$mailer = new Mailer();
							$mailer->sendMail("$email", "Password assistance", 
								"There has been a login issue with your account. If it was you, click on the link below to change your password. <br>
								<a href='http://127.0.0.1/IA_2/giorgioGelateria.php?typeOfPage=2&formType=4'> Click to change password. </a>");

							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=5");
						}
					}
					else{ //new email --> send to create account

						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=1");
					}
				}
				else{

					$this->dataFormContent .= "								
								<form method='post' action='giorgioGelateria.php?typeOfPage=2&formType=2'>
								<fieldset>
									<legend>
										<div id='title'>
											Password assistance
										</div>
									</legend>
									<label> Email </label>
									<br>
									<input type='text' size='45' name='email' style='height:30px;'>
									<br><br>
									<input type='submit' value='Continue' style='height:30px; width:325px'>
								</fieldset>
								</form>";
				}
			}
			else if($this->dataFormType == 3){ //activate account

				if(isset($_POST["email"]) && isset($_POST["code"])){

					//if the code in the mail is right, change the code value to 0
					$this->connect_to_database();

					$email = $this->mysql_fix_string($this->mysqli, $_POST["email"]);
					$code = $this->mysql_fix_string($this->mysqli, $_POST["code"]);
					$status = 0; //confirmed user

					$queryUpdate = "UPDATE users SET status='$status' WHERE mail='$email' AND confirmation_code='$code'";
					$result = $this->mysqli->query($queryUpdate);

					if(!$result){ //something went wrong

						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=3");
					}
					else{ //change was done

						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=0");
					}
				}
				else{

					$this->dataFormContent .= "								
								<form method='post' action='giorgioGelateria.php?typeOfPage=2&formType=3'>
								<fieldset>
									<legend>
										<div id='title'>
											Activate account
										</div>
									</legend>
									<label> Email </label>
									<br>
									<input type='text' size='45' name='email' style='height:30px;'>
									<br><br>
									<label> Code </label>
									<br>
									<input type='text' size='45' name='code' style='height:30px;'>
									<br><br>
									<input type='submit' value='Continue' style='height:30px; width:325px'>
								</fieldset>
								</form>";
				}
			}
			else if($this->dataFormType == 4){ //set new password

				if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password"])
					&& $_POST["password"] == $_POST["password2"]){

					$this->connect_to_database();

					$email = $this->mysql_fix_string($this->mysqli, $_POST["email"]);
					$pass = $this->mysql_fix_string($this->mysqli, $_POST["password"]);
					$status = 2;

					//check if email is already in the database with code 2
					$select = $this->mysqli->prepare("SELECT * FROM users WHERE mail =? AND status=?");

					$select->bind_param("si", $email, $status);
					$select->execute();
					$resultOfSelect = $select->get_result();

					if($resultOfSelect && $resultOfSelect->num_rows == 1){ //email is correct and ready to change passwords
						
						//hash and salt the password before storing it in the database ("md5")
						$hashedPassword = hash("md5", $pass);

						//update the database with the new password and send the user a confirmation email
						$queryUpdate = "UPDATE users SET password='$hashedPassword', status='0' WHERE mail='$email'";
						$result = $this->mysqli->query($queryUpdate);

						if(!$result){ //something went wrong

							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=4");
						}
						else{ //change was done --> send email

							$mailer = new Mailer();
							$mailer->sendMail("$email", "Password assistance", 
								"Your password was successfully updated. Please click on the link below to sign in your account and start shopping.<br>
								<a href='http://127.0.0.1/IA_2/giorgioGelateria.php?typeOfPage=2&formType=0'> Click to sign in. </a>");

							header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=5");
						}
					}
					else{ //either mail is incorrect or status is not the right one --> login page

						header("Refresh:0; url=giorgioGelateria.php?typeOfPage=2&formType=0");
					}
				}
				else{

					$this->dataFormContent .= "								
								<form method='post' action='giorgioGelateria.php?typeOfPage=2&formType=4'>
								<fieldset>
									<legend>
										<div id='title'>
											Change password
										</div>
									</legend>
									<label> Email </label>
									<br>
									<input type='text' size='45' name='email' style='height:30px;'>
									<br><br>
									<label> New password </label>
									<br>
									<input type='password' size='45' name='password' style='height:30px;'>
									<br><br>
									<label> Re-enter password </label>
									<br>
									<input type='password' size='45' name='password2' style='height:30px;'>
									<br><br>
									<input type='submit' value='Continue' style='height:30px; width:325px'>
								</fieldset>
								</form>";
				}

			}
			else if($this->dataFormType == 5){

				$this->dataFormContent .= "								
								
								<fieldset>
									<legend>
										<div id='title'>
											Message sent
										</div>
									</legend>
									A message was successfully sent to your email.
									Please check your inbox.
								</fieldset>";
			}
		}

		private function mysql_fix_string($mysqli, $string){

			if(get_magic_quotes_gpc()){
				$string = stripslashes($string);
			}
			return $mysqli->real_escape_string($string);
		}	

		public function connect_to_database(){ //change variables when uploading

			$hostname = "localhost";
			$user = "jorge";
			$password = "contraseña";
			$db = "ice_cream_shop"; 

			$this->mysqli = new mysqli($hostname, $user, $password, $db);

			if($this->mysqli->connect_errno != 0){
				echo "Error trying to connect to the database <br>";
				die('Connect Error: ' . $mysqli->connect_errno);
			}
		}
	}
?>