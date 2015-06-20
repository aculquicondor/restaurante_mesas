var restaurantControllers = angular.module('restaurantControllers', []);

restaurantControllers.controller('TablesCtrl', ['$scope', 'Table', function($scope, Table) {
    Table.getAll().$promise.then(function(tables) {
        $scope.tables = tables.tables;
    }, function (errResponse) {
        console.log(errResponse);
    });
}]);