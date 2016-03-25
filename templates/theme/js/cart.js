function refreshCart()
{
    var url  = $('#url').val();
    var  sid = $('#sid').val();
    $.ajax({
        type : "POST",
        url : url+'/smallcart.php',
        data:{sid:sid},
        dataType : "html",
        success : function(data)
        {
            $("#cartlist").html(data);

        }
    });



}

function addonedish(goods_id,shop_id,num)
{
    addToCart(goods_id,shop_id,num);

}
function removeonedish(goods_id,shop_id,num)
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_downCart.php',
        data:{goods_id:goods_id,shop_id:shop_id,num:num},
        dataType : "json",
        success : function(data)
        {
            refreshCart();

        }
    });


}
function checkDish()
{

   // alert(this.class());
  //return false;

}
function checkGoodsCart()
{
    var mid = $('#mid').val();
    var url  = $('#url').val();
    var goodsNum =  $('#goodsNum').val();
    if(goodsNum==0)
    {
        return false;
    }
    if(mid==0)
    {
        $.ajax({
            type : "GET",
            url : url+'/ajax_saveUrl.php',
            success : function(data)
            {
                location.href= url  + '/login.php';
            }
        });
     }


}
function checkCart()
{
    var selectedGoods = '';
    var unselectedGoods = '';
    var table = document.getElementById('cartTable'); // 
    var tr = table.children[1].rows; //
    for (var i = 0, len = tr.length; i < len; i++)
    {
        if (tr[i].getElementsByTagName('input')[0].checked)
        {
            selectedGoods +=tr[i].id+'_'+tr[i].getElementsByTagName('input')[1].value+',';

        }
        else
        {

            unselectedGoods += tr[i].id+'_'+tr[i].getElementsByTagName('input')[1].value+',';


        }
    }
    $('#selectedGoods').val(selectedGoods);
    $('#unselectedGoods').val(unselectedGoods);
    if(selectedGoods=='')
    {
        return false;
    }

    //alert(selectedGoods);
    //var url  = $('#url').val();
    //$.ajax({
    //    type : "POST",
    //    url : url+ '/confirmorder.php',
    //    data:{selectedGoods:selectedGoods,unselectedGoods:unselectedGoods},
    //    dataType : "json",
    //    success : function(data)
    //    {
    //       alert(data);
    //
    //    }
    //});






}
function addToCart( goods_id, shop_id,num)
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_addCart.php',
        data:{goods_id:goods_id,shop_id:shop_id,num:num},
        dataType : "json",
        success : function(data)
        {
            refreshCart();

        }
    });

}

function clearCart()
{
    var url  = $('#url').val();
    $.ajax({
        type : "POST",
        url : url+ '/ajax_clearCart.php',
        data:{},
        dataType : "json",
        success : function(data)
        {
            refreshCart();

        }
    });

}