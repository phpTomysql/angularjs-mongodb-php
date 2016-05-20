<?php
      //set db location
      //mongod.exe --dbpath D:\devmongodb\data
      //start mongo
      //mongo
      $m = new MongoClient();
      $db = $m->worcester; // DB
      $collection = $db->worcester_products; // COLLECTION
      $series = $collection->distinct("product_series"); // SELECT DISTINCT SERIES
      $options = json_encode($series); // CONVERT JSON STRING
      echo $options; // RETURN 
      exit;
?>