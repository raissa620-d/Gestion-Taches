<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Récupérer les tâches pour le mois en cours
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Récupérer toutes les tâches de l'utilisateur
        $tasks = Task::where('user_id', $userId)
                    ->whereMonth('scheduled_date', $currentMonth)
                    ->whereYear('scheduled_date', $currentYear)
                    ->orderBy('scheduled_date')
                    ->orderBy('scheduled_time')
                    ->get();
        
        // Générer les jours du mois
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1);
        $lastDayOfMonth = Carbon::create($currentYear, $currentMonth, $daysInMonth);
        
        // Calculer les jours du mois et les jours des autres mois à afficher
        $startDay = $firstDayOfMonth->copy()->startOfWeek();
        $endDay = $lastDayOfMonth->copy()->endOfWeek();
        
        $calendarDays = [];
        $day = $startDay->copy();
        
        while ($day->lte($endDay)) {
            $calendarDays[] = [
                'date' => $day->copy(),
                'isCurrentMonth' => $day->month === $currentMonth,
                'tasks' => $tasks->where('scheduled_date', $day->toDateString())->take(3) // Limiter à 3 tâches par jour
            ];
            $day->addDay();
        }
        
        return view('calendar.index', compact('calendarDays', 'currentMonth', 'currentYear', 'tasks'));
    }
    
    public function getEvents(Request $request)
    {
        $userId = auth()->id();
        
        $startDate = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
        $endDate = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();
        
        $tasks = Task::where('user_id', $userId)
                    ->whereBetween('scheduled_date', [$startDate, $endDate])
                    ->get();
        
        $events = [];
        foreach ($tasks as $task) {
            $events[] = [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->scheduled_date->format('Y-m-d') . 'T' . $task->scheduled_time,
                'className' => $task->completed ? 'event-completed' : ($task->priority === 2 ? 'event-high' : ($task->priority === 1 ? 'event-medium' : 'event-low')),
                'extendedProps' => [
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'completed' => $task->completed,
                    'category' => $task->category ? $task->category->name : null
                ]
            ];
        }
        
        return response()->json($events);
    }
}