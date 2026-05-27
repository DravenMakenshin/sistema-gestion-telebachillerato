@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Usuario</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre completo:</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico:</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contraseña:</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               required>
                        <small class="text-muted">Mínimo 6 caracteres</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rol:</label>
                        <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                            <option value="">-- Seleccione un rol --</option>
                            <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="consultor" {{ old('rol') == 'consultor' ? 'selected' : '' }}>Consultor</option>
                        </select>
                        @error('rol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Usuario
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection