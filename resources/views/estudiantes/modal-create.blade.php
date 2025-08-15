<!-- Modal -->
<div class="modal fade" id="modal-create-estudiante" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('estudiantes.store')}}" method="post">
                @csrf
                @method('post')
                <div class="modal-body">

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">DNI</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-id-card' ></i></span>
                                <input type="text" name="dni_estudiante" class="form-control" maxlength="8" minlength="8" placeholder="Número de DNI" required onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nombres</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" name="nombres_estudiante" class="form-control" placeholder="Nombres" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Apellidos</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-user-detail'></i></span>
                                <input type="text" name="apellidos_estudiante" class="form-control" placeholder="Apellidos" required/>
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
                                    <option>M</option>
                                    <option>F</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Fecha de nacimiento</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bxs-calendar' ></i></span>
                                <input type="date" name="fecha_nacimiento" class="form-control" placeholder="Fecha de nacimiento" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="apoderado-search">Apoderado</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                                <input type="text" id="apoderado-search" class="form-control" placeholder="Buscar apoderado por nombre o DNI..." autocomplete="off"/>
                                <input type="hidden" name="apoderado_id" id="apoderado-id" value="">
                            </div>
                            <div id="apoderado-results" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; width: 100%;"></div>
                            <div id="selected-apoderado" class="mt-2" style="display: none;">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light border rounded selected-apoderado-card">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class='bx bx-user text-white'></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0" id="selected-apoderado-info"></h6>
                                            <small class="text-muted" id="selected-apoderado-dni"></small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light border-0 rounded-circle d-flex align-items-center justify-content-center btn-close-custom" onclick="clearApoderado()" title="Quitar apoderado" style="width: 24px; height: 24px; opacity: 0.7;">
                                        <i class='bx bx-x' style="font-size: 16px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class='bx bx-x'></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class='bx bxs-save' ></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
