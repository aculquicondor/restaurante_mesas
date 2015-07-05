restaurantServices.factory('Table', ['$resource', 'baseURL',
    function ($resource, baseURL) {
        return $resource(baseURL + '/api/tables.json', {}, {
            query: {method: 'GET', isArray: false}
        });
    }]);
