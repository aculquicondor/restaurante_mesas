restaurantControllers.controller('MenuItemCtrl', ['$scope', 'MenuItem', function($scope, MenuItem) {
    MenuItem.getAll().$promise.then(function(items) {
        $scope.items = items.items
    }, function (errResponse) {
        console.log(errResponse);
    });
}]);
