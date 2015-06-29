restaurantServices.factory('AuthSvc', ['$http', '$rootScope', '$cookies', '$q', 'baseURL',
    function ($http, $rootScope, $cookies, $q, baseURL) {
        var authSvc = {};
        authSvc.login = function (credentials) {
            return $http.post(baseURL + '/login_check', credentials)
                .then(function (response) {
                    if (!response.data.username) {
                        return $q.reject(response.data);
                    }
                    $cookies.putObject('user', response.data);
                    return response.data;
                }, function (response) {
                    return $q.reject(response.data);
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
