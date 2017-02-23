<?php

class Cm_MountStorage_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Returns the mounted filesystem path where we want to store all our images.
     *
     * @return string
     */
    public function getMountPath()
    {
        return Mage::getStoreConfig('system/media_storage_configuration/mount_path');
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFullPath($path)
    {
        return $this->getMountPath().DS.ltrim($path, '/');
    }

    /**
     * @return Varien_Io_File
     */
    public function getIo($path = null)
    {
        static $io;
        if ( ! $io) {
            $io = new Varien_Io_File();
        }
        if ($path === NULL) {
            $io->setAllowCreateFolders(FALSE);
            $io->open(['path' => $this->getMountPath()]);
        }
        else {
            if (strpos($path, $this->getMountPath()) !== 0){
                $path = $this->getFullPath($path);
            }
            $io->setAllowCreateFolders(TRUE);
            try {
                $io->open(['path' => $path]);
            } catch (Exception $e) {
                $io->mkdir($path, 0777, true);
                $io->open(['path' => $path]);
            }
        }
        return $io;
    }
}
