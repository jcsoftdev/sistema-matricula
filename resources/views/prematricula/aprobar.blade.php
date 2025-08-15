@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('layouts.alerts')

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="m-0">Aprobar Prematrículas</h5>
            </div>
            <div class="card-body">
                @if($prematriculas->isEmpty())
                    <p class="text-muted">No hay prematrículas pendientes de aprobación.</p>
                @else
                    <div class="table table-responsive">
                        <table class="table table-bordered">
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
