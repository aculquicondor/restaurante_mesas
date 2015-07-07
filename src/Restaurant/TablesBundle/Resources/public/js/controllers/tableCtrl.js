restaurantControllers.controller('TablesCtrl', ['$scope', '$rootScope', 'Tables', 'Table', 'AuthSvc', '$location',
    function ($scope, $rootScope, Tables, Table, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $rootScope.section = 'Tables';

        $scope.tables = Tables.query();
        $scope.availableSwitch = false;
        $scope.available = false;
        $scope.postParams = {
            available: true,
            number: null,
            capacity: 0
        };
        $scope.doQuery = function () {
            var available = $scope.available ? 1 : 0;
            $scope.tables = Tables.query({ available: available });
        };
        $scope.update = function (tableId, availableSwitch) {
            Table.update({tableId: tableId}, {available: availableSwitch}, function (table) {
                console.log(table);
            });
        };
        $scope.closeNewTableModal = function () {
            $('#new-table-modal').closeModal();
        };

        $scope.openNewTableModal = function () {
            $('#new-table-modal').openModal();
        };

        $scope.newTable = function () {
            if ($scope.postParams.number === null){
                $('#table-number').focus();
                return;
            }
            if ($scope.postParams.capacity < 0){
                $('#table-capacity').focus();
                return;
            }

            Tables.save({}, $scope.postParams, function (table) {
                $('#new-table-modal').closeModal();
                $location.path('/tables/' + table.id);
            });
        };
        var init = function () {
            $('ul.tabs').tabs();
        };
        init();
    }]);

restaurantControllers.controller('TableDetailCtrl', ['$scope', '$rootScope', '$routeParams', 'Table', '$location', 'AuthSvc',
    function($scope, $rootScope, $routeParams, Table, $location, AuthSvc) {
        if (!AuthSvc.isAuthenticated()){
            $location.path('/login');
        }
        $rootScope.section = 'Tables';

        $scope.table = Table.get({tableId: $routeParams.tableId});

        $scope.deleteTable = function () {
            Table.delete({tableId: $scope.table.id}, {}, function () {
                $location.path('/tables');
            });
        };

        $scope.changeTable = function (table) {
            Table.update({tableId: $scope.table.id},
                {
                    available: table.available,
                    number: table.number,
                    capacity: table.capacity
                });
        };
    }]);
