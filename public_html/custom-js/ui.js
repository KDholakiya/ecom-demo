$(document).ready(function () {
    $('#image-slider').lightSlider({
        gallery: false,
        item: 1,
        loop: true,
        slideMargin: 0,
        enableDrag: true,
        auto:true,
        pager:false,
        pauseOnHover:true,
        speed:800,
        enableTouch:true
    });
    if ($(".search-box").css('display') !== "none") {
        $("#search-responsive").css('display', "block");
    }
    $(".productsSlider").lightSlider({
        autoWidth:true,
        loop:true,
        auto:true,
        pager:false,
        pauseOnHover:true,
        speed:500,
        enableTouch:true,
        enableDrag: true
    });

    if ($(".sort-filter").css('display') != 'none') {

        $("#mySortButton").show();
        $("#myFilterButton").show();

        $("#mySortButton").click(function () {
            $("#sort").trigger('click');
            sortFilterCalled()
        });
        $("#myFilterButton").click(function () {
            $("#filter").trigger('click');
            sortFilterCalled()
        });
        $(".sort-filter").hide();
    } else {
        $("#mySortButton").hide();
        $("#myFilterButton").hide();
    }

    $(".makeDropDown").click(function(){
        $(this).siblings('ul').first().slideToggle(500,function(){
            if($(this).css('display') == "none"){
                $(this).parent().find('.list-heading').find('.fas').removeClass('fa-chevron-up')
                $(this).parent().find('.list-heading').find('.fas').addClass('fa-chevron-down')
            }else{
                $(this).parent().find('.list-heading').find('.fas').addClass('fa-chevron-up')
                $(this).parent().find('.list-heading').find('.fas').removeClass('fa-chevron-down')
            }
        });
    });

});

function sortFilterCalled(){
    if($(".pager").css('display') !== "none"){
        $("#mySortButton").addClass('btn-black-outline-active');
        $("#mySortButton").removeClass('btn-black-outline');
    }else{
        $("#mySortButton").removeClass('btn-black-outline-active');
        $("#mySortButton").addClass('btn-black-outline');
    }if($(".responsive-layred-filter").css('display') !== "none"){
        $("#myFilterButton").addClass('btn-black-outline-active');
        $("#myFilterButton").removeClass('btn-black-outline');
    }else{
        $("#myFilterButton").removeClass('btn-black-outline-active');
        $("#myFilterButton").addClass('btn-black-outline');
    }
}