/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 log controller
 *
 */
angular
    .module('tcmApp')
    .controller('logCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Logs', 'DS_Collections',
        function ($rootScope, $scope, $translate, $timeout, Modal, DS_Logs, DS_Collections) {

            $scope.logs = DS_Logs;
            $scope.logCollection = [];
            $scope.collections = DS_Collections;
            $scope.activePage = 'logList';

            $scope.getPacketlog = function () {
                $scope.requestType = 'getPacketlog';

                var getdata = {
                    'url': Routing.generate('configuration_packetlog_get'),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.getMaintenancelog = function (storeid) {
                $scope.requestType = 'getMaintenancelogList';

                var getdata = {
                    'url': Routing.generate('administration_maintenance_log_store_get', {'storeid': storeid}),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.changeDisplayMode = function (logid) {
                $scope.logs.updateDisplayMode(logid);
            };

            $scope.showLoglist = function ()
            {
                $scope.activePage = 'logList';
            };

            $scope.showLog = function (logid)
            {
                console.log('we need to show log: ' + logid);

                $scope.requestType = 'getMaintenancelog';

                var getdata = {
                    'url': Routing.generate('administration_maintenance_log_get', {'logid': logid}),
                    'payload': ''
                };

                $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.attachmentUrl = function (attachmentid)
            {
                var route = Routing.generate('administration_collection_attachment_get', {'attachmentid':attachmentid, 'rand':$scope.attachRand});
                return route;
            };

            $scope.showAttachment = function (attachment)
            {
                switch (attachment.Type) {
                    case 1:
                        $scope.lightboxImage = $scope.attachmentUrl(attachment.Id);
                        var modalDefaults = {
                            templateUrl: templatePrefix + "lightbox.tpl.html"
                        };
                        var modalOptions = {
                            closeButtonText: '',
                            actionButtonText: '',
                            headerText: attachment.Name,
                            wide: true,
                            onCancel: function () {
                            },
                            onExecute: function () {
                            },
                            parentScope: $scope
                        };
                        Modal.open(modalDefaults, modalOptions);
                        break;
                    default:
                        break;
                }
            };

            $scope.fetchDataOk = function (data) {
                switch ($scope.requestType) {
                    case 'getPacketlog':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.logs.logsSet(data.contents.packetLog);
                            $scope.logCollection = [].concat($scope.logs.logs());
                        }
                        break;
                    case 'getMaintenancelogList':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.logs.logsSet(data.contents.maintenanceLogList);
                            $scope.logCollection = [].concat($scope.logs.logs());
                        }
                        break;
                    case 'getMaintenancelog':
                        if (!$scope.isValidObject(data))
                            break;
                        if (!data.errorcode) {
                            $scope.collections.updRecord(data.contents.maintenanceLog);
                            $scope.activePage = 'logInfo';
                        }
                        break;
                    default:
                        break;
                }
            };

        }]);
