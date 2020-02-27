<?php

namespace RedInGo\CategoryModification\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Url
     */
    protected $url;

    public function __construct(
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Magento\Framework\Url $url
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectmanager;
        $this->url = $url;
    }

    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    public function getPromoBannerText($bannerText, $product)
    {
        preg_match_all('/{([a-zA-Z:\_0-9]+)}/', $bannerText, $vars);
        if (!$vars[1]) {
            return $bannerText;
        }
        $vars = $vars[1];

        $regularPrice = $product->getPriceInfo()->getPrice('regular_price')->getValue();
        $specialPrice = $product->getFinalPrice();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');

        foreach ($vars as $var) {
            switch ($var) {
                case 'SAVE_AMOUNT':
                    $price = $regularPrice - $specialPrice;
                    $value = $priceHelper->currency($price, true, false);
                    break;
                case 'SAVE_PERCENT':
                    $value = 0;
                    if ($regularPrice != 0) {
                        $value = $regularPrice - $specialPrice;
                        $value = floor($value * 100 / $regularPrice);
                        $value .= '%';
                    }
                    break;
            }
            $bannerText = str_replace('{' . $var . '}', $value, $bannerText);
        }
        return $bannerText;
    }

    public function getProductDiscountLevel($product)
    {
        $regularPrice = $product->getPriceInfo()->getPrice('regular_price')->getValue();
        $specialPrice = $product->getFinalPrice();

        $value = 0;
        if ($regularPrice != 0) {
            $value = $regularPrice - $specialPrice;
            $value = $value * 100 / $regularPrice;
        }

        return $value;
    }

    public function getPaginationStatus($params)
    {
      $pageParam = array_key_exists('p',$params);
      if($pageParam){
           $categoryCurrentPageParam = $params['p'];
           if($categoryCurrentPageParam > 1)
           {
             return 1;
           }
      }
      return 0;
    }

    public function getPaginationPage($params)
    {
      $pageParam = array_key_exists('p',$params);
      if($pageParam){
           $categoryCurrentPageParam = $params['p'];
           return sprintf(__(' - page number %s'), $categoryCurrentPageParam);
      }
      return 0;
    }

    public function getBrandImage($product)
    {
        $imagesDir = '/amasty/shopby/option_images/';
        $sliderDir = 'slider/';

        $attributeValue = $product->getData("marki");

        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('amasty_amshopby_option_setting');
        $sql = $connection->select()
                          ->from($tableName)
                          ->where('value = ?', $attributeValue);

        $result = $connection->fetchAll($sql);

        if($result){
            $imageUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                        .$imagesDir.$sliderDir.$result[0]['slider_image'];
            $title = $result[0]['title'];

            $brandCode = str_replace(\Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX, '', $result[0]['filter_code']);
            $linkUrl = $this->url->getUrl('amshopby/index/index', [
                '_query' => [$brandCode => $result[0]['value']],
            ]);

            $html = '<div class="amshopby-option-link">
                        <a href="'.$linkUrl.'">
                            <img
                                title="'.$title.'"
                                alt="'.$title.'"
                                src="'.$imageUrl.'"
                            />
                            <span>'.$title.'</span>
                        </a>
                    </div>';
            return $html;
        }
    }
}

?>
