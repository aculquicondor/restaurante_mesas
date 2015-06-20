var restaurantApp = angular.module('restaurantApp', [
    'ngRoute',
    'restaurantControllers',
    'restaurantServices'
]);

restaurantApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/tables', {
                templateUrl: '../app/partials/example.html',
                controller: 'TablesCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }
]);
