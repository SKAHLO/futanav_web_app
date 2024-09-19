const successCallback = (position) => {
  //console.log(position);
  gps=true;
  gpspoint = { lat: position.coords.latitude, lng: position.coords.longitude};
  if (pp && pp.getProjection())  {
    pp.draw();
  }
};
let errors;
const errorCallback = (error) => {
  if (error.code == 1) {
    gps = false;
    alert("Location permission not granted, it is required.\nEnable your location and allow access.\nThen reload.");
  } else {
    if(first){
      first = false;
      alert("Location error, check your network");
    }
  }
};
first = true;
setInterval(() => {
  if(gps)navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
}, 3000);


function closeHomeSearch(){
  homeSearchCon.innerHTML = "";
  homeSearchCon.style.display = "none";
  //alert();
}

let gps = true;
let gpspoint = center;
let directionsRenderer;
let directionsService;
let map;
let guide = document.getElementById("guide");
let route;
let directionstext;
let navHtml = document.getElementById("navhtml");
let navTitle = document.getElementById("navtitle");
let distanceText =  document.getElementById("distancetext");
let indoorNavHtml = document.getElementById("indoornavhtml");
let indoorNav = document.getElementById("indoornav");
let indoorNavName = document.getElementById("personname");
let indoorImgHolder = document.getElementById("indoorimgholder");
let steps;


const home = document.getElementById("content1");
const buildingns = document.getElementById("content2");
const lecturerns = document.getElementById("content3");
const eventns = document.getElementById("content4");
const info = document.getElementById("content5");
const buildingcon = document.getElementById("building-list");
const lecturercon = document.getElementById("lecturer-list");
const eventcon = document.getElementById("event-list");

const tabs = [home, buildingns, lecturerns, eventns, info];

function showHome(){
  for (let tab of tabs) {
    tab.style.display = "none";
  }
  home.style.display = "block";
}
function showBuildings(){
  
  for (let tab of tabs) {
    tab.style.display = "none";
  }
  buildingns.style.display = "block";
}
function showLecturers(){
  for (let tab of tabs) {
    tab.style.display = "none";
  }
  lecturerns.style.display = "block";
}

function showEvents(){
  for (let tab of tabs) {
    tab.style.display = "none";
  }
  eventns.style.display = "block";
}

function showInfo(){
  for (let tab of tabs) {
    tab.style.display = "none";
  }
  info.style.display = "block";
}
let i = 0;
Object.keys(buildings).forEach(key => {
    building = buildings[key];
  elem = document.createElement('div');
  elem.innerHTML = `
        <div class="building-list-item" onclick="view(${key})">
          <img src="building_icon.png">
          <div class="div">
            <h3>${building.name}</h3>
            <p>${building.desc} </p>
          </div>
          <img src="list_select.png" style="width: 7px;height: 30px; object-fit: fill; padding-right: 10px;">
        </div>

    `;
  i = i+1;
  buildingcon.appendChild(elem);
});


i = 0;
for (let office of offices) {
    elem = document.createElement('div');
  elem.innerHTML = `
        <div class="building-list-item" onclick="viewLecturer(${i})">
          <img src="person_icon.png">
          <div class="div">
            <h3>${office.name}</h3>
            <p>${office.desc} </p>
          </div>
          <img src="list_select.png" style="width: 7px;height: 30px; object-fit: fill; padding-right: 10px;">
        </div>

    `;
  i = i+1;
  lecturercon.appendChild(elem);
}

function view(indx, home){
  if (!gps) {
    alert("Your current location cannot be determined, please refresh page!");
    return;
  }
  let building = buildings[indx];
  if(home) homeSearchCon.style.display = "none";
  guide.style.display = "none";
  navTitle.innerText = "Directions to " +building.name+ " from your location";
  calcRoute(gpspoint, building.coord);
}

function viewLecturer(indx, home){
  if (!gps) {
    alert("Your current location cannot be determined, please refresh page!");
    return;
  }
  guide.style.display = "none";
  indoornaving = true;
  let lecturer = offices[indx];
  let building = buildings[lecturer.building];
  setIndoorNav(lecturer);
  if(home) homeSearchCon.style.display = "none";
  navTitle.innerText = "Directions to " +lecturer.name+ " from your location";
  calcRoute(gpspoint, building.coord);
}

