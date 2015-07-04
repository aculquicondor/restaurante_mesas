restaurantControllers.controller('OrderListCtrl', ['$scope', 'Orders', 'Table', '$location', 'AuthSvc',
    function($scope, Orders, Table, $location, AuthSvc) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }

        $scope.orders = Orders.query();
        $scope.orderProperty = 'active';
        $scope.orderReverse = true;
        $scope.getParams = {
            active: false,
            employee: false
        };
        $scope.postParams = {
            employee: AuthSvc.getUser().employee.id,
            table: null
        };
        $scope.tables = Table.getAll({ available: true });

        $scope.doQuery = function () {
            var params = {
                active: $scope.getParams.active ? 1 : 0,
                employee: $scope.getParams.employee ? AuthSvc.getUser().employee.id : null
            };
            $scope.orders = Orders.query(params);
        };

        $scope.openNewOrderModal = function () {
            $('#new-order-modal').openModal();
        };

        $scope.closeNewOrderModal = function () {
            $('#new-order-modal').closeModal();
        };

        $scope.newOrder = function () {
            if ($scope.postParams.table === null) {
                $('#table-number').focus();
                return;
            }
            Orders.save({}, $scope.postParams, function (order) {
                // TODO go to order
                console.log(order);
            });
        }

    }]);
