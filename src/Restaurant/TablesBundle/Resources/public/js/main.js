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
            when('/items', {
                templateUrl: 'partials/menu_items/main_menuitems.html',
                controller: 'MenuItemCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }
]);

$(document).ready(function(){
    $(".button-collapse").sideNav();
});
