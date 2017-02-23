<?php

class Cm_MountStorage_Model_Core_File_Storage_Mount extends Mage_Core_Model_File_Storage_Abstract
{
    protected $_eventPrefix = 'cm_mountstorage_core_file_storage_mount';

    private $helper = null;

    private $errors = [];

    private $objects = [];

    protected function _construct()
    {
        $this->_init('cm_mountstorage/core_file_storage_mount');
    }

    /**
     * @return bool
     */
    public function getIdFieldName()
    {
        return false;
    }

    /**
     * @return $this
     */
    public function init()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getStorageName()
    {
        return Mage::helper('cm_mountstorage')->__('Mounted Filesystem');
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function loadByFilename($filePath)
    {
        $data = @file_get_contents($this->getHelper()->getFullPath($filePath));
        if ($data) {
            $this->setData('id', $filePath);
            $this->setData('filename', $filePath);
            $this->setData('content', $data);
        } else {
            $this->unsetData();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        // Nah.. This seems like a bad idea...
        return $this;
    }

    public function exportDirectories($offset = 0, $count = 100)
    {
        return false;
    }

    public function importDirectories(array $dirs = [])
    {
        return $this;
    }

    public function exportFiles($offset = 0, $count = 100)
    {
        return [];
    }

    public function importFiles(array $files = [])
    {
        return $this;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function saveFile($filename)
    {
        $sourcePath = $this->getMediaBaseDirectory() . '/' . $filename;
        return $this->getHelper()->getIo(dirname($filename))->cp($sourcePath, basename($filename));
    }

    /**
     * Check whether a file exists in mounted filesystem
     *
     * @param string $filePath
     * @return bool
     */
    public function fileExists($filePath)
    {
        return file_exists($this->getHelper()->getFullPath($filePath));
    }

    /**
     * @param $oldFilePath
     * @param $newFilePath
     * @return $this
     */
    public function copyFile($oldFilePath, $newFilePath)
    {
        $oldFilePath = $this->getHelper()->getFullPath($oldFilePath);
        $newFilePath = $this->getHelper()->getFullPath($newFilePath);
        $this->getHelper()->getIo(dirname($newFilePath));
        @copy($oldFilePath, $newFilePath);

        return $this;
    }

    /**
     * @param $oldName
     * @param $newName
     * @return $this
     */
    public function renameFile($oldName, $newName)
    {
        $oldName = $this->getHelper()->getFullPath($oldName);
        $newName = $this->getHelper()->getFullPath($newName);
        $this->getHelper()->getIo(dirname($newName));
        @rename($oldName, $newName);

        return $this;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getSubdirectories($path)
    {
        $prefix = Mage::helper('core/file_storage_database')->getMediaRelativePath($path);
        $prefix = rtrim($prefix, '/');

        return $this->getHelper()->getIo($this->getHelper()->getFullPath($prefix))->ls(Varien_Io_File::GREP_DIRS);
    }

    /**
     * @param string $directory
     * @return array
     */
    public function getDirectoryFiles($directory)
    {
        $prefix = Mage::helper('core/file_storage_database')->getMediaRelativePath($directory);
        $prefix = rtrim($prefix, '/');

        return $this->getHelper()->getIo($this->getHelper()->getFullPath($prefix))->ls(Varien_Io_File::GREP_FILES);
    }

    /**
     * @param string $path
     * @return $this
     */
    public function deleteFile($path)
    {
        $path = $this->getHelper()->getFullPath($path);

        @unlink($path);

        return $this;
    }

    /**
     * @return Cm_MountStorage_Helper_Data
     */
    protected function getHelper()
    {
        if (is_null($this->helper)) {
            $this->helper = Mage::helper('cm_mountstorage');
        }
        return $this->helper;
    }
}
