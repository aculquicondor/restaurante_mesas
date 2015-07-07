restaurantControllers.controller('MenuItemListCtrl', ['$scope', '$rootScope', 'MenuItems',
    function($scope, $rootScope, MenuItems) {
        $rootScope.section = 'Menu';

        $scope.items = MenuItems.query();
        $scope.orderProp = 'available';
    }]);

restaurantControllers.controller('MenuItemDetailCtrl', ['$scope', '$rootScope', '$routeParams', 'MenuItem',
    function($scope, $rootScope, $routeParams, MenuItem) {
        $rootScope.section = 'Menu';

        $scope.item = MenuItem.query({itemId: $routeParams.itemId});
    }]);