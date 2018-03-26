<?php
   require '../../vendor/autoload.php';

   class config {
       public $mongo_cluster_uri = 'mongodb://cloud-tarjetas-admin:f0cal0ca$181@cluster-tarjetas-shard-00-00-n7uzs.mongodb.net:27017,cluster-tarjetas-shard-00-01-n7uzs.mongodb.net:27017,cluster-tarjetas-shard-00-02-n7uzs.mongodb.net:27017/admin?replicaSet=Cluster-tarjetas-shard-0&ssl=true';
       public $mongo_data_base;
       public $client;
       //public $mongo_collection = $mongo_data_base->Tarjetealo;

       function connetToMongoDb(){
            try{
                $client = new MongoDB\Client('mongodb://cloud-tarjetas-admin:f0cal0ca$181@cluster-tarjetas-shard-00-00-n7uzs.mongodb.net:27017,cluster-tarjetas-shard-00-01-n7uzs.mongodb.net:27017,cluster-tarjetas-shard-00-02-n7uzs.mongodb.net:27017/admin?replicaSet=Cluster-tarjetas-shard-0&ssl=true');
                echo("conectado al cluster##\n");
                return $client;
            }catch(Exception $e){
                echo($e->getMesage);
            } 
        }

        function selectDataBase(MongoDB\Client $client){
            try{
                //seleccionar la db    
               $mongo_data_base = $client->tarjetealoDB;              
                echo("base de datos seleccionada ##"."\n");
                return $mongo_data_base;
                
            }catch(Exception $e){
                echo($e->getMesage);
            }
        }

        function selectCollection($mongo_data_base){
            
            try{
                //seleccionar la collección    
                $collection = $mongo_data_base->Tarjetealo;            
                echo("Colección seleccionada ##"."\n");
                return $collection;
                
            }catch(Exception $e){
                echo($e->getMesage);
            }
        }

        function addNewUser(MongoDB\Collection $collection, array $userData){
            
            try{
                
                  
                 //Insertar una colección
                 var_dump($collection);
                 $collection->insertOne($userData);
                 echo "Document inserted successfully";
                
            }catch(Exception $e){
                echo($e->getMesage);
            }

        }

        
    }

    $config = new config;
    $client = $config->connetToMongoDb();
    $dataBase = $config->selectDataBase($client);
    $collection = $config->selectCollection($dataBase);

    $document = array( 
        "name" => "Wilian", 
        "lastName" => "Caamal", 
        "nick" => "Wikileaks",
        "password" => "123456"
     );
    $config->addNewUser($collection, $document);
    //$config->simple();


   /*try{
    $client = new MongoDB\Client($mongo_cluster_uri);
    echo("conectado al cluster\n");
    
    //seleccionar la db    
    this->$mongo_data_base;
    echo("base de datos seleccionada"."\n");
    
    //Seleccionar la colección
    this->$mongo_collection;
   echo "Collection selected succsessfully\n";

   $document = array( 
    "name" => "Flor", 
    "lastName" => "ortiz", 
    "nick" => "La Loca",
    "password" => "123456"
 );
  
 //Insertar una colección
 $collection->insertOne($document);
 echo "Document inserted successfully";

   $cursor = $collection->find();
   // iterate cursor to display title of documents
	
   foreach ($cursor as $document) {
      echo $document["name"] . "\n";
   }

   }catch(Exception $e){
       echo($e->getMesage);
   }*/
?>