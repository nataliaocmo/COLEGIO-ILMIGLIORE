document.getElementById('login-form').addEventListener('submit', function(event) {
  event.preventDefault();
  var rol = document.getElementById('rol').value;
  var id = document.getElementById('id').value;
  if (id.trim() === '') {
    alert('Por favor ingrese su ID.');
    return;
  }
  switch (rol) {
    case 'estudiante':
      window.location.href = 'estudiante.html';
      break;
    case 'profesor':
      window.location.href = 'profesor.html';
      break;
    case 'administrativo':
      window.location.href = 'administrativo.html';
      break;
    case 'acudiente':
      window.location.href = 'acudiente.html';
      break;
    default:
      alert('Rol no válido.');
      break;
  }
});