/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Collection controller
 *  
 */
angular
    .module('tcmApp')
    .controller('collectionCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal', 'DS_Collections',
    function ($rootScope, $scope, $translate, $timeout, Modal, DS_Collections)
    {

        $scope.collections = DS_Collections;
        $scope.collectionsCollection = [];
        $scope.activePage = '';
        $scope.storeId = 0;
        $scope.collectioType = '';
        $scope.dzUrl = '/';
        $scope.lightboxImage = '';

        $scope.dzOptions = {
            url : $scope.dzUrl,
            paramName : 'maintenance',
            maxFilesize : '10',
            acceptedFiles : 'image/jpeg, images/jpg, image/png, application/pdf',
            addRemoveLinks : true,
            autoProcessQueue : false
        };

        $scope.dzCallbacks = {
            'success' : function(file, xhr) {
                $scope.showCollectionUrl();
            }
        };

        $scope.dzMethods = {};

        $scope.$on('languageLoaded', function () {
            $scope.dzOptions.dictDefaultMessage = $translate.instant('DROPZONE.DROP_FILES');
        });

        $scope.getCollectionData = function (storeid, collectiontype)
        {
            $scope.storeId = storeid;
            $scope.collectionType = collectiontype;
            $scope.activePage = collectiontype;

            switch ($scope.activePage)
            {
                case 'maintenance':
                    $scope.requestType = 'getMaintenanceLog';
                    var getdata = {
                        'url': Routing.generate('tcm_store_maintenance_get', {'storeid': $scope.storeId}),
                        'payload': ''
                    };
                    break;
                case 'administration_maintenance':
                    $scope.requestType = 'getMaintenanceLog';
                    var getdata = {
                        'url': Routing.generate('administration_maintenance_store_get', {'storeid': $scope.storeId}),
                        'payload': ''
                    };
                    break;
                default:
                    break;
            }

            $scope.BE.get(getdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.saveCollectionData = function ()
        {
            var collectionId = $scope.collections.collection().Id;

            $scope.requestType = 'saveCollectionData';
            var putdata = {
                'url': Routing.generate('administration_collection_save', {'collectionid': collectionId}),
                'payload': $scope.collections.collection()
            };

            $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.uploadCollectionAttachments = function ()
        {
            var collectionId = $scope.collections.collection().Id;
            $scope.dzUrl = Routing.generate('administration_collection_upload', {'collectionid': collectionId});

            $scope.BE.showLoader();
            var dz = $scope.dzMethods.getDropzone();
            dz.options.url = $scope.dzUrl;

            if (dz.files.length)
                $scope.dzMethods.processQueue();
            else
                $scope.showCollectionUrl();
        };

        $scope.addCollection = function (pagetype)
        {
            $scope.collections.clrRecord();
            $scope.activePage = pagetype;
            console.log($scope.collections.collection());
        };

        $scope.showCollection = function (collectionid, pagetype)
        {
            $scope.collections.getRecord(collectionid);
            $scope.activePage = pagetype;
        };

        $scope.editCollection = function (collectionid, pagetype)
        {
            $scope.collections.getRecord(collectionid);
            $scope.activePage = pagetype;
        };

        $scope.removeCollection = function (collectionid)
        {
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_ENTRY'),
                onExecute: function () {
                    $scope.requestType = 'deleteCollection';
                    var deletedata = {
                        'url': Routing.generate('administration_collection_delete', {'collectionid':collectionid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };
            Modal.open({}, modalOptions);
        };

        $scope.showCollections = function (pagetype)
        {
            $scope.activePage = pagetype;
        };

        $scope.attachmentUrl = function (attachmentid)
        {
            var route = Routing.generate('administration_collection_attachment_get', {'attachmentid':attachmentid});
            return route;
        };

        $scope.deleteAttachment = function (attachmentid)
        {
            var collectionid = $scope.collections.collection().Id;
            var modalOptions = {
                closeButtonText: $translate.instant('CANCEL'),
                actionButtonText: $translate.instant('DELETE'),
                headerText: $translate.instant('DELETE'),
                bodyText: $translate.instant('QUESTION.DELETE_ATTACHMENT'),
                onExecute: function () {
                    $scope.requestType = 'deleteAttachment';
                    var deletedata = {
                        'url': Routing.generate('administration_collection_attachment_delete', {'collectionid':collectionid, 'attachmentid': attachmentid}),
                        'payload': ''
                    };
                    $scope.BE.delete(deletedata, $scope.fetchDataOk, $scope.fetchDataFail);
                }
            };
            Modal.open({}, modalOptions);
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
                        headerText: attachment.OriginalName,
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

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getMaintenanceLog':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.collections.collectionsSet(data.contents.collections);
                        $scope.collections.templateSet(data.contents.template);
                        $scope.collectionsCollection = [].concat(data.contents.collections);
                    }
                    break;
                case 'saveCollectionData':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.collections.updRecord(data.contents.collection);
                        $scope.uploadCollectionAttachments();
                    }
                    break;
                case 'deleteCollection':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.showCollectionUrl();
                    }
                    break;
                case 'deleteAttachment':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.showCollectionUrl();
                    }
                    break;
                default:
                    break;
        	}
        };

        $scope.showCollectionUrl = function ()
        {
            switch ($scope.collectionType)
            {   case 'deleteCollection':
                case 'administration_maintenance':
                    $scope.showUrl(Routing.generate('administration_maintenance_store', {'storeid': $scope.storeId}));
                    break;
                default:
                    console.log('No collection url for type: ' + $scope.collectionType);
                    break;
            }
        };
        
    }]);
