@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('layouts.alerts')

        <!-- Período Académico Summary -->
        @if(isset($periodoActivo))
            <div class="card mb-4 border-info">
                <div class="card-body bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-1 text-info">
                                <i class='bx bx-calendar-event'></i> Período Académico Activo
                            </h5>
                            <h4 class="mb-0">{{ $periodoActivo->nombre }}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Total Prematrículas</small>
                                    <span class="badge bg-primary fs-6">{{ $prematriculas->count() }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Estado Prematrícula</small>
                                    @if($periodoActivo->isPrematriculaAberta())
                                        <span class="badge bg-success">Abierta</span>
                                    @else
                                        <span class="badge bg-danger">Cerrada</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">Lista de Prematrículas
                    @if(isset($periodoActivo))
                        - {{ $periodoActivo->nombre }}
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table" id="example">
                        <thead class="table-dark">
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Grado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prematriculas as $p)
                                <tr>
                                    <td>{{ $p->dni_estudiante }}</td>
                                    <td>{{ $p->nombres_estudiante }}</td>
                                    <td>{{ $p->apellidos_estudiante }}</td>
                                    <td>{{ $p->grado_postula }}</td>
                                    <td>
                                        <form action="{{ route('prematriculas.aprobar', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bx bx-check"></i> Aprobar
                                            </button>
                                        </form>
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

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>
<script>
$(document).ready( function () {
    $('#example').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
    });
});
</script>
@endsection
