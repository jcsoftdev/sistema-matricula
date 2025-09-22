@extends('layouts.main')

@section('content')

@php
  $role = $userRole ?? (Auth::user()->rol ?? '');
  $roleLabels = [
    'admin' => 'Administrador',
    'secretario' => 'Secretaría',
    'padre' => 'Apoderado',
  ];
  $roleLabel = $roleLabels[$role] ?? ucfirst($role);
@endphp

<div class="row d-flex justify-content-center">
  <div class="col-lg-9 mb-4">
    <div class="card">
      <div class="row align-items-end">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Bienvenido(a), {{ Auth::user()->name }} 👋</h5>
            <p class="mb-2">
              Has iniciado sesión como <strong>{{ $roleLabel }}</strong>.
              @if ($role === 'padre')
                Aquí puedes revisar el estado de tus prematrículas y mantener tus datos actualizados.
              @else
                Desde aquí puedes gestionar las <strong>Matrículas</strong>, <strong>Pagos</strong> y <strong>Reportes</strong> de forma segura y eficiente.
              @endif
            </p>
          </div>
        </div>
        <div class="col-sm-5 text-center">
          <div class="card-body pb-0 px-0 px-md-4">
            <img
              src="../assets/img/illustrations/man-with-laptop-light.png"
              height="140"
              alt="Usuario Administrador"
              data-app-dark-img="illustrations/man-with-laptop-dark.png"
              data-app-light-img="illustrations/man-with-laptop-light.png" />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@if ($role !== 'padre')
<div class="row d-flex justify-content-center">

  <!-- Tarjeta Matrícula -->
  <div class="col-md-3 mb-4">
    <div class="card h-100">
      <div class="card-body text-center">
        <div class="avatar mb-2">
          <img src="{{ asset('assets/img/unicons/wallet-info.png') }}" alt="Matrícula" class="rounded">
        </div>
        <h6 class="fw-semibold text-muted">Total Matrículas</h6>
        <h3 class="text-info mb-3">{{ $ctd_matricula }}</h3>
        <a href="{{ route('matriculas.index') }}" class="btn btn-outline-info w-100">
          <i class="bx bx-user-pin"></i> Ver Matrículas
        </a>
      </div>
    </div>
  </div>

  <!-- Tarjeta Pagos -->
  <div class="col-md-3 mb-4">
    <div class="card h-100">
      <div class="card-body text-center">
        <div class="avatar mb-2">
          <img src="{{ asset('assets/img/unicons/cc-warning.png') }}" alt="Pagos" class="rounded">
        </div>
        <h6 class="fw-semibold text-muted">Pagos Registrados</h6>
        <h3 class="text-warning mb-3">S/ {{ $pagos_matricula }}</h3>
        <a href="{{ route('pagos.index') }}" class="btn btn-outline-warning w-100">
          <i class="bx bx-money"></i> Ver Pagos
        </a>
      </div>
    </div>
  </div>

  <!-- Tarjeta Reportes -->
  <div class="col-md-3 mb-4">
    <div class="card h-100">
      <div class="card-body text-center">
        <div class="avatar mb-2">
          <img src="{{ asset('assets/img/unicons/chart-success.png') }}" alt="Reportes" class="rounded">
        </div>
        <h6 class="fw-semibold text-muted">Acceso a Reportes</h6>
        <h3 class="text-success mb-3">-</h3>
        <a href="{{ route('reportes') }}" class="btn btn-outline-success w-100">
          <i class='bx bxs-pie-chart-alt-2'></i> Ver Reportes
        </a>
      </div>
    </div>
  </div>

</div>
@else
<div class="row d-flex justify-content-center">
  <div class="col-lg-9 mb-4">
    <div class="card h-100">
      <div class="card-body text-center">
        <div class="avatar mb-3">
          <img src="{{ asset('assets/img/unicons/chart-success2.png') }}" alt="Prematrículas" class="rounded">
        </div>
        <h5 class="fw-semibold text-primary">Información para Apoderados</h5>
        <p class="text-muted">
          No tienes acceso directo a las matrículas ni a los montos registrados. Para más detalles, comunícate con la Secretaría del colegio.
        </p>
      </div>
    </div>
  </div>
</div>
@endif

@endsection
