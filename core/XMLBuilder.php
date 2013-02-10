<?PHP
	class XMLBuilder {
		
		/**
		* looking for annotations with the @xmlattributes take.
		*/
		private static $ATTRIBUTES_NAME = "@xmlattributes";

		private $domDocument = null;
		
		public function __construct($domDocument){
			$this->domDocument = $domDocument;
		}

		/**
		* Is the main function to generate the XML structure.
		* @param Object $object, the current object which should be parsed in an XML structure
		* @param 
		*/
		public function generateXML($object, $parentElement = null){
			$reflection = new ReflectionClass($object);
			
			//First lets check the class name and use the name as root name
			$innerRootElement = $this->domDocument->createElement($reflection->name);
			
			$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
			
			//Now iterate over the properties in the class
			foreach($properties as $property){
				//if we found an object, we must call the same function.
				$varName = $property->getName();
				$varValue = $property->getValue($object);
				if(is_object($varValue)){
					$this->generateXML($varValue, $innerRootElement);
				}
				//if it's no object and no array, we have a basic value like string / int / boolean
				elseif(!is_array($varValue)){
					$element = $this->domDocument->createElement($varName);
					
					//first lets check if our $element has any attributes to add
					if($property->getDocComment()){
						$this->addAttributes($property, $element);
					}

					if(!is_null($varValue))
						$element->appendChild($this->domDocument->createCDATASection($varValue));

					$innerRootElement->appendChild($element);
				}
				//TODO: it doesnt' work for deeper objects with attributes
				//Now, we know we have an array in our value, so lets check it out
				else{
					$element = $this->domDocument->createElement($varName);
					
					//first lets check if our $element has any attributes to add
					if($property->getDocComment()){
						$this->addAttributes($property, $element);
					}

					
					//so lets take a look to this value
					//is it an array ?
					if(is_array($varValue)){
						//manage the array content
						$this->manageArrayValues($varValue, $element);
					}
					//or an object ?
					elseif(is_object($varValue)){
						//start the generateXML function again
						$this->generateXML($varValue,$element);
					}else{
						//its a basic value ? ok, let's write it down!
						$element->appendChild($this->domDocument->createCDATASection($varValue));
					}
					

					$innerRootElement->appendChild($element);
				}
			}

			if($parentElement == null){
				return $innerRootElement;
			}else{
				$parentElement->appendChild($innerRootElement);
				return $parentElement;
			}
		}

		/**
		* if we have an array in our array, lets manage it
		**/
		private function manageArrayValues($varValue,$parentElement){
			foreach($varValue as $arrayName => $arrayValue){
				if(is_object($arrayValue)){
					$this->generateXML($arrayValue, $parentElement);
				}else{
					$parentElement->appendChild($this->domDocument->createCDATASection($arrayValue));
				}
			}
		}

		/**
		* Sometimes u want add some attributes to your nodes. This function checks your array after
		* the value "attributes". If we can found it, lets create attributes
		**/
		private function addAttributes($property, $element){

			$attributes = $property->getDocComment();
			$attributes = $this->filterAttributeInformation($attributes);
			
			foreach($attributes as $name => $value){
				$attribute = $this->domDocument->createAttribute($name);

				if(isset($value)){
					$attribute->value = $value;
				}

				$element->appendChild($attribute);
			}
		
		}

		private function filterAttributeInformation($attributes){
			if(strpos($attributes, self::$ATTRIBUTES_NAME)){
				$filtered = str_replace(array("/","*"), "", $attributes);
				$filtered = substr($filtered, strpos($filtered, self::$ATTRIBUTES_NAME));
				$filtered = strstr($filtered, ']', true);
				$filtered = str_replace(array(self::$ATTRIBUTES_NAME,'[',']','*','/','\\'), "", $filtered);
				$filtered = explode(',', $filtered);
				foreach($filtered as $attribute){
					$values = explode('=>', $attribute);
					$filteredAttributes[trim($values[0])] = trim($values[1]);
				}	

				return $filteredAttributes;
			}
		}			
	}
?>