@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info text-center">
            <h4><i class="bi bi-mortarboard-fill"></i> Bienvenido al Sistema de Gestión</h4>
            <p class="mb-0">Selecciona una opción del menú superior para comenzar</p>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <i class="bi bi-building" style="font-size: 48px;"></i>
                        <h2 class="mt-2">{{ $totalCentros ?? 0 }}</h2>
                        <h5>Centros Educativos</h5>
                        <a href="{{ route('centros.index') }}" class="btn btn-light mt-2">Ver Centros</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <i class="bi bi-people" style="font-size: 48px;"></i>
                        <h2 class="mt-2">{{ $totalAlumnos ?? 0 }}</h2>
                        <h5>Alumnos Registrados</h5>
                        <a href="{{ route('alumnos.index') }}" class="btn btn-light mt-2">Ver Alumnos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <i class="bi bi-star" style="font-size: 48px;"></i>
                        <h2 class="mt-2">{{ $totalCalificaciones ?? 0 }}</h2>
                        <h5>Calificaciones</h5>
                        <a href="{{ route('calificaciones.index') }}" class="btn btn-light mt-2">Capturar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection