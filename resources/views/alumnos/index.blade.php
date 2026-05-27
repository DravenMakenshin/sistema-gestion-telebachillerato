@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-people"></i> Listado de Alumnos</h4>
                <div>
                    <small>Total: {{ $totalAlumnos ?? 0 }} alumnos | 👨 {{ $totalHombres ?? 0 }} | 👩 {{ $totalMujeres ?? 0 }}</small>
                </div>
            </div>
            <div class="card-body">
                <!-- Buscador -->
                <form method="GET" action="{{ route('alumnos.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       name="busqueda" 
                                       placeholder="Buscar por matrícula, nombre, apellido o centro..." 
                                       value="{{ request('busqueda') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="por_pagina" class="form-select" onchange="this.form.submit()">
                                <option value="10" {{ request('por_pagina') == 10 ? 'selected' : '' }}>10 por página</option>
                                <option value="15" {{ request('por_pagina') == 15 ? 'selected' : '' }}>15 por página</option>
                                <option value="25" {{ request('por_pagina') == 25 ? 'selected' : '' }}>25 por página</option>
                                <option value="50" {{ request('por_pagina') == 50 ? 'selected' : '' }}>50 por página</option>
                                <option value="100" {{ request('por_pagina') == 100 ? 'selected' : '' }}>100 por página</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            @if(request('busqueda'))
                                <a href="{{ route('alumnos.index') }}" class="btn btn-secondary w-100">
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Tabla de alumnos -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Matrícula</th>
                                <th>Nombre Completo</th>
                                <th>Centro</th>
                                <th>Género</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumnos as $index => $alumno)
                            <tr>
                                <td>{{ $alumnos->firstItem() + $index }}</td>
                                <td><code>{{ $alumno->matricula }}</code></td>
                                <td>
                                    <strong>{{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</strong>
                                </td>
                                <td>{{ $alumno->centro_nombre ?? 'Sin asignar' }}</td>
                                <td>
                                    @if($alumno->genero == 'M')
                                        <span class="badge bg-info">Masculino</span>
                                    @elseif($alumno->genero == 'F')
                                        <span class="badge bg-success">Femenino</span>
                                    @else
                                        <span class="badge bg-secondary">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($alumno->estatus == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">{{ $alumno->estatus }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('alumnos.show', $alumno->id_alumno) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('alumnos.edit', $alumno->id_alumno) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">No se encontraron alumnos</p>
                                    <small class="text-muted">Intenta con otra búsqueda</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Mostrando {{ $alumnos->firstItem() ?? 0 }} a {{ $alumnos->lastItem() ?? 0 }} de {{ $alumnos->total() }} alumnos
                        </small>
                    </div>
                    <div>
                        {{ $alumnos->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection