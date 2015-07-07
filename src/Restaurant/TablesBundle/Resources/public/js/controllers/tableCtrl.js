restaurantControllers.controller('TablesCtrl', ['$scope', '$rootScope', 'Tables', 'Table', 'AuthSvc', '$location',
    function ($scope, $rootScope, Tables, Table, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $rootScope.section = 'Tables';

        $scope.tables = Tables.query();
        $scope.availableSwitch = false;
        $scope.available = false;
        $scope.doQuery = function () {
            var available = $scope.available ? 1 : 0;
            $scope.tables = Tables.query({ available: available });
        };
        $scope.update = function (tableId, availableSwitch) {
            Table.update({tableId: tableId}, {available: availableSwitch}, function (table) {
                console.log(table);
            });
        };
        var init = function () {
            $('ul.tabs').tabs();
        };
        init();
    }]);

