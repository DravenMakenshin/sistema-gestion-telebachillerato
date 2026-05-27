@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-star-fill"></i> Captura de Calificaciones</h4>
            </div>
            <div class="card-body">
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    Seleccione un alumno, elija la materia, ingrese las 3 calificaciones y el promedio se calculará automáticamente.
                </div>

                <!-- Selección de Alumno -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Seleccionar Alumno:</label>
                        <select id="alumnoSelect" class="form-select">
                            <option value="">-- Seleccione un alumno --</option>
                            @foreach($alumnos as $alumno)
                                <option value="{{ $alumno->id_alumno }}">
                                    {{ $alumno->matricula }} - {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Seleccionar Materia:</label>
                        <select id="materiaSelect" class="form-select">
                            <option value="">-- Seleccione una materia --</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id_materia }}">
                                    {{ $materia->codigo }} - {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Formulario de Calificaciones -->
                <div id="formCalificaciones" style="display: none;">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="bi bi-pencil-square"></i> Ingresar Calificaciones
                            </h5>
                            
                            <!-- Mostrar información del alumno y materia seleccionada -->
                            <div id="infoSeleccion" class="alert alert-secondary">
                                <strong><i class="bi bi-person"></i> Alumno:</strong> <span id="infoAlumno">-</span><br>
                                <strong><i class="bi bi-book"></i> Materia:</strong> <span id="infoMateria">-</span>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label">Primer Parcial:</label>
                                    <input type="number" id="parcial1" class="form-control calificacion" 
                                           placeholder="0-10" min="0" max="10" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Segundo Parcial:</label>
                                    <input type="number" id="parcial2" class="form-control calificacion" 
                                           placeholder="0-10" min="0" max="10" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tercer Parcial:</label>
                                    <input type="number" id="parcial3" class="form-control calificacion" 
                                           placeholder="0-10" min="0" max="10" step="0.01">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="alert alert-secondary text-center">
                                        <h5>Promedio Calculado:</h5>
                                        <h2 id="promedioDisplay">0.00</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert text-center" id="estadoDisplay">
                                        <h5>Estado:</h5>
                                        <h3><span class="badge bg-warning">Pendiente</span></h3>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button id="btnGuardar" class="btn btn-success btn-lg" disabled>
                                    <i class="bi bi-save"></i> Guardar Calificación
                                </button>
                                <button id="btnActualizar" class="btn btn-warning btn-lg" style="display: none;">
                                    <i class="bi bi-arrow-repeat"></i> Actualizar Calificación
                                </button>
                                <button id="btnCancelar" class="btn btn-secondary btn-lg" style="display: none;">
                                    <i class="bi bi-x-circle"></i> Cancelar Edición
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensajes -->
                <div id="mensajeContainer" class="mt-3"></div>
                
                <!-- Tabla de calificaciones guardadas -->
                <div id="tablaCalificaciones" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-table"></i> Calificaciones Guardadas</h5>
                            <button id="btnRecargarTabla" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-repeat"></i> Recargar
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Parcial 1</th>
                                            <th>Parcial 2</th>
                                            <th>Parcial 3</th>
                                            <th>Promedio</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calificacionesTableBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                Seleccione un alumno para ver sus calificaciones
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let alumnoSeleccionado = null;
    let materiaSeleccionada = null;
    let modoEdicion = false;
    let calificacionEditandoId = null;
    
    // Cuando cambia el alumno
    $('#alumnoSelect').change(function() {
        alumnoSeleccionado = $(this).val();
        const alumnoNombre = $('#alumnoSelect option:selected').text();
        
        if (alumnoSeleccionado) {
            $('#infoAlumno').text(alumnoNombre);
            cargarCalificacionesDelAlumno(alumnoSeleccionado);
            
            if (materiaSeleccionada) {
                verificarCalificacionExistente();
            } else {
                $('#formCalificaciones').show();
                $('#infoMateria').text('-');
                limpiarFormulario();
                resetearBotones();
            }
            $('#tablaCalificaciones').show();
        } else {
            $('#formCalificaciones').hide();
            $('#tablaCalificaciones').hide();
            limpiarFormulario();
            resetearBotones();
            $('#infoAlumno').text('-');
        }
    });
    
    // Cuando cambia la materia
    $('#materiaSelect').change(function() {
        materiaSeleccionada = $(this).val();
        const materiaNombre = $('#materiaSelect option:selected').text();
        
        if (materiaSeleccionada) {
            $('#infoMateria').text(materiaNombre);
            
            if (alumnoSeleccionado) {
                verificarCalificacionExistente();
            } else {
                mostrarMensaje('Seleccione un alumno primero', 'warning');
                $('#formCalificaciones').hide();
            }
        } else {
            $('#formCalificaciones').hide();
            limpiarFormulario();
            resetearBotones();
        }
    });
    
    // Botón Cancelar
    $('#btnCancelar').click(function() {
        limpiarFormulario();
        resetearBotones();
        modoEdicion = false;
        calificacionEditandoId = null;
        if (alumnoSeleccionado && materiaSeleccionada) {
            verificarCalificacionExistente();
        }
    });
    
    // Botón Recargar Tabla
    $('#btnRecargarTabla').click(function() {
        if (alumnoSeleccionado) {
            cargarCalificacionesDelAlumno(alumnoSeleccionado);
        }
    });
    
    // Verificar si ya existe calificación
    function verificarCalificacionExistente() {
        if (!alumnoSeleccionado || !materiaSeleccionada) return;
        
        mostrarMensaje('Cargando calificación existente...', 'info');
        
        $.ajax({
            url: `/calificaciones/get-calificacion/${alumnoSeleccionado}/${materiaSeleccionada}`,
            method: 'GET',
            success: function(response) {
                $('#formCalificaciones').show();
                
                if (response && response.id_calificacion) {
                    // Cargar calificación existente para edición
                    $('#parcial1').val(response.parcial1);
                    $('#parcial2').val(response.parcial2);
                    $('#parcial3').val(response.parcial3);
                    calcularPromedio();
                    mostrarMensaje('Ya existe una calificación para esta materia. Puede actualizarla.', 'info');
                    
                    // Mostrar botón de actualizar
                    $('#btnGuardar').hide();
                    $('#btnActualizar').show().data('id', response.id_calificacion);
                    $('#btnCancelar').show();
                    modoEdicion = true;
                    calificacionEditandoId = response.id_calificacion;
                } else {
                    limpiarFormulario();
                    mostrarMensaje('No hay calificación previa. Ingrese los datos.', 'info');
                    resetearBotones();
                    modoEdicion = false;
                    calificacionEditandoId = null;
                }
            },
            error: function() {
                limpiarFormulario();
                $('#formCalificaciones').show();
                resetearBotones();
                modoEdicion = false;
                calificacionEditandoId = null;
            }
        });
    }
    
    // Cargar todas las calificaciones del alumno
    function cargarCalificacionesDelAlumno(idAlumno) {
        $.ajax({
            url: `/calificaciones/list?id_alumno=${idAlumno}`,
            method: 'GET',
            success: function(response) {
                const tbody = $('#calificacionesTableBody');
                tbody.empty();
                
                if (response && response.length > 0) {
                    $.each(response, function(index, calif) {
                        let estadoClass = calif.estado == 'Aprobado' ? 'success' : (calif.estado == 'Reprobado' ? 'danger' : 'warning');
                        const fecha = new Date(calif.created_at).toLocaleDateString();
                        
                        const row = `
                            <tr>
                                <td><strong>${calif.codigo} - ${calif.materia_nombre}</strong></td>
                                <td>${calif.parcial1}</td>
                                <td>${calif.parcial2}</td>
                                <td>${calif.parcial3}</td>
                                <td><strong>${calif.promedio}</strong></td>
                                <td><span class="badge bg-${estadoClass}">${calif.estado}</span></td>
                                <td>${fecha}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btn-editar" 
                                            data-id="${calif.id_calificacion}"
                                            data-materia="${calif.id_materia}">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                    
                    // Evento para botones de editar
                    $('.btn-editar').click(function() {
                        const idMateria = $(this).data('materia');
                        $('#materiaSelect').val(idMateria).trigger('change');
                        mostrarMensaje('Seleccione la materia para editar', 'info');
                    });
                } else {
                    tbody.html('<tr><td colspan="8" class="text-center text-muted">Este alumno no tiene calificaciones registradas</td></tr>');
                }
            },
            error: function() {
                $('#calificacionesTableBody').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar calificaciones</td></tr>');
            }
        });
    }
    
    // Calcular promedio en tiempo real
    $('.calificacion').on('input', function() {
        calcularPromedio();
    });
    
    function calcularPromedio() {
        let p1 = parseFloat($('#parcial1').val()) || 0;
        let p2 = parseFloat($('#parcial2').val()) || 0;
        let p3 = parseFloat($('#parcial3').val()) || 0;
        
        let promedio = (p1 + p2 + p3) / 3;
        let promedioRedondeado = promedio.toFixed(2);
        
        $('#promedioDisplay').text(promedioRedondeado);
        
        // Determinar estado
        let estado = '';
        let estadoColor = '';
        if (promedio >= 6) {
            estado = 'Aprobado';
            estadoColor = 'success';
        } else if (promedio > 0) {
            estado = 'Reprobado';
            estadoColor = 'danger';
        } else {
            estado = 'Pendiente';
            estadoColor = 'warning';
        }
        
        $('#estadoDisplay').html(`
            <h5>Estado:</h5>
            <h3><span class="badge bg-${estadoColor}">${estado}</span></h3>
        `);
        
        // Habilitar botones si hay calificaciones ingresadas
        if (p1 > 0 || p2 > 0 || p3 > 0) {
            if (modoEdicion) {
                $('#btnActualizar').prop('disabled', false);
            } else {
                $('#btnGuardar').prop('disabled', false);
            }
        } else {
            $('#btnGuardar').prop('disabled', true);
            $('#btnActualizar').prop('disabled', true);
        }
    }
    
    // Guardar nueva calificación
    $('#btnGuardar').click(function() {
        guardarCalificacion(false);
    });
    
    // Actualizar calificación existente
    $('#btnActualizar').click(function() {
        guardarCalificacion(true);
    });
    
    function guardarCalificacion(esActualizacion) {
        let p1 = parseFloat($('#parcial1').val()) || 0;
        let p2 = parseFloat($('#parcial2').val()) || 0;
        let p3 = parseFloat($('#parcial3').val()) || 0;
        
        // Validaciones
        if (p1 < 0 || p1 > 10) { mostrarMensaje('La primera calificación debe estar entre 0 y 10', 'danger'); return; }
        if (p2 < 0 || p2 > 10) { mostrarMensaje('La segunda calificación debe estar entre 0 y 10', 'danger'); return; }
        if (p3 < 0 || p3 > 10) { mostrarMensaje('La tercera calificación debe estar entre 0 y 10', 'danger'); return; }
        
        const btn = esActualizacion ? $('#btnActualizar') : $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Procesando...');
        
        $.ajax({
            url: '{{ route("calificaciones.save") }}',
            method: 'POST',
            data: {
                id_alumno: alumnoSeleccionado,
                id_materia: materiaSeleccionada,
                parcial1: p1,
                parcial2: p2,
                parcial3: p3,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    mostrarMensaje(response.message, 'success');
                    // Recargar la tabla de calificaciones
                    cargarCalificacionesDelAlumno(alumnoSeleccionado);
                    // Actualizar la vista
                    if (esActualizacion) {
                        modoEdicion = false;
                        calificacionEditandoId = null;
                        resetearBotones();
                    }
                    calcularPromedio();
                } else {
                    mostrarMensaje(response.message, 'danger');
                }
            },
            error: function(xhr) {
                let error = xhr.responseJSON?.message || 'Error al guardar';
                mostrarMensaje(error, 'danger');
            },
            complete: function() {
                btn.prop('disabled', false).html(esActualizacion ? '<i class="bi bi-arrow-repeat"></i> Actualizar Calificación' : '<i class="bi bi-save"></i> Guardar Calificación');
            }
        });
    }
    
    function resetearBotones() {
        $('#btnGuardar').show().prop('disabled', true);
        $('#btnActualizar').hide().prop('disabled', true);
        $('#btnCancelar').hide();
        modoEdicion = false;
        calificacionEditandoId = null;
    }
    
    function limpiarFormulario() {
        $('#parcial1, #parcial2, #parcial3').val('');
        $('#promedioDisplay').text('0.00');
        $('#estadoDisplay').html(`
            <h5>Estado:</h5>
            <h3><span class="badge bg-warning">Pendiente</span></h3>
        `);
        $('#btnGuardar').prop('disabled', true);
        $('#btnActualizar').prop('disabled', true);
    }
    
    function mostrarMensaje(mensaje, tipo) {
        const iconos = {
            success: 'check-circle',
            danger: 'exclamation-triangle',
            info: 'info-circle',
            warning: 'exclamation-circle'
        };
        
        const alertClass = tipo == 'success' ? 'alert-success' : (tipo == 'danger' ? 'alert-danger' : (tipo == 'warning' ? 'alert-warning' : 'alert-info'));
        
        const html = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi bi-${iconos[tipo]}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#mensajeContainer').html(html);
        
        setTimeout(() => {
            $('.alert').fadeOut('slow', function() { $(this).remove(); });
        }, 4000);
    }
});
</script>
@endpush