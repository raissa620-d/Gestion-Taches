<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion de Tâches | Professional')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --light-text: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            padding-top: 70px;
            color: var(--dark-text);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            height: calc(100vh - 70px);
            width: 250px;
            background: linear-gradient(to bottom, #ffffff, var(--light-bg));
            box-shadow: 3px 0 10px rgba(0,0,0,0.05);
            overflow-y: auto;
            z-index: 999;
            padding-top: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: calc(100vh - 110px);
        }
        
        .nav-link {
            color: var(--dark-text);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            margin-bottom: 25px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px 0;
            margin: -20px -20px 30px -20px;
            border-radius: 0 0 10px 10px;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin: 0 auto 20px;
        }
        
        .stats-card {
            text-align: center;
            padding: 25px 15px;
        }
        
        .stats-number {
            font-size: 2.2rem;
            font-weight: 700;
            display: block;
        }
        
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
        }
        
        .bg-success-gradient {
            background: linear-gradient(135deg, #4CAF50, #2E7D32) !important;
        }
        
        .bg-warning-gradient {
            background: linear-gradient(135deg, #FFC107, #FF9800) !important;
        }
        
        .bg-danger-gradient {
            background: linear-gradient(135deg, #F44336, #D32F2F) !important;
        }
        
        .task-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: all 0.3s;
        }
        
        .task-item:hover {
            background-color: #f0f5ff;
            transform: translateX(5px);
        }
        
        .priority-low {
            border-left: 4px solid #4CAF50;
        }
        
        .priority-medium {
            border-left: 4px solid #FFC107;
        }
        
        .priority-high {
            border-left: 4px solid #F44336;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1500&q=80') no-repeat center center;
            background-size: cover;
            border-radius: 12px;
            padding: 60px 30px;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .feature-card {
            transition: all 0.3s;
            border: none;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-3" href="{{ route('dashboard') }}">
                <i class="fas fa-tasks me-2"></i>Gestion Tâches
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                           <i class="fas fa-home me-1"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" 
                           href="{{ route('tasks.index') }}">
                           <i class="fas fa-list-check me-1"></i> Tâches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                           href="{{ route('categories.index') }}">
                           <i class="fas fa-tags me-1"></i> Catégories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}" 
                           href="{{ route('calendar.index') }}">
                           <i class="fas fa-calendar me-1"></i> Calendrier
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                           href="{{ route('reports.index') }}">
                           <i class="fas fa-chart-pie me-1"></i> Rapports
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Paramètres</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar d-none d-md-block">
        <div class="text-center mb-4">
            <div class="feature-icon mx-auto">
                <i class="fas fa-user"></i>
            </div>
            <h6 class="mb-0">Administrateur</h6>
            <small class="text-muted">Utilisateur</small>
        </div>
        
        <ul class="nav flex-column px-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line me-2"></i> Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" 
                   href="{{ route('tasks.index') }}">
                    <i class="fas fa-list-check me-2"></i> Mes tâches
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                   href="{{ route('categories.index') }}">
                    <i class="fas fa-tags me-2"></i> Catégories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}" 
                   href="{{ route('calendar.index') }}">
                    <i class="fas fa-calendar me-2"></i> Calendrier
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                   href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-pie me-2"></i> Rapports
                </a>
            </li>
        </ul>
        
        <hr class="mx-3">
        
        <h6 class="sidebar-heading px-3 mt-4 mb-2 text-muted">
            <span>Statistiques</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" href="#">
                    <span><i class="fas fa-check-circle me-2"></i> Terminées</span>
                    <span class="badge bg-success">{{ \App\Models\Task::where('user_id', auth()->id() ?? 1)->where('completed', true)->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" href="#">
                    <span><i class="fas fa-clock me-2"></i> En retard</span>
                    <span class="badge bg-danger">{{ \App\Models\Task::where('user_id', auth()->id() ?? 1)->overdue()->count() }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <footer class="mt-5 py-4 text-center text-muted border-top">
        <div class="container">
            <p>&copy; 2026 Gestion de Tâches. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>