<center>
	<video id="my-video" class="video-js" controls preload="auto" width="640" height="264" data-setup="{}">
		<source src="video/v1.mp4" type="video/mp4">
		Техникалық жағдайға байланысты видео қосылмайды. Мұғалімге жолығыңыз.
	</video>
	<h4>Video Name</h4>
</center>
<script type="text/javascript">
$(document).ready(function(){
	$('script[src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"]').remove();
    $('<script>').attr('src', 'http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js').appendTo('body');
    $('script[src="http://vjs.zencdn.net/6.1.0/video.js"]').remove();
    $('<script>').attr('src', 'http://vjs.zencdn.net/6.1.0/video.js').appendTo('body');
});
</script>