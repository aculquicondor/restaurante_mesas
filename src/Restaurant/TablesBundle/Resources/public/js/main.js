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
                auth: true,
                templateUrl: 'partials/main-tables.html',
                controller: 'TablesCtrl'
            }).
            when('/items', {
                auth: true,
                templateUrl: 'partials/menuitem-list.html',
                controller: 'MenuItemListCtrl'
            }).
            when('/items/:itemId', {
                auth: true,
                templateUrl: 'partials/menuitem-detail.html',
                controller: 'MenuItemDetailCtrl'
            }).
            when('/login', {
                auth: false,
                templateUrl: 'partials/login.html',
                controller: 'LoginCtrl'
            }).
            otherwise({
                redirectTo: '/login'
            });
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
