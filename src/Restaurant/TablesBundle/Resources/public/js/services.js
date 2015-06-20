var restaurantServices = angular.module('restaurantServices', ['ngResource']);

restaurantServices.factory('Table', ['$resource',
    function($resource){
        return $resource('http://localhost:8000/api/tables.json', {}, {
            getAll: {method: 'GET'}
        });
    }]);
