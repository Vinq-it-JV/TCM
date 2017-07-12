/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Packetlog controller
 *  
 */
angular
    .module('tcmApp')
    .controller('packetlogCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Logs',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Logs)
    {

        $scope.logs = DS_Logs;
        $scope.logCollection = [];

        $scope.getPacketlog = function ()
        {
            $scope.requestType = 'getPacketlog';

            var getdata = {
                'url': Routing.generate('configuration_packetlog_get'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.changeDisplayMode = function (logid)
        {
            $scope.logs.updateDisplayMode(logid);
        };

        $scope.showData = function () {
            console.log($scope.logs.logs());
            console.log($scope.logCollection);
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getPacketlog':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.logs.logsSet(data.contents.packetLog);
                        $scope.logCollection = [].concat($scope.logs.logs());
                    }
                    break;
                default:
                    break;
        	}
        };
        
    }]);
