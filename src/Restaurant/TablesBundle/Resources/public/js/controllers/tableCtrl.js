restaurantControllers.controller('TablesCtrl', ['$scope', '$rootScope', 'Tables', 'Table', 'AuthSvc', '$location', '$sce',
    function ($scope, $rootScope, Tables, Table, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $rootScope.section = 'Tables';

        $scope.tables = Tables.query();
        $scope.availableSwitch = false;
        $scope.update = function (tableId, availableSwitch) {
            Table.update({tableId: tableId}, {available: availableSwitch}, function (table) {
                console.log(table);
            });
        }
    }]);
