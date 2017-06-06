/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Notifications
 * @description
 *
 * ## TCM V2.0 Notifications
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Notifications', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_input_notifications = [];
            var d_temperature_notifications = [];

            function inputNotificationOnIndex(notification_id) {
                for (index in d_input_notifications)
                    if (d_input_notifications[index].Id == notification_id)
                        return index;
                return -1;
            }

            function temperatureNotificationOnIndex(notification_id) {
                for (index in d_temperature_notifications)
                    if (d_temperature_notifications[index].Id == notification_id)
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
                notificationsDS: function () {
                    return this;
                },
                inputNotificationsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_input_notifications;
                    d_input_notifications = angular.copy(data);
                    return d_input_notifications;
                },
                inputNotifications: function () {
                    return d_input_notifications;
                },
                temperatureNotificationsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_temperature_notifications;
                    d_temperature_notifications = angular.copy(data);
                    return d_temperature_notifications;
                },
                temperatureNotifications: function () {
                    return d_temperature_notifications;
                },
                delInputNotification: function (notification_id) {
                    var index = inputNotificationOnIndex(notification_id);
                    if (index === -1)
                        return null;
                    d_input_notifications.splice(index, 1);
                    return true;
                },
                delTemperatureNotification: function (notification_id) {
                    var index = temperatureNotificationOnIndex(notification_id);
                    if (index === -1)
                        return null;
                    d_temperature_notifications.splice(index, 1);
                    return true;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);