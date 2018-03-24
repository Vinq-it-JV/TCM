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
    .controller('dashboardCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Stores', 'DS_Sensors', 'DS_Charts', 'DS_Sensorlog',
        function ($rootScope, $scope, $translate, $timeout, Modal, DS_Stores, DS_Sensors, DS_Charts, DS_Sensorlog) {

            $scope.stores = DS_Stores;
            $scope.sensors = DS_Sensors;
            $scope.charts = DS_Charts;
            $scope.sensorlog = DS_Sensorlog;
            $scope.imageRand = new Date().getTime();

            $scope.test = 0;

            $scope.updateTimer = null;
            $scope.updateTimeout = 10000;

            $scope.storesCollection = [];

            $scope.activePage = 'dashboard';
            $scope.sensorGroup = null;
            $scope.getSensorLog = false;

            if ($scope.LS.get('subPage')) {
                $scope.activePage = $scope.LS.get('subPage');
                $scope.LS.set('subPage', '');
            }

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

            $scope.showPeriodicMaintenance = function () {

                $scope.showUrl(Routing.generate('tcm_store_maintenance', {'storeid': $scope.stores.store().Id}));
            };

            $scope.showBeerTech = function () {

                $scope.showUrl(Routing.generate('tcm_store_beertech', {'storeid': $scope.stores.store().Id}));
            };

            $scope.showStore = function (storeid) {
                $scope.showUrl(Routing.generate('tcm_store_show', {'storeid': storeid}));
            };

            $scope.showDashboard = function () {
                $scope.activePage = 'dashboard';
            };

            $scope.showMaintenance = function () {
                $scope.activePage = 'maintenance';
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

            $scope.showSensorLog = function (sensor) {
                if (!$scope.isValidObject(sensor))
                    return;
                $scope.getSensorLog = true;
                $scope.sensorlog.sensorSet(sensor);
                $scope.stopUpdateSensors();
                $scope.getSensorLogData();
            };

            $scope.getSensorLogData = function ()
            {
                if (!$scope.getSensorLog)
                    return;

                switch ($scope.sensorlog.sensor().TypeId)
                {
                    case 4:
                        $scope.requestType = 'getInputLog';
                        var getdata = {
                            'url': Routing.generate('tcm_input_sensor_log', {'sensorid': $scope.sensorlog.sensor().Id}),
                            'payload': ''
                        };
                        $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
                        break;
                    case 3:
                        $scope.requestType = 'getTemperatureLog';
                        var getdata = {
                            'url': Routing.generate('tcm_temperature_sensor_log', {'sensorid': $scope.sensorlog.sensor().Id}),
                            'payload': ''
                        };
                        $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
                        break;
                    default:
                        break;
                }
            };

            $scope.showSensorLogGraph = function ()
            {
                var modalDefaults = {
                    templateUrl: templatePrefix + "sensorlog.tpl.html"
                };

                var modalOptions = {
                    closeButtonText: $translate.instant('CLOSE'),
                    actionButtonText: '',
                    headerText: $scope.sensorlog.sensor().Name,
                    bodyText: '',
                    wide: false,
                    onCancel: function () {
                        $scope.getSensorLog = false;
                        $scope.updateSensors();
                    },
                    onExecute: function () { },
                    parentScope: $scope
                };
                Modal.open(modalDefaults, modalOptions);
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

            $scope.stopUpdateSensors = function ()
            {
                $timeout.cancel($scope.updateTimer);
            };

            $scope.updateSensors = function () {
                $scope.updateTimer = $timeout(function () {
                    if ($scope.getSensorLog)
                        return;
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

            $scope.imageUrl = function (imageid)
            {
                var route = Routing.generate('tcm_store_image_get', {'imageid':imageid, 'rand':$scope.imageRand});
                return route;
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
                        else
                            $scope.showRoute('tcm_dashboard');
                        break;
                    case 'getStore':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.stores.updRecord(data.contents.store);
                            $scope.stores.listsSet(data.contents.lists);
                            $scope.sensors.sensorsSet(data.contents.devicegroups);
                            $scope.sensors.setSensorCharts();
                            $scope.updateSensors();
                        }
                        else
                            $scope.showRoute('tcm_dashboard');
                        break;
                    case 'updateSensors':
                        if (!$scope.isValidObject(data)) {
                            $scope.updateSensors();
                            break;
                        }
                        if (!data.errorcode) {
                            $scope.sensors.updateSensors(data.contents.devicegroups);
                            $scope.updateSensors();
                        }
                        else
                            $scope.showRoute('tcm_dashboard');
                        break;
                    case 'getTemperatureLog':
                        $scope.getSensorLog = false;
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.sensorlog.logdataSet(data.contents.temperatures);
                            $scope.showSensorLogGraph();
                        }
                        else
                            $scope.showRoute('tcm_dashboard');
                        break;
                    case 'getInputLog':
                        $scope.getSensorLog = false;
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.sensorlog.logdataSet(data.contents.inputss);
                            $scope.showSensorLogGraph();
                        }
                        else
                            $scope.showRoute('tcm_dashboard');
                        break;
                    default:
                        break;
                }
            };
        }]);
