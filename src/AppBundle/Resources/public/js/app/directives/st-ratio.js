/**
 * Smart-Table ratio for column width
 */
angular
    .module('tcmApp')
    .directive('stRatio', function () {
        return {
            link:function(scope, element, attr) {
                var ratio=+(attr.stRatio);
                element.css('width',ratio+'%');
            }
        };
    });
