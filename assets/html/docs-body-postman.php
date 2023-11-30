<?php

$middlewares = [];

$variables = [
    [
        'key' => 'URL-API',
        'value' => '',
        'type' => 'string'
    ]
];

$getResourcesWithCategoryRecursive = function($categoryNest = null, $key = null) use (&$middlewares, &$variables, &$getResourcesWithCategoryRecursive) {

    if ($categoryNest) {

        $menuItem = [
            'name' => $categoryNest->getName(),
            'item' => []
        ];

        foreach (\PHPBook\Http\Request::getResources() as $resource) {

            if ($categoryNest->getCode() == $resource->getCategoryCode()) {

                if ($resource->getMiddlewareCode()) {

                    $middlewareCodeParts = explode(':', $resource->getMiddlewareCode());
                    if (count($middlewareCodeParts) > 1) {
                        list($middlewareCode, $middlewareParameters) = explode(':', $resource->getMiddlewareCode());
                    } else {
                        list($middlewareCode) = explode(':', $resource->getMiddlewareCode());
                        $middlewareParameters = null;
                    };

                    if (!array_key_exists($middlewareCode, $middlewares)) {

                        $middleware = \PHPBook\Http\Request::getMiddlewareSchema($middlewareCode);

                        list($typeMiddleware, $elementMiddleware, $rulesMiddleware) = $middleware->getInputHeader();

                        $inputMiddlewareElement = new $elementMiddleware();

                        $keysMiddleware = [];

                        foreach($inputMiddlewareElement->getParameters() as $name => $value) {

                            $variables[] =  [
                                'key' => 'HEADER-' . strtoupper($middlewareCode) . '-' . strtoupper($name),
                                'value' => '',
                                'type' => 'string'
                            ];

                            $keysMiddleware[] = [
                                'key' => $name,
                                'value' => '{{HEADER-' . strtoupper($middlewareCode) . '-' . strtoupper($name) . '}}'
                            ];

                        }

                        $middlewares[$middlewareCode] = [
                            'middleware' => $middleware,
                            'keys' => $keysMiddleware
                        ];

                    }

                    $headerResource = $middlewares[$middlewareCode]['keys'];

                } else {

                    $middleware = null;

                    $headerResource = [];

                }

                $queryStrings = [];

                if ($resource->getInputQuery()) {

                    list ($typeQuery, $elementQuery, $rulesQuery) = $resource->getInputQuery();

                    $elementQueryInput = new $elementQuery();

                    foreach($elementQueryInput->getParameters() as $name => $value) {
                        $queryStrings[] = $name;
                    }

                }

                $inputBody = [];

                if ($resource->getInputBody()) {

                    list ($typeBody, $elementBody, $rulesBody) = $resource->getInputBody();

                    $elementBodyInput = new $elementBody();

                    foreach($elementBodyInput->getParameters() as $name => $parameterElement) {

                        $inputBody[$name] = $parameterElement->example($rulesBody);

                    }
                }

                $menuItem['item'][] = [
                    'name' => $resource->getUri(),
                    'request' => [
                        'url' => '{{URL-API}}' . $resource->getUri() . ((count($queryStrings) > 0) ? '?' . implode('&', $queryStrings) : ''),
                        'method' => $resource->getType(),
                        'header' => $headerResource,
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode($inputBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                            'options' => [
                                'raw' => [
                                    'language' => 'json'
                                ]
                            ]
                        ]
                    ],
                ];
            }
        }

        if (count(\PHPBook\Http\Request::getCategories()) > 0) {
            foreach (\PHPBook\Http\Request::getCategories() as $keyChild => $categoryChild) {
                if ($categoryChild->getMainResourceCategoryCode() == $categoryNest->getCode()) {
                    $menuItem['item'][] = $getResourcesWithCategoryRecursive($categoryChild, $keyChild);
                }
            }
        }

        return $menuItem;

    } else {

        $menus = [];

        if (count(\PHPBook\Http\Request::getCategories()) > 0) {
            foreach (\PHPBook\Http\Request::getCategories() as $key => $category) {
                if (!$category->getMainResourceCategoryCode()) {
                    $menus[] = $getResourcesWithCategoryRecursive($category, $key);
                }
            }
        }

        return $menus;

    }

};


$items = $getResourcesWithCategoryRecursive();

$schemaResults = [
    'info' => [
      'name' => \PHPBook\Http\Configuration\Meta::getName(),
      'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
    ],
    "variable" => $variables,
    'item' => $items
];

header('Content-Type:application/json');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=" .  strtolower(str_replace(' ', '-', \PHPBook\Http\Configuration\Meta::getName())) . '-postman.json' . "");

echo json_encode($schemaResults, JSON_PRETTY_PRINT);

?>