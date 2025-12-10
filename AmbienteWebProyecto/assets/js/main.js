
function uid(){
  return 'id' + Date.now() + Math.floor(Math.random()*100000);
}


let citas = [];
let usuarios = [];


function cargarEstado(){
  const c = localStorage.getItem('mc_citas');
  const u = localStorage.getItem('mc_usuarios');
  citas = c ? JSON.parse(c) : [];
  usuarios = u ? JSON.parse(u) : [];
}


function guardarEstado(){
  localStorage.setItem('mc_citas', JSON.stringify(citas));
  localStorage.setItem('mc_usuarios', JSON.stringify(usuarios));
}


function seed(){
  if(citas.length === 0){
    citas = [
      { id: uid(), paciente:'María Gómez', doctor:'Dr. Carlos Rojas — Medicina General', fecha:'2025-11-05', hora:'09:30', motivo:'Control', estado:'Pendiente' },
      { id: uid(), paciente:'Luis Pérez', doctor:'Dra. Laura Méndez — Pediatría', fecha:'2025-11-06', hora:'11:00', motivo:'Consulta', estado:'Confirmada' },
      { id: uid(), paciente:'Ana Solís', doctor:'Dr. Andrés Salas — Dermatología', fecha:'2025-11-08', hora:'14:15', motivo:'Lesión', estado:'Pendiente' }
    ];
  }
  if(usuarios.length === 0){
    usuarios = [
      { id: uid(), nombre:'Administrador Demo', correo:'admin@mediconnect.cr', rol:'Administrador' },
      { id: uid(), nombre:'Dr. Carlos Rojas', correo:'crojas@mediconnect.cr', rol:'Doctor' },
      { id: uid(), nombre:'María Gómez', correo:'maria@correo.com', rol:'Paciente' }
    ];
  }
  guardarEstado();
}


function mostrarSeccion(id){
  var ids = ['home','paciente','doctor','admin','soporte'];
  ids.forEach(function(name){
    var el = document.getElementById(name);
    if(!el) return;
    if(name === id){ el.classList.remove('d-none'); }
    else{ el.classList.add('d-none'); }
  });
 
  document.querySelectorAll('[data-section]').forEach(function(a){
    var active = a.getAttribute('data-section') === id;
    if(active){ a.classList.add('active'); } else{ a.classList.remove('active'); }
  });
  window.scrollTo({top:0, behavior:'smooth'});
}


function renderCitas(){
  var tbody = document.getElementById('tbody-citas');
  tbody.innerHTML = '';
  citas.forEach(function(c){
    var fila =
      '<tr>' +
        '<td><input type="checkbox" class="chkCita" data-id="'+c.id+'"></td>' +
        '<td>'+c.paciente+'</td>' +
        '<td>'+c.doctor+'</td>' +
        '<td>'+c.fecha+'</td>' +
        '<td>'+c.hora+'</td>' +
        '<td>'+ (c.motivo || '') +'</td>' +
        '<td class="text-end">' +
          '<button class="btn btn-sm btn-outline-secondary btn-confirmar" data-id="'+c.id+'">Confirmar</button> ' +
          '<button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="'+c.id+'">Eliminar</button>' +
        '</td>' +
      '</tr>';
    tbody.innerHTML += fila;
  });

  
  document.querySelectorAll('.btn-confirmar').forEach(function(b){
    b.addEventListener('click', function(e){
      var id = e.target.dataset.id;
      var item = citas.find(function(x){ return x.id === id; });
      if(item){ item.estado = 'Confirmada'; guardarEstado(); renderCitas(); renderAgenda(); }
    });
  });
  document.querySelectorAll('.btn-eliminar').forEach(function(b){
    b.addEventListener('click', function(e){
      var id = e.target.dataset.id;
      citas = citas.filter(function(x){ return x.id !== id; });
      guardarEstado(); renderCitas(); renderAgenda();
    });
  });
}


function renderAgenda(){
  var docSel = document.getElementById('filtroDoctor').value;
  var fechaSel = document.getElementById('filtroFecha').value;
  var tbody = document.getElementById('tbody-agenda');
  tbody.innerHTML = '';

  citas.forEach(function(c){
    var okDoc = (docSel === '' || c.doctor === docSel);
    var okFecha = (fechaSel === '' || c.fecha === fechaSel);
    if(okDoc && okFecha){
      var badge = c.estado === 'Confirmada' ? 'text-bg-success' : 'text-bg-warning';
      var fila =
        '<tr>' +
          '<td>'+c.paciente+'</td>' +
          '<td>'+c.doctor+'</td>' +
          '<td>'+c.fecha+'</td>' +
          '<td>'+c.hora+'</td>' +
          '<td>'+ (c.motivo || '') +'</td>' +
          '<td class="text-end"><span class="badge '+badge+'">'+c.estado+'</span></td>' +
        '</tr>';
      tbody.innerHTML += fila;
    }
  });
}

