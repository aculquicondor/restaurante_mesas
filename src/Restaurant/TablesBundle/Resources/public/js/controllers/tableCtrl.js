restaurantControllers.controller('TablesCtrl', ['$scope', 'Tables', function ($scope, Tables) {
    $scope.tables = Tables.query();
}]);
