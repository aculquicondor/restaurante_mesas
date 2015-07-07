restaurantServices.factory('MenuItems', ['$resource', 'baseURL',
    function($resource, baseURL){
        return $resource(baseURL + '/api/menu/items.json', {}, {
            query: { method: 'GET' },
            save: { method: 'POST' }
        });
    }]);

restaurantServices.factory('MenuItem', ['$resource', 'baseURL',
    function($resource, baseURL){
        return $resource(baseURL + '/api/menu/items/:itemId.json', {}, {
            get: { method: 'GET' },
            update: { method: 'PATCH' },
            delete: { method: 'DELETE', isArray: true }
        });
    }]);
