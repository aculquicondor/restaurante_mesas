var restaurantApp = angular.module('restaurantApp', [
    'ngRoute',
    'ngCookies',
    'restaurantControllers',
    'restaurantServices',
    'restaurantFilters'
]);

restaurantApp.value('baseURL', URL);

restaurantApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/tables', {
                templateUrl: 'partials/tables/main_tables.html',
                controller: 'TablesCtrl'
            }).
            when('/items', {
                templateUrl: 'partials/menuitem-list.html',
                controller: 'MenuItemListCtrl'
            }).
            when('/items/:itemId', {
                templateUrl: 'partials/menuitem-detail.html',
                controller: 'MenuItemDetailCtrl'
            }).
            when('/orders', {
                templateUrl: 'partials/order-list.html',
                controller: 'OrderListCtrl'
            }).
            when('/orders/:orderId', {
                templateUrl: 'partials/order-detail.html',
                controller: 'OrderDetailCtrl'
            }).
            when('/login', {
                templateUrl: 'partials/login.html',
                controller: 'LoginCtrl'
            }).
            otherwise({
                redirectTo: '/login'
            })
    }
]);

restaurantApp.run(['$rootScope', '$location', 'AuthSvc', 'baseURL',
    function ($rootScope, $location, AuthSvc, baseURL) {
        $rootScope.baseURL = baseURL;
        if (AuthSvc.isAuthenticated()) {
            $rootScope.user = AuthSvc.getUser();
            $rootScope.logout = function () {
                $rootScope.user = null;
                AuthSvc.logout();
            }
        } else {
            $rootScope.user = null;
        }
    }
]);

$(document).ready(function(){
    $(".button-collapse").sideNav();
});
