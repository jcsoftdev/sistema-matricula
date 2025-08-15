<!-- resources/views/admin/register.blade.php -->

<form action="{{ route('admin.registerParent') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nombre Completo:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
    </div>

    <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirmar Contraseña:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="rol">Rol:</label>
        <select name="user_rol" class="form-select">
            <option value="padre" selected>Padre</option>  <!-- Rol de 'padre' asignado -->
            <option value="admin">Administrador</option>
            <option value="secretario">Secretaría</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Registrar</button>
</form>
