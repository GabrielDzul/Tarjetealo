<?php
  
  $Path = dirname( __FILE__ ); 
  require ($Path.'../../../vendor/autoload.php');

/**
 * Class to mannage the interaction with the mongo data base.
 *
 * @author GabrielDzul
 * @Date 26/03/28
 * @api
 */
   class MongoConnectionMannager {
       private $mongo_data_base;
       private $client;
       private $collection;


       /**
       * Method to conect to the mongodb cluster. Receives the uri to the cluster
       *@return the client
       * @author GabrielDzul
       * @Date 26/03/28
       * @api
       */
       private function connetToMongoDb(){
            try{
                $client = new MongoDB\Client('mongodb://cloud-tarjetas-admin:f0cal0ca$181@cluster-tarjetas-shard-00-00-n7uzs.mongodb.net:27017,cluster-tarjetas-shard-00-01-n7uzs.mongodb.net:27017,cluster-tarjetas-shard-00-02-n7uzs.mongodb.net:27017/admin?replicaSet=Cluster-tarjetas-shard-0&ssl=true');
                echo("conectado al cluster##\n");
                return $client;
            }catch(Exception $e){
                echo($e->getMesage);
            } 
        }

        /**
       * Method to select the database on mongodb
       *@param MongoDB\Client the mongodb client
       *@return $mongo_data_base the database selected
       * @author GabrielDzul
       * @Date 26/03/28
       * @api
       */
        private function selectDataBase(MongoDB\Client $client){
            try{
                //select the database    
               $mongo_data_base = $client->tarjetealoDB;              
                echo("base de datos seleccionada ##"."\n");
                return $mongo_data_base;
                
            }catch(Exception $e){
                echo($e->getMesage);
            }
        }

        /**
       * Method to select the collection on the mongodb database
       *@param $mongo_data_base the mongodb database
       *@return $collection the collection selected
       * @author GabrielDzul
       * @Date 26/03/28
       * @api
       */
        private function selectCollection($mongo_data_base){
            
            try{
                //select the collection   
                $collection = $mongo_data_base->Tarjetealo;            
                echo("Colección seleccionada ##"."\n");
                return $collection;
                
            }catch(Exception $e){
                echo($e->getMesage);
            }
        }

        /**
       * Method to select the collection on the mongodb database
       *@param $mongo_data_base the mongodb database
       * @author GabrielDzul
       * @Date 26/03/28
       * @api
       */
      private function openConnection(){
        $client = $this->connetToMongoDb();
        $dataBase = $this->selectDataBase($client);
        $collection = $this->selectCollection($dataBase);

      return $collection;  
    }

        /**
       * Method to add a new user to the collecion
       *@param array an array with the new user data
       * @author GabrielDzul
       * @Date 26/03/28
       * @api
       */
        public function addNewUser(array $userData){
            
            try{
                
                 $collection = $this->openConnection(); 
                 //Insert in a collection
                 $last_inserted_id = $collection->insertOne($userData);
                 echo "Document inserted successfully";
                 return $last_inserted_id->getInsertedId();
                
            }catch(Exception $e){
                echo($e->getMesage);
            }

        }

        public function getUserById( $id){
            try{
                $collection = $this->openConnection();
                $user = $collection->findOne(array('_id' => $id));
                return $user;

            }catch(Exception $e){
                echo($e->getMesage);
            }
            
        }

        public function updateUser($id, array $newUserData){
            try{
                
                $collection = $this->openConnection(); 
                //update a collection
                $last_inserted_id = $collection->updateOne(array('_id' => $id),
                array('$set'=>$newUserData));
                echo "Document updated successfully";
               
           }catch(Exception $e){
               echo($e->getMesage);
           }
        }

        public function deleteUser($id){
            try{
                $collection = $this->openConnection();
                $user = $collection->deleteOne(array('_id' => $id));
                return true;

            }catch(Exception $e){
                echo($e->getMesage);
            }
        }

        public function isValidUser(array $userCredentials){
            try{
                $collection = $this->openConnection();
               //$valid_user = $collection->findOne(array('$and', array(array('nick' => $userCredentials['nick']),
                //array('password' => $userCredentials['password']))));
                $valid_user = $collection->findOne(array('nick' => $userCredentials['nick'],
                    'password' => $userCredentials['password']));
                 var_dump($valid_user);
                 var_dump($userCredentials);
                if($valid_user !==null){
                    return true;
                }

                return false;

            }catch(Exception $e){
                echo($e->getMesage);
            }
        }

        public function addListToUser($userId, array $list){
            try{
                
                $collection = $this->openConnection(); 
                //Insert a list into a collection
                $collection->updateOne(array('_id'=>$userId), 
                array('$push' => array('lists' => $list)));
                echo "Document inserted successfully";
                //return $last_inserted_id->getInsertedId();
                return true;
               
           }catch(Exception $e){
               echo($e->getMesage);
           }

        }

        
    }

    
?>