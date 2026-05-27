@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-building"></i> Centros Educativos</h4>
                <div>
                    <small>Total: {{ $totalCentros ?? 0 }} centros | {{ $totalAlumnos ?? 0 }} alumnos</small>
                </div>
            </div>
            <div class="card-body">
                <!-- Buscador -->
                <form method="GET" action="{{ route('centros.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       name="busqueda" 
                                       placeholder="Buscar por nombre, clave CT, municipio o encargado..." 
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
                                <a href="{{ route('centros.index') }}" class="btn btn-secondary w-100">
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Tabla de centros -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Clave CT</th>
                                <th>Nombre del Telebachillerato</th>
                                <th>Municipio</th>
                                <th>Encargado</th>
                                <th>Alumnos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($centros as $index => $centro)
                            @php
                                $totalAlumnosCentro = DB::table('alumnos')->where('id_centro', $centro->id_centro)->count();
                            @endphp
                            <tr>
                                <td>{{ $centros->firstItem() + $index }}</td>
                                <td><code>{{ $centro->clave_ct }}</code></td>
                                <td><strong>{{ $centro->nombre }}</strong></td>
                                <td>{{ $centro->municipio ?? '—' }}</td>
                                <td>{{ $centro->encargado ?? '—' }}</td>
                                <td><span class="badge bg-info">{{ $totalAlumnosCentro }}</span></td>
                                <td>
                                    <a href="{{ route('centros.show', $centro->id_centro) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">No se encontraron centros</p>
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
                            Mostrando {{ $centros->firstItem() ?? 0 }} a {{ $centros->lastItem() ?? 0 }} de {{ $centros->total() }} centros
                        </small>
                    </div>
                    <div>
                        {{ $centros->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection