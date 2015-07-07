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
        $scope.postParams = {
            available: true,
            name: null,
            price: 0
        };

        $scope.doQuery = function () {
            var params = {
                available: $scope.getParams.available ? 1 : 0
            };
            $scope.items = MenuItems.query(params);
        };


        $scope.closeNewMenuItemModal = function () {
            $('#new-menuitem-modal').closeModal();
        };

        $scope.openNewMenuItemModal = function () {
            $('#new-menuitem-modal').openModal();
        };

        $scope.newMenuItem = function () {
            if ($scope.postParams.name === null){
                $('#item-name').focus();
                return;
            }
            if ($scope.postParams.price < 0){
                $('#item-price').focus();
                return;
            }

            MenuItems.save({}, $scope.postParams, function (item) {
                    $('new-menuitem-modal').closeModal();
                    $location.path('/menu/' + item.id);
                });
        }
    }]);

restaurantControllers.controller('MenuItemDetailCtrl', ['$scope', '$rootScope', '$routeParams', 'MenuItem', '$location', 'AuthSvc',
    function($scope, $rootScope, $routeParams, MenuItem, $location, AuthSvc) {
        if (!AuthSvc.isAuthenticated()){
            $location.path('/login');
        }
        $rootScope.section = 'Menu';

        $scope.item = MenuItem.get({itemId: $routeParams.itemId});

        $scope.deleteMenuItem = function () {
            MenuItem.delete({itemId: $scope.item.id}, {}, function () {
                $location.path('/menu');
            });
        };

        $scope.changeMenuItem = function (item) {
            MenuItem.update({itemId: $scope.item.id},
                {
                    available: item.available,
                    name: item.name,
                    price: item.price
                });
        };
    }]);