restaurantControllers.controller('OrderListCtrl', ['$scope', 'Orders', '$location', 'AuthSvc',
    function($scope, Orders, $location, AuthSvc) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }

        $scope.orders = Orders.query();
        $scope.orderProperty = 'active';
        $scope.orderReverse = true;
        $scope.params = {
            active: false,
            employee: false
        };

        $scope.doQuery = function () {
            var params = {
                active: $scope.params.active ? 1 : 0,
                employee: $scope.params.employee ? AuthSvc.getUser().employee.id : null
            };
            $scope.orders = Orders.query(params);
        };
    }]);
