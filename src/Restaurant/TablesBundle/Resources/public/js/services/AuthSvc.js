restaurantServices.factory('AuthSvc', ['$http', '$rootScope', '$cookies', 'baseURL',
    function ($http, $rootScope, $cookies, baseURL) {
        var authSvc = {};
        authSvc.login = function (credentials) {
            return $http.post(baseURL + '/login_check', credentials)
                .then(function (user) {
                    $cookies.putObject('user', user.data);
                    return user.data;
                });
        };
        authSvc.isAuthenticated = function () {
            return !!$rootScope.user || !!$cookies.get('user');
        };
        authSvc.getUser = function () {
            return $cookies.getObject('user');
        };
        authSvc.logout = function () {
            $cookies.remove('user');
        };
        return authSvc;
    }]);
