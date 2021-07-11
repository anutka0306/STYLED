<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name = "viewport" content = "width = device-width">
<meta name="description" content="">
<meta name="keywords" content="">

<title></title>
<script src="video/jquery.min.js"></script>



<link href="video/globalStyle.css" rel="stylesheet"
	type="text/css" />




<link rel="stylesheet" href="video/bootstrap.min.css" type="text/css" media="screen, projection" />
<link href="video/custom_animation_link.css" rel="stylesheet" type="text/css" />


<!-- for IE8 we use   respond.js to support @media queries library-->
<script src="video/respond.js" type="text/javascript"></script>

<!--
https://cdnjs.cloudflare.com/ajax/libs/flowplayer/7.0.4/skin/skin.css
https://cdnjs.cloudflare.com/ajax/libs/flowplayer/7.0.4/flowplayer.js
https://releases.flowplayer.org/hlsjs/flowplayer.hlsjs.min.js
-->
<link rel="stylesheet" href="video/skin.css">
<script src="video/flowplayer.js"></script>

<script src="video/flowplayer.hlsjs.min.js"></script>



<style> #maindiv_animation_link { background: url(video/bg-vv.png);}</style>
</head>
<body>
<?php
//https://interim.vehiclevisuals.com/STATIC_DATA_SERVER/_ipad/Global/video/RU/AutomaticTransmissionAVRU.ogv 
if($_GET['id'] == 'to')
	{
	$imya='Плановое техническое обслуживание';
	$url='EngineMechanicalServicingAVRU';
	}
	if($_GET['id'] == 'akpp')
	{
	$imya='Автоматическая коробка передач';
	$url='AutomaticTransmissionAVRU';
	}
	if($_GET['id'] == 'dvigatel')
	{
	$imya='Двигатель';
	$url='EngineAVRU';
	}
	
	if($_GET['id'] == 'disdvigatel')
	{
	$imya='Двигатель';
	$url='EngineAVRU';
	}
	if($_GET['id'] == 'podveska')
	{
	$imya='Подвеска';
	$url='SuspensionAVRU';
	}
	if($_GET['id'] == 'kondei')
	{
	$imya='Отопитель / кондиционер';
	$url='HVAC_AVRU';
	}
	if($_GET['id'] == 'electrika')
	{
	$imya='Электросистема';
	$url='ElectricalSystemAVRU';
	}
	if($_GET['id'] == 'kusov')
	{
	$imya='Кузов';
	$url='BodyAVRU';
	}
	if($_GET['id'] == 'oxlagdenie')
	{
	$imya='Система охлаждения';
	$url='CoolingSystemAVRU';
	}
	if($_GET['id'] == 'toplivo')
	{
	$imya='Топливная система';
	$url='FuelSystemAVRU';
	}
	if($_GET['id'] == 'rulj')
	{
	$imya='Рулевое управление';
	$url='SteeringSystemAVRU';
	}
	if($_GET['id'] == 'tormoz')
	{
	$imya='Тормозная система';
	$url='BrakeSystem_AVRU';
	}
	if($_GET['id'] == 'benso_nasos')
	{
	$imya='Топливный насос';
	$url='FuelSystemAVRU';
	}
	if($_GET['id'] == 'vipusk')
	{
	$imya='Система выпуска';
	$url='ExhaustSystemAVRU';
	}
	if($_GET['id'] == 'kpp')
	{
	$imya='Механическая коробка передач';
	$url='ManualDrivetrainAVRU';
	}
	
	if($_GET['id'] == 'sxod_rasval')
	{
	$imya='Углы установки колес (схождение)';
	$url='AlignmentAVRU';
	}
	if($_GET['id'] == 'zad_reduktor')
	{
	$imya='Задний редуктор';
	$url='RearDifferentialAVRU';
	}
	if($_GET['id'] == 'sceplenie')
	{
	$imya='Сцепление';
	$url='ClutchAVRU';
	}
	
	?>
	<div id="maindiv_animation_link"></div>
	<form id="frm_animation_link" method="post">
		<input type="hidden" id="animationid" name="animationid">
		<img src="video/ScheduledMaintenance.gif" class="img-responsive cravler-img">
		<div class="container">
			<div class="row margin-top">
				<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6">
					<div class="card">
						<div class="card-header text-center custom-text-muted">
							<h4><?=$imya?></h4>
						</div>
						<div class="card-block">

																		
											<video class="" id="loopvideo123" preload controls autoplay>
												<source src="video/<?=$url ?>.mp4" mimetype=video/mp4 ></source>
												<source src="video/<?=$url ?>.webm" mimetype=video/webm ></source>
												<source src="video/<?=$url ?>.ogv" mimetype=video/ogg ></source>
											</video>
											
															</div>
						<div class="card-footer text-center custom-text-muted">
							<div class="row">
								<div class="col-md-12">
									<div class="copytight text-right">&copy;Vehicle Visuals 2017</div>
																						<h3>Автосервис</h3>
													<h5>Москва</h5>
													<h5>.</h5>
																		</div>
							</div>
						</div>
					</div>
				</div>
									</div>
							</div>
		</div> 

</body>
</html>
