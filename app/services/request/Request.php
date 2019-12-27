<?php
include_once 'IRequest.php';

//Http request functional
class Request implements IRequest
{
    function __construct()
    {
        $this->bootstrapSelf();
    }

    //returns body of POST or DELETE requests
    public function getBody()
    {
        if($this->requestMethod === "GET")
        {
            return;
        }

        if($this->requestMethod == "POST" || $this->requestMethod == "DELETE")
        {
            if(strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $postData = file_get_contents('php://input');
                $post = json_decode($postData, true);
            } else {
                $post = $_POST;
            }

            $body = array();
            foreach($post as $key => $value)
            {
                $body[$key] = $post[$key];//TODO: filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }
    }

    private function bootstrapSelf()
    {
        foreach($_SERVER as $key => $value)
        {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match)
        {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

}