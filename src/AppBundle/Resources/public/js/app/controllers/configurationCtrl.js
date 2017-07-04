/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Configuration controller
 *
 */
angular
    .module('tcmApp')
    .controller('configurationCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Stores', 'DS_Controllers', 'DS_Sensors', 'DS_DeviceGroups',
        function ($rootScope, $scope, $translate, $timeout, Modal, DS_Stores, DS_Controllers, DS_Sensors, DS_DeviceGroups) {

            $scope.stores = DS_Stores;
            $scope.controllers = DS_Controllers;
            $scope.sensors = DS_Sensors;
            $scope.devicegroups = DS_DeviceGroups;

            $scope.groupsCollection = [];
            $scope.storesCollection = [];
            $scope.currentDevice = [];
            $scope.currentGroup = [];

            $scope.activePage = 'treeView';

            $scope.treeOptions = {
                accept: function (sourceNodeScope, destNodesScope, destIndex) {
                    return true;
                },
                beforeDrop: function (event) {
                    //console.log(event.source.nodeScope.$modelValue);
                    if (!$scope.isValidObject(event.dest.nodesScope.$parent.$modelValue))
                        return true;
                    if ($scope.isValidObject(event.source.nodeScope.$modelValue))
                        if (event.source.nodeScope.$modelValue.TypeId != 1)
                            return true;
                    return false;
                }
            };

            $scope.getStores = function () {
                $scope.requestType = 'getStores';

                var getdata = {
                    'url': Routing.generate('configuration_stores_get'),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.getStore = function (storeid) {
                $scope.requestType = 'getStore';

                var getdata = {
                    'url': Routing.generate('configuration_store_get', {'storeid': storeid}),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.editStore = function (storeid) {
                $scope.showUrl(Routing.generate('configuration_store_edit', {'storeid': storeid}));
            };

            $scope.saveStore = function () {
                $scope.requestType = 'saveStore';

                var putdata = {
                    'url': Routing.generate('configuration_store_save', {'storeid': $scope.stores.store().Id}),
                    'payload': $scope.groupsCollection
                };

                $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.addDeviceGroup = function () {
                var devicegroup = angular.copy($scope.devicegroups.template());
                devicegroup = angular.extend({}, devicegroup);
                devicegroup.MainStore = $scope.stores.store().Id;
                devicegroup.Name = $translate.instant('SENSOR_GROUP.NEW_GROUP');
                $scope.groupsCollection.push(devicegroup);
            };

            $scope.editDeviceGroup = function (group) {
                $scope.currentGroup = group;
                $scope.activePage = 'editGroup'
            };

            $scope.removeGroup = function (group) {
                var modalOptions = {
                    closeButtonText: $translate.instant('CANCEL'),
                    actionButtonText: $translate.instant('DELETE'),
                    headerText: $translate.instant('DELETE'),
                    bodyText: $translate.instant('QUESTION.DELETE_GROUP'),
                    onExecute: function () {
                        for (index in group.devices) {
                            group.devices[index].Group = 0;
                            $scope.groupsCollection.push(group.devices[index]);
                        }
                        var index = $scope.groupsCollection.indexOf(group);
                        $scope.groupsCollection.splice(index, 1);
                    }
                };
                Modal.open({}, modalOptions);
            };

            $scope.editDevice = function (device) {
                $scope.currentDevice = device;
                $scope.activePage = 'editDevice';
            };

            $scope.unlinkDevice = function (device) {
                var modalOptions = {
                    closeButtonText: $translate.instant('CANCEL'),
                    actionButtonText: $translate.instant('UNLINK'),
                    headerText: $translate.instant('UNLINK'),
                    bodyText: $translate.instant('QUESTION.UNLINK_SENSOR'),
                    onExecute: function () {
                        device.Group = null;
                        device.MainStore = null;
                        if (device.TypeId == 2)
                        {   for (group in $scope.groupsCollection)
                                for (sensor in $scope.groupsCollection[group].devices) {
                                    if ($scope.groupsCollection[group].devices[sensor].Uid == device.Uid) {
                                        $scope.groupsCollection[group].devices[sensor].Group = null;
                                        $scope.groupsCollection[group].devices[sensor].MainStore = null;
                                    }
                                }
                        }
                    }
                };
                Modal.open({}, modalOptions);
            };

            $scope.showTreeView = function () {
                $scope.activePage = 'treeView';
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
                            $scope.stores.updRecord(data.contents.store);
                            $scope.stores.templateSet(data.contents.template);
                            $scope.stores.listsSet(data.contents.lists);
                            $scope.devicegroups.groupsSet(data.contents.devicegroups);
                            $scope.devicegroups.templateSet(data.contents.template.devicegroup);
                            if ($scope.isValidObject(data.contents.devicegroups))
                                $scope.groupsCollection = [].concat(data.contents.devicegroups);
                        }
                        break;
                    case 'saveStore':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode)
                            $scope.showUrl(Routing.generate('configuration_stores'));
                        break;
                    default:
                        break;
                }
            };

        }]);
