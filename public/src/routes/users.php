<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$Path = dirname( __FILE__ ); 
require ($Path.'../../MongoConnectionMannager.php');

$app = new \Slim\App;


$app->group('/user', function(){

    $this->post('/new', function (Request $request, Response $response){

        try{
            $data = $request->getParsedBody();
            $new_user_data=[];
            $new_user_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
            $new_user_data['lastName'] = filter_var($data['lastName'], FILTER_SANITIZE_STRING);
            $new_user_data['rol'] = filter_var($data['rol'], FILTER_SANITIZE_STRING);
            $new_user_data['nick'] = filter_var($data['nick'], FILTER_SANITIZE_STRING);
            $new_user_data['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
        
            $mongoConnection = new MongoConnectionMannager();
            $last_inserted_user_id = $mongoConnection->addNewUser($new_user_data);

            $status= json_encode(array('status' => 'success',
            'message' => 'User added Successfully',
            'user_id' => $last_inserted_user_id));
            $response->getBody()->write($status);
            

        }catch(Exception $exception){
            echo json_encode(array('status' => 'error',
                'message' => $exception->getMessage()));
        }

    });

    $this->get('/getById/{id}', function (Request $request, Response $response, array $args){

        try{
            $userId = new MongoDB\BSON\ObjectId($args['id']);
            $user;
        
            $mongoConnection = new MongoConnectionMannager();
            $user = $mongoConnection->getUserById($userId);

            
            echo json_encode($user);
            //$response->getBody()->write("The user is",$user);
            //return $response;
            

        }catch(Exception $exception){
            echo json_encode(array('status' => 'error',
                'message' => $exception->getMessage()));
        }

    });

    $this->put('/update/{id}', function (Request $request, Response $response, array $args){

        $userId = new MongoDB\BSON\ObjectId($args['id']);
        try{
            $data = $request->getParsedBody();
            $new_user_data=[];
            $new_user_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
            $new_user_data['lastName'] = filter_var($data['lastName'], FILTER_SANITIZE_STRING);
            $new_user_data['rol'] = filter_var($data['rol'], FILTER_SANITIZE_STRING);
            $new_user_data['nick'] = filter_var($data['nick'], FILTER_SANITIZE_STRING);
            $new_user_data['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
        
            $mongoConnection = new MongoConnectionMannager();
            $mongoConnection->updateUser($userId, $new_user_data);

            $status= json_encode(array('status' => 'success',
            'message' => 'User updated Successfully'));
            $response->getBody()->write($status);
            

        }catch(Exception $exception){
            echo json_encode(array('status' => 'error',
                'message' => $exception->getMessage()));
        }

    });

    $this->get('/mira', function (Request $request, Response $response){
        echo getcwd();
    });

    
});
?>
