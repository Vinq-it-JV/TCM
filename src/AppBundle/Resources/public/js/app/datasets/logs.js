/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Collections
 * @description
 *
 * ## TCM V2.0 Logs
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Logs', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_logs = [];
            var d_log = [];

            function recordOnIndex(record_id) {
                for (index in d_logs)
                    if (d_logs[index].Id == record_id)
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
                logsDS: function () {
                    return this;
                },
                logsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_logs;
                    d_logs = angular.copy(data);
                    return d_logs;
                },
                logs: function () {
                    return d_logs;
                },
                log: function () {
                    return d_log;
                },
                getRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_log = angular.copy(d_logs[index]);
                    return d_log;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_logs[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_logs.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_log = angular.copy(d_template);
                    d_log.Date.date = moment();
                    return d_log;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_logs.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_log = angular.copy(record_data);
                    return d_log;
                },
                updateDisplayMode: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_logs[index].DisplayMode++;
                    if (d_logs[index].DisplayMode > 2)
                        d_logs[index].DisplayMode = 0;
                    return true;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);