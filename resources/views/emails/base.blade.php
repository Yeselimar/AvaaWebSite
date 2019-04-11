<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<style type="text/css">
		
		*
		{
			box-sizing: border-box !important;
		}
		.cabecera
		{
			border-bottom: 1px solid #003865;
			background-color: #fff;
			padding-bottom: 15px;
			padding-top: 15px;
			text-align: center;
		}
		.pie
		{
			background-color: #003865;
			color: #fff;
			padding-top: 15px;
			padding-bottom: 15px;
			text-align: center;
		}
		.link-avaa
		{
			color: #fff !important;
			text-decoration: none;
		}
	</style>
	
</head>
<body style="border: 1px solid #003865;background:#fff";>
	<div class="cabecera">
		<a target="_blank" href="http://avaa.org">
			<img src="http://www.revistabusinessvenezuela.com/webv/wp-content/uploads/2017/11/LOGO-AVAA-75-PNG-completo-1-1024x276.png" style="width: 300px;height: auto;" align="center" alt="AVAA - Sisbeca">
		</a>
		
	</div>

	@yield('content')

	<br>
	
	<div class="pie">
		<p style="color:#fff !important">AVAA | (+58)0212-235.78.21 |  <a href="" style="color:#fff">comunicaciones@avaa.org</a> </p>
		<hr style="width: 15%;" align="center">
		<a class="link-avaa" target="_blank" href="http://avaa.org/">Inicio</a> |
		<a class="link-avaa" target="_blank" href="http://avaa.org/nosotros">Nosotros</a> |
		<a class="link-avaa" target="_blank" href="http://avaa.org/programas">Programas</a> |
		<a class="link-avaa" target="_blank" href="http://avaa.org/membresias">Membresías</a> |
		<a class="link-avaa" target="_blank" href="http://avaa.org/contacto">Contacto</a>
	</div>
	
</body>
</html>
