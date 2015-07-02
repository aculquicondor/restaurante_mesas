var restaurantFilters = angular.module('restaurantFilters', []);

restaurantFilters.filter('checkmark', function() {
    return function(input) {
        return input ? '\u2713' : '\u2718';
    };
});

