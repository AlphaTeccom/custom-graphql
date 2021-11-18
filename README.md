# custom-graphql
ATTENTION: This module is still in dev!

For usage, just use:
```
composer config repositories.atbs/graphql git git@github.com:AlphaTeccom/custom-graphql.git
```

Require the module:
```
composer require "atbs/graphql @dev"
```

When you getting an error about the minimum stability, just add
```
"minimum-stability":"dev"
```
 after the module url.

 After the installation, the module is available. When is not enabled, you can enable it per command:
```
bin/magento module:enable atbs/graphql
```

You can send request now per
```
YOUR-DOMAIN.com/graphql
```

Create the attribute brand and add it the product attribute set you want. After this, you can request it per:
```
query {
  brand(
    sku: {SKU}
    brand: {BRAND_CODE}
    language: {LANGUAGE_CODE}
  ) {
   	code
    value
  }
}
```