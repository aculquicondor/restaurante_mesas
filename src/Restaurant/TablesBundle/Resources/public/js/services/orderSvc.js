restaurantServices.factory('Orders', ['$resource', 'baseURL',
    function ($resource, baseURL) {
        return $resource(baseURL + '/api/orders.json', {}, {
            query: { method: 'GET' },
            save: { method: 'POST' }
        });
    }]);

restaurantServices.factory('Order', ['$resource', 'baseURL',
    function ($resource, baseURL) {
        return $resource(baseURL + '/api/orders/:orderId.json', {}, {
            get: { method: 'GET' },
            update: { method: 'PATCH' },
            delete: { method: 'DELETE' }
        });
    }]);
