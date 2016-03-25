function addDishDetailFav(goods_id,type)
{

    var url  = $('#url').val();
    var mid = $('#mid').val();
    var url  = $('#url').val();
    if(mid==0||mid==1)
    {
        $.ajax({
            type : "GET",
            url : url+'/ajax_saveUrl.php',
            success : function(data)
            {

                location.href= url  + '/login.php';
            }
        });
        return;
    }

    $.ajax({
        type : "POST",
        url : url+ '/ajax_addFav.php',
        data:{goods_id:goods_id,type:type},
        dataType : "json",
        success : function(data)
        {

            if(data==true)
            {
                $("#addFavDish").hide();
                $("#delFavDish").show();
            }

        }
    });


}
function addDishFav(goods_id,type)
{


    var url  = $('#url').val();

    $.ajax({
        type : "POST",
        url : url+ '/ajax_addFav.php',
        data:{goods_id:goods_id,type:type},
        dataType : "json",
        success : function(data)
        {

            if(data==true)
            {
                $("#addFav_"+goods_id).hide();
                $("#delFav_"+goods_id).show();
            }

        }
    });

}

function addArticleFav(article_id,type)
{
    var mid = $('#mid').val();
    var url  = $('#url').val();
    if(mid==0||mid==1)
    {
        $.ajax({
            type : "GET",
            url : url+'/ajax_saveUrl.php',
            success : function(data)
            {

                location.href= url  + '/login.php';
            }
        });
        return;
    }
    $.ajax({
        type : "POST",
        url : url+ '/ajax_addFav.php',
        data:{article_id:article_id,type:type},
        dataType : "json",
        success : function(data)
        {
            if(data==true)
            {
                $("#addArticle").hide();
                $("#delArticle").show();
            }
        }
    });

}


function cancelArticleFav( article_id, type )
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_cancelFav.php',
        data:{article_id:article_id,type:type},
        dataType : "json",
        success : function(data)
        {
            if(data == true) {
                
                $("#delArticle").hide();
                $("#addArticle").show();


            }

        }
    });

}

function addShopFav(shop_id,type)
{

    var mid = $('#mid').val();
    var url  = $('#url').val();
    if(mid==0||mid==1)
    {
        $.ajax({
            type : "GET",
            url : url+'/ajax_saveUrl.php',
            success : function(data)
            {

                location.href= url  + '/login.php';
            }
        });
        return;
    }
    $.ajax({
        type : "POST",
        url : url+ '/ajax_addFav.php',
        data:{shop_id:shop_id,type:type},
        dataType : "json",
        success : function(data)
        {
            if(data==true)
            {
                $(".addFav").hide();
                $(".delFav").show();
            }
        }
    });
}

function cancelDishDetailFav(goods_id,type)
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_cancelFav.php',
        data:{goods_id:goods_id,type:type},
        dataType : "json",
        success : function(data)
        {

            if(data == true) {


                $("#delFavDish").hide();

                $("#addFavDish").show();

            }

        }
    });

}
function cancelDishFav( goods_id, type)
{

    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_cancelFav.php',
        data:{goods_id:goods_id,type:type},
        dataType : "json",
        success : function(data)
        {

            if(data == true) {


               $("#delFav_"+goods_id).hide();


                $("#addFav_"+goods_id).show();

            }

        }
    });

}
function cancelShopFav( shop_id, type )
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_cancelFav.php',
        data:{shop_id:shop_id,type:type},
        dataType : "json",
        success : function(data)
        {
            if(data == true) {
                $(".delFav").hide();
                $(".addFav").removeClass("hover");
                $(".addFav").show();

            }

        }
    });

}
function addCouponFav(coupon_id,type)
{

    var mid = $('#mid').val();
    var url  = $('#url').val();
    if(mid==0||mid==1)
    {
        $.ajax({
            type : "GET",
            url : url+'/ajax_saveUrl.php',
            success : function(data)
            {

                location.href= url  + '/login.php';
            }
        });
        return;
    }
    $.ajax({
        type : "POST",
        url : url+ '/ajax_addFav.php',
        data:{coupon_id:coupon_id,type:type},
        dataType : "json",
        success : function(data)
        {
            if(data==true)
            {
                $(".dishAddFavButton").hide();
                $(".dishAddFavButtonHover").show();
            }
        }
    });
}
function cancelCouponFav( coupon_id, type )
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_cancelFav.php',
        data:{coupon_id:coupon_id,type:type},
        dataType : "json",
        success : function(data)
        {
            if(data == true) {
                $(".dishAddFavButtonHover").hide();
                $(".dishAddFavButton").removeClass("hover");
                $(".dishAddFavButton").show();

            }

        }
    });

}