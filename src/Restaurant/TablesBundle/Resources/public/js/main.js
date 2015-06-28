var restaurantApp = angular.module('restaurantApp', [
    'ngRoute',
    'restaurantControllers',
    'restaurantServices'
]);

restaurantApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/tables', {
                templateUrl: 'partials/tables/main_tables.html',
                controller: 'TablesCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }
]);

$(document).ready(function(){
    $(".button-collapse").sideNav();
});
