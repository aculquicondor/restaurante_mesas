restaurantServices.factory('MenuItems', ['$resource',
    function($resource){
        return $resource('http://localhost:8000/api/menu/items.json', {}, {
            query: {method: 'GET', isArray: false}
        });
    }]);

restaurantServices.factory('MenuItem', ['$resource',
    function($resource){
        return $resource('http://localhost:8000/api/menu/items/:itemId.json', {}, {
            query: { method: 'GET', params:{itemId:'item'}, isArray:false }
        });
    }]);