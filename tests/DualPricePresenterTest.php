<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__).'/pnscripts_dualprice/classes/DualPricePresenter.php';

final class DualPricePresenterTest extends TestCase
{
    public function test_it_skips_the_current_currency_and_inactive_rows(): void
    {
        $presenter = new DualPricePresenter();

        $extras = $presenter->extras('BGN', 10.00, [
            ['iso_code' => 'BGN', 'conversion_rate' => 1, 'sign' => 'лв', 'active' => 1],
            ['iso_code' => 'EUR', 'conversion_rate' => 0.511, 'sign' => '€', 'active' => 1],
            ['iso_code' => 'USD', 'conversion_rate' => 0.55, 'sign' => '$', 'active' => 0],
        ], 2);

        $this->assertCount(1, $extras);
        $this->assertSame('EUR', $extras[0]['iso_code']);
        $this->assertSame(5.11, $extras[0]['amount']);
        $this->assertSame('€', $extras[0]['sign']);
    }

    public function test_it_returns_empty_when_only_one_currency_is_active(): void
    {
        $presenter = new DualPricePresenter();

        $this->assertSame([], $presenter->extras('EUR', 20.00, [
            ['iso_code' => 'EUR', 'conversion_rate' => 1, 'sign' => '€'],
        ]));
    }
}
