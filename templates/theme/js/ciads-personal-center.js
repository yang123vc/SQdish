/**
 * Created by Administrator on 2015/11/4.
 */
$(document).ready(function(){
    $(".cp .next").hover(
        function () {$(this).find("img").css("opacity","0.5");},
        function () {$(this).find("img").css("opacity","1");}
    );
    $(".cp .prev").hover(
        function () {$(this).find("img").css("opacity","0.5");},
        function () {$(this).find("img").css("opacity","1");}
    );

    $(".city .xlk2").hover(
        function () {$(this).find(".yuyan").show("");},
        function () {$(this).find(".yuyan").hide("");}
    );
    $(".city .xlk1").hover(
        function () {$(this).find(".chengshi").show("");},
        function () {$(this).find(".chengshi").hide("");}
    );


    $(".map .ml ul li a").click(function () {$(".map").find(".prev").show("");});
    $(".map .ml ul li a").click(function () {$(".map").find(".next").show("");});



    $(".map .ml ul li").click(function(){
        $(this).siblings().find("a").removeClass("hover");
        $(this).find("a").addClass("hover");

    });

    $(".md1 li a").click(function(){

        $(this).parent().siblings().find("a").removeClass("hover");
        $(this).addClass("hover");

    });







    




    $(".nav .ssk").each(function(){
        $(this).data("val",$(this).val());
    });

    $(".nav .ssk").focus(function(){
        var val=$(this).val();
        if( val==$(this).data("val") )
        {
            $(this).val("");
        }
    });

    $(".nav .ssk").blur(function(){
        var val=$(this).val();
        if( val=="" )
        {
            $(this).val($(this).data("val"));
        }
    });
    $(".cpdp  .cpdp_u1 .li1").click(function(){
        $(".cpdp  .cpdp_u1 li").removeClass('hover');
        $(this).addClass('hover');
        $(".cpdp .cp").hide();
        $(".cpdp .cp1").show();
    });

    $(".cpdp  .cpdp_u1 .li2").click(function(){
        $(".cpdp  .cpdp_u1 li").removeClass('hover');
        $(this).addClass('hover');
        $(".cpdp .cp").hide();
        $(".cpdp .cp2").show();
    });


});











