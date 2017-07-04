<?php

namespace CollectionBundle\Controller;

use CollectionBundle\Model\Attachment;
use CollectionBundle\Model\AttachmentQuery;
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionQuery;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use AppBundle\Response\JsonResult;

class DataController extends Controller
{
    /**
     * Get store maintenance log
     * @param Request $request
     * @param $storeid
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
            ->orderByDate('DESC')
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
     * @param $storeid
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
            ->orderByDate('DESC')
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
     * Save collection
     * @param Request $request
     * @param $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveCollectionAction(Request $request, $collectionid)
    {
        if ($request->isMethod('PUT')) {
            $postData = json_decode($request->getContent(), true);
            if (!empty($postData))
            {   $collection = $this->saveCollectionData((object)$postData);
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
     * Delete collection
     * @param Request $request
     * @param $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCollectionAction(Request $request, $collectionid)
    {
        if ($request->isMethod('DELETE')) {
            $collection = CollectionQuery::create()->findOneById($collectionid);
            if (!empty($collection))
            {   $collection->setIsDeleted(true);
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
     * @param $collectionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadCollectionAction(Request $request, $collectionid)
    {
        $helper = $this->getCollectionHelper();
        if ($request->isMethod('POST'))
        {   $collection = CollectionQuery::create()->findOneById($collectionid);
            if (empty($collection))
            {   return JsonResult::create()
                    ->setMessage('Collection not found!')
                    ->setErrorcode(JsonResult::DANGER)
                    ->make();
            }
            $files = $request->files;
            foreach ($files as $uploadedFile)
                $helper->saveCollectionAttachment($collection, $uploadedFile);
        }
        return JsonResult::create()
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get collection attachment (data)
     * @param Request $request
     * @param $attachmentid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCollectionAttachmentAction(Request $request, $attachmentid)
    {
        $attachment = AttachmentQuery::create()->findOneById($attachmentid);
        if (!empty($attachment))
        {   $response = new BinaryFileResponse($attachment->getLinkUrl());
            if ($attachment->getType() == Attachment::TYPE_DOCUMENT)
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $attachment->getOriginalName());
            return $response;
        }
        return NULL;
    }

    /**
     * Delete collection attachment
     * @param Request $request
     * @param $attachmentid
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
     * Save collection data
     * @param $collectionData
     * @return bool
     */
    protected function saveCollectionData($collectionData)
    {
        $helper = $this->getClassHelper();
        $user = $this->getUser();

        if (isset($collectionData->Id)) {
            $collection = CollectionQuery::create()->findOneById($collectionData->Id);
            if (empty($collection)) {
                $collection = new Collection();
                $collection->setUid($helper->createUUID());
            }
        } else
            return false;
        if (isset($collectionData->Type))
        {   $type = CollectionTypeQuery::create()->findOneById($collectionData->Type);
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

        return $collection;
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
     * Get class helper
     * @return object
     */
    protected function getClassHelper()
    {
        $helper = $this->container->get('class_helper');
        return $helper;
    }
}
