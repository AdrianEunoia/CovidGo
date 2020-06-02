<!DOCTYPE html>
<html lang="es">  
<head>    
<title>API</title>    
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- CSS -->
<link rel="stylesheet" href="css/register.css">
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