<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name') }}</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <style>
    /* Reset Box Model */
    * {
      box-sizing: border-box;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.8rem;
      color:rgb(244, 81, 0) !important;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-style: italic;
    }

    .navbar-brand:hover {
      color: rgb(244, 81, 0) !important;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    /* Sidebar Styling */
    .sidebar {
      background-color: #f8f9fa;
      min-height: 100vh;
      position: sticky;
      top: 0;
      padding-top: 20px;
      border-right: 1px solid #dee2e6;
      transition: transform 0.3s ease-in-out;
    }

    .sidebar a {
      color: #333;
      padding: 10px 20px;
      display: block;
      text-decoration: none;
      font-weight: 500;
      transition: background-color 0.2s ease, padding-left 0.2s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #e9ecef;
      padding-left: 25px;
    }

    /* Main Content */
    .main-content {
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }

    /* Sidebar: Hide on Mobile */
    @media (max-width: 768px) {
      .sidebar {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 250px;
        background-color: #f8f9fa;
        z-index: 1000;
      }

      .sidebar.active {
        display: block;
        transform: translateX(0);
      }

      .sidebar:not(.active) {
        transform: translateX(-100%);
      }
    }

    /* Sidebar Overlay */
    #sidebarOverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    /* Main Content: Responsive Padding */
    @media (max-width: 576px) {
      .main-content {
        margin-top: 10px;
        padding: 10px;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  @include('partials.navbar')

  <!-- Sidebar Toggle Button (Moved inside Navbar for alignment) -->
  <button class="btn btn-outline-secondary d-md-none m-2" id="sidebarToggle">
    <i class="bi bi-list"></i> Menu
  </button>

  <!-- Overlay for Closing Sidebar -->
  <div id="sidebarOverlay"></div>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 sidebar" id="sidebar">
        @include('partials.sidebar')
      </nav>

      <!-- Main Content -->
      <main class="col-md-9 col-lg-10 py-4">
        <div class="main-content">
          <h1 class="mb-4">@yield('page-title', 'Default Page Title')</h1>
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar Toggle Script -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // Toggle sidebar open/close
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('active');
      sidebarOverlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
    });

    // Close sidebar when clicking outside
    sidebarOverlay.addEventListener('click', function () {
      sidebar.classList.remove('active');
      sidebarOverlay.style.display = 'none';
    });
  </script>

</body>

</html>