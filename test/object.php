<?php
	class XMLClass{
		private $value0 = 'DONT SHOW';
		/**
		* @xmlattributes[type=>int,extra=>ok]
		* @var value1
		*/
		public $value1 = 1;
		public $value2 = 'HALLO';
		public $value3 = true;
		public $value4 = "Test";
		/**
		* @xmlattributes[type => array]
		*/
		public $innerClasses = array();
		public $innerClass = null;
	}
?>