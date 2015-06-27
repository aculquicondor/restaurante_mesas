var restaurantControllers = angular.module('restaurantControllers', []);

restaurantControllers.controller('TablesCtrl', ['$scope', 'Table', function($scope, Table) {
    Table.getAll().$promise.then(function(tables) {
        $scope.tables = tables.tables;
    }, function (errResponse) {
        console.log(errResponse);
    });
}]);

var restaurantServices = angular.module('restaurantServices', ['ngResource']);

restaurantServices.factory('Table', ['$resource',
    function($resource){
        return $resource('http://localhost:8000/api/tables.json', {}, {
            getAll: {method: 'GET'}
        });
    }]);

$(document).ready(function(){
    $(".button-collapse").sideNav();
});
