<?PHP
	require_once('core/XMLBuilder.php');
	/**
	* XMLSerializer parsed objects to XML.
	* It takes all public properties and convert it in a well done XML Structure
	*
	* @author Pascal Geldmacher
	*/
	class XMLSerializer{
		private $domDocument = null;

		public function __construct(){

		}

		/**
		*
		* @param mixed $object, the object to parse in a XML structure
		* @return String
		*
		*/
		public function parseObjectToXML($object=null){
			if(!isset($object)){
				throw new Exception('object cant be empty');
			}


			$this->domDocument = new DOMDocument('1.0', 'UTF-8');
			$this->domDocument->formatOutput = true;

			$xmlBuilder = new XMLBuilder($this->domDocument);	
			$root = null;
			if(is_array($object)){
				$rootName = array_keys($object)[0];
				$root = $this->domDocument->createElement($rootName);
		
				$arrayValues = $object[$rootName];
				foreach($arrayValues as $value){
					$xmlBuilder->generateXML($value, $root);
				}
			}else{
				$root = $xmlBuilder->generateXML($object);
			}
			
			$this->domDocument->appendChild($root);

			return $this->domDocument->saveXML();
		}

	}
?>