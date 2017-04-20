/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Administration controller
 *
 */
angular
    .module('tcmApp')
    .controller('administrationCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal',
    function ($rootScope, $scope, $translate, $timeout, Modal)
    {
        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                default:
                    break;
        	}
        };
        
    }]);
