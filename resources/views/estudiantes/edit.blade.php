@extends('layouts.main')

@section('content')


<div class="row">

    <div class="col-md-12">

        @include('layouts.alerts')

        <div class="card ">

            <div class="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Actualizar datos del estudiante</h5>
                </div>
                <form action="{{route('estudiantes.update', $estudiante->id)}}" method="POST">
                  @csrf
                  @method('put')

                  <div class="modal-body">

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">DNI</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-id-card' ></i></span>
                                <input type="text" name="dni_estudiante" value="{{ $estudiante->dni_estudiante }}" class="form-control" maxlength="8" minlength="8" placeholder="Número de DNI" required onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nombres</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" name="nombres_estudiante" value="{{ $estudiante->nombres_estudiante }}" class="form-control" placeholder="Nombres" required/>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Apellidos</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-user-detail'></i></span>
                                <input type="text" name="apellidos_estudiante" value="{{ $estudiante->apellidos_estudiante }}" class="form-control" placeholder="Apellidos" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Género</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-select-multiple' ></i></span>
                                <select name="genero" class="form-select" required>
                                    <option selected disabled value="">Seleccione...</option>
                                    <option @if($estudiante->genero=='M'){{'selected'}}@endif>M</option>
                                    <option @if($estudiante->genero=='F'){{'selected'}}@endif>F</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Fecha de nacimiento</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-user-detail'></i></span>
                                <input type="date" name="fecha_nacimiento" value="{{ $estudiante->fecha_nacimiento }}" class="form-control" placeholder="Apellidos" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="apoderado-search-edit">Apoderado</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                                <input type="text" id="apoderado-search-edit" class="form-control"
                                       placeholder="@if($estudiante->apoderado){{ $estudiante->apoderado->nombres_apoderado }} {{ $estudiante->apoderado->apellidos_apoderado }} (DNI: {{ $estudiante->apoderado->dni_apoderado }})@else Buscar apoderado por nombre o DNI...@endif"
                                       autocomplete="off"/>
                                <input type="hidden" name="apoderado_id" id="apoderado-id-edit" value="{{ $estudiante->apoderado_id }}">
                            </div>
                            <div id="apoderado-results-edit" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; width: 100%;"></div>
                            <div id="selected-apoderado-edit" class="mt-2" @if($estudiante->apoderado) style="display: block;" @else style="display: none;" @endif>
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light border rounded selected-apoderado-card">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class='bx bx-user text-white'></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0" id="selected-apoderado-info-edit">
                                                @if($estudiante->apoderado)
                                                    {{ $estudiante->apoderado->nombres_apoderado }} {{ $estudiante->apoderado->apellidos_apoderado }}
                                                @endif
                                            </h6>
                                            <small class="text-muted" id="selected-apoderado-dni-edit">
                                                @if($estudiante->apoderado)
                                                    DNI: {{ $estudiante->apoderado->dni_apoderado }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light border-0 rounded-circle d-flex align-items-center justify-content-center btn-close-custom" onclick="clearApoderadoEdit()" title="Quitar apoderado" style="width: 24px; height: 24px; opacity: 0.7;">
                                        <i class='bx bx-x' style="font-size: 16px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                  </div>
                  <div class="modal-footer">
                      <a href="{{ route('estudiantes.index') }}" class="btn btn-outline-secondary">
                          Cancelar
                      </a>
                      <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
            </div>


        </div>


    </div>
</div>


@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>

<script>
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

    // Search apoderados function for edit form
    function searchApoderadosEdit(query) {
        if (query.length < 2) {
            $('#apoderado-results-edit').hide();
            return;
        }

        $.ajax({
            url: '/api/apoderados/search',
            method: 'GET',
            data: { search: query },
            success: function(data) {
                const resultsContainer = $('#apoderado-results-edit');
                resultsContainer.empty();

                if (data.length === 0) {
                    resultsContainer.html('<div class="list-group-item">No se encontraron apoderados</div>');
                } else {
                    data.forEach(function(apoderado) {
                        const item = `
                            <button type="button" class="list-group-item list-group-item-action" onclick="selectApoderadoEdit(${apoderado.id}, '${apoderado.nombres_apoderado} ${apoderado.apellidos_apoderado}', '${apoderado.dni_apoderado}')">
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
                $('#apoderado-results-edit').html('<div class="list-group-item text-danger">Error al buscar apoderados</div>').show();
            }
        });
    }

    // Debounced search function for edit
    const debouncedSearchEdit = debounce(searchApoderadosEdit, 300);

    // Event listener for search input in edit form
    $(document).on('input', '#apoderado-search-edit', function() {
        const query = $(this).val();
        debouncedSearchEdit(query);
    });

    // Hide results when clicking outside for edit form
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#apoderado-search-edit, #apoderado-results-edit').length) {
            $('#apoderado-results-edit').hide();
        }
    });

    // Select apoderado function for edit form
    function selectApoderadoEdit(id, name, dni) {
        $('#apoderado-id-edit').val(id);
        $('#apoderado-search-edit').val('');
        $('#apoderado-results-edit').hide();
        $('#selected-apoderado-info-edit').text(name);
        $('#selected-apoderado-dni-edit').text(`DNI: ${dni}`);
        $('#selected-apoderado-edit').show();
    }

    // Clear apoderado selection for edit form
    function clearApoderadoEdit() {
        $('#apoderado-id-edit').val('');
        $('#apoderado-search-edit').val('');
        $('#selected-apoderado-edit').hide();
        $('#apoderado-results-edit').hide();
    }
</script>
@endsection
