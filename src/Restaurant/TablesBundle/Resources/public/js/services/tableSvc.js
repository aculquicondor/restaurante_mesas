restaurantServices.factory('Tables', ['$resource', 'baseURL',
    function($resource, baseURL) {
        return $resource(baseURL + '/api/tables/', {}, {
            query: { method: 'GET' },
            update: { method: 'PATCH'}
        });
    }]);
