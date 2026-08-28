{if isset($dual_price_extras) && $dual_price_extras|@count}
  <ul class="pnscripts-dual-price" aria-label="{l s='Prices in other currencies' d='Modules.Pnscriptsdualprice.Shop'}">
    {foreach from=$dual_price_extras item=extra}
      <li class="pnscripts-dual-price__item">
        <span class="pnscripts-dual-price__iso">{$extra.iso_code|escape:'html':'UTF-8'}</span>
        <span class="pnscripts-dual-price__amount">{$extra.sign|escape:'html':'UTF-8'} {$extra.amount|string_format:'%.2f'}</span>
      </li>
    {/foreach}
  </ul>
{/if}
