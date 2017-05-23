/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Stores
 * @description
 *
 * ## TCM V2.0 Sensors
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Sensors', ['$rootScope', '$translate', 'DS_Charts',
        function ($rootScope, $translate, DS_Charts) {

            var charts = DS_Charts;

            var d_sensors = [];
            var d_sensor = [];
            var d_template = [];
            var d_lists = [];

            function recordOnIndex(record_id) {
                for (index in d_sensors)
                    if (d_sensors[index].Id == record_id)
                        return index;
                return -1;
            }

            function isValidObject(object) {
                if (typeof object !== 'object')
                    return false;
                if (object.length === 0)
                    return false;
                if (Object.keys(object).length === 0)
                    return false;
                return true;
            }

            return {
                sensorsDS: function () {
                    return this;
                },
                sensorsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_sensors;
                    d_sensors = angular.copy(data);
                    return d_sensors;
                },
                sensors: function () {
                    return d_sensors;
                },
                sensorGroup: function (id) {
                    var index = recordOnIndex(id);
                    if (index === -1)
                        return null;
                    return d_sensors[index];
                },
                sensor: function () {
                    return d_sensor;
                },
                templateSet: function (template) {
                    d_template = template;
                },
                template: function () {
                    return d_template;
                },
                listsSet: function (lists) {
                    d_lists = lists;
                },
                lists: function () {
                    return d_lists;
                },
                getRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_sensor = angular.copy(d_sensors[index]);
                    return d_sensor;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_sensors[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_sensors.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_sensor = angular.copy(d_template.sensor);
                    return d_sensor;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_sensors.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_sensor = angular.copy(record_data);
                    return d_sensor;
                },
                setSensorCharts: function () {
                    for (group in d_sensors) {
                        var _sensor = d_sensors[group];
                        if (_sensor.TypeId == 1) {
                            for (sensor in d_sensors[group].devices) {
                                var _sensor = d_sensors[group].devices[sensor];
                                charts.setSensorObject(_sensor);
                            }
                        }
                        else {
                            charts.setSensorObject(_sensor);
                        }
                    }
                },
                updateSensors: function (sensordata) {
                    for (group in sensordata) {
                        var _sensor = sensordata[group];
                        if (_sensor.TypeId == 1) {
                            for (sensor in sensordata[group].devices) {
                                var _sensor = sensordata[group].devices[sensor];
                                var __sensor = this.findSensor(_sensor.Id, _sensor.Uid, _sensor.TypeId);
                                if (this.isValidObject(__sensor)) {
                                    this.copySensorValues(_sensor, __sensor);
                                    charts.updateSensorObject(__sensor);
                                }
                            }
                        }
                        else {
                            var __sensor = this.findSensor(_sensor.Id, _sensor.Uid, _sensor.TypeId);
                            if (this.isValidObject(__sensor)) {
                                this.copySensorValues(_sensor, __sensor);
                                charts.updateSensorObject(__sensor);
                            }
                        }
                    }
                },
                copySensorValues: function (src, dst) {
                    switch (src.TypeId) {
                        case 2:
                            dst.Name = angular.copy(src.Name);
                            dst.InternalTemperature = angular.copy(src.InternalTemperature);
                            dst.DataCollectedAt = angular.copy(src.DataCollectedAt);
                            break;
                        case 3:
                            dst.Name = angular.copy(src.Name);
                            dst.Temperature = angular.copy(src.Temperature);
                            dst.LowLimt = angular.copy(src.LowLimit);
                            dst.HighLimit = angular.copy(src.HighLimit);
                            dst.DataCollectedAt = angular.copy(src.DataCollectedAt);
                            break;
                        case 4:
                            dst.SwitchState = angular.copy(src.SwitchState);
                            dst.SwitchWhen = angular.copy(src.SwitchWhen);
                            dst.DataCollectedAt = angular.copy(src.DataCollectedAt);
                            break;
                        default:
                            break;
                    }
                },
                findSensor: function (id, uid, typeid) {
                    for (group in d_sensors) {
                        var _sensor = d_sensors[group];
                        if (_sensor.TypeId == 1) {
                            for (sensor in d_sensors[group].devices) {
                                var _sensor = d_sensors[group].devices[sensor];
                                if ((_sensor.Id == id) && (_sensor.Uid == uid) && (_sensor.TypeId == typeid)) {
                                    return _sensor;
                                }
                            }
                        }
                        else {
                            if ((_sensor.Id == id) && (_sensor.Uid == uid) && (_sensor.TypeId == typeid))
                                return _sensor;
                        }
                    }
                    return null;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            }
                ;
        }]);