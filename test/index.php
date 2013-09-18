<?PHP
	header('Content-Type: application/xml');
try{
	require_once (dirname(__FILE__).'/../XMLSerializer.php');
	require_once (dirname(__FILE__).'/object.php');
	require_once (dirname(__FILE__).'/innerObject.php');
	
	$objects = array();
	$object = new XMLClass();
	$object->innerClasses = array();
	$innerClass = new innerClass();
	$object->innerClasses[] = $innerClass;
	$object->innerClass = new innerClass();
	$xmlMapper = new XMLSerializer();
	$objects["orders"][]  = $object;
	$objects["orders"][]  = $object;
	print_r($xmlMapper->parseObjectToXML($objects));
}
catch(Exception $e){
	echo $e->getMessage();
}

?>