function renderUsuarios(){
  var tbody = document.getElementById('tbody-usuarios');
  tbody.innerHTML = '';
  usuarios.forEach(function(u){
    var fila =
      '<tr>' +
        '<td><input type="checkbox" class="chkUsuario" data-id="'+u.id+'"></td>' +
        '<td>'+u.nombre+'</td>' +
        '<td>'+u.correo+'</td>' +
        '<td>'+u.rol+'</td>' +
        '<td class="text-end"><button class="btn btn-sm btn-outline-danger btn-del-user" data-id="'+u.id+'">Eliminar</button></td>' +
      '</tr>';
    tbody.innerHTML += fila;
  });

  document.querySelectorAll('.btn-del-user').forEach(function(b){
    b.addEventListener('click', function(e){
      var id = e.target.dataset.id;
      usuarios = usuarios.filter(function(x){ return x.id !== id; });
      guardarEstado(); renderUsuarios();
    });
  });
}

function submitFormulario(event){
  event.preventDefault();
  console.log('submit del formulario');
  var form = document.querySelector('#formulario');
  var formData = new FormData(form);
  var data = Object.fromEntries(formData.entries());
  console.log(data);
  console.log('El nombre es: ' + data.nombre);
  console.log('Los apellidos son: ' + data.apellidos);
  console.log('El correo es: ' + data.correo);
  var dataJson = JSON.stringify(data);
  console.log(dataJson);
}

function agregarCita(event){
  event.preventDefault();
  var nueva = {
    id: uid(),
    paciente: document.getElementById('pacienteNombre').value,
    doctor: document.getElementById('doctorSelect').value,
    fecha: document.getElementById('fechaSelect').value,
    hora: document.getElementById('horaSelect').value,
    motivo: document.getElementById('motivoTxt').value,
    estado: 'Pendiente'
  };
  citas.push(nueva);
  guardarEstado();
  renderCitas();
  renderAgenda();
  event.target.reset ? event.target.reset() : null;
}

function agregarUsuario(event){
  event.preventDefault();
  var u = {
    id: uid(),
    nombre: document.getElementById('usuarioNombre').value,
    correo: document.getElementById('usuarioCorreo').value,
    rol: document.getElementById('usuarioRol').value
  };
  usuarios.push(u);
  guardarEstado();
  renderUsuarios();
  event.target.reset ? event.target.reset() : null;
}

function enviarSoporte(event){
  event.preventDefault();
  console.log('Soporte enviado');
  alert('Mensaje enviado (demo).');
}

document.addEventListener('DOMContentLoaded', function(){
  console.log('mi página terminó de cargar');

  var anio = document.getElementById('anio');
  anio.textContent = new Date().getFullYear();

  cargarEstado();
  seed();

  document.querySelectorAll('[data-section]').forEach(function(btn){
    btn.addEventListener('click', function(e){
      e.preventDefault();
      var id = btn.getAttribute('data-section');
      mostrarSeccion(id);
    });
  });

  var btnCargar = document.querySelector('#btnCargar');
  if(btnCargar){
    btnCargar.addEventListener('click', function(e){
      e.preventDefault();
      mostrarSeccion('paciente');
    });
  }

  var selectRol = document.getElementById('select-rol');
  var textoRol  = document.getElementById('texto-rol');
  if(selectRol && textoRol){
    selectRol.addEventListener('change', function(e){
      var rol = e.target.value;
      textoRol.textContent = rol ? rol : '—';
    
    });
  }

  var formMini = document.querySelector('#formulario');
  if(formMini){ formMini.addEventListener('submit', submitFormulario); }

  var formCita = document.getElementById('formCita');
  if(formCita){ formCita.addEventListener('submit', agregarCita); }

  var btnFiltrar = document.getElementById('btnFiltrar');
  if(btnFiltrar){ btnFiltrar.addEventListener('click', renderAgenda); }
  var filtroDoctor = document.getElementById('filtroDoctor');
  if(filtroDoctor){ filtroDoctor.addEventListener('change', renderAgenda); }
  var filtroFecha = document.getElementById('filtroFecha');
  if(filtroFecha){ filtroFecha.addEventListener('change', renderAgenda); }

  var formUsuario = document.getElementById('formUsuario');
  if(formUsuario){ formUsuario.addEventListener('submit', agregarUsuario); }

  var formSoporte = document.getElementById('formSoporte');
  if(formSoporte){ formSoporte.addEventListener('submit', enviarSoporte); }

  renderCitas();
  renderAgenda();
  renderUsuarios();
});
