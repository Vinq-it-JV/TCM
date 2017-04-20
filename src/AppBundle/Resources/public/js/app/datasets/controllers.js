/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Controllers
 * @description
 *
 * ## TCM V2.0 Controllers
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Controllers', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_controllers = [];
            var d_controller = [];
            var d_template = [];
            var d_lists = [];

            function recordOnIndex(record_id) {
                for (index in d_controllers)
                    if (d_controllers[index].Id == record_id)
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
                controllersDS: function () {
                    return this;
                },
                controllersSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_controllers;
                    d_controllers = angular.copy(data);
                    return d_controllers;
                },
                controllers: function () {
                    return d_controllers;
                },
                controller: function () {
                    return d_controller;
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
                    d_controller = angular.copy(d_controllers[index]);
                    return d_controller;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_controllers[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_controllers.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_controller = angular.copy(d_template.controller);
                    return d_controller;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_controllers.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_controller = angular.copy(record_data);
                    return d_controller;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);