<?php

class Cm_MountStorage_Model_Cms_Wysiwyg_Images_Storage extends Mage_Cms_Model_Wysiwyg_Images_Storage
{
    public function getDirsCollection($path)
    {
        /** @var Cm_MountStorage_Helper_Core_File_Storage_Database $helper */
        $helper = Mage::helper('cm_mountstorage/core_file_storage_database');
        if ($helper->checkMountUsage()) {
            $storageModel = $helper->getStorageDatabaseModel();
            $subdirectories = $storageModel->getSubdirectories($path);

            foreach ($subdirectories as $directory) {
                $fullPath = rtrim($path, '/') . '/' . $directory['name'];
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0777, true);
                }
            }
        }
        return parent::getDirsCollection($path);
    }

    public function getFilesCollection($path, $type = null)
    {
        /** @var Cm_MountStorage_Helper_Core_File_Storage_Database $helper */
        $helper = Mage::helper('cm_mountstorage/core_file_storage_database');
        if ($helper->checkMountUsage()) {
            $storageModel = $helper->getStorageDatabaseModel();
            $files = $storageModel->getDirectoryFiles($path);

            /** @var Mage_Core_Model_File_Storage_File $fileStorageModel */
            $fileStorageModel = Mage::getModel('core/file_storage_file');
            foreach ($files as $file) {
                $fileStorageModel->saveFile($file);
            }
        }
        return parent::getFilesCollection($path, $type);
    }
}
