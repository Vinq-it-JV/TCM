<?php

namespace CollectionBundle\Controller;

use AppBundle\Response\JsonResult;
use CollectionBundle\Model\Attachment;
use CollectionBundle\Model\AttachmentQuery;
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionQuery;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use StoreBundle\Model\MaintenanceType;
use StoreBundle\Model\MaintenanceTypeQuery;
use StoreBundle\Model\StoreMaintenanceLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DataController extends Controller
{
    /**
     * Get store maintenance log
     * @param Request $request
     * @param         $storeid
     */
    public function getStoreMaintenanceAction(Request $request, $storeid)
    {
        $dataArr = [];
        $maintenanceLogs = [];
        $date = new \DateTime();

        $type = CollectionTypeQuery::create()->findOneById(CollectionType::TYPE_MAINTENANCE_ID);

        $collections = CollectionQuery::create()
            ->filterByCollectionType($type)
            ->filterByCollectionStore($storeid)
            ->filterByIsDeleted(false)
            ->orderBy('date', 'DESC')
            ->find();

        foreach ($collections as $collection)
            $maintenanceLogs[] = $collection->getCollectionDataArray()['collection'];

        $collection = new Collection();
        $collection->setId(0);
        $collection->setType(CollectionType::TYPE_MAINTENANCE_ID);
        $collection->setDate($date);
        $collection->setCollectionStore($storeid);

        $dataArr['collections'] = $maintenanceLogs;
        $dataArr['template'] = $collection->getFullCollectionTemplateArray()['collection'];

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get store inventory log
     * @param Request $request
     * @param         $storeid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \PropelException
     */
    public function getStoreInventoryAction(Request $request, $storeid)
    {
        $dataArr = [];
        $inventoryLogs = [];
        $date = new \DateTime();

        $type = CollectionTypeQuery::create()->findOneById(CollectionType::TYPE_INVENTORY_ID);

        $collections = CollectionQuery::create()
            ->filterByCollectionType($type)
            ->filterByCollectionStore($storeid)
            ->filterByIsDeleted(false)
            ->orderBy('name', 'ASC')
            ->find();

        foreach ($collections as $collection)
            $inventoryLogs[] = $collection->getCollectionDataArray()['collection'];

        $collection = new Collection();
        $collection->setId(0);
        $collection->setType(CollectionType::TYPE_INVENTORY_ID);
        $collection->setDate($date);
        $collection->setCollectionStore($storeid);

        $dataArr['collections'] = $inventoryLogs;
        $dataArr['template'] = $collection->getFullCollectionTemplateArray()['collection'];

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get store beertech info
     * @param Request $request
     * @param         $storeid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \PropelException
     */
    public function getStoreBeertechAction(Request $request, $storeid)
    {
        $dataArr = [];
        $inventoryLogs = [];
        $date = new \DateTime();

        $type = CollectionTypeQuery::create()->findOneById(CollectionType::TYPE_BEER_TECH_ID);

        $collections = CollectionQuery::create()
            ->filterByCollectionType($type)
            ->filterByCollectionStore($storeid)
            ->filterByIsDeleted(false)
            ->orderBy('date', 'DESC')
            ->find();

        foreach ($collections as $collection)
            $inventoryLogs[] = $collection->getCollectionDataArray()['collection'];

        $collection = new Collection();
        $collection->setId(0);
        $collection->setType(CollectionType::TYPE_BEER_TECH_ID);
        $collection->setDate($date);
        $collection->setCollectionStore($storeid);

        $dataArr['collections'] = $inventoryLogs;
        $dataArr['template'] = $collection->getFullCollectionTemplateArray()['collection'];

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Save collection
     * @param Request $request
     * @param         $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveCollectionAction(Request $request, $collectionid)
    {
        if ($request->isMethod('PUT')) {
            $postData = json_decode($request->getContent(), true);
            if (!empty($postData)) {
                $collection = $this->saveCollectionData((object)$postData);
                if (!is_bool($collection))
                    return JsonResult::create()
                        ->setContents($collection->getCollectionDataArray())
                        ->setErrorcode(JsonResult::SUCCESS)
                        ->make();
            }
        }

        return JsonResult::create()
            ->setMessage('Collection not saved!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Save collection data
     * @param $collectionData
     * @return bool
     */
    protected function saveCollectionData($collectionData)
    {
        $helper = $this->getClassHelper();
        $user = $this->getUser();
        $newCollection = false;

        if (isset($collectionData->Id)) {
            $collection = CollectionQuery::create()->findOneById($collectionData->Id);
            if (empty($collection)) {
                $collection = new Collection();
                $collection->setUid($helper->createUUID());
                $newCollection = true;
            }
        } else
            return false;
        if (isset($collectionData->Type)) {
            $type = CollectionTypeQuery::create()->findOneById($collectionData->Type);
            if (!empty($type))
                $collection->setCollectionType($type);
        }
        if (isset($collectionData->IsPublished))
            $collection->setIsPublished($collectionData->IsPublished);
        if (isset($collectionData->Date)) {
            $collectionData->Date = (object)$collectionData->Date;
            $collection->setDate($helper->removeTimezone($collectionData->Date->date));
        }
        if (isset($collectionData->Name)) {
            $collection->setName($collectionData->Name);
        }
        if (isset($collectionData->CollectionStore))
            $collection->setCollectionStore($collectionData->CollectionStore);
        if (empty($collection->getUid()))
            $collection->setUid($helper->createUUID());
        if (!$collection->GetId())
            $collection->setCreatedBy($user->getId());
        else
            $collection->setEditedBy($user->getId());
        $collection->setDescription($collectionData->Description);
        $collection->save();

        if (!empty($type)) {
            if ($type->getId() == CollectionType::TYPE_MAINTENANCE_ID) {
                $type = MaintenanceTypeQuery::create()->findOneById(MaintenanceType::TYPE_PERIODICALLY_ID);
                if ($newCollection) {
                    $log = new StoreMaintenanceLog();
                    if (!empty($type))
                        $log->setMaintenanceType($type);
                    $log->setCollection($collection);
                    if (isset($collectionData->CollectionStore))
                        $log->setMaintenanceStore($collectionData->CollectionStore);
                    if (isset($collectionData->Date)) {
                        $log->setMaintenanceStartedAt($helper->removeTimezone($collectionData->Date->date));
                        $log->setMaintenanceStoppedAt($log->getMaintenanceStartedAt());
                    }
                    $log->setMaintenanceBy($user->getId());
                    $log->save();
                }
            }
        }

        if (isset($collectionData->Attachments)) {
            if (!empty($collectionData->Attachments)) {
                $position = 0;
                foreach ($collectionData->Attachments as $attachment) {
                    $attachment = (object)$attachment;
                    $_attachment = AttachmentQuery::create()->findOneById($attachment->Id);
                    if (!empty($_attachment)) {
                        $_attachment->setPosition($position);
                        $_attachment->save();
                    }
                    $position++;
                }
            }
        }

        return $collection;
    }

    /**
     * Get class helper
     * @return object
     */
    protected function getClassHelper()
    {
        $helper = $this->container->get('class_helper');

        return $helper;
    }

    /**
     * Delete collection
     * @param Request $request
     * @param         $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCollectionAction(Request $request, $collectionid)
    {
        if ($request->isMethod('DELETE')) {
            $collection = CollectionQuery::create()->findOneById($collectionid);
            if (!empty($collection)) {
                $collection->setIsDeleted(true);
                $collection->save();

                return JsonResult::create()
                    ->setErrorcode(JsonResult::SUCCESS)
                    ->make();
            }
        }

        return JsonResult::create()
            ->setMessage('Collection not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Upload collection attachment
     * @param Request $request
     * @param         $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadCollectionAction(Request $request, $collectionid)
    {
        $helper = $this->getCollectionHelper();
        if ($request->isMethod('POST')) {
            $collection = CollectionQuery::create()->findOneById($collectionid);
            if (empty($collection)) {
                return JsonResult::create()
                    ->setMessage('Collection not found!')
                    ->setErrorcode(JsonResult::DANGER)
                    ->make();
            }
            $files = $request->files;
            foreach ($files as $uploadedFile) {
                $helper->saveCollectionAttachment($collection, $uploadedFile);
            }
        }

        return JsonResult::create()
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get class helper
     * @return object
     */
    protected function getCollectionHelper()
    {
        $helper = $this->container->get('collection_helper');

        return $helper;
    }

    /**
     * Get collection attachment (data)
     * @param Request $request
     * @param         $attachmentid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCollectionAttachmentAction(Request $request, $attachmentid, $rand)
    {
        $attachment = AttachmentQuery::create()->findOneById($attachmentid);
        if (!empty($attachment)) {
            $response = new BinaryFileResponse($attachment->getLinkUrl());
            if ($attachment->getType() == Attachment::TYPE_DOCUMENT) {
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $attachment->getOriginalName());
            }
            if ($attachment->getType() == Attachment::TYPE_IMAGE) {
                $fileInfo = pathinfo($attachment->getOriginalName());
                switch (strtolower($fileInfo[PATHINFO_EXTENSION])) {
                    case 'jpeg':
                    case 'jpg':
                        $contentType = 'image/jpeg';
                        break;
                    case 'png':
                        $contentType = 'image/png';
                        break;
                }
                $response->headers->set('Content-Type', $contentType);
            }

            return $response;
        }

        return JsonResult::create()
            ->setErrorcode(JsonResult::WARNING)
            ->make();
    }

    /**
     * Delete collection attachment
     * @param Request $request
     * @param         $attachmentid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCollectionAttachmentAction(Request $request, $collectionid, $attachmentid)
    {
        $helper = $this->getCollectionHelper();
        if ($request->isMethod('DELETE')) {
            $collection = CollectionQuery::create()->findOneById($collectionid);
            $attachment = AttachmentQuery::create()->findOneById($attachmentid);
            if (empty($attachment)) {
                return JsonResult::create()
                    ->setMessage('Attachment not found!')
                    ->setErrorcode(JsonResult::DANGER)
                    ->make();
            }
            $helper->deleteCollectionAttachment($collection, $attachment);
        }

        return JsonResult::create()
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Save attachment
     * @param Request $request
     * @param         $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAttachmentAction(Request $request, $attachmentid)
    {
        if ($request->isMethod('PUT')) {
            $postData = json_decode($request->getContent(), true);
            if (!empty($postData)) {
                $attachment = $this->saveAttachmentData((object)$postData);
                if (!is_bool($attachment))
                    return JsonResult::create()
                        ->setContents($attachment->getAttachmentDataArray())
                        ->setErrorcode(JsonResult::SUCCESS)
                        ->make();
            }
        }

        return JsonResult::create()
            ->setMessage('Attachment not saved!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Save attachment data
     * @param $collectionData
     * @return bool
     */
    protected function saveAttachmentData($attachmentData)
    {
        $helper = $this->getCollectionHelper();

        if (isset($attachmentData->Id)) {
            $attachment = AttachmentQuery::create()->findOneById($attachmentData->Id);
            if (empty($attachment))
                return false;
        }
        if (isset($attachmentData->Name)) {
            $attachment->setName($attachmentData->Name);
            $attachment->save();
        }
        if (isset($attachmentData->Rotate)) {
            if ($attachmentData->Rotate)
                $helper->rotateImageAttachment($attachment);
        }

        return $attachment;
    }
}
