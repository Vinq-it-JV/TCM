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

        $scope.showStoreInventory = function (storeid)
        {
            $scope.showUrl(Routing.generate('administration_inventory_store', {'storeid': storeid}));
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
                    }
                    break;
                case 'deleteStore':
                    $scope.storesCollection = [].concat($scope.stores.stores());
                    break;
                case 'deleteEmail':
                case 'deletePhone':
                case 'deleteAddress':
                    break;
                default:
                    break;
        	}
        };
        
    }]);
