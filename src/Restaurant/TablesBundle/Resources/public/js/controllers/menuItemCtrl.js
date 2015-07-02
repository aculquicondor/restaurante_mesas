restaurantControllers.controller('MenuItemListCtrl', ['$scope', 'MenuItems', function($scope, MenuItems) {
    $scope.items = MenuItems.query();
    $scope.orderProp = 'available';
}]);

restaurantControllers.controller('MenuItemDetailCtrl', ['$scope', '$routeParams', 'MenuItem', function($scope, $routeParams, MenuItem) {
    $scope.item = MenuItem.query({itemId: $routeParams.itemId});
}]);