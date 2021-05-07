@if (app('Webkul\Product\Repositories\ProductRepository')->getAll()->count())
<?php
    function haveSpecialPrice($product)
    {
        $rulePrice = app('Webkul\CatalogRule\Helpers\CatalogRuleProductPrice')->getRulePrice($product);

        if ((is_null($product->special_price) || ! (float) $product->special_price) && ! $rulePrice) {
            return false;
        }

        if (! (float) $product->special_price) {
            if ($rulePrice && $rulePrice->price < $product->price) {
                $product->special_price = $rulePrice->price;

                return true;
            }
        } else {
            if ($rulePrice && $rulePrice->price <= $product->special_price) {
                $product->special_price = $rulePrice->price;

                return true;
            } else {
                if (core()->isChannelDateInInterval($product->special_price_from, $product->special_price_to)) {
                    return true;
                } elseif ($rulePrice) {
                    $product->special_price = $rulePrice->price;

                    return true;
                }
            }
        }

        return false;
    }
?>
    <section class="featured-products">

        <div class="featured-heading">
            Products On Sale<br/>

            <span class="featured-seperator" style="color:lightgrey;">_____________</span>
        </div>

        <div class="product-grid-4">

            @foreach (app('Webkul\Product\Repositories\ProductRepository')->getAll() as $productFlat)
                @if (haveSpecialPrice($productFlat))
                    @include ('shop::products.list.card', ['product' => $productFlat])
                @endif
            @endforeach

        </div>

    </section>
@endif