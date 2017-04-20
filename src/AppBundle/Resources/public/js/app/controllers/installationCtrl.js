/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Installation controller
 *
 */
angular
    .module('tcmApp')
    .controller('installationCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Sensors',
        function ($rootScope, $scope, $translate, $timeout, Modal, DS_Sensors) {

            $scope.sensors = DS_Sensors;
            $scope.sensorsCollection = [];

            $scope.getSensors = function () {
                $scope.requestType = 'getSensors';

                var getdata = {
                    'url': Routing.generate('installation_sensors_get'),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.getSensor = function (sensorid, typeid) {
                $scope.requestType = 'getSensor';

                var getdata = {
                    'url': Routing.generate('installation_sensor_get', {'sensorid': sensorid, 'typeid': typeid}),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.editSensor = function (sensorid, typeid) {
                $scope.showUrl(Routing.generate('installation_sensor_edit', {'sensorid': sensorid, 'typeid': typeid}));
            };

            $scope.saveSensor = function () {
                $scope.requestType = 'saveSensor';

                var putdata = {
                    'url': Routing.generate('installation_sensor_save', {'sensorid': $scope.sensors.sensor().Id}),
                    'payload': $scope.sensors.sensor()
                };

                $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.fetchDataOk = function (data) {
                switch ($scope.requestType) {
                    case 'getSensors':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.sensors.sensorsSet(data.contents.sensors);
                            $scope.sensorsCollection = [].concat(data.contents.sensors);
                        }
                        break;
                    case 'getSensor':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.sensors.updRecord(data.contents.sensor);
                            $scope.sensors.templateSet(data.contents.template);
                            $scope.sensors.listsSet(data.contents.lists);
                        }
                        break;
                    case 'saveSensor':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode)
                            $scope.showUrl(Routing.generate('installation_sensors'));
                        break;
                    default:
                        break;
                }
            };

        }]);
