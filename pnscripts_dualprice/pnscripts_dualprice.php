<?php

declare(strict_types=1);

if (! defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/classes/DualPricePresenter.php';

class Pnscripts_Dualprice extends Module
{
    public function __construct()
    {
        $this->name = 'pnscripts_dualprice';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.0';
        $this->author = 'PN Scripts';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = [
            'min' => '8.0.0',
            'max' => '9.99.99',
        ];

        parent::__construct();

        $this->displayName = $this->trans('Dual Price', [], 'Modules.Pnscriptsdualprice.Admin');
        $this->description = $this->trans(
            'Show converted prices for every other active currency next to the product price.',
            [],
            'Modules.Pnscriptsdualprice.Admin',
        );
        $this->confirmUninstall = $this->trans(
            'Remove Dual Price from product pages?',
            [],
            'Modules.Pnscriptsdualprice.Admin',
        );
    }

    public function hookDisplayHeader(array $params): string
    {
        $this->context->controller->registerStylesheet(
            'pnscripts-dualprice',
            'modules/'.$this->name.'/views/css/front.css',
            ['media' => 'all', 'priority' => 150],
        );

        return '';
    }

    public function install(): bool
    {
        return parent::install()
            && $this->registerHook('displayProductPriceBlock')
            && $this->registerHook('displayHeader');
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function hookDisplayProductPriceBlock(array $params): string
    {
        if (($params['type'] ?? '') !== 'after_price') {
            return '';
        }

        $price = $this->productPrice($params['product'] ?? null);
        if ($price === null) {
            return '';
        }

        $context = $this->context;
        $current = $context->currency;
        if (! is_object($current) || empty($current->iso_code)) {
            return '';
        }

        $currencies = [];
        foreach (Currency::getCurrencies(false, true) as $currency) {
            $currencies[] = [
                'iso_code' => (string) ($currency['iso_code'] ?? ''),
                'conversion_rate' => (float) ($currency['conversion_rate'] ?? 0),
                'sign' => (string) ($currency['sign'] ?? ''),
                'active' => (bool) ($currency['active'] ?? true),
            ];
        }

        $extras = (new DualPricePresenter())->extras(
            (string) $current->iso_code,
            $price,
            $currencies,
            (int) _PS_PRICE_DISPLAY_PRECISION_,
        );

        if ($extras === []) {
            return '';
        }

        $this->context->smarty->assign([
            'dual_price_extras' => $extras,
        ]);

        return $this->fetch('module:pnscripts_dualprice/views/templates/hook/displayProductPriceBlock.tpl');
    }

    /**
     * @param  mixed  $product
     */
    private function productPrice(mixed $product): ?float
    {
        if (is_array($product) && isset($product['price_amount'])) {
            return (float) $product['price_amount'];
        }

        if (is_array($product) && isset($product['price'])) {
            return (float) $product['price'];
        }

        if (is_object($product) && isset($product->price_amount)) {
            return (float) $product->price_amount;
        }

        if (is_object($product) && isset($product->price)) {
            return (float) $product->price;
        }

        return null;
    }
}
