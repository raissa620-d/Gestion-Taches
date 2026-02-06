<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Récupérer les statistiques pour le tableau de bord
        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('completed', true)->count();
        $pendingTasks = Task::where('user_id', $userId)->where('completed', false)->count();
        $overdueTasks = Task::where('user_id', $userId)->overdue()->count();
        $totalCategories = Category::where('user_id', $userId)->count();
        
        // Tâches récentes
        $recentTasks = Task::where('user_id', $userId)
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();
        
        // Tâches en retard
        $overdueTasksList = Task::where('user_id', $userId)
                              ->overdue()
                              ->orderBy('due_date', 'asc')
                              ->limit(5)
                              ->get();
        
        // Tâches à venir cette semaine
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $upcomingTasks = Task::where('user_id', $userId)
                           ->whereBetween('scheduled_date', [$weekStart, $weekEnd])
                           ->where('completed', false)
                           ->orderBy('scheduled_date')
                           ->orderBy('scheduled_time')
                           ->limit(5)
                           ->get();

        return view('dashboard', compact(
            'totalTasks', 
            'completedTasks', 
            'pendingTasks', 
            'overdueTasks',
            'totalCategories',
            'recentTasks',
            'overdueTasksList',
            'upcomingTasks'
        ));
    }
}