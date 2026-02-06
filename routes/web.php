<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ReportController;

// Page d'accueil
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Routes pour les tâches
Route::resource('tasks', TaskController::class);
Route::put('/tasks/{id}/update-time', [TaskController::class, 'updateTime'])->name('tasks.update-time');
Route::put('/tasks/{id}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

// Routes pour les catégories
Route::resource('categories', CategoryController::class);

// Routes pour le calendrier
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

// Routes pour les rapports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'exportPdf'])->name('reports.export');