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







    $(".ybg3 .yh .ul1 .li1").hover(
        function () {
            $(this).find("img").stop().animate({top:'-30px'});
            $(this).find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li1").find("img").css({opacity:'1'});

        },
        function () {
            $(this).find("img").stop().animate({top:'0px'});
            $(this).find("img").css({opacity:'0.6'});
            $(".ybg3 .yh .ul2 .li1").find("img").css({opacity:'0.8'});
        }
    );

    $(".ybg3 .yh .ul1 .li1").hover(
        function () {
            $(this).find("img").stop().animate({top:'-30px'});
            $(this).find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li1").find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li1").find("i").css({color:'#000'});

        },
        function () {
            $(this).find("img").stop().animate({top:'0px'});
            $(this).find("img").css({opacity:'0.6'});
            $(".ybg3 .yh .ul2 .li1").find("img").css({opacity:'0.8'});
            $(".ybg3 .yh .ul2 .li1").find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul1 .li2").hover(
        function () {
            $(this).find("img").stop().animate({top:'-30px'});
            $(this).find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li2").find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li2").find("i").css({color:'#000'});

        },
        function () {
            $(this).find("img").stop().animate({top:'0px'});
            $(this).find("img").css({opacity:'0.6'});
            $(".ybg3 .yh .ul2 .li2").find("img").css({opacity:'0.8'});
            $(".ybg3 .yh .ul2 .li2").find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul1 .li3").hover(
        function () {
            $(this).find("img").stop().animate({top:'-30px'});
            $(this).find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li3").find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li3").find("i").css({color:'#000'});

        },
        function () {
            $(this).find("img").stop().animate({top:'0px'});
            $(this).find("img").css({opacity:'0.6'});
            $(".ybg3 .yh .ul2 .li3").find("img").css({opacity:'0.8'});
            $(".ybg3 .yh .ul2 .li3").find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul1 .li4").hover(
        function () {
            $(this).find("img").stop().animate({top:'-30px'});
            $(this).find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li4").find("img").css({opacity:'1'});
            $(".ybg3 .yh .ul2 .li4").find("i").css({color:'#000'});

        },
        function () {
            $(this).find("img").stop().animate({top:'0px'});
            $(this).find("img").css({opacity:'0.6'});
            $(".ybg3 .yh .ul2 .li4").find("img").css({opacity:'0.8'});
            $(".ybg3 .yh .ul2 .li4").find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul2 .li1").hover(
        function () {
            $(".ybg3 .yh .ul1 .li1").find("img").stop().animate({top:'-30px'});
            $(".ybg3 .yh .ul1 .li1").find("img").css({opacity:'1'});
            $(this).find("img").css({opacity:'1'});
            $(this).find("i").css({color:'#000'});

        },
        function () {
            $(".ybg3 .yh .ul1 .li1").find("img").stop().animate({top:'0px'});
            $(".ybg3 .yh .ul1 .li1").find("img").css({opacity:'0.6'});
            $(this).find("img").css({opacity:'0.8'});
            $(this).find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul2 .li2").hover(
        function () {
            $(".ybg3 .yh .ul1 .li2").find("img").stop().animate({top:'-30px'});
            $(".ybg3 .yh .ul1 .li2").find("img").css({opacity:'1'});
            $(this).find("img").css({opacity:'1'});
            $(this).find("i").css({color:'#000'});

        },
        function () {
            $(".ybg3 .yh .ul1 .li2").find("img").stop().animate({top:'0px'});
            $(".ybg3 .yh .ul1 .li2").find("img").css({opacity:'0.6'});
            $(this).find("img").css({opacity:'0.8'});
            $(this).find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul2 .li3").hover(
        function () {
            $(".ybg3 .yh .ul1 .li3").find("img").stop().animate({top:'-30px'});
            $(".ybg3 .yh .ul1 .li3").find("img").css({opacity:'1'});
            $(this).find("img").css({opacity:'1'});
            $(this).find("i").css({color:'#000'});

        },
        function () {
            $(".ybg3 .yh .ul1 .li3").find("img").stop().animate({top:'0px'});
            $(".ybg3 .yh .ul1 .li3").find("img").css({opacity:'0.6'});
            $(this).find("img").css({opacity:'0.8'});
            $(this).find("i").css({color:'#555'});
        }
    );

    $(".ybg3 .yh .ul2 .li4").hover(
        function () {
            $(".ybg3 .yh .ul1 .li4").find("img").stop().animate({top:'-30px'});
            $(".ybg3 .yh .ul1 .li4").find("img").css({opacity:'1'});
            $(this).find("img").css({opacity:'1'});
            $(this).find("i").css({color:'#000'});

        },
        function () {
            $(".ybg3 .yh .ul1 .li4").find("img").stop().animate({top:'0px'});
            $(".ybg3 .yh .ul1 .li4").find("img").css({opacity:'0.6'});
            $(this).find("img").css({opacity:'0.8'});
            $(this).find("i").css({color:'#555'});
        }
    );



    $(".shlb").click(function() {
        $(".map .md2").stop().animate({left: '207px'});
        if (parseInt( $(".map .md2").css("left")) == 0)
        {
            $(".map .md2").stop().animate({left: '207px'});
        }
        else
        {
            $(".map .md2").stop().animate({left: '0px'});
        }
    });


    $(".map .ml ul li").each(function(){
        var i=$(this).index()+1;
        $(this).click(function(){$(".map .md2").hide(); $(".map .md2"+i+"").show();});
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


});





$(function(){

    $(".ymap .map .scroll2").scrollable({size:1,items:".ymap .map .scroll2",vertical:true,loop:true,clickable:false});

    $(".cpdp .cp .scroll").scrollable({size:1,items:".cpdp .cp .scroll",loop:true}).navigator({navi:".cpdp_u1",naviItem:"li",activeClass:"focus"});



});

$(function(){
    $('.menu dl').hover(function(){$(this).addClass('hover')},function(){$('.menu dl').removeClass('hover')});
    $('.banner .scroll ul li').width($(document.body).outerWidth(true));
    $('.banner .next').hover(function(){$(this).find('span.png').css({"display":"block"})},function(){$(this).find('span.png').css({"display":"none"})});
    $('.banner .prev').hover(function(){
        $(this).find('span.png').css({"display":"block"})
        if(e){
            $(this).find('span.png').css({"display":"block"})
        }
    },function(){$(this).find('span.png').css({"display":"none"})});

    var scrollable = $('.banner .scroll').scrollable({size:1,items:'.banner .scroll ul',loop:true}).autoscroll({autoplay: true,autopause:false,interval:60000,steps:1}).navigator({navi:".num",naviItem:"li",activeClass:"hover"});
    var _m = $('.banner .scroll ul li').length;
    scrollable.scrollable().onBeforeSeek(function(){
        //alert(this.getIndex());
        _t = this.getPageIndex()+2;
        if( _t == $('.banner .scroll ul li').length){
            $('.banner .next span').html("<i>"+ 1 +"</i><em>"+_m+"</em>");
            //$('.banner .prev span').html("<i>"+ [_t - 1] +"</i><em>"+_m+"</em>")

        }else if( _t == $('.banner .scroll ul li').length +1 ){
            $('.banner .next span').html("<i>"+ 2 +"</i><em>"+_m+"</em>");
            //$('.banner .prev span').html("<i>"+ 1 +"</i><em>"+_m+"</em>")
        }else{
            //$('.banner .prev span').html("<i>"+ [_t -1] +"</i><em>"+_m+"</em>")
            $('.banner .next span').html("<i>"+ [_t +1] +"</i><em>"+_m+"</em>")
        }
    });
    $('.banner .next span').html("<i>"+ 2 +"</i><em>"+_m+"</em>")
    //$('.banner .prev span').html("<i>"+ _m +"</i><em>"+_m+"</em>")
});

