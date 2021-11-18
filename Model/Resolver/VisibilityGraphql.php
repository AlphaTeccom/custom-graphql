<?php
namespace Atbs\Graphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class VisibilityGraphql implements ResolverInterface
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
        if (!isset($args['sku']) || empty($args['sku']))
        {
            throw new GraphQlInputException(__('Invalid parameter'));
        }

        // Declare Graphql Output Array
        $output = [];
        
        // Set graphql query params
        $sku = $args['sku'];
        $attributeCode = $args['attributeCode'];
        $language = $args['language'];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $productRepo = $objectManager->get('\Magento\Catalog\Model\ProductRepository');

        // Select product per sku
        $product = $productRepo->get($sku);

        // Decleare StoreID var
        $storeId;
        
        // Switch-case selects storeId per language param
        switch ($language) {
            case "DE":
                $storeId = 1;
              break;
            case "FR":
                $storeId = 2;
              break;
              case "IT":
                $storeId = 3;
              break;
            case "EN":
                $storeId = 4;
              break;
            default:
                $storeId = 0;
        }

        // Set code for specific language. Should be selected per language param
        $output['visibility'] = $product->getResource()->getAttribute("visibility")->setStoreId($storeId)->getFrontend()->getValue($product);
      
        // return graphql array
        return $output;
    }
}