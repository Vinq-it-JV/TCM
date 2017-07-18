/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Store controller
 *  
 */
angular
    .module('tcmApp')
    .controller('storeCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Stores',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Stores)
    {
        $scope.stores = DS_Stores;
        $scope.storesCollection = [];

        $scope.dzUrl = '/';
        $scope.imageRand = new Date().getTime();

        $scope.dzOptions = {
            url : $scope.dzUrl,
            paramName : 'store',
            maxFilesize : '10',
            maxFiles: '1',
            acceptedFiles : 'image/jpeg, images/jpg, image/png',
            addRemoveLinks : true,
            autoProcessQueue : true
        };

        $scope.dzCallbacks = {
            'success' : function(file, xhr) {
                $scope.imageRand = new Date().getTime();
                $scope.reloadPage();
            }
        };

        $scope.dzMethods = {};

        $scope.$on('languageLoaded', function () {
            $scope.initDropzone($scope.stores.store().Id);
        });

        $scope.initDropzone = function(storeid)
        {
            $scope.dzOptions.dictDefaultMessage = $translate.instant('DROPZONE.DROP_FILES');
            $scope.dzOptions.dictRemoveFile = $translate.instant('DROPZONE.REMOVE_FILE');

            if (!angular.element('#storeDropzone').length)
                return;

            $scope.dzUrl = Routing.generate('administration_store_upload', {'storeid': storeid});
            var dz = $scope.dzMethods.getDropzone();
            dz.options.url = $scope.dzUrl;
        };

        $scope.getStores = function ()
        {
            $scope.requestType = 'getStores';

            var getdata = {
                'url': Routing.generate('administration_stores_get'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.getStore = function (storeid)
        {
            $scope.requestType = 'getStore';

            var getdata = {
                'url': Routing.generate('administration_store_get', {'storeid': storeid}),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.companyChanged = function (company)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('COPY'),
                headerText: $translate.instant('COPY'),
                bodyText: $translate.instant('QUESTION.COPY_COMPANY_DATA'),
                onExecute: function () {
                    $scope.stores.copyCompanyData(company);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.newStore = function ()
        {
            $scope.requestType = 'newStore';

            var getdata = {
                'url': Routing.generate('administration_store_new'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.editStore = function (storeid)
        {
            $scope.showUrl(Routing.generate('administration_store_edit', {'storeid': storeid}));
        };

        $scope.addStore = function ()
        {
            $scope.showUrl(Routing.generate('administration_store_add'));
        };

        $scope.showStoreMaintenance = function (storeid)
        {
            $scope.showUrl(Routing.generate('administration_maintenance_store', {'storeid': storeid}));
        };

        $scope.showStoreMaintenanceLog = function (storeid)
        {
            $scope.showUrl(Routing.generate('administration_maintenance_log_store', {'storeid': storeid}));
        };

        $scope.showStoreInventory = function (storeid)
        {
            $scope.showUrl(Routing.generate('administration_inventory_store', {'storeid': storeid}));
        };

        $scope.startGeneralMaintenance = function (storeid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('START'),
                headerText: $translate.instant('START'),
                bodyText: $translate.instant('QUESTION.START_MAINTENANCE'),
                onExecute: function () {
                    $scope.stores.getRecord(storeid);
                    $scope.requestType = 'startGeneralMaintenance';
                    var putdata = {
                        'url': Routing.generate('administration_maintenance_general_start', {'storeid': storeid}),
                        'payload': ''
                    };
                    $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };
            Modal.open({}, modalOptions);
        };

        $scope.stopGeneralMaintenance = function (storeid)
        {
            $scope.stores.getRecord(storeid);
            $scope.requestType = 'stopGeneralMaintenance';
            var putdata = {
                'url': Routing.generate('administration_maintenance_general_stop', {'storeid': storeid}),
                'payload': ''
            };
            $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.deleteStore = function (storeid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_STORE'),
                onExecute: function () {
                    $scope.stores.delRecord(storeid);
                    $scope.requestType = 'deleteStore';
                    var deletedata = {
                        'url': Routing.generate('administration_store_delete', {'storeid': storeid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.deleteEmail = function (emailid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_EMAIL'),
                onExecute: function () {
                    emailid = $scope.stores.deleteEmail(emailid);
                    if ($scope.stores.store().Id == 0 || emailid == 0)
                        return;
                    $scope.requestType = 'deleteEmail';
                    var deletedata = {
                        'url': Routing.generate('administration_store_delete_email', {'storeid':$scope.stores.store().Id, 'emailid': emailid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };
            Modal.open({}, modalOptions);
        };

        $scope.deletePhone = function (phoneid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_PHONE'),
                onExecute: function () {
                    phoneid = $scope.stores.deletePhone(phoneid);
                    if ($scope.stores.store().Id == 0 || phoneid == 0)
                        return;
                    $scope.requestType = 'deletePhone';
                    var deletedata = {
                        'url': Routing.generate('administration_store_delete_phone', {'storeid': $scope.stores.store().Id, 'phoneid': phoneid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.deleteAddress = function (addressid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_ADDRESS'),
                onExecute: function () {
                    addressid = $scope.stores.deleteAddress(addressid);
                    if ($scope.stores.store().Id == 0 || addressid == 0)
                        return;
                    $scope.requestType = 'deleteAddress';
                    var deletedata = {
                        'url': Routing.generate('administration_store_delete_address', {'storeid': $scope.stores.store().Id, 'addressid': addressid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.isAutomaticPayment = function ()
        {
            var paymentMethod = $scope.stores.store().PaymentMethod;
            if ($scope.isValidObject(paymentMethod))
                if (paymentMethod.Id == 4)
                    return true;
            return false;
        };

        $scope.uploadStoreImage = function ()
        {
            var storeId = $scope.stores.store().Id;
            $scope.dzUrl = Routing.generate('administration_store_upload', {'storeid': storeId});

            $scope.BE.showLoader();
            var dz = $scope.dzMethods.getDropzone();
            dz.options.url = $scope.dzUrl;

            if (dz.files.length)
                $scope.dzMethods.processQueue();
        };

        $scope.imageUrl = function (imageid)
        {
            var route = Routing.generate('administration_store_image_get', {'imageid':imageid, 'rand':$scope.imageRand});
            return route;
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getStores':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.stores.storesSet(data.contents.stores);
                        $scope.storesCollection = [].concat(data.contents.stores);
                    }
                    break;

                case 'newStore':
                case 'getStore':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.stores.updRecord(data.contents.store);
                        $scope.stores.templateSet(data.contents.template);
                        $scope.stores.listsSet(data.contents.lists);
                        $scope.initDropzone($scope.stores.store().Id);
                    }
                    break;
                case 'deleteStore':
                    $scope.storesCollection = [].concat($scope.stores.stores());
                    break;
                case 'deleteEmail':
                case 'deletePhone':
                case 'deleteAddress':
                    break;
                case 'startGeneralMaintenance':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.stores.store().IsMaintenance = true;
                        $scope.stores.setRecord($scope.stores.store());
                        $scope.storesCollection = [].concat($scope.stores.stores());
                    }
                    break;
                case 'stopGeneralMaintenance':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.stores.store().IsMaintenance = false;
                        $scope.stores.setRecord($scope.stores.store());
                        $scope.storesCollection = [].concat($scope.stores.stores());
                    }
                    break;
                default:
                    break;
        	}
        };
        
    }]);
