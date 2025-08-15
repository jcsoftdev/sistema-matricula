@extends('layouts.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        #apoderado-results, #apoderado-results-edit {
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .list-group-item-action:hover {
            background-color: #f8f9fa;
        }

        .selected-apoderado-card {
            transition: all 0.2s ease-in-out;
        }

        .selected-apoderado-card:hover {
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .btn-close-custom {
            transition: all 0.2s ease-in-out;
        }

        .btn-close-custom:hover {
            opacity: 1 !important;
            background-color: #f8f9fa !important;
            transform: scale(1.1);
        }

        .selected-apoderado-card:hover .btn-close-custom {
            opacity: 1 !important;
        }
    </style>
@endsection

@section('content')

<div class="row">

    <div class="col-md-12">

        @include('layouts.alerts')

        <div class="card ">

            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">ESTUDIANTES</h5>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-create-estudiante" >
                    <i class='bx bx-plus'></i> Agregar estudiante
                </button>
            </div>

            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table tablesorter" id="example">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-white">ID</th>
                                <th class="text-white">DNI</th>
                                <th class="text-white">Nombres y apellidos</th>
                                <th class="text-white">Género</th>
                                <th class="text-white">Fecha de nacimiento</th>
                                <th class="text-white">Apoderado</th>
                                <th class="text-white">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estudiantes as $estudiante)
                                <tr>
                                    <td>{{$estudiante->id}}</td>
                                    <td>{{$estudiante->dni_estudiante}}</td>
                                    <td>{{$estudiante->nombres_estudiante." ".$estudiante->apellidos_estudiante}}</td>
                                    <td>{{$estudiante->genero}}</td>
                                    <td>{{$estudiante->fecha_nacimiento}}</td>
                                    <td>
                                        @if($estudiante->apoderado)
                                            {{$estudiante->apoderado->nombres_apoderado." ".$estudiante->apoderado->apellidos_apoderado}}
                                            <br><small class="text-muted">DNI: {{$estudiante->apoderado->dni_apoderado}}</small>
                                        @else
                                            <span class="text-muted">Sin apoderado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('estudiantes.edit', $estudiante->id)}}" class="btn btn-sm btn-outline-warning"><i class='bx bx-edit-alt' ></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$estudiante->id}}">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        @include('estudiantes.modal-delete')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="card-footer">
                {{ $estudiantes->links() }}
            </div> --}}
        </div>
    </div>
</div>

@include('estudiantes.modal-create')

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

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Search apoderados function
    function searchApoderados(query) {
        if (query.length < 2) {
            $('#apoderado-results').hide();
            return;
        }

        $.ajax({
            url: '/api/apoderados/search',
            method: 'GET',
            data: { search: query },
            success: function(data) {
                const resultsContainer = $('#apoderado-results');
                resultsContainer.empty();

                if (data.length === 0) {
                    resultsContainer.html('<div class="list-group-item">No se encontraron apoderados</div>');
                } else {
                    data.forEach(function(apoderado) {
                        const item = `
                            <button type="button" class="list-group-item list-group-item-action" onclick="selectApoderado(${apoderado.id}, '${apoderado.nombres_apoderado} ${apoderado.apellidos_apoderado}', '${apoderado.dni_apoderado}')">
                                <strong>${apoderado.nombres_apoderado} ${apoderado.apellidos_apoderado}</strong><br>
                                <small class="text-muted">DNI: ${apoderado.dni_apoderado}</small>
                            </button>
                        `;
                        resultsContainer.append(item);
                    });
                }
                resultsContainer.show();
            },
            error: function() {
                $('#apoderado-results').html('<div class="list-group-item text-danger">Error al buscar apoderados</div>').show();
            }
        });
    }

    // Debounced search function
    const debouncedSearch = debounce(searchApoderados, 300);

    // Event listener for search input
    $(document).on('input', '#apoderado-search', function() {
        const query = $(this).val();
        debouncedSearch(query);
    });

    // Hide results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#apoderado-search, #apoderado-results').length) {
            $('#apoderado-results').hide();
        }
    });

    // Select apoderado function
    function selectApoderado(id, name, dni) {
        $('#apoderado-id').val(id);
        $('#apoderado-search').val('');
        $('#apoderado-results').hide();
        $('#selected-apoderado-info').text(name);
        $('#selected-apoderado-dni').text(`DNI: ${dni}`);
        $('#selected-apoderado').show();
    }

    // Clear apoderado selection
    function clearApoderado() {
        $('#apoderado-id').val('');
        $('#apoderado-search').val('');
        $('#selected-apoderado').hide();
        $('#apoderado-results').hide();
    }

    // Reset form when modal is closed
    $('#modal-create-estudiante').on('hidden.bs.modal', function () {
        clearApoderado();
        $(this).find('form')[0].reset();
    });
</script>
@endsection