function setIndoorNav(a){
  indoorNavHtml.innerHTML = a.guide;
  indoorNavName.innerHTML = a.name;
  indoorImgHolder.innerHTML = "<img src='images/"+ a.img + "'>";
}
homeSearchCon = document.getElementById('home-search-container');
let home_search_input = document.getElementById('home-search-text');
function homeSearch(){
  let search =home_search_input.value;
  //alert(search);
  homeSearchCon.innerHTML = "";
  if(search==""){ homeSearchCon.style.display = "none"; return}
  homeSearchCon.style.display = "flex";
  Object.keys(buildings).forEach(key => {
    building = buildings[key];
    if(building.name.toLowerCase().includes(search.toLowerCase())){
      elem = document.createElement('div');
      elem.innerHTML = `
        <div class="building-list-item" onclick="view(${key}, true)">
          <div class="div">
            <h3>${building.name}</h3>
            <p>${building.desc} </p>
          </div>
          <img src="list_select.png" style="width: 7px;height: 30px; object-fit: fill; padding-right: 10px;">
        </div>

      `;
      homeSearchCon.appendChild(elem);
    };
  });
  let i=-1;
  for (let office of offices) {
    i = i+1;
    if(!office.name.toLowerCase().includes(search.toLowerCase())){ continue; }
    elem = document.createElement('div');
    elem.innerHTML = `
          <div class="building-list-item" onclick="viewLecturer(${i}, true)">
            <img src="person_icon.png" style="flex-basis: 17%;width: 12%;">
            <div class="div">
              <h3>${office.name}</h3>
              <p>${office.desc} </p>
            </div>
            <img src="list_select.png" style="width: 7px;height: 30px; object-fit: fill; padding-right: 10px;">
          </div>

      `;
    homeSearchCon.appendChild(elem);
  }
}

let building_search_input = document.getElementById('building-search-text');
function building_search(){
  let search = building_search_input.value;
  //alert(search);
  buildingcon.innerHTML = "";
  Object.keys(buildings).forEach(key => {
    building = buildings[key];
    if(building.name.toLowerCase().includes(search.toLowerCase())){
      elem = document.createElement('div');
      elem.innerHTML = `
        <div class="building-list-item" onclick="view(${key})">
          <img src="building_icon.png">
          <div class="div">
            <h3>${building.name}</h3>
            <p>${building.desc} </p>
          </div>
          <img src="list_select.png" style="width: 7px;height: 30px; object-fit: fill; padding-right: 10px;">
        </div>

      `;
      buildingcon.appendChild(elem);
    };
  });
}

function hidePanel() {
  
}


let office_search_input = document.getElementById('lecturer-search-text');
function office_search(){
  let search = office_search_input.value;
  //alert(search);
  lecturercon.innerHTML = "";
  for (let office of offices) {
    if(!office.name.toLowerCase().includes(search.toLowerCase())){ continue; }
    elem = document.createElement('div');
    elem.innerHTML = `
          <div class="building-list-item" onclick="viewLecturer(${i})">
            <img src="building_icon.png">
            <div class="div">
              <h3>${office.name}</h3>
              <p>${office.desc} </p>
            </div>
            <img src="list_select.png" style="width: 7px;height: 30px; object-fit: fill; padding-right: 10px;">
          </div>

      `;
    i = i+1;
    lecturercon.appendChild(elem);
  }
}

