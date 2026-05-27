<x-guest-layout>
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0"><i class="bi bi-mortarboard-fill"></i> Sistema de Gestión</h4>
            <small>Telebachillerato</small>
        </div>
        <div class="card-body p-4">
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Correo electrónico
                    </label>
                    <input id="email" 
                           type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="ejemplo@correo.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Contraseña
                    </label>
                    <input id="password" 
                           type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required
                           placeholder="********">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Recordarme en este equipo
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-question-circle"></i> ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-footer text-center text-muted bg-light">
            <small>
                <i class="bi bi-info-circle"></i> 
                Credenciales de prueba:<br>
                <strong>Admin:</strong> admin@sistema.com / admin123<br>
                <strong>Consultor:</strong> consultor@sistema.com / consultor123
            </small>
        </div>
    </div>
</x-guest-layout>