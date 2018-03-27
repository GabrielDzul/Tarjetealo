<?php
   require '../../vendor/autoload.php';

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
       * Method to add a new user to the collecion
       *@param array an array with the new user data
       * @author GabrielDzul
       * @Date 26/03/28
       * @api
       */
        public function addNewUser(array $userData){
            
            try{
                
                 $collection = $this->openConnection(); 
                 //TODO:Remove this
                 var_dump($collection);
                 //Insert in a collection
                 $collection->insertOne($userData);
                 echo "Document inserted successfully";
                
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

        
    }

    $config = new MongoConnectionMannager;

    $document = array( 
        "name" => "Brayan", 
        "lastName" => "Herrasti", 
        "nick" => "Diosito",
        "password" => "123456"
     );
    $config->addNewUser( $document);
    
?>