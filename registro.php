<!DOCTYPE html>
<html lang="es">  
<head>    
<title>API</title>    
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- SWAL LIBRARY -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <form style="padding-top: 35px;">
      <input type="text" id="login" class="fadeIn second nombreRegistro" name="login" placeholder="Nombre">
      <input type="text" id="login" class="fadeIn second emailRegistro" name="login" placeholder="Email">
      <input type="password" id="password" class="fadeIn third passRegistro" name="login" placeholder="Password">
      <input type="password" id="password" class="fadeIn third passRegistroConfirmacion" name="login" placeholder="Confirmar password">
      <select class="form-control fadeIn third selectorCCAA">
        <option value='alava'>Álava</option>
        <option value='albacete'>Albacete</option>
        <option value='alicante'>Alicante/Alacant</option>
        <option value='almeria'>Almería</option>
        <option value='asturias'>Asturias</option>
        <option value='avila'>Ávila</option>
        <option value='badajoz'>Badajoz</option>
        <option value='barcelona'>Barcelona</option>
        <option value='burgos'>Burgos</option>
        <option value='caceres'>Cáceres</option>
        <option value='cadiz'>Cádiz</option>
        <option value='cantabria'>Cantabria</option>
        <option value='castellon'>Castellón/Castelló</option>
        <option value='ceuta'>Ceuta</option>
        <option value='ciudadreal'>Ciudad Real</option>
        <option value='cordoba'>Córdoba</option>
        <option value='cuenca'>Cuenca</option>
        <option value='girona'>Girona</option>
        <option value='laspalmas'>Las Palmas</option>
        <option value='granada'>Granada</option>
        <option value='guadalajara'>Guadalajara</option>
        <option value='guipuzcoa'>Guipúzcoa</option>
        <option value='huelva'>Huelva</option>
        <option value='huesca'>Huesca</option>
        <option value='illesbalears'>Illes Balears</option>
        <option value='jaen'>Jaén</option>
        <option value='acoruña'>A Coruña</option>
        <option value='larioja'>La Rioja</option>
        <option value='leon'>León</option>
        <option value='lleida'>Lleida</option>
        <option value='lugo'>Lugo</option>
        <option value='madrid'>Madrid</option>
        <option value='malaga'>Málaga</option>
        <option value='melilla'>Melilla</option>
        <option value='murcia'>Murcia</option>
        <option value='navarra'>Navarra</option>
        <option value='ourense'>Ourense</option>
        <option value='palencia'>Palencia</option>
        <option value='pontevedra'>Pontevedra</option>
        <option value='salamanca'>Salamanca</option>
        <option value='segovia'>Segovia</option>
        <option value='sevilla'>Sevilla</option>
        <option value='soria'>Soria</option>
        <option value='tarragona'>Tarragona</option>
        <option value='santacruztenerife'>Santa Cruz de Tenerife</option>
        <option value='teruel'>Teruel</option>
        <option value='toledo'>Toledo</option>
        <option value='valencia'>Valencia/Valéncia</option>
        <option value='valladolid'>Valladolid</option>
        <option value='vizcaya'>Vizcaya</option>
        <option value='zamora'>Zamora</option>
        <option value='zaragoza'>Zaragoza</option>
      </select>
      <input type="button" onclick="registerFunction()" class="fadeIn fourth" value="Registrarme">
    </form>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
  jQuery( document ).ready(function() {
    console.log("Ready login");
  });

  function registerFunction(){
    console.log("Registrando...");
    var emailRegister = jQuery(".emailRegistro").val();
    var passRegisterConfirm = jQuery(".passRegistroConfirmacion").val();
    var passRegister = jQuery(".passRegistro").val();
    var nombreRegister = jQuery(".nombreRegistro").val();
    var selectorRegister = jQuery(".selectorCCAA").val();

    if(!nombreRegister || !emailRegister || !passRegister || !passRegisterConfirm || !selectorRegister){
      console.log("Completa campos");
       swal({
          title: "Ha ocurrido un problema!",
          text: "Completa todos los campos del registro.",
          icon: "error",
          button: "Ok",
        });
    }else{
      if(passRegister === passRegisterConfirm){
        var ajaxurl = "x_actions.php";
        jQuery.post(ajaxurl, {
            type: "POST",
            dataType: 'text', 
            action: 'registerAction',
            offset0:emailRegister,
            offset1:passRegister,
            offset2:nombreRegister,
            offset3:selectorRegister,
        }, function (datos) {
            swal({
              title: "Enhorabuena!",
              text: "El registro se ha completado con exito.",
              icon: "success",})
            .then((value) => {
              window.location.replace("login.php");
            });
            jQuery(".nombreRegistro").val("");
            jQuery(".emailRegistro").val("");
            jQuery(".passRegistro").val("");
            jQuery(".passRegistroConfirmacion").val("");
        });
      }else{
        swal({
          title: "Ha ocurrido un problema!",
          text: "No coinciden las contraseñas.",
          icon: "error",
          button: "Ok",
        });
        jQuery(".passRegistro").val("");
        jQuery(".passRegistroConfirmacion").val("");
        borderPass = jQuery(".passRegistro");
        borderPassConfirm = jQuery(".passRegistroConfirmacion");
        setTimeout(function(){
          borderPass.css('border-color', '#ff9494');
          borderPassConfirm.css('border-color', '#ff9494');
        }, 1500);
      }
    }
  }
