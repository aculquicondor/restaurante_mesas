restaurantControllers.controller('TablesCtrl', ['$scope', '$rootScope', '$location', 'Table', 'AuthSvc',
    function ($scope, $rootScope, $location, Table) {
        if (!$rootScope.user) {
            $location.path('/login');
        }
        Table.getAll().$promise.then(function(tables) {
            $scope.tables = tables.tables;
        }, function (errResponse) {
            console.log(errResponse);
        });
    }]);
