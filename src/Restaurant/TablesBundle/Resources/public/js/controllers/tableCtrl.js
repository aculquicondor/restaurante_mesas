restaurantControllers.controller('TablesCtrl', ['$scope', 'Tables', 'Table', 'AuthSvc', '$location',
    function ($scope, Tables, Table, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $scope.tables = Tables.query();
        $scope.availableSwitch = false;
        $scope.hideTables = function (option) {
            if (option === 'availables') {
                $('.available').hide();
                $('.unavailable').show();

            } else if (option === 'unavailables') {
                $('.unavailable').hide();
                $('.available').show();
            } else {
                $('.available, .unavailable').show();
            }
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
