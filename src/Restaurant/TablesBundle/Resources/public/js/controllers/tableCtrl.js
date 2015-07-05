restaurantControllers.controller('TablesCtrl', ['$scope', 'Tables', 'AuthSvc', '$location', '$sce',
    function ($scope, Tables, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $scope.tables = Tables.query();
        $scope.availableSwitch = false;
        $scope.update = function (tableId, availableSwitch) {
            Tables.update({}, {id: tableId, available: availableSwitch}, function (table) {
                console.log(table);
            });
        }
    }]);
