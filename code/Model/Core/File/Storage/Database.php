<?php

class Cm_MountStorage_Model_Core_File_Storage_Database extends Mage_Core_Model_File_Storage_Database
{

    /**
     * @param string $filePath
     * @return $this|Mage_Core_Model_File_Storage_Database
     */
    public function loadByFilename($filePath)
    {
        $storage = Mage::helper('core/file_storage')->getCurrentStorageCode();
        if ($storage == Cm_MountStorage_Model_Core_File_Storage::STORAGE_MEDIA_MOUNT) {
            /** @var Cm_MountStorage_Model_Core_File_Storage_Mount $mountStorageModel */
            $mountStorageModel = Mage::getModel('cm_mountstorage/core_file_storage_mount');
            $mountStorageModel->loadByFilename($filePath);

            if ($mountStorageModel->getData('id')) {
                $this->setData('id', $mountStorageModel->getData('id'));
                $this->setData('filename', $mountStorageModel->getData('filename'));
                $this->setData('content', $mountStorageModel->getData('content'));
            }

            return $this;
        }
        return parent::loadByFilename($filePath);
    }

    /**
     * Return directory listing
     *
     * @param string $directory
     * @return mixed
     */
    public function getDirectoryFiles($directory)
    {
        $directory = Mage::helper('core/file_storage_database')->getMediaRelativePath($directory);

        try {
            return $this->_getResource()->getDirectoryFiles($directory);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getId()
    {
        $storage = Mage::helper('core/file_storage')->getCurrentStorageCode();
        if ($storage == Cm_MountStorage_Model_Core_File_Storage::STORAGE_MEDIA_MOUNT) {
            return $this->getData('id');
        }
        return parent::getId();
    }
}
