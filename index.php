<!DOCTYPE html>
<html lang="es">  
<head>    
<title>CovidGo</title>    
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- LEAFLET LIBRARY -->
<link rel="stylesheet" href="libs/leaflet.css"/>
<script src="libs/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<!-- SWAL LIBRARY -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- FA -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/js/all.min.js" integrity="sha256-+Q/z/qVOexByW1Wpv81lTLvntnZQVYppIL1lBdhtIq0=" crossorigin="anonymous"></script>
<!-- SOCKET -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<!-- WPA -->
<link rel="manifest" href="webmanifest.webmanifest">
<script type='text/javascript'>
if ('serviceWorker' in navigator) {
  console.log('CLIENT: service worker registration in progress.');
  navigator.serviceWorker.register('/service-worker.js').then(function() {
    console.log('CLIENT: service worker registration complete.');
    }, function() {
      console.log('CLIENT: service worker registration failure.');
    });
} else {
  console.log('CLIENT: service worker is not supported.');
}
</script>
</head>
<body>
<?php
  include 'x_actions.php';
  if(empty($_SESSION)){
    ?>
    <script>
      window.location.replace("login.php");
    </script>
    <?php
  }
?>
  <div class="button_container" id="toggle">
    <span class="top"></span>
    <span class="middle"></span>
    <span class="bottom"></span>
  </div>
  <div class="overlay" id="overlay">
    <nav class="overlay-menu">
      <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a id="modalHistorico">Historico</a></li>
        <li><a href="#" onclick="desbloquear()">Admin</a></li>
        <li><a onclick="deslogear()">Cerrar sesión</a></li>
      </ul>
    </nav>
  </div>
  <div id="map">
  </div>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="padding: 5%;">

        </div>
      </div>
    </div>
  </div>

</body>
</html>
<script>
// Funcion simple MAP
var map, marker, markerLocation, idUserSession, nombreUserSession;
jQuery(function(){
    map = L.map('map').setView([40.48, -3.69], 16);
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18
    }).addTo(map);
    markerGroup = new L.LayerGroup();
    map.on('click', addMarker);
});

// Funcion para añadir todos los puntos registrados en la base de datos
jQuery( document ).ready(function() {
    // Pintamos markers registrados en la BD
    var ajaxurl = "x_actions.php";
        jQuery.post(ajaxurl, {
            type: "POST",
            dataType: 'text', 
            action: 'getAllMarkers',
        }, function (datos) {
          var parsedData = JSON.parse(datos);
          parsedData.forEach(element => {
            if(element['status'] == 0){
              var iconPrinted = L.icon({iconUrl: 'content/statusimg/0.png',iconSize: [25, 41]});
            }else if(element['status'] == 1){
              var iconPrinted = L.icon({iconUrl: 'content/statusimg/1.png',iconSize: [25, 41]});
            }else if(element['status'] == 2){
              var iconPrinted = L.icon({iconUrl: 'content/statusimg/2.png',iconSize: [25, 41]});
            }
            var latPrinted = element['cords'].split(",", 1)[0];
            var longPrinted = element['cords'].split(",", 2)[1];
            L.marker([latPrinted,longPrinted],{icon: iconPrinted}).on("popupopen", onPopupOpen).addTo(map).bindPopup("<p class='descriptionPopUp "+element['iduser']+"'>"+element['description']+"</p><br><button type='button' class='btn btn-warning marker-delete-button'>Eliminar petición</button><br><button type='button' class='btn btn-info marker-update-button'>Actualizar petición</button>");
          });
        }
      );

      // Identificacion de cada usuario por ID único
      var emailUserSession = "<?php echo $_SESSION['email'] ?>";
      var ajaxurl = "x_actions.php";
      jQuery.post(ajaxurl, {
        type: "POST",
        dataType: 'text', 
        action: 'getUserId',
        offset0:emailUserSession
      }, function (datos) {
          var parsedData = JSON.parse(datos);
          idUserSession = parsedData['id'];
          nombreUserSession = parsedData['nombre'];
      });
});

