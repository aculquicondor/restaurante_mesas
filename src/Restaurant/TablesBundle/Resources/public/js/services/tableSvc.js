restaurantServices.factory('Table', ['$resource', 'baseURL',
    function ($resource, baseURL) {
        return $resource(baseURL + '/api/tables.json', {}, {
            getAll: {method: 'GET'}
        });
    }]);
