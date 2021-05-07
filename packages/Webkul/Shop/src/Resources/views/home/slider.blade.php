<section class="slider-block">
    <!-- <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="">
        <div class="carousel-inner">
            @foreach($sliderData as $data)
            <div class="carousel-item {{ $loop->index == 0 ? 'active' : ''}}">
                <img class="d-block w-100" src="{{ url()->to('/') . '/storage/' . $data['path'] }}" alt="Second slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{$data['title']}}</h5>
                    <p>{!!$data['content']!!}</p>
                </div>
            </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div> -->

    <ul id="image-slider">
        @foreach($sliderData as $data)
        <li>
            @if(!$data['slider_path'] == "")
            <a href="{{$data['slider_path']}}">
                <img width="100%" src="{{ url()->to('/') . '/storage/' . $data['path'] }}" />
            </a>
            @else
            <img width="100%" src="{{ url()->to('/') . '/storage/' . $data['path'] }}" />
            @endif

            <!-- <div class="caption">
                <h1>Hello</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam suscipit vehicula aliquam</p>
            </div> -->
        </li>
        @endforeach
    </ul>
    <br>
    <br>
    <br>
    <div style="text-align: center; font-size: 18px">
        <div>SHOP BY CATEGORIES</div>
        <span class="featured-seperator" style="color:lightgrey;">___________</span>
        <br>
        <br>
        @foreach(app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $cat)
        <a href="{{$cat->url_path}}" class="btn btn-md cat-button btn-black-outline">{{$cat->name}}</a href="/asdasd">

        @endforeach
        <br>
        <br>
    </div>

    <!-- <image-slider :slides='@json($sliderData)' public_path="{{ url()->to('/') }}"></image-slider> -->
</section>