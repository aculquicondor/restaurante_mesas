restaurantServices.factory('Tables', ['$resource', 'baseURL',
    function($resource, baseURL) {
        return $resource(baseURL + '/api/tables/', {}, {
            query: { method: 'GET' }
        });
    }]);

restaurantServices.factory('Table', ['$resource', 'baseURL',
    function($resource, baseURL) {
        return $resource(baseURL + '/api/tables/:tableId.json', {}, {
            update: { method: 'PATCH'},
            get: {method: 'GET'}
        });
    }]);
