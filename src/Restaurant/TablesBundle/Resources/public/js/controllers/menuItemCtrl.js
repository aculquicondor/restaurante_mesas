restaurantControllers.controller('MenuItemListCtrl', ['$scope', '$rootScope', 'MenuItems', '$location', 'AuthSvc',
    function($scope, $rootScope, MenuItems, $location, AuthSvc) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }

        $rootScope.section = 'Menu';
        $scope.items = MenuItems.query();
        $scope.orderProperty = 'available';
        $scope.orderReverse = true;
        $scope.getParams = {
            available: false
        };

        $scope.doQuery = function () {
            var params = {
                available: $scope.getParams.available ? 1 : 0
            };
            $scope.items = MenuItems.query(params);
        }

    }]);

restaurantControllers.controller('MenuItemDetailCtrl', ['$scope', '$rootScope', '$routeParams', 'MenuItem', '$location', 'AuthSvc',
    function($scope, $rootScope, $routeParams, MenuItem, $location, AuthSvc) {
        if (!AuthSv.isAuthenticated()){
            $location.path('/login');
        }
        $rootScope.section = 'Menu';

        $scope.item = MenuItem.get({itemId: $routeParams.itemId});

        $scope.deleteMenuItem = function () {
            MenuItem.delete({itemId: $scope.item.id}, {}, function () {
                $location.path('/menu');
            });
        };

        $scope.itemChange = function (item) {
            MenuItem.update({itemId: $scope.item.id})
        };
    }]);