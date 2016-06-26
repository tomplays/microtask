
var app = angular.module('presstool', ['ngResource','ngAnimate', 'ngSanitize'])
.controller('MicrotaskCtrl', ['$window','$scope', '$http','$sce', '$location', '$timeout', function($window, $scope, $http, $sce,$location, $timeout) {
	
		$scope.order_by = function(param){
				$scope.screen_items = _.sortBy($scope.items_store, function(i){return i[param]});
		}

		$scope.minimal_time_circular = function(){
				$scope.circular_total_time = 0
				$scope.circular = {
						//time:0,
						tasks_value:0,
				}
				$scope.itemsGroup = _.groupBy($scope.items_store, function(i){return i['commission']});
				_.each($scope.itemsGroup, function(iGroup){
						
						var best = 0
						//var min_time = _.sortBy(iGroup, function(num){ return parseFloat(num.temps_individuel); });
						//console.log('min group time = '+min_time)
						
						_.each(iGroup, function(e,ei){

							// var eparse = e.temps_individuel.split(':')
							// console.log(eparse)
							 //var hour 	 = parseFloat(eparse[0])
							 //var minutes = parseFloat(eparse[1])
							 //var seconds = parseFloat(eparse[2])
							 //var total_time = seconds

							// console.log(total_time)
							// var realmin = minutes % 60
    						// var hours = Math.floor(minutes / 60)
							// console.log(e.temps_individuel)
							

							if(parseFloat(e.task_value) > best){
								best = parseFloat(e.task_value)
							}
								//	console.log('e index '+ei+' vs '+ _.size(iGroup))

								if(ei+1 == _.size(iGroup) ){
										console.log('best for '+e.commission+' == '+best)

										$scope.circular.tasks_value = parseFloat($scope.circular.tasks_value+best);

								}

						})
						


				})
				console.log('$scope.circular')
				console.log($scope.circular)

		}


		$scope.group_by = function(param){
				$scope.itemsGroup = _.groupBy($scope.items_store, function(i){return i[param]});
				$scope.screen_items = [];
				_.each($scope.itemsGroup, function(iGroup){
					_.each(iGroup, function(e){
						$scope.screen_items.push(e);
					})
				})
		}
		$scope.filter_ = function(param, value){
					$scope.screen_items =   _.filter($scope.items_store, function(item){ return item[param]  == value; });
		}	
		$scope.reset_ = function(param, value){
					$scope.screen_items =  $scope.items_store;
		}
		$scope.getScreen_ = function(name){
					$scope.screen_ = name;
					if(name == 'random'){
						$scope.screen_items = _.sample($scope.items_store, 1);


					}
					if(name == 'grid'){
						$scope.screen_items = $scope.items_store;
					}
		}
		

		$scope.init = function(){ 
			
			$http.get('data/data.json').success(function(d) {
				
			
				_.each(d, function(i,ix){
				i.task_upper_details = 'd'
					if(i.task_upper){
							_.each(d, function(ied,ns){
								if(parseFloat(i.task_upper) == parseFloat(ied.id_) ){
									tt = _.clone(ied)
									tt.task_upper_details = ''
									i.task_upper_details = tt
								}	
							})
					}				
				})
				$scope.items_store = d;
				$scope.screen_items =d;	
				$scope.getScreen_('random');
				$scope.ready = true;	
				$scope.minimal_time_circular()
	        })

		}
		// init load game
		$scope.init();
}])
