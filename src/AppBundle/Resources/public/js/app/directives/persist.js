/**
 * Created by jeroenvisser on 28-05-14.
 *
 * By setting the persist="name" directve in an <input element this
 * element will be stored in the local storage.
 */

angular
    .module('tcmApp')
    .directive('persist', function ($timeout, $parse, $localStorage) {
        return {
            link: function(scope, element, attrs) {
                var name = attrs.persist;
                var model = $parse(attrs.persist);
                element.change( function () {
                    $localStorage[name] = $(this).val();
                });
                element.load( function () {
                    $(this).text($localStorage[name]);
                    console.log($localStorage[name]);
                });
            }
        };
    });