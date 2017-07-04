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

            $scope.test = 0;

            $scope.updateTimer = null;
            $scope.updateTimeout = 10000;

            $scope.storesCollection = [];

            $scope.activePage = 'dashboard';
            $scope.sensorGroup = null;

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

            $scope.showInventory = function () {

                $scope.showUrl(Routing.generate('tcm_store_inventory', {'storeid': $scope.stores.store().Id}));
            };

            $scope.showMaintenance = function () {

                $scope.showUrl(Routing.generate('tcm_store_maintenance', {'storeid': $scope.stores.store().Id}));
            };

            $scope.showStore = function (storeid) {
                $scope.showUrl(Routing.generate('tcm_store_show', {'storeid': storeid}));
            };

            $scope.showDashboard = function () {
                $scope.activePage = 'dashboard';
            };

            $scope.showInformation = function () {
                $scope.activePage = 'information';
            };

            $scope.showNotifications = function () {
                $scope.activePage = 'notifications';
            };

            $scope.showSensors = function () {
                $scope.activePage = 'sensors';
                $scope.sensorGroup = null;
            };

            $scope.gotoSensor = function (groupid) {
                if (groupid == 0)
                    $scope.showSensors();
                else
                    $scope.showGroupSensors(groupid);
            };

            $scope.isPage = function (pageid) {
                return $scope.activePage == pageid;
            };

            $scope.updateSensors = function () {
                $scope.updateTimer = $timeout(function () {
                    switch ($scope.activePage) {
                        case 'sensors':
                        case 'groupSensors':
                            $scope.requestType = 'updateSensors';

                            var getdata = {
                                'url': Routing.generate('tcm_update_sensors', {'storeid': $scope.stores.store().Id}),
                                'payload': '',
                                'loaderdelay': 2000
                            };

                            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
                            break;
                        default:
                            break;
                    }
                }, $scope.updateTimeout);
            };

            $scope.showGroupSensors = function (groupid) {
                $scope.sensorGroup = groupid;
                $scope.activePage = 'groupSensors';
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
                            console.log(data.contents.store);
                            $scope.stores.updRecord(data.contents.store);
                            $scope.stores.listsSet(data.contents.lists);
                            $scope.sensors.sensorsSet(data.contents.devicegroups);
                            $scope.sensors.setSensorCharts();
                            $scope.updateSensors();
                        }
                        break;
                    case 'updateSensors':
                        if (!$scope.isValidObject(data)) {
                            $timeout.cancel($scope.updateTimer);
                            break;
                        }
                        if (!data.errorcode) {
                            $scope.sensors.updateSensors(data.contents.devicegroups);
                            $scope.updateSensors();
                        }
                        break;
                    default:
                        break;
                }
            };
        }]);
