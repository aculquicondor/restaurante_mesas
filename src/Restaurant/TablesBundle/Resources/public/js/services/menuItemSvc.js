restaurantServices.factory('MenuItem', ['$resource',
    function($resource){
        return $resource('http://localhost:8000/api/menu/items.json', {}, {
            query: {method: 'GET', isArray: false}
        });
    }]);
