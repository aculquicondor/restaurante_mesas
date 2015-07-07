restaurantControllers.controller('OrderListCtrl', ['$scope', '$rootScope', 'Orders', 'Tables', '$location', 'AuthSvc',
    function($scope, $rootScope, Orders, Tables, $location, AuthSvc) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }

        $rootScope.section = 'Orders';
        $scope.orders = Orders.query();
        $scope.orderProperty = 'date';
        $scope.orderReverse = true;
        $scope.getParams = {
            active: false,
            employee: false
        };
        $scope.postParams = {
            employee: AuthSvc.getUser().employee.id,
            table: null
        };
        $scope.tables = Tables.query({ available: true });

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
                $('#new-order-modal').closeModal();
                $location.path('/orders/' + order.id);
            });
        };

    }]);


restaurantControllers.controller('OrderDetailCtrl',
    ['$scope', '$rootScope', '$routeParams', 'Order', 'OrderItems', 'OrderItem', 'MenuItems', '$location', 'AuthSvc',
        function ($scope, $rootScope, $routeParams, Order, OrderItems, OrderItem, MenuItems, $location, AuthSvc) {
            if (!AuthSvc.isAuthenticated()) {
                $location.path('/login');
            }
            $rootScope.section = 'Orders';

            $scope.order = Order.get({orderId: $routeParams.orderId});
            $scope.orderItems = OrderItems.query({orderId: $routeParams.orderId});

            $scope.orderProperty = 'delivered';
            $scope.orderReverse = false;

            $scope.menuItems = MenuItems.query({available: true});

            $scope.deleteOrder = function () {
                Order.delete({orderId: $scope.order.id}, {}, function () {
                    $location.path('/orders');
                });
            };

            $scope.closeOrder = function () {
                Order.update({orderId: $scope.order.id}, {active: false}, function () {
                    $scope.order.active = false;
                });
            };

            $scope.itemChange = function (item) {
                OrderItem.update({orderId: $scope.order.id, itemId: item.id},
                    {delivered: item.delivered});
            };

            $scope.itemDelete = function (itemId) {
                OrderItem.delete({orderId: $scope.order.id, itemId: itemId}, function () {
                    $scope.orderItems = OrderItems.query({orderId: $routeParams.orderId});
                });
            };

            $scope.openNewItemModal = function () {
                $('#new-item-modal').openModal();
            };

            $scope.itemParams = {
                menu_item: null,
                observations: ''
            };

            $scope.closeNewItemModal = function () {
                $scope.itemParams.menu_item = null;
                $scope.itemParams.observations = '';
                $('#new-item-modal').closeModal();
            };

            $scope.newItem = function () {
                if ($scope.itemParams.menu_item === null) {
                    $('#item-menu-item').focus();
                    return;
                }
                OrderItems.save({orderId: $scope.order.id}, $scope.itemParams,
                    function (orderItem) {
                        $scope.orderItems.items.push(orderItem);
                        $scope.itemParams.menu_item = null;
                        $scope.itemParams.observations = '';
                        $('#new-item-modal').closeModal();
                    });
            }

        }]);
