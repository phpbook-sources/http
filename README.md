    
+ [About Http](#about-http) 
+ [Composer Install](#composer-install)
- [Examples](#examples)
	* [Declare Configurations](#declare-configurations)
	* [Declare Application Elements](#declare-application-elements)
	* [Declare Application Controllers](#declare-application-controllers)
	* [Register Application Request Categories](#register-application-request-categories)
	* [Register Application Request Resources](#register-application-request-resources)
	* [Http URI Get Path](#http-uri-get-path)
	* [Start Application Http](#start-application-http)
	* [Application Apache htaccess File](#application-apache-htaccess-file)
	* [Application Nginx File](#application-nginx-file)

### About Http

- PHPBook Http is a lightweight and fast http PHP library to create apps and apis and provides auto documentation.  

### Composer Install

	composer require phpbook/http

# Examples

##### Declare Configurations

```php
<?php

/********************************************
* 
*  Declare Configurations
* 
* ******************************************/

//APP name
//Default null
\PHPBook\Http\Configuration\Meta::setName('APP');

//APP version
//Default null
\PHPBook\Http\Configuration\Meta::setVersion('1.0');

//Contact e-mail.
//Default null
\PHPBook\Http\Configuration\Meta::setEmail('contact@app.com');

//Contact phone.
//Default null
\PHPBook\Http\Configuration\Meta::setPhone('99 9999-9999');

//Prefix router to access the docs, http://localhost/docs/
//Default "docs"
\PHPBook\Http\Configuration\Directory::setDocs('docs');

//Prefix router to access the api, http://localhost/api/....
//Default "app"
\PHPBook\Http\Configuration\Directory::setApp('app');

//Output for structured data response. JSON or XML. 
//Not used in buffer dispatch.
//Default "json"
\PHPBook\Http\Configuration\Output::setType('json');

//Exception template output format, where @ contains the string message
//Default ['type' => 'exception', 'message' => '@']
//Dispatch using the output Type
\PHPBook\Http\Configuration\Output::setException(['type' => 'exception', 'message' => '@']);

//Content template output format, where @ contains the response data structure
//Default ['type' => 'success', 'content' => '@']
//Dispatch using the output Type
\PHPBook\Http\Configuration\Output::setContent(['type' => 'success', 'content' => '@']);	

?>
```

##### Declare Application Elements

```php
<?php 

/********************************************
* 
*  Declare Application Elements
* 
* ******************************************/

class AuthenticationElement extends \PHPBook\Http\Element {

	public function __construct() {

		$this->setParameter('My-Key', new \PHPBook\Http\Parameter\Value('header key auth'));
		$this->setParameter('User-Agent', new \PHPBook\Http\Parameter\Value('header key auth'));
		$this->setParameter('Cache-Control', new \PHPBook\Http\Parameter\Value('header key auth'));

	}

}

class CustomerQueryElement extends \PHPBook\Http\Element {

	public function __construct() {

		$this->setParameter('ageStarts', new \PHPBook\Http\Parameter\Value('age starts with'));

	}

}

class FoodElement extends \PHPBook\Http\Element {

	public function __construct() {

		$this->setParameter('id', new \PHPBook\Http\Parameter\Value('food id description'));
		$this->setParameter('name', new \PHPBook\Http\Parameter\Value('food name description'));

	}

}

class FriendElement extends \PHPBook\Http\Element {

	public function __construct() {

		$this->setParameter('id', new \PHPBook\Http\Parameter\Value('friend id description'));
		$this->setParameter('name', new \PHPBook\Http\Parameter\Value('friend name description'));
		$this->setParameter('bestFood', new \PHPBook\Http\Parameter\One('FoodElement', 'best food'));
		$this->setParameter('foods', new \PHPBook\Http\Parameter\Many('FoodElement', 'all foods'));
	}

}

class CustomerElement extends \PHPBook\Http\Element {

	public function __construct() {

		$this->setParameter('id', new \PHPBook\Http\Parameter\Value('customer id description'));
		$this->setParameter('name', new \PHPBook\Http\Parameter\Value('customer name description'));
		$this->setParameter('age', new \PHPBook\Http\Parameter\Value('customer age description'));
		$this->setParameter('friends', new \PHPBook\Http\Parameter\Many('FriendElement', 'all friends'));
		$this->setParameter('bestFriend', new \PHPBook\Http\Parameter\One('FriendElement', 'best friend'));

	}

}

/*********************************************
* 
*  Get Elements Parameters
* 
* *******************************************/

//Get one parameter
$customer = new CustomerElement;
$parameter = $customer->getParameter('name');
$parameter->getDescription();

//Get all parameters
$customer = new CustomerElement;
$parameters = $customer->getParameters();
foreach($parameters as $name => $parameter) {
	$description = $parameter->getDescription();
};

/*********************************************
* 
*  Work With Element Query Manually
* 
* *******************************************/

$element = 'CustomerElement';

$description = 'Customer Description';

$rules = ['only' => ['name']];

$user = new \StdClass;
$user->name = 'Jhon';

//Get query with ony element
$query = new \PHPBook\Http\Query(new \PHPBook\Http\Parameter\One($element, $description), $rules);
$query->schema(); //get element schema recursively following the rules
$query->intercept($user); //get element with data according to the schema recursively following the rules

//Get query with many of element
$query = new \PHPBook\Http\Query(new \PHPBook\Http\Parameter\Many($element, $description), $rules);
$query->schema(); //get element schema recursively following the rules
$query->intercept([$user]); //get element with data according to the schema recursively following the rules

?>
```

##### Declare Application Controllers

```php
<?php 

/*********************************************
* 
*  Declare Application Controllers
* 
* *******************************************/

class CustomerController {

	public function post($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end

		$customer = new stdClass();
		$customer->id = 10;
		$customer->name = $inputs->body->name;
		$customer->age = $inputs->body->age;

		//$customer->save();

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customer);

	}

	public function put($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end

		//get by $inputs->uri->id to edit;

		$customer = new stdClass();
		$customer->id = $inputs->uri->id;
		$customer->name = $inputs->body->name;
		$customer->age = $inputs->body->age;

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customer);
	}

	public function get($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end

		//get by $inputs->uri->id;

		$customer = new stdClass();
		$customer->name = 'Jhon';
		$customer->age = 10;

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customer);

	}

	public function query($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end

		$customers = [];

		$jhon = new stdClass();
		$jhon->id = 25;
		$jhon->name = 'Jhon';
		$jhon->age = 10;

		foreach([$jhon, $paul] as $customer) {
			if ($inputs->query->ageStarts <= $customer->age) {
				$customers[] = $customer;
			};
		};

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customers);
	
	}

	public function photo($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		
		//get by $inputs->uri->id;
		//the $inputs->uri->alias is a practice that you can use just to control cache request;
		//you do not need use $inputs->uri->alias to get the file
		//$inputs->uri->alias can be the current file name or any other name.

		$customer = new stdClass();
		
		$customer->photo = '@data-buffer-here';

		$buffer = $customer->photo;

		return $buffer;
	
	}

	public function delete($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end

		//get $inputs->header->{'My-Key'} to authentication;
		//get by $inputs->uri->id to delete;
		
		return Null;

	}

}

?>
```

##### Register Application Request Categories

```php
<?php

/***************************************************
* 
*  Register Application Request Categories
* 
* *************************************************/

\PHPBook\Http\Request::setCategory((new \PHPBook\Http\Category)
	->setCode('customerCategory')
	->setName('Customers Resources'));


/***************************************************
* 
*  Get All Application Request Categories
* 
* *************************************************/

$categories = \PHPBook\Http\Request::getCategories();

foreach($categories as $category) {
	$code = $category->getCode();
	$name = $category->getName();
};

?>
```

##### Register Application Request Resources

```php
<?php

/***************************************************
* 
*  Register Application Request Resources
* 
* *************************************************/

\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource())
	->setCategoryCode('customerCategory')
	->setUri('customer/post')
	->setNotes('Any important note')
	->setType('post')
	->setInputBody('\PHPBook\Http\Parameter\One', 'CustomerElement', ['except' => ['id']])
	->setController('CustomerController', 'post')
	->setOutput('\PHPBook\Http\Parameter\One', 'CustomerElement', []));

\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource)
	->setCategoryCode('customerCategory')
	->setUri('customer/put/:id')
	->setNotes('Any important note')
	->setType('put')
	->setInputUri('\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']])
	->setInputBody('\PHPBook\Http\Parameter\One', 'CustomerElement', ['except' => ['id']])
	->setController('CustomerController', 'put')
	->setOutput('\PHPBook\Http\Parameter\One', 'CustomerElement', []));

\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource())
	->setCategoryCode('customerCategory')
	->setUri('customer/get/:id')
	->setNotes('Any important note')
	->setType('get')
	->setInputUri('\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']])
	->setController('CustomerController', 'get')
	->setOutput('\PHPBook\Http\Parameter\One', 'CustomerElement', ['except' => ['id', 'friends.id']]));

\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource())
	->setCategoryCode('customerCategory')
	->setUri('customer/query')
	->setInputQuery('\PHPBook\Http\Parameter\One', 'CustomerQueryElement', [])
	->setNotes('Any important note')
	->setType('get')
	->setController('CustomerController', 'query')
	->setOutput('\PHPBook\Http\Parameter\Many', 'CustomerElement', []));

\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource())
	->setCategoryCode('customerCategory')
	->setUri('customer/get/:id/photo/:alias')
	->setNotes('Any important note')
	->setType('get')
	->setInputUri('\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']])
	->setController('CustomerController', 'photo')
	->setIsBufferOutput(true)
	->setCacheHours(72));

\PHPBook\Http\Request::setResource((new \PHPBook\Http\Resource())
	->setCategoryCode('customerCategory')
	->setUri('customer/delete/:id')
	->setNotes('Any important note with authentication')
	->setType('delete')
	->setInputHeader('\PHPBook\Http\Parameter\One', 'AuthenticationElement', [])
	->setInputUri('\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']])
	->setController('CustomerController', 'delete'));

/***************************************************
* 
*  Get All Application Request Resources
* 
* *************************************************/

$resources = \PHPBook\Http\Request::getResources();

foreach($resources as $resource) {
	$categoryCode = $resource->getCategoryCode();
	$uri = $resource->getUri();
	$notes = $resource->getNotes();
	$type = $resource->getType();
	list($inputHeaderType, $inputHeaderElement, $inputHeaderRules) = $resource->getInputHeader();
	list($inputUriType, $inputUriElement, $inputUriRules) = $resource->getInputUri();
	list($inputQueryType, $inputQueryElement, $inputQueryRules) = $resource->getInputQuery();
	list($inputBodyType, $inputBodyElement, $inputBodyRules) = $resource->getInputBody();
	list($controller, $method) = $resource->getController();
	list($outputType, $outputElement, $outputRules) = $resource->getOutput();
	$isBufferOutput = $resource->getIsBufferOutput();
	$cacheHours = $resource->getCacheHours();
};

?>
```

##### Http URI Get Path
###### You can get server http string url base or string url base with a uri resource

```php
<?php

/***************************************************
* 
*  Http URI Get Path
* 
* *************************************************/

$base = \PHPBook\Http\Url::get();

$resource = \PHPBook\Http\Url::get('customer/get/2');

?>
```

##### Start Application Http

```php
<?php

/***************************************************
* 
*  Start Application Http
* 
* *************************************************/

/* FILE index.php*/

//check the script is not running in console
if (!\PHPBook\Http\Script::isConsole()) {

	\PHPBook\Http\Http::start();

};

?>
```

##### Application Apache htaccess File
###### This htaccess must be in the same directory where you call \PHPBook\Http\Http::start();
###### Do not forget to change index.php file name inside Apache htaccess if necessary

	<IfModule mod_rewrite.c>

		Options +FollowSymLinks
		RewriteEngine On
		RewriteRule ^(.*)$ index.php [NC,L]
		
	</IfModule>

##### Application Nginx File
###### Do not forget to change index.php file and directory name inside Nginx configuration file if necessary
###### Make sure you have Nginx configuration with "index index.php";

	location /app/diretory/ {
		try_files $uri $uri/ /app/diretory/index.php?$args;
	}
