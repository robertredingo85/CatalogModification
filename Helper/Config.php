<?php

namespace RedInGo\CategoryModification\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Get module settings
     *
     * @param $key
     * @return mixed
     */
    public function getConfigModule($key)
    {
        return $this->scopeConfig->getValue(
            'redingo_categorymodification/general/' . $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
