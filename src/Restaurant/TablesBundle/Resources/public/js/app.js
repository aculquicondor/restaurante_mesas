var restaurantApp = angular.module('restaurantApp', [
    'ngRoute',
    'restaurantControllers'
]);

restaurantApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/example', {
                templateUrl: '../app/example.html',
                controller: 'ExampleCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);