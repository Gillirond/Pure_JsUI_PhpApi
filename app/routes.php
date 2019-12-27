<?php

include_once 'services/request/Request.php';
include_once 'services/router/Router.php';
include_once 'controllers/UserController.php';
//include_once 'services/database/DBConnector.php';
$router = new Router(new Request);


//List of API routes
$router->post('/createUser', function($request) {
    (new UserController())->createUser($request);
});
$router->get('/getUser/{id}', function($request, $id) {
    (new UserController())->getUser($request, $id);
});
$router->delete('/deleteUser/{id}', function($request, $id) {
    (new UserController())->deleteUser($request, $id);
});