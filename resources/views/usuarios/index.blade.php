@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-people"></i> Gestión de Usuarios</h4>
                <a href="{{ route('usuarios.create') }}" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Nuevo Usuario
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>
                                    {{ $usuario->name }}
                                    @if($usuario->id === auth()->id())
                                        <span class="badge bg-info ms-2">Tú</span>
                                    @endif
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->rol == 'admin')
                                        <span class="badge bg-danger">Administrador</span>
                                    @else
                                        <span class="badge bg-secondary">Consultor</span>
                                    @endif
                                </td>
                                <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    @if($usuario->id !== auth()->id())
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection