restaurantControllers.controller('TablesCtrl', ['$scope', 'Tables', 'AuthSvc', '$location',
    function ($scope, Tables, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $scope.tables = Tables.query();
    }]);