// 
jQuery('#modalHistorico').on( "click", function() {
  jQuery('#exampleModalLong').modal('show');
  jQuery('.modal-title').html("Peticiones realizadas de: "+nombreUserSession);
    var ajaxurl = "x_actions.php";
      jQuery.post(ajaxurl, {
        type: "POST",
        dataType: 'text', 
        action: 'getMarkerHistory',
        offset0:idUserSession
      }, function (datos) {
          var parsedData = JSON.parse(datos);
          estructura="";
            parsedData.forEach(element => {
              console.log(element['description']);
              estructura+="<div class='col-md-12' style='background: #cce2e2;text-align: center;margin-top:3%;border-radius: 25px;border: 2px solid #95d43d;margin-bottom:1%;'>";
              estructura+="<p>"+element['description']+"</p>";
              estructura+="</div>";
              estructura+="<div class='row' style='background-color: #c6efe1;padding: 2%;text-align: center;border-radius: 25px;border: 2px solid #95d43d;'>";
              estructura+="<div class='col-md-3'>";
              estructura+="<p>"+element['date']+"</p>";
              estructura+="</div>";
              estructura+="<div class='col-md-6'>";
              estructura+="<p>Lat/Ln: "+element['cords']+"</p>";
              estructura+="</div>";
              estructura+="<div class='col-md-3'>";
              if(element['status'] != 4){
                estructura+="Estado: <i class='fas fa-circle' style='font-size: 12px;'></i>";
              }else{
                estructura+="Estado: <i class='far fa-circle' style='font-size: 12px;'></i>";
              }
              estructura+="</div>";
              estructura+="</div>";
            });
            jQuery(".modal-body").append(estructura);
      });
});

// Funcion para añadir marker
var enabled = true;
function addMarker(e){
  // Add marker to map at click location; add popup window
  if(enabled == true){
    swal("Escribe la descripción de tu petición.", {
      showCloseButton: true,
      showCancelButton: true,
      content: "input",
      }).then((value) => {
      if(value){
        var popUpDescription = value;
        var marker = new L.marker(e.latlng).on("popupopen", onPopupOpen).bindPopup("<p class='descriptionPopUp'>"+popUpDescription+"</p><br><button type='button' class='btn btn-warning marker-delete-button'>Eliminar petición</button><br><button type='button' class='btn btn-info marker-update-button'>Actualizar petición</button>").openPopup();
        // Preparo vars para DB
        var latLong = marker._latlng;
        var coordsMarker = latLong.toString();
        var coordsMarkerFormat = coordsMarker.replace("(","").replace(")","").replace("LatLng","");
        var idUser = idUserSession;
        var status = 0;
        var descripcionMarker = popUpDescription;
        var ajaxurl = "x_actions.php";
        jQuery.post(ajaxurl, {
            type: "POST",
            dataType: 'text', 
            action: 'registerMarker',
            offset0:coordsMarkerFormat,
            offset1:idUser,
            offset2:status,
            offset3:descripcionMarker,
        }, function (datos) {
          sockets.emit('new-point', {
            popUpDescription:popUpDescription,
            coordsMarkerFormat:coordsMarkerFormat,
            status:status,
            idUser:idUser
  	     });
	     });
        enabled = false;
      }else{
        swal({
          title: "Ha ocurrido un problema!",
          text: "No se ha podido añadir una petición sin una descripción.",
          icon: "error",
          button: "Ok",
        });
      }
    });
  }
}

