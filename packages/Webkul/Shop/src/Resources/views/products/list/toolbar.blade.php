@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}

<div class="top-toolbar mb-35">

    <div class="page-info">
        <span>
            {{ __('shop::app.products.pager-info', ['showing' => $products->firstItem() . '-' . $products->lastItem(), 'total' => $products->total()]) }}
        </span>

        <span class="sort-filter">
                {{-- <label>Sort</label> --}}
                <i title="Sort" class="icon sort-icon" id="sort" ></i>
                <i title="Filter" class="icon filter-icon" id="filter"></i>
        </span>
    </div>
    <div>
        <button id="mySortButton" style="display:none;width: 49%; border-radius:1000px 0px 0px 1000px;padding:3px;margin-top:3px" class="btn-black-outline">Sort</button>
        <button id="myFilterButton" style="display:none;width: 49%; border-radius:0px 1000px 1000px 0px;padding:3px;margin-top:3px" class="btn-black-outline">Filter</button>
    </div>
    <div class="pager">

        <div class="view-mode">
            @if ($toolbarHelper->isModeActive('grid'))
                <span class="grid-view">
                    <i class="icon grid-view-icon"></i>
                </span>
            @else
                <a href="{{ $toolbarHelper->getModeUrl('grid') }}" class="grid-view">
                    <i class="icon grid-view-icon"></i>
                </a>
            @endif

            @if ($toolbarHelper->isModeActive('list'))
                <span class="list-view">
                    <i class="icon list-view-icon"></i>
                </span>
            @else
                <a href="{{ $toolbarHelper->getModeUrl('list') }}" class="list-view">
                    <i class="icon list-view-icon"></i>
                </a>
            @endif
        </div>

        <div class="sorter">
            <label>{{ __('shop::app.products.sort-by') }}</label>

            <select onchange="window.location.href = this.value">

                @foreach ($toolbarHelper->getAvailableOrders() as $key => $order)

                    <option value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                        {{ __('shop::app.products.' . $order) }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="limiter">
            <label>{{ __('shop::app.products.show') }}</label>

            <select onchange="window.location.href = this.value">

                @foreach ($toolbarHelper->getAvailableLimits() as $limit)

                    <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                        {{ $limit }}
                    </option>

                @endforeach

            </select>
        </div>

    </div>

</div>

{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}


<div class="responsive-layred-filter mb-20">
    <layered-navigation></layered-navigation>
</div>