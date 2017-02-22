angular
    .module('tcmApp')
    .directive('noEnter', function () {
        return {
            link: function(scope, element, attrs) {
                element.keypress(function (e) {
                    if (e.keyCode == 13)
                        e.preventDefault();
                });
            }
        };
    });
