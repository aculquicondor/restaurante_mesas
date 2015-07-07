restaurantControllers.controller('ReservationCtrl', ['$scope', 'Reservations', 'ReservationTables', 'AuthSvc', '$location',
    function ($scope, Reservations, ReservationTables, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $scope.reservations = Reservations.query();
        $scope.reservationTables = function (reservationId) {
            ReservationTables.get({reservationId: reservationId}, {}, function (reservation) {
                console.log(reservation);
            });
        }
    }]);

restaurantControllers.controller('ReservationDetailCtrl', ['$scope', '$routeParams', 'ReservationTables', 'AuthSvc', '$location',
    function ($scope, $routeParams, ReservationTables, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $scope.reservationTables = ReservationTables.get({reservationId: $routeParams.reservationId});
    }]);
