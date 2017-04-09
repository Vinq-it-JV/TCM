/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Region controller
 *  
 */
angular
    .module('tcmApp')
    .controller('regionCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Regions',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Regions)
    {

        $scope.regions = DS_Regions;
        $scope.regionsCollection = [];

        $scope.getRegions = function ()
        {
            $scope.requestType = 'getRegions';

            var getdata = {
                'url': Routing.generate('administration_regions_get'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.getRegion = function (regionid)
        {
            $scope.requestType = 'getRegion';

            var getdata = {
                'url': Routing.generate('administration_region_get', {'regionid': regionid}),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.newRegion = function ()
        {
            $scope.requestType = 'newRegion';

            var getdata = {
                'url': Routing.generate('administration_region_new'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.editRegion = function (regionid)
        {
            $scope.showUrl(Routing.generate('administration_region_edit', {'regionid': regionid}));
        };

        $scope.addRegion = function ()
        {
            $scope.showUrl(Routing.generate('administration_region_add'));
        };

        $scope.deleteRegion = function (regionid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_REGION'),
                onExecute: function () {
                    $scope.regions.delRecord(regionid);
                    $scope.requestType = 'deleteRegion';
                    var deletedata = {
                        'url': Routing.generate('administration_region_delete', {'regionid':regionid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getRegions':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.regions.regionsSet(data.contents.regions);
                        $scope.regionsCollection = [].concat(data.contents.regions);
                    }
                    break;

                case 'newRegion':
                case 'getRegion':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.regions.updRecord(data.contents.region);
                        $scope.regions.templateSet(data.contents.template);
                    }
                    break;
                case 'deleteRegion':
                    $scope.regionsCollection = [].concat($scope.regions.regions());
                    break;
                default:
                    break;
        	}
        };
        
    }]);
