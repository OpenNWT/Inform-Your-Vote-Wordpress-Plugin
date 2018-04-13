<?php

	/**
	* Activates the theme basen on the selection
	*/
	function act($theme){
		switch_theme($theme);
		echo "Theme {$theme} activated.";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome using the Election Data Plugin</title>
	<style>
		h1,p {font-family: 'Noto Sans', sans-serif; text-align: center; margin-top: 30px;}
		p {font-size: 24px;}
		.versions {margin: 10px auto; width: 1400px; height: 900px;}
		.column {float: left; width: 600px; margin: 10px 30px; cursor: pointer;}
		.title {font-size: 22px; margin-bottom: 30px; font-weight: bold;}
		span {font-weight: bold;}
		img {height: 700px; width: 600px;}
		body {background-color: #f0f5f5;}
	</style>

</head>
<body>
	<h1>Please select a theme</h1>
	<div class="versions">
		<p>There are two themes in the ElectionData plugin.
			<ul>
				<li><span>Election Data - V1</span></li>
				<li><span>Election Data - V2</span></li>
			</ul>
			Click on the theme you want to choose.</p>
		<div class="column" id="column-1" onclick="show_V1();">
			<p class="title">Electon Data - V1</p>
			<img src="/wp-content/themes/ElectionData/images/theme-v1.jpg" alt="theme-v1" />
		</div>
		<div class="column" id="column-2" onclick="show_V2();">
			<p class="title">Electon Data - V2</p>
			<img src="/wp-content/themes/ElectionData/images/theme-v2.jpg" alt="theme-v2" />
		</div>
	</div>

</body>
<script>
	function show_V1(){
		var x = "<?php act("ElectionData/ElectionData-V1"); ?>";
		alert(x);
		location.reload();
		return false;
	}

	function show_V2(){
		var x = "<?php act("ElectionData/ElectionData-V2"); ?>";
		alert(x);
		location.reload();
		return false;
	}
</script>
</html>
