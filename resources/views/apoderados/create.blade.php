@extends('layouts.main')

@section('content')


<div class="row">

    <div class="col-md-8 offset-md-2">
        @include('layouts.alerts')

        <div class="card ">

            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Registrar apoderado</h5>
                    <small class="text-muted">Complete los datos del apoderado y su cuenta de usuario</small>
                </div>
                <form action="{{route('apoderados.store')}}" method="post">
                    @csrf
                    @method('post')
                    <div class="card-body">

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">DNI</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bxs-id-card' ></i></span>
                                    <input type="text" name="dni_apoderado" id="dni_search" class="form-control" maxlength="8" minlength="8" placeholder="DNI del apoderado" required onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/>
                                    {{-- <button class="btn btn-outline-primary" type="button" id="btn_search_student"> <i class='bx bx-search-alt-2'></i> Buscar</button> --}}
                                    <input id="btnBuscar" type="button" value="Buscar" class="btn btn-outline-primary" >

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-info" id="mensaje_busqueda" ></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nombres</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="text" name="nombres_apoderado" id="nombres" class="form-control" placeholder="Nombres" required/>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Apellidos</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bxs-user-detail'></i></span>
                                    <input type="text" name="apellidos_apoderado" id="apellidos" class="form-control" placeholder="Apellidos" required/>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Celular</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bx-phone' ></i></span>
                                    <input type="text" name="celular_apoderado" class="form-control phone-mask" required maxlength="9" minlength="9" placeholder="Número de celular" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-primary">
                            <i class='bx bx-user'></i> Datos de usuario del sistema
                        </h6>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="username">Usuario</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de usuario" required/>
                                </div>
                                <div class="form-text">Este será el nombre de usuario para acceder al sistema</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="password">Contraseña</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bx-lock'></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña (opcional - se usará el DNI por defecto)" minlength="6"/>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres. Si se deja vacío, se usará el DNI como contraseña</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="password_confirmation">Confirmar</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmar contraseña (opcional)" minlength="6"/>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-center">
                        <a class="btn btn-secondary" href="{{route('apoderados.index')}}" ><i class='bx bx-x'></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class='bx bxs-save' ></i> Guardar</button>
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
<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
<script src="{{ asset("assets/js/reniec.js") }}"></script>
<script>
$(document).ready(function() {
    console.log('Create form JS loaded'); // Debug log

    // Auto-generate username based on names
    $('#nombres, #apellidos').on('input', function() {
        console.log('Names changed, generating username'); // Debug log
        var nombres = $('#nombres').val().toLowerCase().trim();
        var apellidos = $('#apellidos').val().toLowerCase().trim();

        if (nombres && apellidos) {
            var username = nombres.split(' ')[0] + '.' + apellidos.split(' ')[0];
            // Remove accents and special characters
            username = username.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            username = username.replace(/[^a-z0-9.]/g, '');
            console.log('Generated username:', username); // Debug log
            $('#username').val(username);
        }
    });

    // Password confirmation validation
    $('#password_confirmation').on('input', function() {
        var password = $('#password').val();
        var confirmation = $(this).val();

        if (password !== confirmation) {
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

    // Test if fields exist
    console.log('Nombres field exists:', $('#nombres').length > 0);
    console.log('Apellidos field exists:', $('#apellidos').length > 0);
    console.log('Username field exists:', $('#username').length > 0);
});
</script>
@endsection
