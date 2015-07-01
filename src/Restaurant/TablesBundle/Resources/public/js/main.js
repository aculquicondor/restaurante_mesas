var restaurantApp = angular.module('restaurantApp', [
    'ngRoute',
    'restaurantControllers',
    'restaurantServices',
    'restaurantFilters'
]);

restaurantApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/tables', {
                templateUrl: 'partials/tables/main_tables.html',
                controller: 'TablesCtrl'
            }).
            when('/items', {
                templateUrl: 'partials/menu_items/menuitem-list.html',
                controller: 'MenuItemListCtrl'
            }).
            when('/items:itemId', {
                templateUrl: 'partials/menu_items/menuitem-detail.html',
                controller: 'MenuItemDetailCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }
]);

$(document).ready(function(){
    $(".button-collapse").sideNav();
});
