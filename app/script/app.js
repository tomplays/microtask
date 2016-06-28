
var app = angular.module('presstool', ['ngResource','ngAnimate', 'ngSanitize'])
.controller('MicrotaskCtrl', ['$window','$scope', '$http','$sce', '$location', '$timeout', function($window, $scope, $http, $sce,$location, $timeout) {
	
		$scope.order_by = function(param){
			
			if(param=='tasks_value'){
				$scope.circular.grouped = _.sortBy($scope.circular.grouped, function(g){return -g.tasks_value} )
			}
			if(param=='tasks_count'){
				$scope.circular.grouped = _.sortBy($scope.circular.grouped, function(g){return -g.tasks_count} )
			}

			else{
				$scope.screen_items = _.sortBy($scope.items_store, function(i){return i[param]});
			}
			
		}

		$scope.minimal_time_circular = function(){
				$scope.circular_total_time = 0
				$scope.circular = {
						//time:0,
						tasks_value:0,
						grouped : []
				}
				$scope.itemsGroup = _.groupBy($scope.items_store, function(i){return i['commission']});
				

				
				_.each($scope.itemsGroup, function(iGroup){
					if(!iGroup.tasks_value){
						iGroup.tasks_value  = 0;
					}
					iGroup.tasks_count = _.size(iGroup)
					iGroup.best = 0
					iGroup.tasks_count_ratio  = Math.round( (_.size(iGroup)*100)/$scope.tasks_infos.count)+'%';
						
						//var min_time = _.sortBy(iGroup, function(num){ return parseFloat(num.temps_individuel); });
						//console.log('min group time = '+min_time)
						
						_.each(iGroup, function(e,ei){
							if(!iGroup.commission){
								iGroup.commission = e.commission
							}
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
							
						    iGroup.tasks_value = iGroup.tasks_value+parseFloat(e.task_value)
							iGroup.tasks_value_ratio  =	  iGroup.tasks_value+'/'+$scope.tasks_infos.tasks_value

							if(parseFloat(e.task_value) > iGroup.best){
								iGroup.best = parseFloat(e.task_value)
							}

								if(ei+1 == _.size(iGroup) ){
										// console.log('best for '+e.commission+' == '+iGroup.best)
										$scope.circular.tasks_value = parseFloat($scope.circular.tasks_value+iGroup.best);
								}

						})

						


				})
				$scope.circular.grouped = $scope.itemsGroup
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
					if(name == 'storyboard_3' ){
						$scope.me.name_ 	= $scope.form_.name
						$scope.me.facebook_fan 	= true;
						$scope.me.progress  = $scope.me.progress+1;
					}
					if(name == 'stats'){
						 $scope.minimal_time_circular();
					}
		}
		

		$scope.init = function(){ 
			

			$scope.storyboard = {

					steps : [
								{
									order:0,
									name: 'say hello',
									current : true
								},
								{
									order:1,
									name: 'another',
									current : false
								}
							]
			}
				


			$scope.get_storyboard = function(){

			}

			$http.get('data/data.json').success(function(d) {
				 
				$scope.form_ = {
				 	name : 'Votre prénom'
				}
				
				$scope.me = {
					name_ : '',
					facebook_fan : false,
					progress: 0
				}
				
				$scope.tasks_infos = {
					count : _.size(d),
					tasks_value: 0
				}
				_.each(d, function(i,ix){
					i.task_upper_details = ''
					$scope.tasks_infos.tasks_value = $scope.tasks_infos.tasks_value+parseFloat(i.task_value)
					if(i.task_upper){
						_.each(d, function(ied,ns){
							if(parseFloat(i.task_upper) == parseFloat(ied.id_) ){
								t = _.clone(ied)
								t.task_upper_details = '' // no recurs.
								i.task_upper_details = t
							}	
						})
					}				
				})

				$scope.items_store  = d;
				$scope.screen_items = d;	
				$scope.getScreen_('storyboard_0');
				$scope.ready = true;	
				
	        })

		}
		// init load game
		$scope.init();
}])
