@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
            <a href="{{ route('alumnos.edit', $alumno->id_alumno) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar Alumno
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-person-badge"></i> Datos del Alumno</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Matrícula:</th>
                                <td><strong>{{ $alumno->matricula ?? '—' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Nombre completo:</th>
                                <td>{{ $alumno->nombre ?? '' }} {{ $alumno->apellido_paterno ?? '' }} {{ $alumno->apellido_materno ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Género:</th>
                                <td>
                                    @if(($alumno->genero ?? '') == 'M')
                                        <span class="badge bg-info">Masculino</span>
                                    @elseif(($alumno->genero ?? '') == 'F')
                                        <span class="badge bg-success">Femenino</span>
                                    @else
                                        <span class="badge bg-secondary">No especificado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha de Nacimiento:</th>
                                <td>{{ isset($alumno->fecha_nacimiento) && $alumno->fecha_nacimiento ? date('d/m/Y', strtotime($alumno->fecha_nacimiento)) : '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Centro:</th>
                                <td><strong>{{ $alumno->centro_nombre ?? 'Sin asignar' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Clave del Centro:</th>
                                <td>{{ $alumno->clave_ct ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Estatus:</th>
                                <td>
                                    @if(($alumno->estatus ?? '') == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">{{ $alumno->estatus ?? '—' }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Generación:</th>
                                <td>{{ $alumno->generacion ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calificaciones del alumno -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-star"></i> Calificaciones</h5>
            </div>
            <div class="card-body">
                @if(isset($calificaciones) && $calificaciones->count() > 0)
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calificaciones as $cal)
                                <tr>
                                    <td>{{ $cal->materia_nombre ?? $cal->nombre ?? '—' }}</td>
                                    <td>{{ $cal->parcial1 ?? 0 }}</td>
                                    <td>{{ $cal->parcial2 ?? 0 }}</td>
                                    <td>{{ $cal->parcial3 ?? 0 }}</td>
                                    <td><strong>{{ $cal->promedio ?? 0 }}</strong></td>
                                    <td>
                                        @php
                                            $estadoClass = ($cal->estado ?? '') == 'Aprobado' ? 'success' : (($cal->estado ?? '') == 'Reprobado' ? 'danger' : 'warning');
                                        @endphp
                                        <span class="badge bg-{{ $estadoClass }}">{{ $cal->estado ?? 'Pendiente' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="4" class="text-end">Promedio General:</th>
                                    <th colspan="2"><strong>{{ $promedioGeneral ?? 0 }}</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Este alumno no tiene calificaciones registradas
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection