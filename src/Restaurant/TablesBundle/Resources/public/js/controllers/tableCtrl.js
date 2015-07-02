restaurantControllers.controller('TablesCtrl', ['$scope', '$rootScope', '$location', 'Table',
    function ($scope, $rootScope, $location, Table) {
        Table.getAll().$promise.then(function(tables) {
            $scope.tables = tables.tables;
        }, function (errResponse) {
            console.log(errResponse);
        });
    }]);
