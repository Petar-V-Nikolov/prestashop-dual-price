<?php

declare(strict_types=1);

/**
 * Pure presenter: extra currency amounts for a product price.
 * Keep PrestaShop Context out of this class so PHPUnit can cover it.
 */
final class DualPricePresenter
{
    /**
     * @param  list<array{
     *     iso_code: string,
     *     conversion_rate: float|int|string,
     *     sign?: string,
     *     active?: bool|int
     * }>  $currencies
     * @return list<array{iso_code: string, amount: float, sign: string}>
     */
    public function extras(
        string $currentIso,
        float $priceInDefaultCurrency,
        array $currencies,
        int $precision = 2,
    ): array {
        $currentIso = strtoupper($currentIso);
        $out = [];

        foreach ($currencies as $currency) {
            if (! $this->isActive($currency)) {
                continue;
            }

            $iso = strtoupper((string) ($currency['iso_code'] ?? ''));
            if ($iso === '' || $iso === $currentIso) {
                continue;
            }

            $rate = (float) ($currency['conversion_rate'] ?? 0);
            if ($rate <= 0) {
                continue;
            }

            $out[] = [
                'iso_code' => $iso,
                'amount' => round($priceInDefaultCurrency * $rate, $precision),
                'sign' => (string) ($currency['sign'] ?? $iso),
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $currency
     */
    private function isActive(array $currency): bool
    {
        if (! array_key_exists('active', $currency)) {
            return true;
        }

        return (bool) $currency['active'];
    }
}
