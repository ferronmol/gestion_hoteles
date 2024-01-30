// Función para cerrar automáticamente los mensajes después de 3 segundos
setTimeout(function () {
  var mensajesExito = document.getElementsByClassName("auto-dismiss");
  var mensajesError = document.getElementsByClassName("auto-dismiss-error");
  for (var i = 0; i < mensajesExito.length; i++) {
    mensajesExito[i].style.display = "none";
  }
  for (var i = 0; i < mensajesError.length; i++) {
    mensajesError[i].style.display = "none";
  }
}, 4000); // 4000 milisegundos = 4 segundos
