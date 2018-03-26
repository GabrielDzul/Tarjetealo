<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->group('/user', function(){

    $this->post('/new', function (Request $request, Response $response){
        $data = $request->getParsedBody();
        $new_user_data=[];
        $new_user_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $new_user_data['lastName'] = filter_var($data['lastName'], FILTER_SANITIZE_STRING);
        $new_user_data['nick'] = filter_var($data['nick'], FILTER_SANITIZE_STRING);
        $new_user_data['password'] = filter_var($data['lastName'], FILTER_SANITIZE_STRING);
        
        

    });
});