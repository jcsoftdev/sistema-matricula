@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            @include('layouts.alerts')

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Períodos Académicos</h4>
                    <a href="{{ route('periodos.create') }}" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Nuevo Período
                    </a>
                </div>
                <div class="card-body">
                    @if($periodos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Período Académico</th>
                                        <th>Prematrícula</th>
                                        <th>Costos</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($periodos as $periodo)
                                        <tr>
                                            <td>
                                                <strong>{{ $periodo->nombre }}</strong>
                                            </td>
                                            <td>{{ $periodo->descripcion ?: 'Sin descripción' }}</td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $periodo->fecha_inicio->format('d/m/Y') }} -
                                                    {{ $periodo->fecha_fin->format('d/m/Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($periodo->fecha_inicio_prematricula && $periodo->fecha_fin_prematricula)
                                                    <small class="text-muted">
                                                        {{ $periodo->fecha_inicio_prematricula->format('d/m/Y') }} -
                                                        {{ $periodo->fecha_fin_prematricula->format('d/m/Y') }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">No configurado</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <div><strong>Matrícula:</strong> S/. {{ number_format($periodo->costo_matricula, 2) }}</div>
                                                    <div><strong>Mensualidad:</strong> S/. {{ number_format($periodo->costo_mensualidad, 2) }}</div>
                                                    @if($periodo->descuento_maximo > 0)
                                                        <div><strong>Desc. máx:</strong> {{ number_format($periodo->descuento_maximo, 2) }}%</div>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($periodo->activo)
                                                    <span class="badge bg-success">
                                                        <i class='bx bx-check'></i> Activo
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(!$periodo->activo)
                                                        <form action="{{ route('periodos.activate', $periodo) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success" title="Activar período">
                                                                <i class='bx bx-play'></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class='bx bx-edit'></i>
                                                    </a>

                                                    @if($periodo->matriculas->count() === 0)
                                                        <form action="{{ route('periodos.destroy', $periodo) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('¿Estás seguro de eliminar este período académico?')"
                                                                    title="Eliminar">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class='bx bx-calendar-event display-1 text-muted'></i>
                            <p class="text-muted mt-3">No hay períodos académicos registrados</p>
                            <a href="{{ route('periodos.create') }}" class="btn btn-primary">
                                <i class='bx bx-plus'></i> Crear Primer Período
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
