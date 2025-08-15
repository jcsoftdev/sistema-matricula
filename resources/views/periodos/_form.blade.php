{{-- Reusable form for create and edit operations --}}
@php
    $isEdit = isset($periodo) && $periodo->exists;
    $formAction = $isEdit ? route('periodos.update', $periodo) : route('periodos.store');
    $formTitle = $isEdit ? 'Editar Período Académico' : 'Crear Período Académico';
    $submitText = $isEdit ? 'Actualizar Período' : 'Crear Período';
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('layouts.alerts')

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $formTitle }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ $formAction }}" method="POST">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Período *</label>
                                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $isEdit ? $periodo->nombre : '') }}" placeholder="Ej: 2025-I" required>
                                <div class="form-text">Identificador único del período académico</div>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                       value="{{ old('descripcion', $isEdit ? $periodo->descripcion : '') }}" placeholder="Ej: Primer semestre 2025">
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                       value="{{ old('fecha_inicio', $isEdit && $periodo->fecha_inicio ? \Carbon\Carbon::parse($periodo->fecha_inicio)->format('Y-m-d') : '') }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha de Fin *</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror"
                                       value="{{ old('fecha_fin', $isEdit && $periodo->fecha_fin ? \Carbon\Carbon::parse($periodo->fecha_fin)->format('Y-m-d') : '') }}" required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_inicio_prematricula" class="form-label">Inicio Prematrícula</label>
                                <input type="date" name="fecha_inicio_prematricula" id="fecha_inicio_prematricula"
                                       class="form-control @error('fecha_inicio_prematricula') is-invalid @enderror"
                                       value="{{ old('fecha_inicio_prematricula', $isEdit && $periodo->fecha_inicio_prematricula ? \Carbon\Carbon::parse($periodo->fecha_inicio_prematricula)->format('Y-m-d') : '') }}">
                                @error('fecha_inicio_prematricula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin_prematricula" class="form-label">Fin Prematrícula</label>
                                <input type="date" name="fecha_fin_prematricula" id="fecha_fin_prematricula"
                                       class="form-control @error('fecha_fin_prematricula') is-invalid @enderror"
                                       value="{{ old('fecha_fin_prematricula', $isEdit && $periodo->fecha_fin_prematricula ? \Carbon\Carbon::parse($periodo->fecha_fin_prematricula)->format('Y-m-d') : '') }}">
                                @error('fecha_fin_prematricula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Cost Configuration Section --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <h5 class="text-primary"><i class='bx bx-money'></i> Configuración de Costos</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="costo_matricula" class="form-label">Costo de Matrícula *</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="costo_matricula" id="costo_matricula"
                                           class="form-control @error('costo_matricula') is-invalid @enderror"
                                           value="{{ old('costo_matricula', $isEdit ? $periodo->costo_matricula : '0.00') }}"
                                           step="0.01" min="0" required>
                                </div>
                                <div class="form-text">Costo único de matrícula por estudiante</div>
                                @error('costo_matricula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="costo_mensualidad" class="form-label">Costo de Mensualidad *</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="costo_mensualidad" id="costo_mensualidad"
                                           class="form-control @error('costo_mensualidad') is-invalid @enderror"
                                           value="{{ old('costo_mensualidad', $isEdit ? $periodo->costo_mensualidad : '0.00') }}"
                                           step="0.01" min="0" required>
                                </div>
                                <div class="form-text">Costo mensual por estudiante</div>
                                @error('costo_mensualidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="descuento_maximo" class="form-label">Descuento Máximo</label>
                                <div class="input-group">
                                    <input type="number" name="descuento_maximo" id="descuento_maximo"
                                           class="form-control @error('descuento_maximo') is-invalid @enderror"
                                           value="{{ old('descuento_maximo', $isEdit ? $periodo->descuento_maximo : '0.00') }}"
                                           step="0.01" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Porcentaje máximo de descuento aplicable</div>
                                @error('descuento_maximo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                           {{ old('activo', $isEdit ? $periodo->activo : false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        <strong>Período Activo</strong>
                                    </label>
                                </div>
                                <div class="form-text">Solo puede haber un período activo a la vez. Si marca este período como activo, los demás se desactivarán automáticamente.</div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class='bx bx-info-circle'></i>
                            <strong>Información:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Solo puede haber un período académico activo a la vez</li>
                                <li>Las fechas de prematrícula son opcionales pero recomendadas</li>
                                <li>Los costos de matrícula y mensualidad son obligatorios</li>
                                <li>El descuento máximo permite establecer un límite para becas y promociones</li>
                                <li>Los cambios se aplicarán inmediatamente al sistema</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('periodos.index') }}" class="btn btn-secondary me-2">
                                <i class='bx bx-x'></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> {{ $submitText }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
