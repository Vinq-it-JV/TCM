/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Splash controller
 *
 */
angular
    .module('tcmApp')
    .controller('splashCtrl', ['$rootScope', '$scope', '$translate', '$timeout',
        function ($rootScope, $scope, $translate, $timeout, Modal) {
            $scope.init = function () {
                var $splashText = $('#splashText');
                if ($scope.isValidObject($splashText)) {
                    $splashText.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                        $scope.showUrl(Routing.generate('tcm_dashboard'));
                    });
                }
            };
        }]);