function onPopupOpen() {
    var tempMarker = this;
    var tempLat = tempMarker.getLatLng();
    var tempLatFormated = tempLat.toString().replace("(","").replace(")","").replace("LatLng","");
    var idMarkerSelected = tempMarker._popup._content.split("'")[1].split(" ")[1];
    jQuery(".marker-delete-button:visible").click(function () {
      if(idMarkerSelected == idUserSession){
          //map.removeLayer(tempMarker);
          console.log(tempMarker)
           // UPDATE EN LA BASE DE DATOS CON LA LAT
          var ajaxurl = "x_actions.php";
          jQuery.post(ajaxurl, {
              type: "POST",
            dataType: 'text', 
            action: 'updateDelete',
            offset0:tempLatFormated,
        }, function (datos) {
          sockets.emit('remove-point', {
            tempMarker:tempMarker
         });
        });
      }else{
        swal({
          title: "Ha ocurrido un problema!",
          text: "No eres el propietario de esta petición.",
          icon: "error",
          button: "Ok",
        });
      }
    });
    jQuery(".marker-update-button:visible").click(function () {
          var iconUpdateProcess = L.icon({
            iconUrl: 'libs/images/proceso.png',
            iconSize: [25, 41]
          });
          var iconFinishProcess = L.icon({
            iconUrl: 'libs/images/completado.png',
            iconSize: [25, 41]
          });
          var iconRebootProcess = L.icon({
            iconUrl: 'libs/images/marker-icon-2x.png',
            iconSize: [25, 41]
          });
      swal({
        text: "Cuidado, vas a alterar el estado de la petición.",
        icon: "warning",
        buttons: {
          Finalizar: {
            text: "Finalizar",
            value: "Finalizar",
          },
          Reiniciar: {
            text: "Reiniciar",
            value: "Reiniciar",
          },
          Procesando: true,
        },
      })
      .then((value) => {
        switch (value) {
          case "Procesando":
            tempMarker.setIcon(iconUpdateProcess);
            tempMarker.setLatLng(tempLat);
            swal("Estado actualizado a 'En proceso'");
            // UPDATE EN LA BASE DE DATOS CON LA LAT
            var ajaxurl = "x_actions.php";
            jQuery.post(ajaxurl, {
                type: "POST",
                dataType: 'text', 
                action: 'updateProcess',
                offset0:tempLatFormated,
            }, function (datos) {
            });
          break;
          case "Finalizar":
            tempMarker.setIcon(iconFinishProcess);
            tempMarker.setLatLng(tempLat);
            swal("Estado actualizado a 'Finalizado'");
            // UPDATE EN LA BASE DE DATOS CON LA LAT
            var ajaxurl = "x_actions.php";
            jQuery.post(ajaxurl, {
                type: "POST",
                dataType: 'text', 
                action: 'updateFinished',
                offset0:tempLatFormated,
            }, function (datos) {
            });
          break;
          case "Reiniciar":
            tempMarker.setIcon(iconRebootProcess);
            tempMarker.setLatLng(tempLat);
            swal("Estado actualizado a 'Reiniciado'");
            // UPDATE EN LA BASE DE DATOS CON LA LAT
            var ajaxurl = "x_actions.php";
            jQuery.post(ajaxurl, {
                type: "POST",
                dataType: 'text', 
                action: 'updateReboot',
                offset0:tempLatFormated,
            }, function (datos) {
            });
          break;
        }
      });
    });
}
 // Menu functions
  jQuery('#toggle').click(function() {
    jQuery(this).toggleClass('active');
    jQuery('#overlay').toggleClass('open');
  });

  // Funciones mias
  jQuery(document).on('dblclick', "div.leaflet-popup-content", function() {
    console.log(this)
  });

  // Funcion desbloquear mapa
  function desbloquear(){
     enabled = true;
  }
  // Funcion deslogear
   function deslogear() {
    var ajaxurl = "x_actions.php";
    jQuery.post(ajaxurl, {
        type: "POST",
        dataType: 'text', 
        action: 'deslogear',
    }, function (datos) {
        if(datos = "Ok"){
          window.location.replace("login.php");
        }
    });
  }
  // NOT USE FUNCTIONS
  /* function onMarkerClick(e) {
    console.log("asd")
    var popup = e.target.getPopup();
    var chart_div = document.getElementById("graphdiv");
    //alert(popup)
    popup.setContent("hola");
 }*/

  var sockets = io.connect('https://covidgo.es:4000', { 'forceNew': true });
  sockets.on('receive-point', (data) =>{
      if(data['status'] == 0){
        var iconPrinted = L.icon({iconUrl: 'content/statusimg/0.png',iconSize: [25, 41]});
      }else if(data['status'] == 1){
        var iconPrinted = L.icon({iconUrl: 'content/statusimg/1.png',iconSize: [25, 41]});
      }else if(data['status'] == 2){
        var iconPrinted = L.icon({iconUrl: 'content/statusimg/2.png',iconSize: [25, 41]});
      }
      var latPrinted = data['coordsMarkerFormat'].split(",", 1)[0];
      var longPrinted = data['coordsMarkerFormat'].split(",", 2)[1];
      L.marker([latPrinted,longPrinted],{icon: iconPrinted}).on("popupopen", onPopupOpen).addTo(map).bindPopup("<p class='descriptionPopUp "+idUserSession+"'>"+data['popUpDescription']+"</p><br><button type='button' class='btn btn-warning marker-delete-button'>Eliminar petición</button><br><button type='button' class='btn btn-info marker-update-button'>Actualizar petición</button>");
  });

  sockets.on('remove-point', (data) =>{
    console.log(data);
  });

