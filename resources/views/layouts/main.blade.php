<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>home</title>
  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  @yield('css')

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="#" class="app-brand-link">
            <div class="logo-container">
              <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Shekiná School" class="logo-img">
            </div>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <li class="menu-item active">
            <a href="{{ route('home') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div>Panel de control</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase"><span class="menu-header-text">Opciones</span></li>

          @if (Auth::user()->rol != "padre")
            <li class="menu-item">
              <a href="{{ route('bancos.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-bank"></i>
                <div>Bancos</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="{{ route('estudiantes.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user-account"></i>
                <div>Estudiantes</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="{{ route('apoderados.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-face"></i>
                <div>Apoderados</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="{{ route('matriculas.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-archive"></i>
                <div>Matrículas</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="{{ route('pagos.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div>Pagos</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Reportes</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route('reportes') }}" class="menu-link">
                    <div>Estado de cuenta</div>
                  </a>
                </li>
              </ul>
            </li>
          @endif

          @if (Auth::user()->rol == "padre")
            <li class="menu-item">
              <a href="{{ route('prematricula.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-edit"></i>
                <div>Prematrícula</div>
              </a>
            </li>
          @endif


          {{-- @if (Auth::user()->rol == "admin")
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin</span></li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-cog"></i>
                <div>Administrar</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route('users.index') }}" class="menu-link">
                    <div>Gestión de usuarios</div>
                  </a>
                </li>
              </ul>
            </li>
          @endif --}}
          @if (Auth::user()->rol == "admin")
  <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin</span></li>
  <li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bxs-cog"></i>
      <div>Administrar</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item">
        <a href="{{ route('users.index') }}" class="menu-link">
          <div>Gestión de usuarios</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="{{ route('periodos.index') }}" class="menu-link">
          <div>Períodos académicos</div>
        </a>
      </li>

    </ul>
  </li>
