@if (app('Webkul\Product\Repositories\ProductRepository')->getFeaturedProducts()->count())
    <section class="featured-products">

        <div class="featured-heading">
            {{ __('shop::app.home.featured-products') }}<br/>

            <span class="featured-seperator" style="color:lightgrey;">_____</span>
        </div>
            <ul class="productsSlider">
                @foreach (app('Webkul\Product\Repositories\ProductRepository')->getFeaturedProducts() as $productFlat)
                <li class="productsSlide">
                    <div class="featured-grid product-grid-1">
                        @include ('shop::products.list.card', ['product' => $productFlat])
                    </div>
                </li>
                @endforeach
            </ul>
            <br>
    </section>
@endif