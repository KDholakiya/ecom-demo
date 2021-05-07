<div class="insta-wp">
    @if(core()->getConfigData("general.social.instagram.instagram_toggle"))
    <span>
        <a href={{core()->getConfigData("general.social.instagram.instagram_link") ?? "#"}}>
            @if(core()->getConfigData("general.social.instagram.instagram_banner"))
                <img alt="Instagram" src={{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData("general.social.instagram.instagram_banner")) ??  'https://via.placeholder.com/600x200?text=instagram 600x200' }}>
            @else
                <h3 style="color:orangered">Instagram<h3>
            @endif

        </a>
    </span>
    @endif
    @if(core()->getConfigData("general.social.whatsapp.whatsapp_toggle"))
    <span>
        <a  href={{core()->getConfigData("general.social.whatsapp.whatsapp_link") ?? "#"}}>
            @if(core()->getConfigData("general.social.whatsapp.whatsapp_banner"))
                <img alt="WhatsApp" src={{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData("general.social.whatsapp.whatsapp_banner")) ?? 'https://via.placeholder.com/600x200?text=whatsapp 600x200'}}>
            @else
                <h3 style="color:mediumspringgreen">WhatsApp<h3>
            @endif
        </a>
    </span>
    @endif
</div>