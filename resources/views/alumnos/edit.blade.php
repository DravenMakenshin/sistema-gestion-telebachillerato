@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="mb-3">
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="{{ route('alumnos.show', $alumno->id_alumno) }}" class="btn btn-info">
                <i class="bi bi-eye"></i> Ver Alumno
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Alumno</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('alumnos.update', $alumno->id_alumno) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Matrícula:</label>
                            <input type="text" class="form-control" value="{{ $alumno->matricula }}" disabled>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Centro:</label>
                            <select name="id_centro" class="form-select">
                                <option value="">-- Seleccione un centro --</option>
                                @foreach($centros as $centro)
                                    <option value="{{ $centro->id_centro }}" 
                                        {{ ($alumno->id_centro ?? '') == $centro->id_centro ? 'selected' : '' }}>
                                        {{ $centro->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" 
                                   value="{{ old('nombre', $alumno->nombre ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Apellido Paterno:</label>
                            <input type="text" name="apellido_paterno" class="form-control" 
                                   value="{{ old('apellido_paterno', $alumno->apellido_paterno ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Apellido Materno:</label>
                            <input type="text" name="apellido_materno" class="form-control" 
                                   value="{{ old('apellido_materno', $alumno->apellido_materno ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Género:</label>
                            <select name="genero" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option value="M" {{ ($alumno->genero ?? '') == 'M' ? 'selected' : '' }}>Masculino (M)</option>
                                <option value="F" {{ ($alumno->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino (F)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Generación:</label>
                            <input type="text" name="generacion" class="form-control" 
                                   value="{{ old('generacion', $alumno->generacion ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estatus:</label>
                            <select name="estatus" class="form-select">
                                <option value="Activo" {{ ($alumno->estatus ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ ($alumno->estatus ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="Egresado" {{ ($alumno->estatus ?? '') == 'Egresado' ? 'selected' : '' }}>Egresado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Municipio de Residencia:</label>
                            <input type="text" name="municipio_residencia" class="form-control" 
                                   value="{{ old('municipio_residencia', $alumno->municipio_residencia ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">País de Nacimiento:</label>
                            <input type="text" name="pais_nacimiento" class="form-control" 
                                   value="{{ old('pais_nacimiento', $alumno->pais_nacimiento ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" 
                                   value="{{ old('fecha_nacimiento', $alumno->fecha_nacimiento ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Alumno
                        </button>
                        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection