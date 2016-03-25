<?php
	require_once "jssdk.php";
	
	$jssdk = new JSSDK("wx92cda0e793456b59", "9d3b1d60056b703386cbcbd58f86ffa1");
	$signPackage = $jssdk->GetSignPackage();

	//echo json_encode($signPackage);
?>

<html>
<head>
	<script src="/member/resources/js/jquery.1.8.3.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script>
		$(function(){
			var path = location.href.split('#')[0];
			//var param = path.split("?")[1];
			
			//$.post("/share/getShareSignPackage.php?"+param,{},function(data){
				//var signPackage = eval('(' + data + ')');
				
				//alert(signPackage.timestamp);
				//alert(signPackage.nonceStr);
				
				wx.config({
					debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					appId: '<?php echo $signPackage["appId"];?>',
					timestamp: <?php echo $signPackage["timestamp"];?>,
					nonceStr: '<?php echo $signPackage["nonceStr"];?>',
					signature: '<?php echo $signPackage["signature"];?>',
					jsApiList: [
						'onMenuShareTimeline',
						'onMenuShareAppMessage'
					] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
				});
				
				wx.ready(function (){
					path = location.href.split('#')[0];
					//param = path.split("?")[1];
					alert(path);
				});
				
				wx.onMenuShareAppMessage({
					title: '互联网之子',
					desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
					link: 'http://movie.douban.com/subject/25785114/',
					imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
					trigger: function (res) {
						alert('用户点击发送给朋友');
					},
					success: function (res) {
						alert('已分享');
					},
					cancel: function (res) {
						alert('已取消');
					},
					fail: function (res) {
						//alert(signPackage.timestamp);
						//alert(signPackage.nonceStr);
						//alert(signPackage.signature);
						alert(JSON.stringify(res));
					}
				});
	
				wx.onMenuShareTimeline({
					title: '互联网之子',
					link: 'http://movie.douban.com/subject/25785114/',
					imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
					trigger: function (res) {
						alert('用户点击分享到朋友圈');
					},
					success: function (res) {
						alert('已分享');
					},
					cancel: function (res) {
						alert('已取消');
					},
					fail: function (res) {
						alert(JSON.stringify(res));
					}
				});
			//});
		});
	</script>
</head>
<body>
	
</body>
</html>