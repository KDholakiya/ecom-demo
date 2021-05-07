<div class="footer">


    <div class="footer-find-us">
        <span class="">Find Us</span><br/><br/>
        <a href="https://wa.me/message/ICI2OIVDISJ3L1" target="_blank" ><i class="fab fa-whatsapp-square whatsapp"></i></a>
        <a href="https://www.facebook.com/getvastraa/" target="_blank" ><i class="fab fa-facebook-square facebook"></i></a>
        <a href="https://instagram.com/getvastra?igshid=1l9tkf2jkt8fb" target="_blank" ><i class="fab fa-instagram-square instagram"></i></a>
        <a href="https://pin.it/2rFqkxZ" target="_blank" ><i class="fab fa-pinterest-square pinterest"></i></a>
        <a href="" target="_blank" ><i class="fab fa-youtube-square youtube"></i></a>
        <a href="" target="_blank" ><i class="fab fa-linkedin linkedin"></i></a>
        </ul>
    </div>
    <div class="footer-content">

        <div class="footer-list-container">

            {{-- {!! DbView::make(core()->getCurrentChannel())->field('footer_content')->render() !!} --}}

            {{-- <li><a href="@php echo route('shop.cms.page', 'about-us') @endphp">About Us</a></li> --}}
            <div class="list-container">
                <span class="list-heading makeDropDown">Company <i class="fas fa-chevron-down"></i></span>
                <ul class="list-group" style="display:none">
                    <li><a href="@php echo route('shop.cms.page', 'about-us') @endphp">About Us</a></li>
                    <li><a href="@php echo route('shop.cms.page', 'contact-us') @endphp">Contact Us</a></li>
                </ul>
            </div>

            <div class="list-container">
                <span class="list-heading makeDropDown">Policies <i class="fas fa-chevron-down"></i></span>
                <ul class="list-group" style="display:none">
                    <li><a href="@php echo route('shop.cms.page', 'terms-and-condition') @endphp">Terms and Condition</a></li>
                    <li><a href="@php echo route('shop.cms.page', 'order-and-shipping') @endphp">Order and Shipping</a></li>
                    <li><a href="@php echo route('shop.cms.page', 'return-and-exchange') @endphp">Return and Echange</a></li>
                    <li><a href="@php echo route('shop.cms.page', 'privacy-policy') @endphp">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="list-container">
                @if(core()->getConfigData('customer.settings.newsletter.subscription'))
                    <span class="list-heading">{{ __('shop::app.footer.subscribe-newsletter') }}</span>
                    <div class="form-container">
                        <form action="{{ route('shop.subscribe') }}">
                            <div class="control-group" :class="[errors.has('subscriber_email') ? 'has-error' : '']">
                                <input type="email" class="control subscribe-field" name="subscriber_email" placeholder="Email Address" required><br/>

                                <button class="btn btn-md btn-primary">{{ __('shop::app.subscription.subscribe') }}</button>
                            </div>
                        </form>
                    </div>
                @endif

                <?php
                    $term = request()->input('term');

                    if (! is_null($term)) {
                        $serachQuery = 'term='.request()->input('term');
                    }
                ?>

            <?php
                $categories = [];

                foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category){
                    if ($category->slug)
                        array_push($categories, $category);
                }
            ?>
            @if (count($categories))
                <div class="list-container">
                    <span class="list-heading makeDropDown">Categories <i class="fas fa-chevron-down"></i></span>

                    <ul class="list-group"  style="display:none">
                        @foreach ($categories as $key => $category)
                            <li>
                                <a href="{{ route('shop.productOrCategory.index', $category->slug) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

                {{-- <span class="list-heading">{{ __('shop::app.footer.locale') }}</span>
                <div class="form-container">
                    <div class="control-group">
                        <select class="control locale-switcher" onchange="window.location.href = this.value" @if (count(core()->getCurrentChannel()->locales) == 1) disabled="disabled" @endif>

                            @foreach (core()->getCurrentChannel()->locales as $locale)
                                @if (isset($serachQuery))
                                    <option value="?{{ $serachQuery }}&locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                                @else
                                    <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="currency">
                    <span class="list-heading">{{ __('shop::app.footer.currency') }}</span>
                    <div class="form-container">
                        <div class="control-group">
                            <select class="control locale-switcher" onchange="window.location.href = this.value">

                                @foreach (core()->getCurrentChannel()->currencies as $currency)
                                    @if (isset($serachQuery))
                                        <option value="?{{ $serachQuery }}&currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                                    @else
                                        <option value="?currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>

        <footer style="text-align: center;margin:5px 0px">
            Crafted with <span style="color:rgb(207, 0, 0)">❤</span> by <b><a style="color:#000" href="https://saptsys.com">Saptsys</a></b>
        </footer>

    </div>
</div>
