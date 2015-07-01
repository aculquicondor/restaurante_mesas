var restaurantFilters = angular.module('restaurantFilters', []);

restaurantFilters.filter('checkmark', function() {
    return function(input) {
        return input ? '\u2713' : '\u2718';
    };
});

restaurantFilters.filter('round', function() {
    return function(input) {
        return parseFloat(input).toFixed(2);
    };
});