    
+ [About Http](#about-http) 
+ [Composer Install](#composer-install)
- [Examples](#examples)
	* [Declare Configurations](#declare-configurations)
	* [Declare Application Elements](#declare-application-elements)
	* [Declare Application Middleware](#declare-application-middleware)
	* [Declare Application Controllers](#declare-application-controllers)
	* [Generate Request Proxies](#generate-request-proxies)
	* [Start Request Proxies](#start-request-proxies)
	* [Start Application Http](#start-application-http)
	* [Http URI Get Path](#http-uri-get-path)
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

//Prefix router to access the app, http://localhost/app/....
//Default "app"
\PHPBook\Http\Configuration\Directory::setApp('app');

//Default router, http://localhost/docs/
//Default "docs"
\PHPBook\Http\Configuration\Directory::setDefault('docs');

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

//Controllers path, the phpbook will load all controllers by folders recursively inside
//Default null. But its required to set if you want use phpbook http.
\PHPBook\Http\Configuration\Request::setControllersPathRoot('app\controllers');

//Controllers proxies path, the phpbook will generate the proxies based on controllers
//Default null. But its required to set if you want use phpbook http.
\PHPBook\Http\Configuration\Request::setProxiesPathRoot('proxies');

//Controllers proxies namespace, the phpbook will generate the proxies classes using this namespace
//Default null. But its required to set if you want use phpbook http.
\PHPBook\Http\Configuration\Request::setProxiesNamespace('App\Controllers');


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

class EncapsulationBeanElement extends \PHPBook\Http\Element {

	public function __construct() {

		$this->setParameter('id', new \PHPBook\Http\Parameter\Value('customer id description', 'getId'));
		$this->setParameter('name', new \PHPBook\Http\Parameter\Value('customer name description', 'getName'));
		$this->setParameter('age', new \PHPBook\Http\Parameter\Value('customer age description', 'getAge'));
		$this->setParameter('friends', new \PHPBook\Http\Parameter\Many('FriendElement', 'all friends', 'getFriends'));
		$this->setParameter('bestFriend', new \PHPBook\Http\Parameter\One('FriendElement', 'best friend', 'getBestFriend'));

	}

}

?>
```

##### Declare Application Middleware

```php
<?php 

/*********************************************
* 
*  Declare Application Middleware
* 
* *******************************************/

/**
 * @PHPBookHttpMiddleware{
 *      "setCode": "'authenticationMiddleware'"
 *      "setName": "'Authentication Middleware'"
 * 		"setInputHeader": "'\PHPBook\Http\Parameter\One', 'AuthenticationElement', []"
 *		"setParameters": "['requireRole']"
 * }
 */
class AuthenticationMiddleware {


	public function intercept($header, $parameter) {

		//get header value;
		$header->{'My-Key'};

		//get parameter value
		$parameter->requireRole;

		//throw exception when something is wrong
		throw new Exception("authentication denied");

		//return whatever you want
		return $authentication;

	}


}


?>
```

##### Declare Application Controllers

PHPBook Http uses docs notations to declare request resources.

```php
<?php 

/*********************************************
* 
*  Declare Application Controllers
* 
* *******************************************/

/**
 * @PHPBookHttpRequestCategory{
 *      "setCode": "'customerCategory'"
 *      "setName": "'Customer Category'"
 * }
 */
class CustomerController {

	/**
	 * @PHPBookHttpRequestResource{
	 *      "setCategoryCode": "'customerCategory'"
	 *      "setUri": "'customer/post'"
	 * 		"setNotes": "'Any important note'"
	 * 		"setType": "'post'"
	 * 		"setInputBody": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['except' => ['id']]"
	 * 		"setOutput": "'\PHPBook\Http\Parameter\One', 'CustomerElement', []"
	 * }
	 */
	public function post($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		//values non defined by user, will be defined null
		//extra values defined by user, will be ignored

		$customer = new stdClass();
		$customer->id = 10;
		$customer->name = $inputs->body->name;
		$customer->age = $inputs->body->age;

		//$customer->save();

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customer);

	}


	/**
	 * @PHPBookHttpRequestResource{
	 *      "setCategoryCode": "'customerCategory'"
	 *      "setUri": "'customer/put/:id'"
	 * 		"setNotes": "'Any important note'"
	 * 		"setType": "'put'"
	 * 		"setInputUri": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']]"
	 * 		"setInputBody": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['except' => ['id']]"
	 * 		"setOutput": "'\PHPBook\Http\Parameter\One', 'CustomerElement', []"
	 * }
	 */
	public function put($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		//values non defined by user, will be defined null
		//extra values defined by user, will be ignored

		//get by $inputs->uri->id to edit;

		$customer = new stdClass();
		$customer->id = $inputs->uri->id;
		$customer->name = $inputs->body->name;
		$customer->age = $inputs->body->age;

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customer);
	}

	/**
	 * @PHPBookHttpRequestResource{
	 *      "setCategoryCode": "'customerCategory'"
	 *      "setUri": "'customer/get/:id'"
	 * 		"setNotes": "'Any important note'"
	 * 		"setType": "'get'"
	 * 		"setInputUri": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']]"
	 * 		"setOutput": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['except' => ['id', 'friends.id']]"
	 * }
	 */
	public function get($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		//values non defined by user, will be defined null
		//extra values defined by user, will be ignored

		//get by $inputs->uri->id;

		$customer = new stdClass();
		$customer->name = 'Jhon';
		$customer->age = 10;

		//inside the $output primitive values, the whitespace are stripped from the beginning and end
		return $output->intercept($customer);


		//or if using an output element with encapsulation....

		//using an element with encapsulation(EncapsulationBeanElement in this case) method mapped, you can get a standard class of that object
		//very useful for database entities that you cant access the attributes directly for example.
		$customer = new MyCustomerBean();
		$customer->getId();
		$customer->getName();

		$customerStdclass = $output->standard($customer);
		$customerStdclass->id;
		$customerStdclass->name;

		return $output->intercept($customerStdclass);

	}

	/**
	 * @PHPBookHttpRequestResource{
	 *      "setCategoryCode": "'customerCategory'"
	 *      "setUri": "'customer/query'"
	 * 		"setNotes": "'Any important note'"
	 * 		"setType": "'get'"
	 * 		"setInputQuery": "'\PHPBook\Http\Parameter\One', 'CustomerQueryElement', []"
	 * 		"setOutput": "'\PHPBook\Http\Parameter\One', 'CustomerElement', []"
	 * }
	 */
	public function query($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		//values non defined by user, will be defined null
		//extra values defined by user, will be ignored

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

	/**
	 * @PHPBookHttpRequestResource{
	 *      "setCategoryCode": "'customerCategory'"
	 *      "setUri": "'customer/get/:id/photo/:alias'"
	 * 		"setNotes": "'Any important note'"
	 * 		"setType": "'get'"
	 * 		"setInputUri": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']]"
	 * 		"setOutput": "'\PHPBook\Http\Parameter\One', 'CustomerElement', []"
	 * 		"setIsBufferOutput": "true"
	 * 		"setCacheHours": "72"
	 * }
	 */
	public function photo($inputs, $output) {

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		//values non defined by user, will be defined null
		//extra values defined by user, will be ignored

		//get by $inputs->uri->id;
		//the $inputs->uri->alias is a practice that you can use just to control cache request;
		//you do not need use $inputs->uri->alias to get the file
		//$inputs->uri->alias can be the current file name or any other name.

		$buffer = $customer->photo;

		return $buffer;

		//or
	
	}

	/**
	 * @PHPBookHttpRequestResource{
	 *      "setCategoryCode": "'customerCategory'"
	 *      "setUri": "'customer/delete/:id'"
	 * 		"setNotes": "'Any important note'"
	 *		"setMiddlewareCode": "'authenticationMiddleware:roleDeleteCustomer'"
	 * 		"setType": "'delete'"
	 * 		"setInputUri": "'\PHPBook\Http\Parameter\One', 'CustomerElement', ['only' => ['id']]"
	 * }
	 */
	public function delete($inputs, $output, \Authentication\User $user) {

		//variable $user in the third parameter contains the return of the middleware method intercept

		//inside the $inputs primitive values, the whitespace are stripped from the beginning and end
		//values non defined by user, will be defined null
		//extra values defined by user, will be ignored
		
		//get by $inputs->uri->id to delete;
		
		return Null;

	}

}

?>
```

##### Generate Request Proxies

```php
<?php

/***************************************************
* 
*  Generate Request Proxies
* 
* *************************************************/

/* The Directory will be cleared recursively before generate, so you should have a unique folder to this proxies.*/

/* You must generate or re-generate de proxy file when create or change controllers notations */

/* You cannot start http without proxies */

\PHPBook\Http\Proxy::generate();

?>
```

##### Start Request Proxies

```php
<?php

/***************************************************
* 
*  Start Request Proxies
* 
* *************************************************/

/* You must start proxies before start the http */

\PHPBook\Http\Proxy::start();

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

/* FILE index.php */

if (!\PHPBook\Http\Script::isConsole()) {

	\PHPBook\Http\Http::start();

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
