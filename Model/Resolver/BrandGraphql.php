<?php
namespace Atbs\Graphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class BrandGraphql implements ResolverInterface
{
    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null)
    {

        // Check parameters
        if (!isset($args['sku']) || empty($args['sku']) || !isset($args['attributeCode']) || empty($args['attributeCode']))
        {
            throw new GraphQlInputException(__('Invalid parameter'));
        }

        // Declare Graphql Output Array
        $output = [];
        
        // Set graphql query params
        $sku = $args['sku'];
        $attributeCode = $args['attributeCode'];
        $language = $args['language'];

        // COULD / SHOULD BE HANDLED WITH DEPENDENCY-INJECTION!
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $productRepo = $objectManager->get('\Magento\Catalog\Model\ProductRepository');

        // Select product per sku
        $product = $productRepo->get($sku);

        // Declare StoreID var
        $storeId;
        
        // Switch-case selects storeId per language param
        switch ($language) {
            case "DE":
            case "de":
            case "deutsch":
            case "german":
                $storeId = 1;
              break;
            case "FR":
            case "fr":
            case "french":
                $storeId = 2;
              break;
            case "IT":
            case "it":
            case "italian":
                $storeId = 3;
              break;
            case "EN":
            case "en":
            case "english":
                $storeId = 4;
              break;
            default:
                $storeId = 0;
        }

        // Set code for admin value
        $output['code'] = $product->getResource()->getAttribute($attributeCode)->setStoreId(0)->getFrontend()->getValue($product);

        // Set code for specific language. Should be selected per language param
        $output['value'] = $product->getResource()->getAttribute($attributeCode)->setStoreId($storeId)->getFrontend()->getValue($product);
      
        // return graphql array
        return $output;
    }
}