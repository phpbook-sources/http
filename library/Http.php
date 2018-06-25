<?php namespace PHPBook\Http;

abstract class Http {
    
    public static function start() {

        $requestAddress = explode('?', $_SERVER['REQUEST_URI']);

        $requestUri = explode('/', $requestAddress[0]);

        $directory = explode('/', pathinfo($_SERVER['PHP_SELF'])['dirname']);

        $parameters = array_values(array_diff($requestUri, $directory));

        $path = array_shift($parameters);

        if ($path == Configuration\Directory::getApp()) {

            Static::startApp($parameters);

        } else if ($path == Configuration\Directory::getDocs()) {

            Static::startDocs($parameters);

        } else {

            Dispatch::exception('not found', 404);

        };
        
    }

    private static function startApp(Array $parameters) {

        $dispatch = null;

        $uri = null;
        
        foreach(Request::getResources() as $resource) {
            
            $fragments = explode('/', $resource->getUri());

            if ((count($fragments) <> count($parameters)) or (strtolower($resource->getType()) != strtolower($_SERVER['REQUEST_METHOD']))) {

                continue;

            } else {

                $match = true;

                $uri = [];

                foreach($fragments as $position => $fragment) {
    
                    if (mb_substr($fragment, 0, 1) == ':') {

                        if (!array_key_exists($position, $parameters)) {

                            $match = false;

                            break;
                        };

                        $uri[substr($fragment, 1)] = $parameters[$position];

                    } else {

                        if ((!array_key_exists($position, $parameters)) or ($fragment != $parameters[$position])) {
                           
                            $match = false;

                            break;

                        };
                    };
    
                };

                if ($match) {

                    $dispatch = $resource;

                    break;

                };
                
            };

        };

        if ($dispatch) {

            $header = getallheaders();

            parse_str(file_get_contents("php://input"), $body);

            $query = $_GET;

            list($classController, $controllerMethod) = $dispatch->getController();
            
            $controller = new $classController;

            $inputs = new \StdClass;

            $inputs->uri = Null;

            $inputs->query = Null;

            $inputs->body = Null;

            $inputs->header = Null;

            $output = null;

            if ($dispatch->getInputUri()) {
                
				list($type, $element, $rules) = $dispatch->getInputUri();

				$inputs->uri = (new \PHPBook\Http\Query(new $type($element, 'Input URI'), $rules))->intercept($uri);
                
            };

            if ($dispatch->getInputQuery()) {
                
				list($type, $element, $rules) = $dispatch->getInputQuery();

				$inputs->query = (new \PHPBook\Http\Query(new $type($element, 'Input Query'), $rules))->intercept($query);
                
            };

            if ($dispatch->getInputBody()) {

                list($type, $element, $rules) = $dispatch->getInputBody();

				$inputs->body = (new \PHPBook\Http\Query(new $type($element, 'Input Body'), $rules))->intercept($body);
                
            };

            if ($dispatch->getInputHeader()) {
                
                list($type, $element, $rules) = $dispatch->getInputHeader();

				$inputs->header = (new \PHPBook\Http\Query(new $type($element, 'Input Header'), $rules))->intercept($header);
                
            };

            if ($dispatch->getOutput()) {

				list($type, $element, $rules) = $dispatch->getOutput();
				
                $output = new \PHPBook\Http\Query(new $type($element, 'Output'), $rules);
                
            };        
            
            unset($type, $element, $rules);
            
            try {

                $response = $controller->{$controllerMethod}($inputs, $output);

                if ($dispatch->getCacheHours()) {
                    Dispatch::cache($dispatch->getCacheHours());
                };

				if ($dispatch->getIsBufferOutput()) {
					
					Dispatch::buffer($response);
					
				} else {
					
					Dispatch::content($response);
					
				};

            } catch(\Exception $exception) {

                Dispatch::exception($exception->getMessage());

            };
            
        } else {

            Dispatch::exception('not found', 404);

        };

    }

    private static function startDocs(Array $parameters) {
        
        list($type, $value) = count($parameters) == 2 ?  : ['none', 'none'];

        include __DIR__ . '/../assets/html/docs-header.php';

        if (count($parameters) == 2) {

            list($type, $value) = $parameters;
            
            switch($type) {
                case 'resources':
                case 'resource':
                case 'search':
                    include __DIR__ . '/../assets/html/docs-body-' . $type . '.php';
                    break;
                default:
                    include __DIR__ . '/../assets/html/docs-body-notfound.php';
            };

        } else if (count($parameters) == 0) {

            include __DIR__ . '/../assets/html/docs-body-welcome.php';

        } else {

            include __DIR__ . '/../assets/html/docs-body-notfound.php';

        };        

        include __DIR__ . '/../assets/html/docs-footer.php';
		
	}

}
