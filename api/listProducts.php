<?php
$json = json_decode(file_get_contents("php://input")); // GET ALL VALUES

$product_series         = [];
$product_series_seconds = [];
foreach ($json as $col) {

      if($col->col == "product_series") {
         $product_series[$col->col] = $col->val;
      }
      if(isset($col->val))
      $product_series_seconds[$col->col] = $col->val;
}

$m = new MongoClient(); // CREATE CLIENT 
$db = $m->worcester; // DB
$collection = $db->worcester_products; // COLLECTION
$array_fields =[];
$fields = $collection->find($product_series)->limit(1);
$html = '';
	foreach ($fields as $field) {
      foreach ($field as $key => $value) {
         
      	 if($key !== '_id' && $key !== 'row' && $key !== 'product_series' && $key!='') {
         	$array_fields[] 	 = $key;
            $columns_head[]    = ucwords(implode(" ",explode("_" , $key)));
         	$select_fields[$key] = true;
            $distinct    = $collection->distinct("$key",$product_series_seconds);
            if($key !== 'partnumber') {
         	$html.="<select class='myselect'  ng-model = '".$key."' id = '".$key."'  ng-change = 'columns(".$key.");'>";
            $html.="<option  value='".$key."'>Choose ".$key."</option>";
         	foreach ($distinct as $item) {
         		$html.="<option value='".$key."/".$item."'>".$item."</option>";
         	}
         	$html.="</select>";
         }
     	 }
      }
   }
   
$cursor = $collection->find($product_series_seconds,$select_fields);
$results = [];
	foreach ($cursor as $document) {
      $results[] = $document;
   }


   echo json_encode(array('data'=>$results,'fields'=>$array_fields,'col_heads'=>$columns_head,'elements'=>$html));
   exit;