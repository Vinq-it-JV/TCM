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
        $scope.previousPage = '';
        $scope.storeId = 0;
        $scope.attachmentId = 0;
        $scope.collectioType = '';
        $scope.dzShow = false;
        $scope.lightboxImage = '';

        $scope.attachRand = new Date().getTime();

        $scope.dzOptions = {
            url : '/',
            paramName: 'maintenance',
            maxFilesize: '10',
            maxFiles: 3,
            resizeWidth: 1024,
            parallelUploads: 3,
            acceptedFiles: 'image/jpeg, images/jpg, image/png, application/pdf',
            addRemoveLinks: true,
            autoProcessQueue: false
        };

        $scope.dzCallbacks = {
            'success' : function(file, xhr) {
                $scope.showCollectionUrl();
            },
            'processing' : function (file) {
                var dz = $scope.dzMethods.getDropzone();
                dz.options.url = Routing.generate('administration_collection_upload', {'collectionid': $scope.collections.collection().Id});
            }
        };

        $scope.dzMethods = {};

        $scope.dragControlListeners = {
            orderChanged: function(event) { console.log(event); },
            allowDuplicates: false,
            containerPositioning: 'relative',
            additionalPlaceholderClass: 'sortable-bounds'
        };

        $scope.sortable_mode = "on";

        $scope.sortable_option = {
            handle:'div.drag-cursor',
            construct: function(model) {
            },
            stop: function(list, dropped_index) {
            }
        };

        $scope.$on('languageLoaded', function () {
            $scope.initDropzone();
            $timeout( function () {
                $scope.dzShow = true;
            }, 1000);
        });

        $scope.initDropzone = function()
        {
            $scope.dzOptions.dictDefaultMessage = $translate.instant('DROPZONE.DROP_FILES');
            $scope.dzOptions.dictRemoveFile = $translate.instant('DROPZONE.REMOVE_FILE');
        };

        $scope.getCollectionData = function (storeid, collectiontype)
        {
            var requestUrl = '';

            $scope.storeId = storeid;
            $scope.collectionType = collectiontype;
            $scope.activePage = collectiontype;
            $scope.requestType = 'getCollectionData';

            switch ($scope.activePage)
            {
                case 'maintenance':
                    requestUrl = Routing.generate('tcm_store_maintenance_get', {'storeid': $scope.storeId});
                    break;
                case 'administration_maintenance':
                    requestUrl = Routing.generate('administration_maintenance_store_get', {'storeid': $scope.storeId});
                    break;
                case 'inventory':
                    requestUrl = Routing.generate('tcm_store_inventory_get', {'storeid': $scope.storeId});
                    break;
                case 'administration_inventory':
                    requestUrl = Routing.generate('administration_inventory_store_get', {'storeid': $scope.storeId});
                    break;
                case 'beertech':
                    requestUrl = Routing.generate('tcm_store_beertech_get', {'storeid': $scope.storeId});
                    break;
                case 'administration_beertech':
                    requestUrl = Routing.generate('administration_beertech_store_get', {'storeid': $scope.storeId});
                    break;
                default:
                    console.log('Undefined getCollectionData for page: ' + $scope.activePage);
                    break;
            }

            var getdata = {
                'url': requestUrl,
                'payload': ''
            };
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
            $scope.BE.showLoader();
            var dz = $scope.dzMethods.getDropzone();

            if (dz.files.length)
                $scope.dzMethods.processQueue();
            else
                $scope.showCollectionUrl();
        };

        $scope.addCollection = function (pagetype)
        {
            $scope.collections.clrRecord();
            $scope.activePage = pagetype;
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
            $scope.initDropzone();
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

        $scope.showPreviousPage = function ()
        {
            $scope.activePage = $scope.previousPage;
        };

        $scope.attachmentUrl = function (attachmentid)
        {
            var route = Routing.generate('administration_collection_attachment_get', {'attachmentid':attachmentid, 'rand':$scope.attachRand});
            return route;
        };

        $scope.editAttachment = function (attachmentid)
        {
            var result = $scope.collections.getAttachment(attachmentid);
            if (result == null)
                return;
            $scope.previousPage = $scope.activePage;
            $scope.activePage = 'attachmentEdit';
        };

        $scope.saveAttachmentData = function ()
        {
            $scope.attachemntId = $scope.collections.attachment().Id;
            $scope.requestType = 'saveAttachmentData';
            var putdata = {
                'url': Routing.generate('administration_attachment_save', {'attachmentid': $scope.attachemntId}),
                'payload': $scope.collections.attachment()
            };

            $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
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

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
                case 'getCollectionData':
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
                case 'saveAttachmentData':
                    if (!$scope.isValidObject(data))
                        break;
                    if (!data.errorcode) {
                        $scope.collections.updAttachment($scope.attachemntId, data.contents.attachment);
                        $scope.attachRand = new Date().getTime();
                        $scope.showPreviousPage();
                    }
                    break;
                default:
                    break;
        	}
        };

        $scope.showCollectionUrl = function ()
        {
            switch ($scope.collectionType)
            {   case 'administration_maintenance':
                    $scope.showUrl(Routing.generate('administration_maintenance_store', {'storeid': $scope.storeId}));
                    break;
                case 'administration_inventory':
                    $scope.showUrl(Routing.generate('administration_inventory_store', {'storeid': $scope.storeId}));
                    break;
                case 'administration_beertech':
                    $scope.showUrl(Routing.generate('administration_beertech_store', {'storeid': $scope.storeId}));
                    break;
                default:
                    console.log('No collection url for type: ' + $scope.collectionType);
                    break;
            }
        };
        
    }]);
