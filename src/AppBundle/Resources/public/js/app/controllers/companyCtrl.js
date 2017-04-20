/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Company controller
 *  
 */
angular
    .module('tcmApp')
    .controller('companyCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Companies',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Companies)
    {

        $scope.companies = DS_Companies;
        $scope.companiesCollection = [];

        $scope.getCompanies = function ()
        {
            $scope.requestType = 'getCompanies';

            var getdata = {
                'url': Routing.generate('administration_companies_get'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.getCompany = function (companyid)
        {
            $scope.requestType = 'getCompany';

            var getdata = {
                'url': Routing.generate('administration_company_get', {'companyid': companyid}),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.newCompany = function ()
        {
            $scope.requestType = 'newCompany';

            var getdata = {
                'url': Routing.generate('administration_company_new'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.editCompany = function (companyid)
        {
            $scope.showUrl(Routing.generate('administration_company_edit', {'companyid': companyid}));
        };

        $scope.addCompany = function ()
        {
            $scope.showUrl(Routing.generate('administration_company_add'));
        };

        $scope.deleteCompany = function (companyid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_COMPANY'),
                onExecute: function () {
                    $scope.companies.delRecord(companyid);
                    $scope.requestType = 'deleteCompany';
                    var deletedata = {
                        'url': Routing.generate('administration_company_delete', {'companyid':companyid}),
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
                    emailid = $scope.companies.deleteEmail(emailid);
                    if ($scope.companies.company().Id == 0 || emailid == 0)
                        return;
                    $scope.requestType = 'deleteEmail';
                    var deletedata = {
                        'url': Routing.generate('administration_company_delete_email', {'companyid':$scope.companies.company().Id, 'emailid':emailid}),
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
                    phoneid = $scope.companies.deletePhone(phoneid);
                    if ($scope.companies.company().Id == 0 || phoneid == 0)
                        return;
                    $scope.requestType = 'deletePhone';
                    var deletedata = {
                        'url': Routing.generate('administration_company_delete_phone', {'companyid':$scope.companies.company().Id, 'phoneid':phoneid}),
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
                    addressid = $scope.companies.deleteAddress(addressid);
                    if ($scope.companies.company().Id == 0 || addressid == 0)
                        return;
                    $scope.requestType = 'deleteAddress';
                    var deletedata = {
                        'url': Routing.generate('administration_company_delete_address', {'companyid':$scope.companies.company().Id, 'addressid':addressid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.isAutomaticPayment = function ()
        {
            var paymentMethod = $scope.companies.company().PaymentMethod;
            if ($scope.isValidObject(paymentMethod))
                if (paymentMethod.Id == 4)
                    return true;
            return false;
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getCompanies':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.companies.companiesSet(data.contents.companies);
                        $scope.companiesCollection = [].concat(data.contents.companies);
                    }
                    break;

                case 'newCompany':
                case 'getCompany':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.companies.updRecord(data.contents.company);
                        $scope.companies.templateSet(data.contents.template);
                        $scope.companies.listsSet(data.contents.lists);
                    }
                    break;
                case 'deleteCompany':
                    $scope.companiesCollection = [].concat($scope.companies.companies());
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
