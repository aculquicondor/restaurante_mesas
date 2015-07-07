restaurantServices.factory('Reservations', ['$resource', 'baseURL',
    function($resource, baseURL) {
        return $resource(baseURL + '/api/reservations/', {}, {
            query: { method: 'GET' }
        });
    }]);

restaurantServices.factory('Reservation', ['$resource', 'baseURL',
    function($resource, baseURL) {
        return $resource(baseURL + '/api/reservations/:reservationId.json', {}, {
            get: { method: 'GET' }
        });
    }]);

restaurantServices.factory('ReservationTables', ['$resource', 'baseURL',
    function($resource, baseURL) {
        return $resource(baseURL + '/api/reservations/:reservationId/tables.json', {}, {
            get: { method: 'GET' }
        });
    }]);
