@extends('layouts.main')

@section('content')


<div class="row">

    <div class="col-md-12">

        @include('layouts.alerts')

        <div class="card ">

            <div class="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Editar usuario</h5>
                </div>
                <form action="{{route('users.update', $usuario->id)}}" method="POST">
                  @csrf
                  @method('put')

                  <div class="modal-body">
                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nombre</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class='bx bxs-user-detail'></i></span>
                            <input type="text" class="form-control" name="nombre_usuario" value="{{ $usuario->name }}" id="basic-icon-default-fullname" required placeholder="Nombre del usuario" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2">
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Usuario</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class='bx bxs-user-circle' ></i></span>
                            <input type="text" class="form-control" name="username" value="{{ $usuario->username }}" id="basic-icon-default-fullname" required placeholder="Username" aria-describedby="basic-icon-default-fullname2">
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="password">Contraseña</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-password" class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Dejar en blanco para mantener la contraseña actual" aria-describedby="basic-icon-password">
                          </div>
                          @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                          @enderror
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="password_confirmation">Confirmar contraseña</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-password-confirm" class="input-group-text"><i class='bx bx-lock'></i></span>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirme la nueva contraseña" aria-describedby="basic-icon-password-confirm">
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Rol</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i class='bx bx-checkbox-checked'></i></span>
                                <select name="user_rol" class="form-select">
                                    <option selected disabled value="">Seleccione...</option>
                                    @if ($usuario->rol=="admin")
                                      <option value="Administrador" selected>Administrador</option>
                                      <option value="Secretaría">Secretaría</option>
                                    @elseif($usuario->rol=="secretario")
                                      <option value="Administrador" >Administrador</option>
                                      <option value="Secretaría" selected>Secretaría</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>


                  </div>
                  <div class="modal-footer">
                      <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
