<?php namespace PHPBook\Http;

abstract class Http {

    public static function start() {

        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET,POST,HEAD,PUT,DELETE,OPTIONS");
        header("Access-Control-Allow-Headers:*");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            header('Access-Control-Max-Age: 3600');
            exit(0);

        }

        try {

            $requestAddress = explode('?', $_SERVER['REQUEST_URI']);

            $requestUri = array_filter(explode('/', $requestAddress[0]), function($item) {
                return strlen($item) > 0;
            });

            $directory = array_filter(explode('/', pathinfo($_SERVER['PHP_SELF'])['dirname']), function($item) {
                return strlen($item) > 0;
            });;

            $parameters = $requestUri;

            foreach($directory as $key => $itemDirectory) {
                array_shift($parameters);
            }

            if (count($parameters)) {

                $path = array_shift($parameters);

                if ($path == Configuration\Directory::getApp()) {

                    Static::startApp($parameters);

                } else if ($path == Configuration\Directory::getDocs()) {

                    Static::startDocs($parameters);

                } else {

                    Dispatch::exception('not found', 404);

                };

            } else {

                header('location:' . Configuration\Directory::getDefault());

            };

        } catch(\Exception $exception) {

            Dispatch::exception($exception->getMessage());

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

            $header = new HeaderParameter(getallheaders());

            if (count($_POST) == 0) {

                $body = json_decode(file_get_contents("php://input"));

                if (!$body) {
                    $body = new \stdClass();
                }

            } else {

                $body = $_POST;

            }

            $query = $_GET;

            list($classController, $controllerMethod) = $dispatch->getController();

            $controller = new $classController;

            $inputs = new \StdClass;

            $inputs->uri = Null;

            $inputs->query = Null;

            $inputs->body = Null;

            $inputs->header = Null;

            $output = null;

            $responseMiddleware = null;

            if ($dispatch->getMiddlewareCode()) {

                $middlewareCodeParts = explode(':', $resource->getMiddlewareCode());

                if (count($middlewareCodeParts) > 1) {
                    list($middlewareCode, $parametersMiddlewareValues) = $middlewareCodeParts;
                } else {
                    list($middlewareCode) = $middlewareCodeParts;
                    $parametersMiddlewareValues = '';
                }


                $parametersMiddlewareValues = explode(',', $parametersMiddlewareValues);

                $middlewareIntercept = Request::getMiddlewareInterceptFactory($middlewareCode);

                $middlewareSchema = Request::getMiddlewareSchema($middlewareCode);

                if (($middlewareSchema) and ($middlewareIntercept)) {

                    $middlewareParameters = new \StdClass;

                    foreach($middlewareSchema->getParameters() as $key => $parameter) {

                        $middlewareParameters->{$parameter} = array_key_exists($key, $parametersMiddlewareValues) ? $parametersMiddlewareValues[$key] : null;

                    }

                    list($typeMiddleware, $elementMiddleware, $rulesMiddleware) = $middlewareSchema->getInputHeader();

                    $responseMiddleware = $middlewareIntercept->intercept((new \PHPBook\Http\Query(new $typeMiddleware($elementMiddleware, 'Input Header'), $rulesMiddleware))->intercept($header), $middlewareParameters);
                }

            };

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

            if ($dispatch->getOutput()) {

                list($type, $element, $rules) = $dispatch->getOutput();

                $output = new \PHPBook\Http\Query(new $type($element, 'Output'), $rules);

            };

            unset($type, $element, $rules);

            $response = $controller->{$controllerMethod}($inputs, $output, $responseMiddleware);

            if ($dispatch->getCacheHours()) {
                Dispatch::cache($dispatch->getCacheHours());
            };

            if ($dispatch->getIsBufferOutput()) {

                Dispatch::buffer($response);

            } else {

                Dispatch::content($response);

            };

        } else {

            Dispatch::exception('not found', 404);

        };

    }

    private static function startDocs(Array $parameters) {

        list($type, $value) = count($parameters) == 2 ? $parameters : ['none', 'none'];

        include __DIR__ . '/../assets/html/docs-header.php';

        if (count($parameters) == 2) {

            list($type, $value) = $parameters;

            switch($type) {
                case 'resources':
                case 'resource':
                    include __DIR__ . '/../assets/html/docs-body-' . $type . '.php';
                    break;
                default:
                    include __DIR__ . '/../assets/html/docs-body-notfound.php';
            };

        } else if (count($parameters) == 1) {

            $searchString = array_key_exists('search', $_GET) ? $_GET['search'] : '';

            include __DIR__ . '/../assets/html/docs-body-search.php';

        } else if (count($parameters) == 0) {

            include __DIR__ . '/../assets/html/docs-body-welcome.php';

        } else {

            include __DIR__ . '/../assets/html/docs-body-notfound.php';

        };

        include __DIR__ . '/../assets/html/docs-footer.php';

    }

}