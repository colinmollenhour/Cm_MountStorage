<?php

class Cm_MountStorage_Model_Resource_Core_File_Storage_Mount
{

    /**
     * @param string $folderName
     */
    public function deleteFolder($folderName = '')
    {
        $helper     = Mage::helper('cm_mountstorage');
        $folderName = rtrim($folderName, '/');
        $mountPath  = $helper->getFullPath($folderName);
        if (!strlen($folderName) || ! strlen($mountPath) || $folderName == '/' || $mountPath == '/') {
            return;
        }

        $helper->getIo()->rmdir($mountPath, TRUE);
    }
}
