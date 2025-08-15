@extends('layouts.main')

@section('css')
    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        .student-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .student-card.selected {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }

        .student-card:hover:not(.selected):not(.disabled) {
            border-color: #dee2e6;
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .student-card.disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .apoderado-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
@endsection

@section('content')
<div class="container">
    @include('layouts.alerts')

    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Período Académico Summary -->
            @if(isset($periodoActivo))
                <div class="card mb-4 border-primary">
                    <div class="card-body bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="card-title mb-1 text-primary">
                                    <i class='bx bx-calendar-event'></i> Período Académico Actual
                                </h5>
                                <h4 class="mb-2">{{ $periodoActivo->nombre }}</h4>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small class="text-muted">
                                            <strong>Período:</strong>
                                            {{ \Carbon\Carbon::parse($periodoActivo->fecha_inicio)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($periodoActivo->fecha_fin)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">
                                            <strong>Prematrícula:</strong>
                                            @if($periodoActivo->fecha_inicio_prematricula && $periodoActivo->fecha_fin_prematricula)
                                                {{ \Carbon\Carbon::parse($periodoActivo->fecha_inicio_prematricula)->format('d/m/Y') }} -
                                                {{ \Carbon\Carbon::parse($periodoActivo->fecha_fin_prematricula)->format('d/m/Y') }}
                                            @else
                                                No definido
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                @if($periodoActivo->isPrematriculaAberta())
                                    <span class="badge bg-success fs-6 p-2">
                                        <i class='bx bx-check-circle'></i> Prematrícula Abierta
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6 p-2">
                                        <i class='bx bx-x-circle'></i> Prematrícula Cerrada
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Formulario de Prematrícula</h4>
                </div>
                <div class="card-body">

                    @if(isset($mensaje))
                        <div class="alert alert-warning">
                            <i class='bx bx-info-circle'></i> {{ $mensaje }}
                        </div>
                    @else

                    <!-- Información del Apoderado Logueado -->
                    <div class="mb-4">
                        <div class="card apoderado-info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class='bx bx-user text-white'></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 text-white">{{ $apoderado->nombres_apoderado }} {{ $apoderado->apellidos_apoderado }}</h5>
                                        <small class="text-white-50">DNI: {{ $apoderado->dni_apoderado }}</small>
                                        @if($apoderado->celular_apoderado)
                                            <small class="text-white-50"> | Celular: {{ $apoderado->celular_apoderado }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('prematricula.store') }}" method="POST" id="prematricula-form">
                        @csrf

                        <!-- Lista de Estudiantes del Apoderado -->
                        <div class="mb-4">
                            <label class="form-label">Seleccionar Estudiante</label>
                            @if(isset($estudiantes) && $estudiantes->count() > 0)
                                <div class="row" id="students-list">
                                    @foreach($estudiantes as $student)
                                        @php
                                            $matricula = $student->matriculas->first();
                                            $status = $matricula ? $matricula->status : null;
                                        @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="card student-card h-100 @if($status) disabled @endif"
                                                 @if(!$status) onclick="selectStudent({{ $student->id }})" @endif>
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <i class='bx bx-user text-white'></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title mb-1">{{ $student->nombres_estudiante }} {{ $student->apellidos_estudiante }}</h6>
                                                            <small class="text-muted">DNI: {{ $student->dni_estudiante }}</small><br>
                                                            <small class="text-muted">Género: {{ $student->genero }} | Nacimiento: {{ $student->fecha_nacimiento }}</small>

                                                            @if($status)
                                                                <div class="mt-2">
                                                                    @if($status === 'prematricula')
                                                                        <span class="badge bg-warning">
                                                                            <i class='bx bx-clock'></i> Prematriculado
                                                                        </span>
                                                                    @elseif($status === 'matricula')
                                                                        <span class="badge bg-success">
                                                                            <i class='bx bx-check'></i> Matriculado
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="mt-2">
                                                                    <span class="badge bg-secondary">
                                                                        <i class='bx bx-plus'></i> Disponible para prematrícula
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(!isset($estudiantes))
                                <!-- Students not loaded due to inactive period or other issues -->
                            @else
                                <div class="alert alert-info">
                                    <i class='bx bx-info-circle'></i> No tienes estudiantes registrados.
                                    <a href="{{ route('estudiantes.index') }}" class="alert-link">Registra un estudiante primero</a>.
                                </div>
                            @endif
                            <input type="hidden" name="estudiante_id" id="estudiante-id" value="">
                        </div>

                        <!-- Información adicional de la matrícula -->
                        <div id="matricula-section" class="mb-4" style="display: none;">
                            <h5 class="mb-3">Información de la Matrícula</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nivel" class="form-label">Nivel</label>
                                    <select name="nivel" id="nivel_select" class="form-select" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        <option value="inicial">Inicial</option>
                                        <option value="primaria">Primaria</option>
                                        <option value="secundaria">Secundaria</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="grado_postula" class="form-label">Grado al que postula</label>
                                    <select name="grado_postula" id="grado_select" class="form-select" required>
                                        <option value="" selected disabled>Seleccione nivel primero...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                <i class='bx bx-send'></i> Enviar Prematrícula
                            </button>
                        </div>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>

<script>
    // Grade options for each level
    const inicial = ['3 Años', '4 Años', '5 Años'];
    const primaria = ['1°','2°','3°','4°','5°','6°'];
    const secundaria = ['1°','2°','3°','4°','5°'];

    // Handle level change to update grade options
    $(document).on('change','#nivel_select', function(){
        let result = '<option value="" selected disabled>Seleccione grado...</option>';

        switch($('#nivel_select').val()){
            case 'inicial':
                inicial.forEach(element => {
                    result += `<option value="${element}">${element}</option>`;
                });
                break;
            case 'primaria':
                primaria.forEach(element => {
                    result += `<option value="${element}">${element}</option>`;
                });
                break;
            case 'secundaria':
                secundaria.forEach(element => {
                    result += `<option value="${element}">${element}</option>`;
                });
                break;
        }
        $("#grado_select").html(result);

        // Reset grade selection and recheck form
        checkFormComplete();
    });

    // Select student function
    function selectStudent(studentId) {
        $('.student-card').removeClass('selected');
        $(event.currentTarget).addClass('selected');
        $('#estudiante-id').val(studentId);
        $('#matricula-section').show();
        checkFormComplete();
    }

    // Check if form is complete
    function checkFormComplete() {
        const studentSelected = $('#estudiante-id').val();
        const gradoSelected = $('#grado_select').val();
        const nivelSelected = $('#nivel_select').val();

        if (studentSelected && gradoSelected && nivelSelected) {
            $('#submit-btn').prop('disabled', false);
        } else {
            $('#submit-btn').prop('disabled', true);
        }
    }

    // Form validation
    $('#grado_select, #nivel_select').on('change', function() {
        checkFormComplete();
    });

    // Auto-focus on nivel when student is selected
    $(document).on('click', '.student-card', function() {
        setTimeout(function() {
            $('#nivel_select').focus();
        }, 300);
    });
</script>
@endsection
