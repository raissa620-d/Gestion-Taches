<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Statistiques générales
        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('completed', true)->count();
        $pendingTasks = Task::where('user_id', $userId)->where('completed', false)->count();
        $overdueTasks = Task::where('user_id', $userId)->overdue()->count();
        $totalCategories = Category::where('user_id', $userId)->count();
        
        // Tâches par statut
        $tasksByStatus = [
            'completed' => $completedTasks,
            'pending' => $pendingTasks,
        ];
        
        // Tâches par priorité
        $tasksByPriority = [
            'low' => Task::where('user_id', $userId)->where('priority', 0)->count(),
            'medium' => Task::where('user_id', $userId)->where('priority', 1)->count(),
            'high' => Task::where('user_id', $userId)->where('priority', 2)->count(),
        ];
        
        // Tâches par catégorie
        $tasksByCategory = Category::where('user_id', $userId)
            ->withCount(['tasks' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->get();
        
        // Tâches terminées par mois (sur les 6 derniers mois)
        $tasksCompletedByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Task::where('user_id', $userId)
                ->where('completed', true)
                ->whereYear('updated_at', $month->year)
                ->whereMonth('updated_at', $month->month)
                ->count();
                
            $tasksCompletedByMonth[$month->format('M Y')] = $count;
        }
        
        // Tâches créées par mois (sur les 6 derniers mois)
        $tasksCreatedByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Task::where('user_id', $userId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
                
            $tasksCreatedByMonth[$month->format('M Y')] = $count;
        }
        
        return view('reports.index', compact(
            'totalTasks',
            'completedTasks', 
            'pendingTasks', 
            'overdueTasks',
            'totalCategories',
            'tasksByStatus',
            'tasksByPriority',
            'tasksByCategory',
            'tasksCompletedByMonth',
            'tasksCreatedByMonth'
        ));
    }
    
    public function exportPdf()
    {
        // Pour l'instant, on redirige vers la page des rapports
        // La fonctionnalité d'export PDF nécessite l'installation de barryvdh/laravel-dompdf
        return redirect()->route('reports.index')->with('info', 'Export PDF bientôt disponible');
    }
}