var restaurantApp = angular.module('restaurantApp', [
    'ngRoute',
    'restaurantControllers',
    'restaurantServices'
]);

restaurantApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/tables', {
                templateUrl: '../app/partials/tables/main_tables.html',
                controller: 'TablesCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }
]);
