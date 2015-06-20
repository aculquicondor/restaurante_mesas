var restaurantControllers = angular.module('restaurantControllers', []);

restaurantControllers.controller('TablesCtrl', ['$scope', 'Table', function($scope, Table) {
    $scope.tables = Table.query()
}]);