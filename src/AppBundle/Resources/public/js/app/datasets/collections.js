/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Collections
 * @description
 *
 * ## TCM V2.0 Collections
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Collections', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_collections = [];
            var d_collection = [];
            var d_template = [];
            var d_attachment = [];

            function recordOnIndex(record_id) {
                for (index in d_collections)
                    if (d_collections[index].Id == record_id)
                        return index;
                return -1;
            }

            function attachmentOnIndex(attachment_id) {
                for (index in d_collection.Attachments)
                    if (d_collection.Attachments[index].Id == attachment_id)
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
                collectionsDS: function () {
                    return this;
                },
                collectionsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_collections;
                    d_collections = angular.copy(data);
                    return d_collections;
                },
                collections: function () {
                    return d_collections;
                },
                collection: function () {
                    return d_collection;
                },
                templateSet: function (template) {
                    d_template = template;
                },
                template: function () {
                    return d_template;
                },
                attachment: function () {
                    return d_attachment;
                },
                getRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_collection = angular.copy(d_collections[index]);
                    d_collection.Date.date = moment(d_collection.Date.date, "YYYY-MM-DD HH:mm");
                    d_collection.UpdatedAt.date = moment(d_collection.UpdatedAt.date, "YYYY-MM-DD HH:mm");
                    return d_collection;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_collections[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_collections.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_collection = angular.copy(d_template);
                    d_collection.Date.date = moment();
                    return d_collection;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_collections.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_collection = angular.copy(record_data);
                    return d_collection;
                },
                getAttachment: function (attachment_id) {
                    var index = attachmentOnIndex(attachment_id);
                    if (index === -1)
                        return null;
                    d_attachment = angular.copy(d_collection.Attachments[index]);
                    return d_attachment;
                },
                updAttachment: function (attachment_id, data) {
                    var index = attachmentOnIndex(attachment_id);
                    if (index === -1)
                        return null;
                    d_collection.Attachments[index] = angular.copy(data);
                    return d_collection.Attachments[index];
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);