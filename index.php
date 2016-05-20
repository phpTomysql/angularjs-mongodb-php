<!doctype html>
<html ng-app = "productapp">
   
   <head>
   	  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <link rel="stylesheet" href="style.css">
      <script src = "https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.7/angular.min.js"></script>
      <title>My App</title>
   </head>
   
   
   <body>
   	<?php
   	//set db location
   	//mongod.exe --dbpath D:\devmongodb\data
   	// start mongo
   	//mongo
    $m = new MongoClient();
   	$db = $m->worcester;
   	$collection = $db->worcester_products;
   	$series = $collection->distinct("product_series");
    $options = json_encode($series);
   	?>
      <div ng-controller = "mainController" ng-init="init()">
       
         <div ng-show="loader" class="loading">Loading&#8230;</div>   
         <label>Product series : </label><select ng-change = "list();" ng-model = "series_selecter">
                    <option ng-repeat="item in pseries" value="{{item}}">{{item}}</option>

         </select>
         <input type="button" ng-click = "init();" value="Reset Me">
        
         <div id="added"></div>
         <table class="table table-striped">
         			<tr>
         				<td ng-repeat ="heads in col_heads">{{heads}}</td>
         			</tr>
         			 <tr ng-repeat = "product in products">
         				<td ng-repeat ="head in headers">{{product[head]}}</td>
         			</tr>
         </table>

      </div>
      
   </body>
   <script>

   				var app = angular.module("productapp", []);


               app.service("listService" , function( $http ){

                  this.products = function ( conditions ) {
                  return   $http({
                                        cache: false,
                                        url: "api/listProducts.php",
                                        method: "POST",
                                        data: conditions
                     });
                  },
                  this.getOptions = function () {
                     return $http.get("api/listOptions.php",{cache:false});
                  }

               });


   				app.controller("mainController",function( $rootScope ,$scope ,$http ,$compile,listService,$log,$interval ){


                  
                  $scope.conditions = [];
                  
                  $scope.list = function() {
                     $rootScope.loader = true;
                     $scope.conditions = [];
                     $scope.conditions.push({col:"product_series" , val:$scope.series_selecter});
                      listService.products($scope.conditions).success(function(response) {
                                $rootScope.loader = false;
                                 $scope.products = response.data; 
                                 $scope.headers  = response.fields;
                                 $scope.col_heads  = response.col_heads;
                                 var newElement = angular.element(response.elements);
                                 var myEl = angular.element( document.querySelector( '#added' ) );
                                 myEl.empty();

                                 var complieIt = $compile(newElement);
                                 var content   = complieIt($scope);
                                 myEl.append(content); 

                     });

                        
                     
                  };

                  $scope.columns = function ( idd ) {

                     $rootScope.loader = true;
                     var cond = idd.split("/");
                     if(cond.length <= 1) {
                        console.log($scope.conditions);
                        for (var i =0; i < $scope.conditions.length; i++) {
                              if ($scope.conditions[i].col == cond) {
                                 $scope.conditions.splice(i,1);
                                 break;
                              }
                           }
                          
                     }else {
                        $scope.conditions.push({col:cond[0] , val:cond[1]});
                     }
                   
                     listService.products($scope.conditions).success(function(response) {
                        $rootScope.loader = false;
                        $scope.products = response.data; 
                        $scope.headers  = response.fields;
                        $scope.col_heads  = response.col_heads;
                        var newElement = angular.element(response.elements);
                        var myEl = angular.element( document.querySelector( '#added' ) );
                        myEl.empty();

                        var complieIt = $compile(newElement);
                        var content   = complieIt($scope);
                        myEl.append(content); 

                     });

                  };



                  $scope.init = function () {

                     listService.getOptions().success(function(response){

                     $scope.pseries = response;
                     $scope.series_selecter = $scope.pseries[0]; 
                     $scope.list();
                    
                      
                     });

                  };

                  
   				});


   </script>
</html>