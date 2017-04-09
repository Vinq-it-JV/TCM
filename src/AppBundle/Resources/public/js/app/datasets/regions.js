/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Regions
 * @description
 *
 * ## TCM V2.0 Regions
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Regions', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_regions = [];
            var d_region = [];
            var d_template = [];
            var d_lists = [];

            function recordOnIndex(record_id) {
                for (index in d_regions)
                    if (d_regions[index].Id == record_id)
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
                regionsDS: function () {
                    return this;
                },
                regionsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_regions;
                    d_regions = angular.copy(data);
                    return d_regions;
                },
                regions: function () {
                    return d_regions;
                },
                region: function () {
                    return d_region;
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
                    d_region = angular.copy(d_regions[index]);
                    return d_region;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_regions[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_regions.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_region = angular.copy(d_template.region);
                    return d_region;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_regions.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_region = angular.copy(record_data);
                    return d_region;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);