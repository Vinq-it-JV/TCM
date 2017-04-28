/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Dashboard controller
 *
 */
angular
    .module('tcmApp')
    .controller('dashboardCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Stores', 'DS_Sensors', 'DS_Charts',
        function ($rootScope, $scope, $translate, $timeout, Modal, DS_Stores, DS_Sensors, DS_Charts) {

            $scope.stores = DS_Stores;
            $scope.sensors = DS_Sensors;
            $scope.charts = DS_Charts;

            $scope.storesCollection = [];

            $scope.activePage = 'dashboard';

            $scope.getStores = function () {
                $scope.requestType = 'getStores';

                var getdata = {
                    'url': Routing.generate('tcm_stores_get'),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.getStore = function (storeid) {
                $scope.requestType = 'getStore';

                var getdata = {
                    'url': Routing.generate('tcm_store_get', {'storeid': storeid}),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.showStore = function (storeid) {
                $scope.showUrl(Routing.generate('tcm_store_show', {'storeid': storeid}));
            };

            $scope.showDashboard = function () {
                $scope.activePage = 'dashboard';
            };

            $scope.showSensors = function () {
                $scope.activePage = 'sensors';
            };

            $scope.showGroupSensors = function (groupid) {
                $scope.sensors.getRecord(groupid);
                if ($scope.isValidObject($scope.sensors.sensor()))
                    $scope.activePage = 'groupSensors';
                console.log($scope.sensors.sensor());
            };

            $scope.fetchDataOk = function (data) {
                switch ($scope.requestType) {
                    case 'getStores':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.stores.storesSet(data.contents.stores);
                            $scope.storesCollection = [].concat(data.contents.stores);
                        }
                        break;

                    case 'getStore':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            console.log(data.contents);
                            $scope.stores.updRecord(data.contents.store);
                            $scope.stores.listsSet(data.contents.lists);
                            $scope.sensors.sensorsSet(data.contents.devicegroups);
                            $scope.sensors.setSensorCharts();
                        }
                        break;
                    default:
                        break;
                }
            };

        }]);
