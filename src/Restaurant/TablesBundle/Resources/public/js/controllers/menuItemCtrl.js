restaurantControllers.controller('MenuItemListCtrl', ['$scope', 'MenuItem', function($scope, MenuItem) {
    $scope.items = MenuItem.query();
    $scope.orderProp = 'available';
}]);