class CoordMapType {
  tileSize;
  alt = null;
  maxZoom = 17;
  minZoom = 0;
  name = null;
  projection = null;
  radius = 6378137;
  constructor(tileSize) {
    this.tileSize = tileSize;
  }
  getTile(coord, zoom, ownerDocument) {
    const div = ownerDocument.createElement("div");
    div.innerHTML = String(coord);
    div.style.width = this.tileSize.width + "px";
    div.style.height = this.tileSize.height + "px";
    div.style.fontSize = "10";
    div.style.borderStyle = "solid";
    div.style.borderWidth = "1px";
    div.style.borderColor = "#AAAAAA";
    div.innerHTML += "<img src='person_icon.png' width='100%'>"
    return div;
  }
  releaseTile(tile) {}
}
let pp;
function initMap() {

  class Popup extends google.maps.OverlayView {
    position;
    containerDiv;
    constructor(position) {
      super();
      this.position = gpspoint;
      this.containerDiv = document.createElement("div");
      this.containerDiv.style = "position: fixed;width:14px;height:14px;border-radius:50%;border:3px solid #37144d;background:white;color:#37144d";
      // Optionally stop clicks, etc., from bubbling up to the map.
      Popup.preventMapHitsAndGesturesFrom(this.containerDiv);
      pp=this;
    }
    /** Called when the popup is added to the map. */
    onAdd() {
      this.getPanes().floatPane.appendChild(this.containerDiv);
    }
    /** Called when the popup is removed from the map. */
    onRemove() {
      if (this.containerDiv.parentElement) {
        this.containerDiv.parentElement.removeChild(this.containerDiv);
      }
    }
    /** Called each frame when the popup needs to draw itself. */
    draw() {
      const divPosition = this.getProjection().fromLatLngToDivPixel(
        gpspoint,
      );
      // Hide the popup when it is far out of view.
      const display =
        Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000
          ? "block"
          : "none";
  
      if (display === "block") {
        this.containerDiv.style.left = (divPosition.x-10) + "px";
        this.containerDiv.style.top = (divPosition.y-10) + "px";
      }
  
      if (this.containerDiv.style.display !== display) {
        this.containerDiv.style.display = display;
      }
    }
  }
  

  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer();
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 15,
    center: center,
    streetViewControl: false,
    mapId: "DEMO_MAP_ID",
    minZoom: 12
  });

  directionsRenderer.setMap(map);
  //directionsRenderer.setPanel(hiddenpanel);

  const onChangeHandler = function () {
    calculateAndDisplayRoute(directionsService, directionsRenderer);
  };
  navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
  popup = new Popup(
    new google.maps.LatLng(-33.866, 151.196),
    document.getElementById("content"),
  );
  popup.setMap(map);
  //const coordMapType = new CoordMapType(new google.maps.Size(128,128));

  //map.overlayMapTypes.insertAt(0, coordMapType);
}
let maxStep;
let currentStep;
function calcRoute(start, end) {
  start = new google.maps.LatLng(start.lat, start.lng);
  end = new google.maps.LatLng(end.lat, end.lng);
  
  directionsService
    .route({
      origin: start,
      destination: end,
      travelMode: google.maps.TravelMode.DRIVING,
    })
    .then((response) => {
      console.log(response);
      showHome();
      directionsRenderer.setDirections(response);
      route = response;
      
      setTimeout(()=>{
        steps = route.routes[0].legs[0].steps;
        
        guide.style.display = "flex";
        maxStep = steps.length;
        currentStep = 0;
        navHtml.innerHTML = "<span style='bold'>"+ (currentStep+1) +"/"+ maxStep+"</span><span>"+steps[currentStep].instructions+"</span>";
        distanceText.innerHTML = route.routes[0].legs[0].distance.text +" ("+ route.routes[0].legs[0].duration.text+")";
      }, 2000);
      
    })
    .catch((e) => window.alert("Directions request failed due to " + e));
}

function showIndoorNav() {
  indoorNav.style.display = "block";
}
function hideIndoorNav() {
  indoorNav.style.display = "none";
}

let indoornaving = false;
nextButton = document.getElementById("nextbutton");
prevButton = document.getElementById("prevbutton");

function nextStep(){
  if(currentStep==maxStep-1){
    if(indoornaving) showIndoorNav();
    nextButton.style.opacity = "0.5";
  }else{
  currentStep += 1;
  hideIndoorNav();
  }
  navHtml.innerHTML = "<span style='bold'>"+ (currentStep+1) +"/"+ maxStep+"</span><span>"+steps[currentStep].instructions+"</span>";
  if (currentStep==1) {
    prevButton.style.opacity = "";
  }
}
function prevStep(){
  currentStep += currentStep==0? 0:-1;
  if (currentStep==0) {
    prevButton.style.opacity = "0.5";
  }
  if (currentStep==maxStep-2) {
    nextButton.style.opacity = "";
  }
  navHtml.innerHTML = "<span style='bold'>"+ (currentStep+1) +"/"+ maxStep+"</span><span>"+steps[currentStep].instructions+"</span>";
  hideIndoorNav();
}

window.initMap = initMap;