restaurantServices.factory('MenuItem', ['$resource',
    function($resource){
        return $resource('http://localhost:8000/api/menu/items.json', {}, {
            getAll: {method: 'GET'}
        });
    }]);
