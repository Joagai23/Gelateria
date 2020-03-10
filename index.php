<?php

	include "pagePrinter.php";

	class Index{

		private $pagePrinterReference;
		private $headerStyles;
		private $bodyContent;

		function __construct(){

			$this->pagePrinterReference = new PagePrinter();
		}

		function updateReferences(int $formType, int $typeOfPage){

			$this->pagePrinterReference->updateTypeOfPage($typeOfPage);
			$this->pagePrinterReference->updateDataFormType($formType);
			$this->headerStyles = $this->pagePrinterReference->outputHeader();
			$this->bodyContent = $this->pagePrinterReference->outputBody();
		}

		//return the string containing all the html information
		function outputHTML(int $formType, int $typeOfPage){
			
			$this->updateReferences($formType, $typeOfPage);			

			$content = "";
			$content .= $this->headerStyles;
			$content .= $this->bodyContent;
			return $content;
		}
	}
?>