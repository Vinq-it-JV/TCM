/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_DeviceGroups
 * @description
 *
 * ## TCM V2.0 DeviceGroups
 *
 */
angular
    .module('tcmApp')
    .factory('DS_DeviceGroups', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_groups = [];
            var d_group = [];
            var d_template = [];
            var d_lists = [];

            function recordOnIndex(record_id) {
                for (index in d_groups)
                    if (d_groups[index].Id == record_id)
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
                deviceGroupsDS: function () {
                    return this;
                },
                groupsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_groups;
                    d_groups = angular.copy(data);
                    return d_groups;
                },
                groups: function () {
                    return d_groups;
                },
                group: function () {
                    return d_group;
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
                    d_group = angular.copy(d_groups[index]);
                    return d_group;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_groups[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_groups.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_group = angular.copy(d_template.devicegroup);
                    return d_group;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_groups.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_group = angular.copy(record_data);
                    return d_group;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);