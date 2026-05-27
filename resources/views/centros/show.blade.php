@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <a href="{{ route('centros.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        @if(isset($centro) && $centro)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-building"></i> {{ $centro->nombre }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Clave del Centro de Trabajo:</th>
                                <td><code class="fw-bold">{{ $centro->clave_ct ?? '—' }}</code></td>
                            </tr>
                            <tr>
                                <th>Municipio:</th>
                                <td>{{ $centro->municipio ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Encargado:</th>
                                <td>{{ $centro->encargado ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Correo electrónico:</th>
                                <td>
                                    @if($centro->correo_encargado)
                                        <a href="mailto:{{ $centro->correo_encargado }}">
                                            <i class="bi bi-envelope"></i> {{ $centro->correo_encargado }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-4">
                                        <h3 class="text-primary">{{ $totalAlumnos ?? 0 }}</h3>
                                        <small>Total Alumnos</small>
                                    </div>
                                    <div class="col-4">
                                        <h3 class="text-info">{{ $hombres ?? 0 }}</h3>
                                        <small>Hombres</small>
                                    </div>
                                    <div class="col-4">
                                        <h3 class="text-success">{{ $mujeres ?? 0 }}</h3>
                                        <small>Mujeres</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de alumnos -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Alumnos inscritos ({{ $totalAlumnos ?? 0 }})</h5>
            </div>
            <div class="card-body">
                @if(isset($alumnos) && $alumnos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Matrícula</th>
                                    <th>Nombre completo</th>
                                    <th>Género</th>
                                    <th>Generación</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alumnos as $index => $alumno)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><code>{{ $alumno->matricula }}</code></td>
                                    <td>
                                        <strong>{{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno ?? '' }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if(($alumno->genero ?? '') == 'M')
                                            <span class="badge bg-info">Masculino</span>
                                        @elseif(($alumno->genero ?? '') == 'F')
                                            <span class="badge bg-success">Femenino</span>
                                        @else
                                            <span class="badge bg-secondary">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $alumno->generacion ?? '—' }}</td>
                                    <td>
                                        @if(($alumno->estatus ?? '') == 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">{{ $alumno->estatus ?? '—' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('alumnos.show', $alumno->id_alumno) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-inbox"></i> No hay alumnos inscritos en este centro
                    </div>
                @endif
            </div>
        </div>
        @else
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> Centro no encontrado
        </div>
        @endif
    </div>
</div>
@endsection