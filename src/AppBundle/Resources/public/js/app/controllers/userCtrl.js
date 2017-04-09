/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 User controller
 *  
 */
angular
    .module('tcmApp')
    .controller('userCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Users',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Users)
    {

        $scope.users = DS_Users;
        $scope.usersCollection = [];

        $scope.getUsers = function ()
        {
            $scope.requestType = 'getUsers';

            var getdata = {
                'url': Routing.generate('administration_users_get'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.getUser = function (userid)
        {
            $scope.requestType = 'getUser';

            var getdata = {
                'url': Routing.generate('administration_user_get', {'userid': userid}),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.newUser = function ()
        {
            $scope.requestType = 'newUser';

            var getdata = {
                'url': Routing.generate('administration_user_new'),
                'payload': ''
            };

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.editUser = function (userid)
        {
            $scope.showUrl(Routing.generate('administration_user_edit', {'userid': userid}));
        };

        $scope.addUser = function ()
        {
            $scope.showUrl(Routing.generate('administration_user_add'));
        };

        $scope.deleteUser = function (userid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_USER'),
                onExecute: function () {
                    $scope.users.delRecord(userid);
                    $scope.requestType = 'deleteUser';
                    var deletedata = {
                        'url': Routing.generate('administration_user_delete', {'userid':userid}),
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
                    emailid = $scope.users.deleteEmail(emailid);
                    if ($scope.users.user().Id == 0 || emailid == 0)
                        return;
                    $scope.requestType = 'deleteEmail';
                    var deletedata = {
                        'url': Routing.generate('administration_user_delete_email', {'userid':$scope.users.user().Id, 'emailid':emailid}),
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
                    phoneid = $scope.users.deletePhone(phoneid);
                    if ($scope.users.user().Id == 0 || phoneid == 0)
                        return;
                    $scope.requestType = 'deletePhone';
                    var deletedata = {
                        'url': Routing.generate('administration_user_delete_phone', {'userid':$scope.users.user().Id, 'phoneid':phoneid}),
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
                    addressid = $scope.users.deleteAddress(addressid);
                    if ($scope.users.user().Id == 0 || addressid == 0)
                        return;
                    $scope.requestType = 'deleteAddress';
                    var deletedata = {
                        'url': Routing.generate('administration_user_delete_address', {'userid':$scope.users.user().Id, 'addressid':addressid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };

            Modal.open({}, modalOptions);
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getUsers':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.users.usersSet(data.contents.users);
                        $scope.usersCollection = [].concat(data.contents.users);
                    }
                    break;

                case 'newUser':
                case 'getUser':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.users.updRecord(data.contents.user);
                        $scope.users.templateSet(data.contents.template);
                        $scope.users.listsSet(data.contents.lists);
                        console.log($scope.users.emails().length);
                    }
                    break;
                case 'deleteUser':
                case 'deleteEmail':
                case 'deletePhone':
                case 'deleteAddress':
                    break;
                default:
                    break;
        	}
        };
        
    }]);
