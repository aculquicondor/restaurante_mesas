var restaurantServices = angular.module('restaurantServices', ['ngResource']);

restaurantServices.factory('Table', ['$resource',
    function($resource){
        return $resource('http://localhost/api/tables.json', {}, {
            query: {method:'GET', params:{}, isArray:true}
        });
    }]);
