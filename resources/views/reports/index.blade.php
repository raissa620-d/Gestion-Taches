@extends('layouts.app')

@section('title', 'Rapports | Gestion de Tâches')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rapports et Analyses</h1>
        <div class="btn-toolbar">
            <button type="button" class="btn btn-sm btn-outline-secondary me-2" disabled>
                <i class="fas fa-download me-2"></i>Exporter PDF
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-print me-2"></i>Imprimer
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-primary-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total des tâches</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-success-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tâches terminées</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $completedTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-warning-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tâches en cours</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $pendingTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-danger-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tâches en retard</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $overdueTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <!-- Tâches par statut -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="m-0"><i class="fas fa-chart-pie me-2 text-primary"></i> Tâches par statut</h5>
                </div>
                <div class="card-body text-center">
                    <canvas id="statusChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Tâches par priorité -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="m-0"><i class="fas fa-chart-bar me-2 text-success"></i> Tâches par priorité</h5>
                </div>
                <div class="card-body text-center">
                    <canvas id="priorityChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Tâches par catégorie -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="m-0"><i class="fas fa-chart-pie me-2 text-info"></i> Tâches par catégorie</h5>
                </div>
                <div class="card-body">
                    @if(count($tasksByCategory) > 0)
                        <canvas id="categoryChart" width="100%" height="50"></canvas>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune catégorie définie</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Activité récente -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="m-0"><i class="fas fa-chart-line me-2 text-warning"></i> Activité récente</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des détails -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="m-0"><i class="fas fa-table me-2 text-secondary"></i> Détails des tâches</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Statistiques</th>
                            <th>Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total des tâches</td>
                            <td>{{ $totalTasks }}</td>
                        </tr>
                        <tr>
                            <td>Tâches terminées</td>
                            <td>{{ $completedTasks }}</td>
                        </tr>
                        <tr>
                            <td>Tâches en cours</td>
                            <td>{{ $pendingTasks }}</td>
                        </tr>
                        <tr>
                            <td>Tâches en retard</td>
                            <td>{{ $overdueTasks }}</td>
                        </tr>
                        <tr>
                            <td>Nombre de catégories</td>
                            <td>{{ $totalCategories }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Graphique par statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Terminées', 'En cours'],
        datasets: [{
            data: [{{ $tasksByStatus['completed'] }}, {{ $tasksByStatus['pending'] }}],
            backgroundColor: [
                '#28a745',
                '#ffc107'
            ],
            borderColor: [
                '#28a745',
                '#ffc107'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique par priorité
const priorityCtx = document.getElementById('priorityChart').getContext('2d');
const priorityChart = new Chart(priorityCtx, {
    type: 'bar',
    data: {
        labels: ['Basse', 'Moyenne', 'Haute'],
        datasets: [{
            label: 'Nombre de tâches',
            data: [{{ $tasksByPriority['low'] }}, {{ $tasksByPriority['medium'] }}, {{ $tasksByPriority['high'] }}],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderColor: [
                '#1e7e34',
                '#e0a800',
                '#c82333'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Graphique par catégorie
@if(count($tasksByCategory) > 0)
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = {
    labels: [@foreach($tasksByCategory as $category)'{{ $category->name }}',@endforeach],
    datasets: [{
        data: [@foreach($tasksByCategory as $category){{ $category->tasks_count }},@endforeach],
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'],
        borderColor: ['#2e59d9', '#17a673', '#2c9faf', '#d3a72c', '#d53026', '#676a72', '#42444a']
    }]
};
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: categoryData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
@endif

// Graphique d'activité
const activityCtx = document.getElementById('activityChart').getContext('2d');
const months = Object.keys(@json($tasksCompletedByMonth));
const completedData = Object.values(@json($tasksCompletedByMonth));
const createdData = Object.values(@json($tasksCreatedByMonth));

const activityChart = new Chart(activityCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Tâches terminées',
            data: completedData,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            tension: 0.3
        }, {
            label: 'Tâches créées',
            data: createdData,
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.05)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection