<!DOCTYPE html>
<html lang="es">  
<head>    
<title>CovidGo</title>    
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- CSS -->
<link rel="stylesheet" href="css/login.css">
<!-- SWAL LIBRARY -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <form style="padding-top: 35px;">
      <input type="text" id="login" class="fadeIn second emailLogin" name="login" placeholder="Email">
      <input type="password" id="password" class="fadeIn third passwordLogin" name="login" placeholder="Password">
      <input type="button" onclick="loginFunction()" class="fadeIn fourth" value="Log In">
    </form>
    <div id="formFooter">
      <a class="underlineHover" href="registro.php">Registrarme</a>
    </div>
    <div id="formFooter">
      <a class="underlineHover" href="#">Olvide contraseña</a>
    </div>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
  jQuery( document ).ready(function() {
    console.log("Ready login");
  });

  function loginFunction(){
    var emailLog = jQuery(".emailLogin").val();
    var passLog = jQuery(".passwordLogin").val();
    var nombreLog = jQuery(".nombreLogin").val();
    if(!emailLog || !passLog){
      console.log("Completa campos");
       swal({
          title: "Ha ocurrido un problema!",
          text: "Completa todos los campos del Log in.",
          icon: "error",
          button: "Ok",
        });
    }else{
      console.log("Logeando correctamente");
      var ajaxurl = "x_actions.php";
      jQuery.post(ajaxurl, {
          type: "POST",
          dataType: 'text', 
          action: 'loginAction',
          offset0:emailLog,
          offset1:passLog,
      }, function (datos) {
          // Esta parte es obviamente mejorable, seguridad casi nula.
          console.log(datos)
          if(datos){
            window.location.replace("index.php");
          }else{
            swal({
              title: "Ha ocurrido un problema!",
              text: "Los datos son incorrectos.",
              icon: "error",
              button: "Ok",
            });
          }
      });
    }
  }
</script>