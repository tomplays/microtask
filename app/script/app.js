
var app = angular.module('presstool', ['ngResource','ngAnimate', 'ngSanitize'])
.controller('MicrotaskCtrl', ['$window','$scope', '$http','$sce', '$location', '$timeout', function($window, $scope, $http, $sce,$location, $timeout) {
	
$scope.order_by = function(param){
alert(param)
}
		
		$scope.init = function(){ 
			

			$http.get('data/data.json').success(function(d) {
					$scope.items = d;				
				_.each(d, function(i,ix){
						console.log(i)

			})
			
				$scope.ready = true;
				
	        })

		}
		// init load game
		$scope.init()
}])
