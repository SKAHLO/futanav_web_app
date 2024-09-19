<?php
session_start();
require "ddb.php";

$offices = getAllOffices($pdo);
$buildings = getAllBuildings($pdo);
require "app.php";
/*{name: "SEN HOD ( PROF KOLAWOLE G. AKINTOLA)", desc:"Lecturer Detail", coord: {}, building: 3, guide: "7.27m", img: "/img1.jpg"},
			{name: "Prof Iwashokun", desc:"Lecturer Detail", coord: {}, building: 3, guide: "7.27m WEST  and 13. 79 SOUTH", img: "/img16.jpg"},
			{name: "School Officer2", desc:"Lecturer Detail", coord: {}, building: 3, guide: "walk 4.67m West To the Stairs, Climb 4.22m East , Another’s stairs of 4.43m to the West, 19.28m to the North (345°)", img: "/img5.jpg"},
			{name: "School Officer", desc:"Lecturer Detail", coord: {}, building: 3, guide: "walk 4.67m West To the Stairs, Climb 4.22m East , Another’s stairs of 4.43m to the West, 3.84m to the North (345°)", img: "/img5.jpg"},
			{name: "Prof S .A Oluwadare", desc:"Lecturer Detail", coord: {}, building: 3, guide: "walk 4.67m West To the Stairs, Climb 4.22m East , Another’s stairs of 4.43m to the West, 36.05 to the North (345°)", img: "/img4.jpg"},
			{name: "Prof Olufemi Akinyede", desc:"Lecturer Detail", coord: {}, building: 3, guide: "walk 4.67m West To the Stairs, Climb 4.22m East , Another’s stairs of 4.43m to the West, Walk 4.06m South (174°) , Walk down the stairs for 2.77m , Continue for 9.50m South (174°)", img: "/img16.jpg"},
			{name: "Dr. Olutola Agbelusi", desc:"Lecturer Detail", coord: {}, building: 3, guide: "walk 4.67m West To the Stairs, Climb 4.22m East , Another’s stairs of 4.43m to the West, Walk 4.06m South (174°) , Walk down the stairs for 2.77m , Continue for 24.36 South (174°)", img: "/img16.jpg"},
			{name: "Dr. Olutola Agbelusi (2)", desc:"Lecturer Detail", coord: {}, building: 3, guide: "walk 4.67m West To the Stairs, Climb 4.22m East , Another’s stairs of 4.43m to the West, Walk 4.06m South (174°) , Walk down the stairs for 2.77m , Continue for 27.47 South (174°)", img: "/img9.jpg"}

      3: {name: "SOC", desc:"School of Computing Main Admin Building", coord: {lat: 7.304635297956574, lng: 5.13238549232483}},
			30: {name: "SEET", desc:"School of Computing Main Admin Building", coord: {lat: 7.303012426223441, lng: 5.1358938217163095}},
			35: {name: "SAAT", desc:"School of Computing Main Admin Building", coord: {lat: 7.3015491761678035, lng: 5.1353466510772705}},
			34: { name: "SOS", desc:"School of Computing Main Admin Building", coord: {lat: 7.301980170318051, lng: 5.134086012840272}},
			36: { name: "Adamu Building", desc:"School of Computing Main Admin Building", coord: {lat: 7.30135230215849, lng: 5.1346439123153695}}
*/