</script>
<style>
/* CSS NECESARIO MOBILE MAP */
body {
  padding: 0;
  margin: 0;
  background-image: url("content/backlogin.jpg");
  background-size: cover;
}
html, body, #map {
  height: 100%;
  width: 100vw;
  z-index: 99;
}
/* ESTILO LOGIN */
.wrapper {
  display: flex;
  align-items: center;
  flex-direction: column; 
  justify-content: center;
  width: 100%;
  min-height: 100%;
  padding: 20px;
}
#formContent {
  -webkit-border-radius: 10px 10px 10px 10px;
  border-radius: 10px 10px 10px 10px;
  background: #fff;
  padding: 30px;
  width: 90%;
  max-width: 450px;
  position: relative;
  padding: 0px;
  -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  text-align: center;
}
#formFooter {
  background-color: #f6f6f6;
  border-top: 1px solid #dce8f1;
  padding: 25px;
  text-align: center;
  -webkit-border-radius: 0 0 10px 10px;
  border-radius: 0 0 10px 10px;
}
input[type=button], input[type=submit], input[type=reset]  {
  background-color: #56baed;
  border: none;
  color: white;
  padding: 15px 80px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  text-transform: uppercase;
  font-size: 13px;
  -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
  margin: 5px 20px 40px 20px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover{
  background-color: #39ace7;
}
input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
  -moz-transform: scale(0.95);
  -webkit-transform: scale(0.95);
  -o-transform: scale(0.95);
  -ms-transform: scale(0.95);
  transform: scale(0.95);
}
input[type=text], input[type=password] {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}
input[type=text]:focus, input[type=password]:focus {
  background-color: #fff;
  border-bottom: 2px solid #5fbae9;
}

input[type=text]:placeholder, input[type=password]:placeholder{
  color: #cccccc;
}
.fadeInDown {
  -webkit-animation-name: fadeInDown;
  animation-name: fadeInDown;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}
@-webkit-keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}
@keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}
@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
.fadeIn {
  opacity:0;
  -webkit-animation:fadeIn ease-in 1;
  -moz-animation:fadeIn ease-in 1;
  animation:fadeIn ease-in 1;
  -webkit-animation-fill-mode:forwards;
  -moz-animation-fill-mode:forwards;
  animation-fill-mode:forwards;
  -webkit-animation-duration:1s;
  -moz-animation-duration:1s;
  animation-duration:1s;
}
.fadeIn.first {
  -webkit-animation-delay: 0.4s;
  -moz-animation-delay: 0.4s;
  animation-delay: 0.4s;
}
.fadeIn.second {
  -webkit-animation-delay: 0.6s;
  -moz-animation-delay: 0.6s;
  animation-delay: 0.6s;
}
.fadeIn.third {
  -webkit-animation-delay: 0.8s;
  -moz-animation-delay: 0.8s;
  animation-delay: 0.8s;
}
.fadeIn.fourth {
  -webkit-animation-delay: 1s;
  -moz-animation-delay: 1s;
  animation-delay: 1s;
  margin-top: 35px;
}
.underlineHover:after {
  display: block;
  left: 0;
  bottom: -10px;
  width: 0;
  height: 2px;
  background-color: #56baed;
  content: "";
  transition: width 0.2s;
}
.underlineHover:hover {
  color: #0d0d0d;
}
.underlineHover:hover:after{
  width: 100%;
}
*:focus {
    outline: none;
}
a:hover {
    color: #0056b3;
    text-decoration: none;
}

/* SELECTOR CCAA */
.form-control {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}
</style>