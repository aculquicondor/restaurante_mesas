restaurantServices.factory('MenuItems', ['$resource', 'baseURL',
    function($resource, baseURL){
        return $resource(baseURL + '/api/menu/items.json', {}, {
            query: {method: 'GET', isArray: false}
        });
    }]);

restaurantServices.factory('MenuItem', ['$resource', 'baseURL',
    function($resource, baseURL){
        return $resource(baseURL + '/api/menu/items/:itemId.json', {}, {
            query: { method: 'GET', params:{itemId:'item'}, isArray:false }
        });
    }]);