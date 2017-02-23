<?php

class Cm_MountStorage_Model_Adminhtml_System_Config_Source_Storage_Media_Storage extends Mage_Adminhtml_Model_System_Config_Source_Storage_Media_Storage
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        $options[] = [
            'value' => Cm_MountStorage_Model_Core_File_Storage::STORAGE_MEDIA_MOUNT,
            'label' => Mage::helper('cm_mountstorage')->__('Mounted Filesystem')
        ];
        return $options;
    }
}
