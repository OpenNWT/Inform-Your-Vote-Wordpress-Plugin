<?php wp_head(); ?>
<script type="text/javascript">
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
</script>
<body>
	<h1>Please select a theme</h1>
	<div class="versions">
		<p>There are two themes in the ElectionData plugin.
				<br>
				<span>Election Data - V1</span></li>
				<br>
				<span>Election Data - V2</span></li>
				<br>
			Click on the theme you want to choose.</p>
		<div class="column" id="column-1">
			<p class="title">Electon Data - V1</p>
			<img src="/wp-content/themes/ElectionData/images/theme-v1.jpg" alt="theme-v1" />
		</div>
		<div class="column" id="column-2">
			<p class="title">Electon Data - V2</p>
			<img src="/wp-content/themes/ElectionData/images/theme-v2.jpg" alt="theme-v2" />
		</div>
	</div>

</body>

</html>
