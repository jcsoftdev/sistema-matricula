<!-- Modal -->
<div class="modal fade" id="modal-delete-{{$apoderado->id}}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel1">Eliminar apoderado</h5>
              <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
          </div>
          <form action="{{route('apoderados.destroy', $apoderado->id)}}" method="POST">
            @csrf
            @method('delete')

            <div class="modal-body">
                <p>¿Estás seguro de eliminar al apoderado {{$apoderado->nombres_apoderado." ".$apoderado->apellidos_apoderado}}?</p>
                <div class="alert alert-warning">
                    <strong>⚠️ Advertencia:</strong> Esta acción también eliminará:
                    <ul class="mb-0 mt-2">
                        @if($apoderado->user)
                        <li>Su cuenta de usuario: <strong>{{ $apoderado->user->username }}</strong></li>
                        @endif
                        @if($apoderado->matriculas->count() > 0)
                        <li><strong>{{ $apoderado->matriculas->count() }}</strong> matrícula(s) asociada(s)</li>
                        @endif
                    </ul>
                </div>
                <small> <i>Este proceso es irreversible.</i> </small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
          </form>
      </div>
  </div>
</div>

