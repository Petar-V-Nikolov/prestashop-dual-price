# Dual Price

PrestaShop **8 and 9** module by [PN Scripts](https://pnscripts.com). It prints every other active shop currency next to the product price so a visitor in Bulgaria can see BGN and EUR without switching the catalog currency.

This repository is the installable module. Copy `pnscripts_dualprice/` into your shop `modules/` folder. It is not a Laravel app.

## What it does

- Hooks `displayProductPriceBlock` with type `after_price`
- Converts with PrestaShop currency rates (`conversion_rate`)
- Skips the current currency and inactive currencies
- Leaves the theme price block in place

## Requirements

- PHP 8.1+
- PrestaShop 8.0–9.x
- At least two active currencies

## Install

1. Copy `pnscripts_dualprice` into `modules/` on the shop.
2. In Back Office → Modules, install **Dual Price**.
3. Open a product page. Extra currencies appear under the main price.

## Tests

```bash
composer install
composer test
```

The presenter is a plain PHP class. Module wiring still needs a PrestaShop shop to click through.

## License

MIT. This site overview on pnscripts.com does not take payment. A CodeCanyon or ThemeForest URL can be added later without changing the module.
