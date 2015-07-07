restaurantApp.controller('LoginCtrl', ['$scope', '$rootScope', '$location', 'AuthSvc',
    function ($scope, $rootScope, $location, AuthSvc) {
        $rootScope.section = 'Login';

        $scope.message = '';
        $scope.credentials = {
            _username: '',
            _password: ''
        };
        $scope.login = function (credentials) {
            AuthSvc.login(credentials).then(function (user) {
                $rootScope.user = user;
                $location.path('/tables');
            }, function () {
                $scope.credentials._password = '';
                $scope.message = 'Invalid credentials';
            });
        }
    }]);
