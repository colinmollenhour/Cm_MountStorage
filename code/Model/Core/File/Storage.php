<?php

class Cm_MountStorage_Model_Core_File_Storage extends Mage_Core_Model_File_Storage
{
    const STORAGE_MEDIA_MOUNT = 3;

    /**
     * @param int|null $storage
     * @param array $params
     * @return bool|Cm_MountStorage_Model_Core_File_Storage_Mount|Mage_Core_Model_Abstract
     */
    public function getStorageModel($storage = null, $params = [])
    {
        $storageModel = parent::getStorageModel($storage, $params);
        if ($storageModel === false) {
            if (is_null($storage)) {
                $storage = Mage::helper('core/file_storage')->getCurrentStorageCode();
            }
            switch ($storage) {
                case self::STORAGE_MEDIA_MOUNT:
                    /** @var Cm_MountStorage_Model_Core_File_Storage_Mount $storageModel */
                    $storageModel = Mage::getModel('cm_mountstorage/core_file_storage_mount');
                    break;
                default:
                    return false;
            }

            if (isset($params['init']) && $params['init']) {
                $storageModel->init();
            }
        }

        return $storageModel;
    }
}
