XML Serializer
==============

###Requirement

- PHP 5 < above

###Introduction

The xml serializer takes an object or an array of objects and parsed it in a well formed xml structure.
If you need attributes you can define it with php annotations like @xmlattributes[key => value]

###Example

First we create two models order and customer

	class Order 
	{
		public $id = 1;
		public $name = "order";
		//private properties will ignored
		private $price = 25.55;
		/**
		* @xmlattributes[key => value, type => array]
		*/
		public $customers = null;
	}
	
	class Customer
	{
		public $firstname = "Max";
		public $lastname = "Mustermann";
	{
				
Now we instantiate an array of objects and serialize this in an xml structure.

	class index
	{
		$order = new Order();
		
		$customer = new Customer();
		$nextCustomer = new Customer();
		
		$order->$customers[] = $customer;
		$order->$customers[] = $nextCustomer;
		
		$orders["orders"][] = $order;
		$orders["orders"][] = new Order();
		
		$xmlSerializer = new XMLSerializer();
			
		$xmlSerializer->parseObjectToXML($orders);
	}

That is the result after parsing through the xml serializer

	<?xml version="1.0" encoding="UTF-8"?>
	<orders>
		<order>
			<id><![CDATA[1]]></id>
			<name><![CDATA[order]]></name>
			<customers key="value" type="array">
				<customer>
					<firstname>Max</firstname>
					<lastname>Mustermann</lastname>
				</customer>
				<customer>
					<firstname>Max</firstname>
					<lastname>Mustermann</lastname>
				</customer>
			</customers>
		</order>
		<order>
			<id><![CDATA[1]]></id>
			<name><![CDATA[order]]></name>
			<customers key="value" type="array"/>
		</order>
	</orders>