@endif

        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached bg-navbar-theme" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Notifications Bell -->
              <li class="nav-item dropdown me-3" id="notification-dropdown">
                <a class="nav-link dropdown-toggle hide-arrow position-relative" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-bell bx-sm"></i>
                  <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="notification-count" style="display: none;">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                  <li class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Notificaciones</span>
                    <button class="btn btn-sm btn-outline-secondary" id="mark-all-read" style="font-size: 10px;">Marcar todas como leídas</button>
                    <button class="btn btn-sm btn-outline-info" id="test-notification" style="font-size: 10px;">Test</button>
                  </li>
                  <li><hr class="dropdown-divider" /></li>
                  <div id="notification-list">
                    <li class="dropdown-item text-center text-muted">
                      <small>No hay notificaciones</small>
                    </li>
                  </div>
                </ul>
              </li>

              <li class="nav-item lh-1 me-3">
                <a style="pointer-events: none">{{ Auth::user()->rol }}</a>
              </li>

              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="avatar avatar-online me-3">
                          <img src="{{ asset('assets/img/avatars/1.png') }}" class="w-px-40 h-auto rounded-circle" />
                        </div>
                        <div>
                          <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                          <small class="text-muted">{{ Auth::user()->rol }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li><div class="dropdown-divider"></div></li>
                  <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i>Mi cuenta</a></li>
                  <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i>Cambiar contraseña</a></li>
                  <li><div class="dropdown-divider"></div></li>
                  <li>
                    <form action="{{ route('logout') }}" method="POST">@csrf
                      <button type="submit" class="dropdown-item d-flex align-items-center">
                        <i class="bx bx-power-off me-2"></i> Cerrar sesión
                      </button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div>© <script>document.write(new Date().getFullYear());</script> - <strong>Shekiná School</strong></div>
              <div>Versión 1.0</div>
            </div>
          </footer>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

  <!-- Menú interactivo -->
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Broadcasting Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>

  <script>
    // Real-time notifications setup
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM Content Loaded - Starting notification setup');
      console.log('User auth check:', @json(Auth::check()));
      console.log('User role:', @json(Auth::check() ? Auth::user()->rol : 'not authenticated'));

      @if(Auth::check() && in_array(Auth::user()->rol, ['admin', 'secretario']))
        console.log('User has admin/secretario role - initializing notifications');

        // Initialize notifications
        let notificationCount = 0;
        const notificationBadge = document.getElementById('notification-count');
        const notificationList = document.getElementById('notification-list');
        const markAllReadBtn = document.getElementById('mark-all-read');

        console.log('Notification elements found:', {
          badge: !!notificationBadge,
          list: !!notificationList,
          markAllBtn: !!markAllReadBtn
        });

        // Restore notifications from localStorage
        function restoreNotifications() {
          let notifications = JSON.parse(localStorage.getItem('admin_notifications') || '[]');
          notificationCount = 0;
          if (notifications.length > 0) {
            notificationList.innerHTML = '';
            notifications.forEach(n => {
              notificationCount += n.read ? 0 : 1;
              const studentName = n.estudiante?.nombre_completo || (n.estudiante?.nombres + ' ' + n.estudiante?.apellidos) || n.estudiante?.nombres || 'Estudiante sin nombre';
              const notificationHtml = `
                <li class="dropdown-item notification-item${n.read ? ' read' : ''}" data-id="${n.id}" data-codigo="${n.cod_matricula}" style="white-space: normal; padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;${n.read ? 'opacity:0.6;' : ''}">
                  <div class="d-flex align-items-start">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial bg-primary rounded-circle">
                        <i class="bx bx-user-plus"></i>
                      </span>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1" style="font-size: 12px; font-weight: 600;">Nueva Prematrícula</h6>
                      <p class="mb-1" style="font-size: 11px; margin: 0;">
                        <strong>${studentName}</strong><br>
                        ${n.nivel} - ${n.grado}° grado<br>
                        <small class="text-muted">Código: ${n.cod_matricula}</small>
                      </p>
                      <small class="text-muted" style="font-size: 10px;">${n.created_at}</small>
                      <div class="mt-2 d-flex justify-content-between align-items-center">
                        <small class="text-info">Clic para ver en matrículas</small>
                        <button class="btn btn-sm btn-outline-danger ms-2 delete-notification" title="Eliminar" style="font-size:10px; padding:2px 6px;">&times;</button>
                      </div>
                    </div>
                  </div>
                </li>
              `;
              notificationList.insertAdjacentHTML('beforeend', notificationHtml);
            });
            updateNotificationBadge();
          } else {
            notificationList.innerHTML = `<li class="dropdown-item text-center text-muted"><small>No hay notificaciones</small></li>`;
            updateNotificationBadge();
          }
        }

        // Wait for Echo to be available
        function waitForEcho() {
          console.log('🔍 Checking Echo availability:', typeof window.Echo);

          if (typeof window.Echo !== 'undefined') {
            console.log('✅ Echo is available!');
            console.log('📡 Echo object:', window.Echo);
            console.log('🔌 Echo connector:', window.Echo.connector);
            console.log('⚙️ Echo options:', window.Echo.options);

            // Setup channel listener
            console.log('📻 Setting up admin-notifications channel...');

            const channel = window.Echo.channel('admin-notifications');
            console.log('📺 Channel created:', channel);

            // Add connection status debugging
            if (window.Echo.connector && window.Echo.connector.socket) {
              const socket = window.Echo.connector.socket;
              console.log('🔗 Socket object found:', socket);
              console.log('🟢 Initial socket state:', socket.readyState);

              socket.addEventListener('open', (event) => {
                console.log('🟢 WebSocket connection opened:', event);
              });

              socket.addEventListener('close', (event) => {
                console.log('🔴 WebSocket connection closed:', event);
              });

              socket.addEventListener('error', (event) => {
                console.error('❌ WebSocket error:', event);
              });

              socket.addEventListener('message', (event) => {
                console.log('📨 Raw WebSocket message received:', event.data);
                try {
                  const data = JSON.parse(event.data);
                  console.log('📨 Parsed WebSocket message:', data);
                } catch (e) {
                  console.log('📨 Non-JSON WebSocket message:', event.data);
                }
              });
            }

            channel.listen('.prematricula.submitted', (e) => {
              console.log('🎉 Received prematricula notification:', e);
              console.log('🎉 Full event object:', JSON.stringify(e, null, 2));
              addNotification(e);
              showNotificationToast(e);
            });

            channel.error((error) => {
              console.error('❌ Echo channel error:', error);
            });

            // Test if channel is subscribed
            setTimeout(() => {
              console.log('📊 Channel subscription status:', {
                channelName: channel.name,
                subscribed: channel.subscribed,
                subscription: channel.subscription
              });
            }, 2000);

            console.log('✅ Channel listener setup completed');
          } else {
            console.log('⏳ Echo not available yet, retrying in 500ms...');
            setTimeout(waitForEcho, 500);
          }
        }

        // Restore notifications on page load
        restoreNotifications();
        // ...existing code...

        // Start checking for Echo
        console.log('🚀 Starting waitForEcho...');
        waitForEcho();

        // Generate Laravel route URL
        const prematriculaListUrl = '{{ route("prematriculas.lista") }}';

        function addNotification(data) {
          console.log('Received notification data:', data); // Debug log
          console.log('Current notification list content:', notificationList.innerHTML);

          notificationCount++;
          updateNotificationBadge();

          // Try different name sources
          const studentName = data.estudiante.nombre_completo ||
                             (data.estudiante.nombres + ' ' + data.estudiante.apellidos) ||
                             data.estudiante.nombres ||
                             'Estudiante sin nombre';

          console.log('Student name resolved to:', studentName);

          // Create notification element
          const matriculaUrl = "{{ route('matriculas.index') }}";
          const notificationHtml = `
            <li class="dropdown-item notification-item" data-id="${data.id}" data-codigo="${data.cod_matricula}" style="white-space: normal; padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;">
              <div class="d-flex align-items-start">
                <div class="avatar avatar-sm me-3">
                  <span class="avatar-initial bg-primary rounded-circle">
                    <i class="bx bx-user-plus"></i>
                  </span>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1" style="font-size: 12px; font-weight: 600;">Nueva Prematrícula</h6>
                  <p class="mb-1" style="font-size: 11px; margin: 0;">
                    <strong>${studentName}</strong><br>
                    ${data.nivel} - ${data.grado}° grado<br>
                    <small class="text-muted">Código: ${data.cod_matricula}</small>
                  </p>
                  <small class="text-muted" style="font-size: 10px;">${data.created_at}</small>
                  <div class="mt-2">
                    <small class="text-info">Clic para ver en matrículas</small>
                  </div>
                </div>
              </div>
            </li>
          `;

          console.log('Generated notification HTML:', notificationHtml);

          // Add to list
          const hasEmptyMessage = notificationList.querySelector('.text-center');
          console.log('Has empty message:', !!hasEmptyMessage);
          console.log('Current notification list content before update:', notificationList.innerHTML);

          if (hasEmptyMessage) {
            // Replace the "No hay notificaciones" message
            console.log('Replacing empty message with notification');
            // Clear the entire content and add the new notification
            notificationList.innerHTML = notificationHtml;
          } else {
            // Add to the beginning of existing notifications
            console.log('Adding notification to existing list');
            notificationList.insertAdjacentHTML('afterbegin', notificationHtml);
          }

          console.log('Updated notification list content:', notificationList.innerHTML);

          // Store in localStorage
          storeNotification(data);
        }

        function updateNotificationBadge() {
          if (notificationCount > 0) {
            notificationBadge.textContent = notificationCount > 99 ? '99+' : notificationCount;
            notificationBadge.style.display = 'block';
          } else {
            notificationBadge.style.display = 'none';
          }
        }

        function showNotificationToast(data) {
          const studentName = data.estudiante.nombre_completo ||
                             (data.estudiante.nombres + ' ' + data.estudiante.apellidos) ||
                             data.estudiante.nombres ||
                             'Estudiante sin nombre';

          // Create a simple toast notification
          const toast = document.createElement('div');
          toast.className = 'position-fixed';
          toast.style.cssText = `
            top: 20px; right: 20px; z-index: 9999;
            background: #fff; border-left: 4px solid #007bff;
            padding: 15px; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 300px; animation: slideIn 0.3s ease-out;
          `;
          toast.innerHTML = `
            <div class="d-flex align-items-center">
              <i class="bx bx-bell text-primary me-2"></i>
              <div>
                <strong style="font-size: 14px;">Nueva Prematrícula</strong><br>
                <small>${studentName}</small>
              </div>
              <button onclick="this.parentElement.parentElement.remove()"
                      style="border: none; background: none; color: #aaa; margin-left: 10px;">&times;</button>
            </div>
          `;

          document.body.appendChild(toast);

          // Auto remove after 5 seconds
          setTimeout(() => {
            if (toast.parentElement) {
              toast.remove();
            }
          }, 5000);
        }

        function storeNotification(data) {
          let notifications = JSON.parse(localStorage.getItem('admin_notifications') || '[]');
          notifications.unshift({
            ...data,
            read: false,
            stored_at: new Date().toISOString()
          });

          // Keep only last 50 notifications
          notifications = notifications.slice(0, 50);
          localStorage.setItem('admin_notifications', JSON.stringify(notifications));
        }

        // Mark all as read functionality
        markAllReadBtn.addEventListener('click', function() {
          notificationCount = 0;
          updateNotificationBadge();

          // Update localStorage
          let notifications = JSON.parse(localStorage.getItem('admin_notifications') || '[]');
          notifications = notifications.map(n => ({ ...n, read: true }));
          localStorage.setItem('admin_notifications', JSON.stringify(notifications));

          // Update UI
          document.querySelectorAll('.notification-item').forEach(item => {
            item.classList.add('read');
            item.style.opacity = '0.6';
          });
        });

        // Test notification functionality
        const testNotificationBtn = document.getElementById('test-notification');
        testNotificationBtn.addEventListener('click', function() {
          console.log('Testing notification...');
          const testData = {
            id: 999,
            cod_matricula: 'PRE-2025-001',
            estudiante: {
              nombre_completo: 'Juan Pérez Test',
              nombres: 'Juan',
              apellidos: 'Pérez Test',
              dni: '12345678'
            },
            apoderado: {
              nombre_completo: 'María González',
              telefono: '987654321'
            },
            periodo: '2026',
            nivel: 'Inicial',
            grado: '4 Años',
            created_at: new Date().toLocaleString(),
            timestamp: Date.now()
          };

          addNotification(testData);
          showNotificationToast(testData);
        });

        // Handle notification item clicks
        document.addEventListener('click', function(e) {
          // Delete notification
          if (e.target.classList.contains('delete-notification')) {
            const notificationItem = e.target.closest('.notification-item');
            if (notificationItem) {
              const id = notificationItem.getAttribute('data-id');
              let notifications = JSON.parse(localStorage.getItem('admin_notifications') || '[]');
              notifications = notifications.filter(n => String(n.id) !== String(id));
              localStorage.setItem('admin_notifications', JSON.stringify(notifications));
              notificationItem.remove();
              // If no notifications left, show empty message
              if (!document.querySelector('.notification-item')) {
                notificationList.innerHTML = `<li class="dropdown-item text-center text-muted"><small>No hay notificaciones</small></li>`;
              }
              // Update badge
              notificationCount = notifications.filter(n => !n.read).length;
              updateNotificationBadge();
            }
            return;
          }
          // View notification
          const notificationItem = e.target.closest('.notification-item');
          if (notificationItem && !e.target.classList.contains('delete-notification')) {
            const codigo = notificationItem.getAttribute('data-codigo');
            if (codigo) {
              // Build URL with search parameter
              const matriculaUrl = "{{ route('matriculas.index') }}" + '?search=' + encodeURIComponent(codigo);
              window.location.href = matriculaUrl;
            }
          }
        });

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
          @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
          }
          .notification-dropdown {
            animation: fadeIn 0.2s ease-out;
          }
          @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
          }
        `;
        document.head.appendChild(style);
      @else
        console.log('User does not have admin/secretario role - notifications disabled');
      @endif
    });
  </script>

  @yield('js')
</body>
</html>
