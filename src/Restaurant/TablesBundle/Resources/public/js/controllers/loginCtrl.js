restaurantApp.controller('LoginCtrl',
    function ($scope, $rootScope, $location, AuthSvc) {
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
    });
