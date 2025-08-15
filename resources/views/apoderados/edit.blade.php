@extends('layouts.main')

@section('content')


<div class="row">

    <div class="col-md-12">

        @include('layouts.alerts')

        <div class="card ">

            <div class="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Actualizar datos del apoderado</h5>
                    <small class="text-muted">Edite los datos del apoderado y su cuenta de usuario</small>
                </div>
                <form action="{{route('apoderados.update', $apoderado->id)}}" method="POST">
                  @csrf
                  @method('put')

                  <div class="modal-body">

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">DNI</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-id-card' ></i></span>
                                <input type="text" name="dni_apoderado" value="{{ $apoderado->dni_apoderado }}" class="form-control" maxlength="8" minlength="8" placeholder="Número de DNI" required onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nombres</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" name="nombres_apoderado" id="nombres" value="{{ $apoderado->nombres_apoderado }}" class="form-control" placeholder="Nombres" required/>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Apellidos</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-user-detail'></i></span>
                                <input type="text" name="apellidos_apoderado" id="apellidos" value="{{ $apoderado->apellidos_apoderado }}" class="form-control" placeholder="Apellidos" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Celular</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-phone' ></i></span>
                                <input type="text" name="celular_apoderado" value="{{ $apoderado->celular_apoderado }}" class="form-control phone-mask" required maxlength="9" minlength="9" placeholder="Número de celular" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-primary">
                        <i class='bx bx-user'></i> Datos de usuario del sistema
                        @if($apoderado->user)
                            <span class="badge bg-success ms-2">
                                <i class='bx bx-check'></i> Activo
                            </span>
                        @else
                            <span class="badge bg-warning ms-2">
                                <i class='bx bx-user-x'></i> Sin usuario
                            </span>
                        @endif
                    </h6>

                    @if(!$apoderado->user)
                        <div class="alert alert-info">
                            <i class='bx bx-info-circle'></i>
                            Este apoderado no tiene cuenta de usuario. Complete los campos para crear una.
                        </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="username">Usuario</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                                <input type="text" name="username" id="username" class="form-control"
                                       value="{{ $apoderado->user ? $apoderado->user->username : '' }}"
                                       placeholder="Nombre de usuario" required/>
                            </div>
                            <div class="form-text">Este será el nombre de usuario para acceder al sistema</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="password">
                            {{ $apoderado->user ? 'Nueva contraseña' : 'Contraseña' }}
                        </label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-lock'></i></span>
                                <input type="password" name="password" id="password" class="form-control"
                                       placeholder="{{ $apoderado->user ? 'Dejar vacío para mantener actual' : 'Contraseña (opcional - se usará DNI por defecto)' }}"
                                       minlength="6"/>
                            </div>
                            <div class="form-text">
                                {{ $apoderado->user ? 'Dejar vacío para mantener la contraseña actual. Mínimo 6 caracteres si se cambia.' : 'Mínimo 6 caracteres. Si se deja vacío, se usará el DNI como contraseña.' }}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="password_confirmation">Confirmar</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                       placeholder="Confirmar contraseña" minlength="6"/>
                            </div>
                        </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                      <a href="{{ route('apoderados.index') }}" class="btn btn-outline-secondary">
                          Cancelar
                      </a>
                      <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
            </div>

        </div>

    </div>
</div>

@if ($errors->any())
    <div class="bs-toast toast toast-placement-ex m-2 fade bg-warning bottom-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
        <div class="toast-header">
            <i class='bx bx-error'></i>
            <div class="me-auto fw-semibold">Alerta</div>
            <small>Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Complete los siguientes campos
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@endsection

@section('js')
<script>
$(document).ready(function() {
    // Auto-generate username based on names if no user exists or when names change
    $('#nombres, #apellidos').on('input', function() {
        @if(!$apoderado->user)
            generateUsername();
        @endif
    });

    function generateUsername() {
        var nombres = $('#nombres').val().toLowerCase().trim();
        var apellidos = $('#apellidos').val().toLowerCase().trim();

        if (nombres && apellidos) {
            var username = nombres.split(' ')[0] + '.' + apellidos.split(' ')[0];
            // Remove accents and special characters
            username = username.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            username = username.replace(/[^a-z0-9.]/g, '');
            $('#username').val(username);
        }
    }

    // Password confirmation validation
    $('#password_confirmation').on('input', function() {
        var password = $('#password').val();
        var confirmation = $(this).val();

        if (password && confirmation && password !== confirmation) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Las contraseñas no coinciden</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Username validation
    $('#username').on('input', function() {
        var username = $(this).val();
        if (username.length > 0 && username.length < 3) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">El usuario debe tener al menos 3 caracteres</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Password validation for existing users - make confirmation required if password is entered
    @if($apoderado->user)
        $('#password').on('input', function() {
            var password = $(this).val();

            // If password is provided, confirmation is required
            if (password.length > 0) {
                $('#password_confirmation').attr('required', true);
                if (password.length < 6) {
                    $(this).addClass('is-invalid');
                    if (!$(this).siblings('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }
            } else {
                $('#password_confirmation').removeAttr('required').val('');
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            }
        });
    @endif

    // Auto-focus behavior for new users
    @if(!$apoderado->user)
        $('#nombres, #apellidos').on('input', function() {
            setTimeout(function() {
                if ($('#nombres').val() && $('#apellidos').val() && !$('#username').val()) {
                    $('#username').focus();
                }
            }, 300);
        });
    @endif
});
</script>
