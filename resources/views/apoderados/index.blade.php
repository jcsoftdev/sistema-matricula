@extends('layouts.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')


<div class="row">

    <div class="col-md-12">

        @include('layouts.alerts')

        <div class="card ">

            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title m-0">Apoderados</h5>
                    @php
                        $apoderadosSinUsuario = $apoderados->filter(function($apoderado) {
                            return is_null($apoderado->user_id);
                        })->count();
                    @endphp
                    @if($apoderadosSinUsuario > 0)
                        <small class="text-warning">
                            <i class='bx bx-info-circle'></i> {{$apoderadosSinUsuario}} apoderado(s) sin usuario
                        </small>
                    @endif
                </div>
                <!-- Action buttons -->
                <div class="btn-group" role="group">
                    <a href="{{route('apoderados.create')}}" class="btn btn-primary btn-sm" >
                        <i class='bx bx-user-plus'></i> Registrar apoderado
                    </a>
                    @if($apoderadosSinUsuario > 0)
                        <button type="button" class="btn btn-success btn-sm" onclick="createUsersForAll()" id="createUsersBtn">
                            <i class='bx bx-group'></i> Crear {{$apoderadosSinUsuario}} usuario(s)
                        </button>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table tablesorter " id="example">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-white">ID</th>
                                <th class="text-white">DNI</th>
                                <th class="text-white">Nombres</th>
                                <th class="text-white">Apellidos</th>
                                <th class="text-white">Celular</th>
                                <th class="text-white">Usuario</th>
                                <th class="text-white">Matrículas</th>
                                <th class="text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; ?>
                            @foreach ($apoderados as $apoderado)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$apoderado->dni_apoderado}}</td>
                                    <td>{{$apoderado->nombres_apoderado}}</td>
                                    <td>{{$apoderado->apellidos_apoderado}}</td>
                                    <td>{{$apoderado->celular_apoderado}}</td>
                                    <td>
                                        @if($apoderado->user)
                                            <span class="badge bg-success">
                                                <i class='bx bx-check'></i> Sí
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class='bx bx-x'></i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{count($apoderado->matriculas)}}</td>
                                    <td>
                                        @if(!$apoderado->user)
                                            <button type="button" class="btn btn-sm btn-outline-success me-1"
                                                    onclick="createUserForApoderado({{$apoderado->id}})"
                                                    title="Crear usuario">
                                                <i class='bx bx-user-plus'></i>
                                            </button>
                                        @endif
                                        <a href="{{route('apoderados.edit', $apoderado->id)}}" class="btn btn-sm btn-outline-warning"><i class='bx bx-edit-alt' ></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$apoderado->id}}">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        @include('apoderados.modal-delete')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="card-footer">
                {{ $apoderados->links() }}
            </div> --}}
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

    // Create user for specific apoderado
    function createUserForApoderado(apoderadoId) {
        if (confirm('¿Crear usuario para este apoderado? Se usará nombre_apellido como usuario y DNI como contraseña.')) {
            $.ajax({
                url: '/api/apoderados/' + apoderadoId + '/create-user',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Usuario creado exitosamente!\nUsuario: ' + response.username + '\nContraseña: ' + response.password);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error al crear el usuario. Intente nuevamente.');
                }
            });
        }
    }

    // Create users for all apoderados without user accounts
    function createUsersForAll() {
        if (confirm('¿Crear usuarios para todos los apoderados que no tienen cuenta? Se usará nombre_apellido como usuario y DNI como contraseña.')) {
            $('#createUsersBtn').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Creando...');

            $.ajax({
                url: '/api/apoderados/create-users-all',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        let message = 'Usuarios creados exitosamente!\n\n';
                        response.users.forEach(function(user) {
                            message += 'Usuario: ' + user.username + ' | Contraseña: ' + user.password + '\n';
                        });

                        if (response.errors.length > 0) {
                            message += '\n\nErrores:\n';
                            response.errors.forEach(function(error) {
                                message += '- ' + error + '\n';
                            });
                        }

                        alert(message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error al crear los usuarios. Intente nuevamente.');
                },
                complete: function() {
                    $('#createUsersBtn').prop('disabled', false).html('<i class="bx bx-group"></i> Crear usuarios faltantes');
                }
            });
        }
    }
</script>
@endsection
