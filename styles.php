<?php
	
	class Stylist{

		function outputTypePageStyles(int $i){

			$content = "";

			if($i == 0 || $i == 3){

				$content .= "<title>Gelateria di Giorgio</title>
				<style>
				html, body{
					font-family: Arial, Helvetica, sans-serif;
					text-align: center;
				    height: 100%;
				    margin: 0px;
				}
				p {
					margin: 0px;
				}
					#top{
						height: 15%;
						background-color:#d4d2d1;
						display: block;
						color: black;
						text-align: center;
						text-decoration: none;
						font-size: 17px;
					}
					#top input[type=text] {
					  float: right;
					  padding: 6px;
					  border: none;
					  margin-top: 8px;
					  margin-right: 16px;
					  font-size: 17px;
					}
					#middle{
						height: 85%;
						background-color:#edebe9;
					}
					#left{
						font-size: 18px;
						display:inline-block;
						float:left;
						text-align: left;
						width:15%;
						height:100%;
					}
					li {
						  font-size: 20px;
						  font-weight: bold;
						}
 					#center{
						display:inline-block;
						float:left;
						width:85%;
						height:100%;
					}
					#centerTop{
						height: 80%;
					}
					#logo{
						float: left;
						margin-left: 10px;
					}
					#userCart{
						float: right;
						margin-left: 10px;
						margin-right: 20px;
						margin-top: 20px;
					}
					#user{
						float: right;
						display: inline-block;
						background-color:#adaaa7;
						margin-left: 10px;
						margin-right: 10px;
						margin-top: 30px;
					}
					#search{
						display: inline-block;
						margin-top: 30px;
						float: right;
					}
					#title{
						margin-top: 30px;
						font-size: 40px;
						display: inline-block;
					}
					.button{
					  background-color: #adaaa7;
					  border: none;
					  color: black;
					  padding: 10px 25px;
					  text-align: left;
					  text-decoration: none;
					  display: inline-block;
					  font-size: 20px;
					  margin: -5px 15px;
					  cursor: pointer;
					  font-weight: bold;
					}
					.product{
					  padding: 30px 15px;
					  font-size: 12px;
					  text-align: center;
					  text-decoration: none;
  					  display: inline-block;
  					  font-weight: bold;
					}
					.productView{
					  padding: 30px 15px;
					  font-size: 20px;
					  text-align: center;
					  text-decoration: none;
  					  display: inline-block;
  					  font-weight: bold;
					}
					#pagination{				  
					  padding: 0px 1px;
					  text-align: center;
					  text-decoration: none;
  					  display: inline-block;

					}
					#pag{
					  font-size: 20px;
					  cursor: pointer;
					  font-weight: bold;
					}
					#cart{
					  font-size: 25px;
					  text-align: left;
					  margin-left: 500px;
					}
				</style>";
			}
			else if($i == 1){


			}
			else if($i == 2){

				$content .= "<title>Gelateria di Giorgio</title>
				<style>
				html, body{
					text-align: center;
				    height: 100%;
				    margin: 0px;
				}
				p {
					margin: 0px;
				}
					#top{
						height: 20%;
					}
					#middle{
						height: 80%;
						text-align: center;
						vertical-align: middle;
					}
					#insideMiddle{
						font-family: Arial, Helvetica, sans-serif;
						margin: 80px;
						width: 25%;						
						text-align: left;
						display: inline-block;
						background-color:#edebe9;
					}	
					#logo{
						width: 10%;
						height: 10%;
					}
					#title{
						font-size: 30px;
						margin: 10px;
					}
					#formContent{
						font-size: 20px;
						margin: 10px;
					}
				</style>";
			}

			return $content;
		}
	}
?>