</script>
<style>
/* CSS MARKERS IMPORTADOS */
.importedMarker{
  margin-left: -12px;
  margin-top: -41px;
  width: 25px;
  height: 41px;
  transform: translate3d(30px, 264px, 0px);
  z-index: 264;
  filter: drop-shadow(5px 5px 5px #222);
}
.leaflet-marker-icon{
  filter: drop-shadow(5px 5px 5px #222);
}
/* CSS BUTTONS  & POP UP*/
.marker-delete-button, .marker-update-button{
  margin-top: 4%;
}
.leaflet-popup-content{
  text-align: center;
}
.descriptionPopUp{
  font-size: 16px;
}
/* CSS NECESARIO MOBILE MAP */
body {
    padding: 0;
    margin: 0;
}
html, body, #map {
    height: 100%;
    width: 100vw;
    z-index: 99;
}
/* CSS MENU */
.button_container {
  position: fixed;
  top: 5%;
  right: 2%;
  height: 27px;
  width: 35px;
  cursor: pointer;
  z-index: 101;
  transition: opacity .25s ease;
}
.button_container.active .top {
  transform: translateY(11px) translateX(0) rotate(45deg);
  background: #FFF;
}
.button_container.active .middle {
  opacity: 0;
  background: #FFF;
}
.button_container.active .bottom {
  transform: translateY(-11px) translateX(0) rotate(-45deg);
  background: #FFF;
}
.button_container span {
  background: #636363;
  border: none;
  height: 5px;
  width: 100%;
  position: absolute;
  top: 0;
  left: -15px;
  transition: all .35s ease;
  cursor: pointer;
}
.button_container span:nth-of-type(2) {
  top: 11px;
}
.button_container span:nth-of-type(3) {
  top: 22px;
}
.overlay {
  position: fixed;
  background: #1abc9c;
  top: 0;
  left: 0;
  z-index: 100;
  width: 100%;
  height: 0%;
  opacity: 0;
  visibility: hidden;
  transition: opacity .35s, visibility .35s, height .35s;
  overflow: hidden;
}
.overlay.open {
  opacity: .9;
  visibility: visible;
  height: 100%;
}
.overlay.open li {
  animation: fadeInRight .5s ease forwards;
  animation-delay: .35s;
}
.overlay.open li:nth-of-type(2) {
  animation-delay: .4s;
}
.overlay.open li:nth-of-type(3) {
  animation-delay: .45s;
}
.overlay.open li:nth-of-type(4) {
  animation-delay: .50s;
}
.overlay nav {
  position: relative;
  height: 70%;
  top: 50%;
  transform: translateY(-50%);
  font-size: 50px;
  font-weight: 400;
  text-align: center;
}
.overlay ul {
  list-style: none;
  padding: 0;
  margin: 0 auto;
  display: inline-block;
  position: relative;
  height: 100%;
}
.overlay ul li {
  display: block;
  height: 25%;
  height: calc(100% / 4);
  min-height: 50px;
  position: relative;
  opacity: 0;
}
.overlay ul li a {
  display: block;
  position: relative;
  color: #FFF;
  text-decoration: none;
  overflow: hidden;
}
.overlay ul li a:hover:after, .overlay ul li a:focus:after, .overlay ul li a:active:after {
  width: 100%;
}
.overlay ul li a:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0%;
  transform: translateX(-50%);
  height: 3px;
  background: #FFF;
  transition: .35s;
}
@keyframes fadeInRight {
  0% {
    opacity: 0;
    left: 20%;
  }
  100% {
    opacity: 1;
    left: 0;
  }
}
/* SWAL CSS */
.swal-footer{
  text-align: center;
}
/* FIX MOMENTANEO ACTIONS SESIONES */
.xdebug-error{
  display: none;
}
body > br:nth-child(1){
  display: none;
}
body > br:nth-child(3){
  display: none;
}
</style>
