/**
 * Created by jeroenvisser on 12/05/2017.
 */

angular
    .module('tcmApp')
    .directive('refresher', function () {
        return {
            transclude: true,
            controller: function ($scope, $transclude,
                                  $attrs, $element) {
                var childScope;

                $scope.$watch($attrs.condition, function (value) {
                    $element.empty();
                    if (childScope) {
                        childScope.$destroy();
                        childScope = null;
                    }

                    $transclude(function (clone, newScope) {
                        childScope = newScope;
                        $element.append(clone);
                    });
                });
            }
        };
    });