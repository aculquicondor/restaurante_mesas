restaurantControllers.controller('ReservationCtrl', ['$scope', '$rootScope', 'Reservations', 'ReservationTables', 'AuthSvc', '$location',
    function ($scope, $rootScope, Reservations, ReservationTables, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $rootScope.section = 'Reservations';
        $scope.reservations = Reservations.query();
    }]);

restaurantControllers.controller('ReservationDetailCtrl', ['$scope', '$routeParams', 'Reservation', 'ReservationTables', 'AuthSvc', '$location',
    function ($scope, $routeParams, Reservation, ReservationTables, AuthSvc, $location) {
        if (!AuthSvc.isAuthenticated()) {
            $location.path('/login');
        }
        $scope.currentReservation = Reservation.get({reservationId: $routeParams.reservationId});
        $scope.reservationTables = ReservationTables.get({reservationId: $routeParams.reservationId});
    }]);
