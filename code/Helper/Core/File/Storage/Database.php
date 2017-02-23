<?php

class Cm_MountStorage_Helper_Core_File_Storage_Database extends Mage_Core_Helper_File_Storage_Database
{
    private $useMount = null;

    /**
     * Check whether we are using the database or mount for storing images.
     *
     * @return bool
     */
    public function checkDbUsage()
    {
        return parent::checkDbUsage() || $this->checkMountUsage();
    }

    /**
     * Check whether we are using mount for storing images.
     *
     * @return bool
     */
    public function checkMountUsage()
    {
        if (is_null($this->useMount)) {
            $currentStorage = (int) Mage::app()->getConfig()
                ->getNode(Mage_Core_Model_File_Storage::XML_PATH_STORAGE_MEDIA);
            $this->useMount = $currentStorage == Cm_MountStorage_Model_Core_File_Storage::STORAGE_MEDIA_MOUNT;
        }
        return $this->useMount;
    }

    /**
     * @return Cm_MountStorage_Model_Core_File_Storage_Mount|Mage_Core_Model_File_Storage_Database
     */
    public function getStorageDatabaseModel()
    {
        if (is_null($this->_databaseModel)) {
            if ($this->checkMountUsage()) {
                $this->_databaseModel = Mage::getModel('cm_mountstorage/core_file_storage_mount');
            } else {
                parent::getStorageDatabaseModel();
            }
        }
        return $this->_databaseModel;
    }

    /**
     * @param string $filename
     * @return bool|int
     */
    public function saveFileToFilesystem($filename)
    {
        if ($this->checkMountUsage()) {
            $storageModel = $this->getStorageDatabaseModel();
            $file = $storageModel
                ->loadByFilename($this->_removeAbsPathFromFileName($filename));
            if (!$file->getId()) {
                return false;
            }

            return $this->getStorageFileModel()->saveFile($file, true);
        }
        return parent::saveFileToFilesystem($filename);
    }
}
