restaurantServices.factory('Orders', ['$resource', 'baseURL',
    function ($resource, baseURL) {
        return $resource(baseURL + '/api/orders.json', {}, {
            query: {method: 'GET'}
        });
    }]);

restaurantServices.factory('Order', ['$resource', 'baseURL',
    function ($resource, baseURL) {
        return $resource(baseURL + '/api/orders/:orderId.json', {}, {
            get: { method: 'GET' },
            post: { method: 'POST' },
            patch: { method: 'PATCH' },
            delete: { method: 'DELETE' }
        });
    }]);
