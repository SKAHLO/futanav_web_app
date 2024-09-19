<!DOCTYPE html>
<html>
<head>
	<title>FUTA NAVIGATOR</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" >
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<link rel="stylesheet" type="text/css" href="css.css">
	
	<style type="text/css">
		#map {
		 height: 100%;
		}
      
      </style>
	  <script type="text/javascript">
		const center = { lng: 5.135335922241212, lat: 7.3003865540735955};
		const buildings = {
			<?php
			foreach ($buildings as $key => $value) {
				echo "{$value['id']}:{name: '{$value['building_name']}', desc:'{$value['building_desc']}', coord: {lat:{$value['lat']}, lng:{$value['lng']}}, img: '{$value['building_image']}'},
				\n";
			};
			?>
		};
		const offices = [
			<?php
			foreach ($offices as $key => $value) {
				echo "{name: '{$value['office_name']}', desc:'{$value['office_desc']}', coord: {lat:{$value['lat']}, lng:{$value['lng']}}, building: {$value['building']}, guide: '{$value['guide']}', img: '{$value['office_image']}'},
				\n";
			}
			?>
		];
		
	  </script>
</head>
<body>
	<div class="main">
		<div id="top" class="top">
		</div>
		<div id="content" class="content">
			<div id="content1" class="inner" >
				<div id="selector" class="selector">
					
				</div>
				<div id="home-search" class="home-search">
					<input type="text" oninput="homeSearch()" id="home-search-text" name="home_search_text" placeholder="find an office"/>
					<button type="submit"><img src="search_icon.png"></button>
				</div>
				<div id="home-search-container" class="home-search" style="display:none">
					
				</div>
				<div id="markers-on-the-map" onclick="closeHomeSearch()">
					<div id="map"></div>
					<div id="panel"></div>
				</div>
				<div id="guide">
					<div id="navtitle">Direction to .... from your location.</div>
					<div id="nav">
						<div><img id="prevbutton" style="opacity:0.5;" src="prev_active.png" onclick="prevStep()"/></div>
						<span id="distancetext"> distance text </span>
						<div><img id="nextbutton" src="next_active.png" onclick="nextStep()"/></div>
					</div>
					<div id="navhtml">
						<span>Direction</span>
					</div>
				</div>
				<div id="indoornav">
					<div id="nav2">
						<span><b>Direction guide to:</b></span>
						<span id="personname"> person </span>
						<div onclick="this.parentNode.parentNode.style.display='none'" style="display: flex;justify-content: center;align-items: center;width:40px;height:40px; background:purple; color:white; border-radius:5px;"> x </div>
					</div>
					<div id="indoornavhtml">
						<span>Direction</span>
					</div>
					<div id="indoorimgholder">
						
					</div>
				</div>
			</div>
			<div id="content2" class="inner inner2"style="display:none">
				<div id="top-search" class="top-search">
					<input type="text" id="building-search-text" name="building_text" placeholder="find a building" oninput="building_search()"/>
					<button type="submit"><img src="search_icon.png"></button>
				</div>
				<div id="building-list" class="list-container">
					
				</div>
			</div>
			<div id="content3" class="inner inner2"style="display:none">
				<div id="top-search" class="top-search">
					<input type="text" id="lecturer-search-text" name="building_text" placeholder="find an office" oninput="office_search()"/>
					<button type="submit"><img src="search_icon.png"></button>
				</div>
				<div id="lecturer-list" class="list-container">
					
				</div>
			</div>
			<div id="content4" class="inner inner2"style="display:none">
				<div id="e">
					<p>Events will be displayed here</p>
				</div>
			</div><div id="content5" class="inner inner2"style="display:none">
				<div id="i">
					<p>Tips, Guides, Information about the app, terms and copyright etc here</p>
				</div>
			</div>
		</div>
		<div id="footer" class="footer" onclick="closeHomeSearch()">
			<div class="footer-menu">
				<div class="item" onclick="showHome()">
					<img src="home_icon.png">
				</div>
				<div class="item" onclick="showBuildings()">
					<img src="buildings_icon.png">
				</div>
				<div class="item" onclick="showLecturers()">
					<img src="lecturers_icon.png">
				</div>
				<div class="item" onclick="showEvents()">
					<img src="event_icon.png">
				</div>
				<div class="item" onclick="showInfo()">
					<img src="info_icon.png">
				</div>
			</div>
		</div>
	</div>
</body>
<script
      src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyEraOnfGcUO2FWWYcoP7VgjEpzdmBjxZTy&callback=initMap&v=weekly"
      defer
    ></script>
<script src='./index.js'></script>
</html>
