<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channels')->where('id',1)->update([
            'theme'             => env("DEFAULT_THEME","velocity"),
            'root_category_id'  => 1,
            'home_page_content' => '<p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p><div class="banner-container"><div class="left-banner"><img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581f9494b8a1.png" /></div><div class="right-banner"><img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581fb045cf02.png" /> <img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581fc352d803.png" /></div></div>',
            'footer_content'    => '<div class="list-container"><span class="list-heading">Quick Links</span><ul class="list-group"><li><a href="@php echo route(\'shop.cms.page\', \'about-us\') @endphp">About Us</a></li><li><a href="@php echo route(\'shop.cms.page\', \'return-policy\') @endphp">Return Policy</a></li><li><a href="@php echo route(\'shop.cms.page\', \'refund-policy\') @endphp">Refund Policy</a></li><li><a href="@php echo route(\'shop.cms.page\', \'terms-conditions\') @endphp">Terms and conditions</a></li><li><a href="@php echo route(\'shop.cms.page\', \'terms-of-use\') @endphp">Terms of Use</a></li><li><a href="@php echo route(\'shop.cms.page\', \'contact-us\') @endphp">Contact Us</a></li></ul></div><div class="list-container"><span class="list-heading">Connect With Us</span><ul class="list-group"><li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li><li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li><li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li><li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li><li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li></ul></div>',
            'name'              => 'Default',
            'default_locale_id' => 1,
            'base_currency_id'  => 1,
            'home_seo'          => '{"meta_title": "'.env('APP_NAME',"Demo store").'", "meta_keywords": "'.env('APP_NAME',"Demo store").'", "meta_description": "'.env('APP_NAME',"Demo store").'"}',
        ]);
    }
}
