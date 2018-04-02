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

    $this->delete('/delete/{id}', function (Request $request, Response $response, array $args){

        try{
            $userId = new MongoDB\BSON\ObjectId($args['id']);
            $userDeleted;
        
            $mongoConnection = new MongoConnectionMannager();
            $userDeleted = $mongoConnection->deleteUser($userId);

            if($userDeleted){
                $status= json_encode(array('status' => 'success',
                'message' => 'User deleted Successfully'));
                echo($status);
            }
            

        }catch(Exception $exception){
            echo json_encode(array('status' => 'error',
                'message' => $exception->getMessage()));
        }

    });

    $this->post('/login', function (Request $request, Response $response){

        try{
            $data = $request->getParsedBody();
            $user_data=[];
            $user_data['nick'] = filter_var($data['nick'], FILTER_SANITIZE_STRING);
            $user_data['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
        
            $mongoConnection = new MongoConnectionMannager();
            $valid_user = $mongoConnection->isValidUser($user_data);
            if($valid_user){
                $status= json_encode(array('status' => 'success',
                'message' => 'valid users credentials'));
                 $response->getBody()->write($status);

            }else{
                $status= json_encode(array('status' => 'error',
                'message' => 'invalid users credentials'));
                 $response->getBody()->write($status);
            }

            
            

        }catch(Exception $exception){
            echo json_encode(array('status' => 'error',
                'message' => $exception->getMessage()));
        }

    });
    
});

//###################### List Group  ################
$app->group('/list', function(){
    $this->post('/new', function (Request $request, Response $response){
        try{
            $data = $request->getParsedBody();
            //$userId = new MongoDB\BSON\ObjectId($args['id']);
            $userId = new MongoDB\BSON\ObjectId($data['userId']);
            $new_list_data=[];
            $new_list_data['_id'] = new MongoDB\BSON\ObjectId();
            $new_list_data['listName'] = filter_var($data['listName'], FILTER_SANITIZE_STRING);
            $new_list_data['score'] = filter_var($data['score'], FILTER_SANITIZE_STRING);
            $new_list_data['isFavorite'] = filter_var($data['isFavorite'], FILTER_SANITIZE_STRING);
        
            $mongoConnection = new MongoConnectionMannager();
            $mongoConnection->addListToUser($userId, $new_list_data);

            $status= json_encode(array('status' => 'success',
            'message' => 'list added Successfully',
            'list_id' => $new_list_data['_id']));
            $response->getBody()->write($status);
            

        }catch(Exception $exception){
            echo json_encode(array('status' => 'error',
                'message' => $exception->getMessage()));
        }
    });

});
?>
