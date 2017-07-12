/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Notification controller
 *  
 */
angular
    .module('tcmApp')
    .controller('notificationCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Notifications',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Notifications)
    {

        $scope.notifications = DS_Notifications;
        $scope.inputsCollection = [];
        $scope.temperaturesCollection = [];
        $scope.inputTableState = [];
        $scope.temperatureTableState = [];

        $scope.getOpenNotifications = function ()
        {
            $scope.requestType = 'getOpenNotifications';

            var getdata = {
                'url': Routing.generate('administration_open_notifications_get'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.getClosedInputNotifications = function (tableState)
        {
            $scope.inputTableState = tableState;

            var putdata = {
                'url': Routing.generate('administration_closed_input_notifications_get'),
                'payload': $scope.inputTableState
            };

            $scope.BE.put(putdata, $scope.fetchInputDataOk, $scope.fetchDataFail);
        };

        $scope.getClosedTemperatureNotifications = function (tableState)
        {
            $scope.temperatureTableState = tableState;

            var putdata = {
                'url': Routing.generate('administration_closed_temperature_notifications_get'),
                'payload': $scope.temperatureTableState
            };

            $scope.BE.put(putdata, $scope.fetchTemperatureDataOk, $scope.fetchDataFail);
        };

        $scope.handleInputNotification = function (notificationid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('NOTIFICATIONS.HANDLE'),
                headerText: $translate.instant('NOTIFICATIONS.HANDLE'),
                bodyText: $translate.instant('QUESTION.HANDLE_NOTIFICATION'),
                onExecute: function () {
                    // This must actually be moved if fetch data is SUCCESSS
                    $scope.notifications.delInputNotification(notificationid);
                    $scope.requestType = 'handleInputNotification';
                    var putdata = {
                        'url': Routing.generate('administration_handle_input_notification', {'notificationid':notificationid}),
                        'payload': ''
                    };
                    $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.handleTemperatureNotification = function (notificationid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('NOTIFICATIONS.HANDLE'),
                headerText: $translate.instant('NOTIFICATIONS.HANDLE'),
                bodyText: $translate.instant('QUESTION.HANDLE_NOTIFICATION'),
                onExecute: function () {
                    // This must actually be moved if fetch data is SUCCESSS
                    $scope.notifications.delTemperatureNotification(notificationid);
                    $scope.requestType = 'handleTemperatureNotification';
                    var putdata = {
                        'url': Routing.generate('administration_handle_temperature_notification', {'notificationid':notificationid}),
                        'payload': ''
                    };
                    $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.fetchInputDataOk = function (data)
        {
            if (!$scope.isValidObject(data))
                return;
            if (!data.errorcode) {
                $scope.inputTableState.pagination = data.contents.tableState.pagination;
                $scope.inputsCollection = [].concat(data.contents.notifications);
            }
        };

        $scope.fetchTemperatureDataOk = function (data)
        {
            if (!$scope.isValidObject(data))
                return;
            if (!data.errorcode) {
                $scope.temperatureTableState.pagination = data.contents.tableState.pagination;
                $scope.temperaturesCollection = [].concat(data.contents.notifications);
            }
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getOpenNotifications':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.notifications.inputNotificationsSet(data.contents.notifications.Inputs);
                        $scope.notifications.temperatureNotificationsSet(data.contents.notifications.Temperatures);
                        $scope.inputsCollection = [].concat(data.contents.notifications.Inputs);
                        $scope.temperaturesCollection = [].concat(data.contents.notifications.Temperatures);
                        console.log($scope.inputsCollection);
                        console.log($scope.notifications.inputNotifications());
                    }
                    break;
                case 'handleInputNotification':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.inputsCollection = [].concat($scope.notifications.inputNotifications());
                    }
                    break;
                case 'handleTemperatureNotification':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.temperaturesCollection = [].concat($scope.notifications.temperatureNotifications());
                    }
                    break;
                default:
                    break;
        	}
        };
        
    }]);
