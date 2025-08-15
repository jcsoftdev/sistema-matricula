@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Período Académico: {{ $periodo->nombre }}</h1>
            @include('layouts.alerts')
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Período</h3>
                    <div class="card-tools">
                        @if($periodo->activo)
                            <span class="badge badge-success">ACTIVO</span>
                        @else
                            <span class="badge badge-secondary">INACTIVO</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Información General</h5>
                            <table class="table table-striped">
                                <tr>
                                    <td><strong>Nombre:</strong></td>
                                    <td>{{ $periodo->nombre }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Inicio:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Fin:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        @if($periodo->activo)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <h5>Prematrícula</h5>
                            <table class="table table-striped">
                                <tr>
                                    <td><strong>Inicio:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio_prematricula)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fin:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_fin_prematricula)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        @if($periodo->isPrematriculaAberta())
                                            <span class="badge badge-success">Abierta</span>
                                        @else
                                            <span class="badge badge-danger">Cerrada</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <h5>Costos y Descuentos</h5>
                            <table class="table table-striped">
                                <tr>
                                    <td><strong>Costo Matrícula:</strong></td>
                                    <td>S/. {{ number_format($periodo->costo_matricula, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Costo Mensualidad:</strong></td>
                                    <td>S/. {{ number_format($periodo->costo_mensualidad, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Descuento Máximo:</strong></td>
                                    <td>{{ number_format($periodo->descuento_maximo, 2) }}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Base:</strong></td>
                                    <td><strong>S/. {{ number_format($periodo->costo_matricula + $periodo->costo_mensualidad, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>

                    @if(!$periodo->activo)
                        <form action="{{ route('periodos.activate', $periodo) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('¿Está seguro de activar este período? Se desactivarán los demás períodos activos.')">
                                <i class="fas fa-check"></i> Activar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('periodos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    @if($periodo->matriculas()->count() == 0)
                        <form action="{{ route('periodos.destroy', $periodo) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('¿Está seguro de eliminar este período académico?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Matrículas</span>
                            <span class="info-box-number">{{ $periodo->matriculas()->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Prematrículas</span>
                            <span class="info-box-number">{{ $periodo->matriculas()->prematricula()->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-check"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Matriculados</span>
                            <span class="info-box-number">{{ $periodo->matriculas()->matricula()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
