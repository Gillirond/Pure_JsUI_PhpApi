<?php

//API routing functional
class Router
{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST",
        "DELETE"
    );

    function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    //Handles all function calls like $router->post($route, $method) and creates
    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route
     *
     * @param $route (string)
     * @return (string) Route cleared from slashes '/'
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        //TODO get correct requestUri
        $this->request->requestUri = str_replace('/DexDigital%20PhpJs/app', '', $this->request->requestUri);
        //
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = null;
        //First: look for fully equal route
        if(isset($methodDictionary[$formatedRoute])) {
            $method = $methodDictionary[$formatedRoute];
        };

        if (is_null($method)) {
            //If route wasn't found before, do second: now look in routes with param
            $method = $this->getMethodWithParam($methodDictionary, $formatedRoute);
            if (is_null($method)) {
                $this->defaultRequestHandler();
                return;
            } else {
                echo call_user_func_array($method["method"], array($this->request, $method["paramValue"]));
            }

        } else {
            echo call_user_func_array($method, array($this->request));
        }
    }

    /**
     * Gets parameterized method that satisfyes your request and value of parameter
     *
     * This
     *
     * @param $methodDictionary (array) Array of all routes=>methods with searched requestMethod
     * @param $formatedRoute (string) Formatted requested route
     * @return (array) ['method' => routeFunction, 'paramValue' => param from request's url] | null If there is no method that satisfyes your request
     */
    private function getMethodWithParam($methodDictionary, $formatedRoute)
    {
        foreach($methodDictionary as $key => $value)
        {
            //Check if route is parameterized
            preg_match("/{([^\/]+)}$/", $key,$paramNameArr);
            if(!is_null($paramNameArr)) {
                $brackets = $paramNameArr[0];
                $paramName = $paramNameArr[1];
                $beforeBrackets = str_replace($brackets, "", $key);

                //Check if route with param equals to requested route
                if(strpos($formatedRoute, $beforeBrackets) === 0) {
                    $paramValue = str_replace($beforeBrackets, "", $formatedRoute);
                    //Convert param value to int if possible
                    if(ctype_digit($paramValue)) {
                        $paramValue = (int)$paramValue;
                    }
                    return [
                        "method" => $value,
                        "paramValue" => $paramValue
                    ];
                }
            } else {
                continue;
            }
        }

        return null;
    }

    function __destruct()
    {
        $this->resolve();
    }
}