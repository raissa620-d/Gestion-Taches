<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Affiche la liste des tâches
     */
    public function index()
    {
        $tasks = Task::with(['user', 'category'])
                     ->where('user_id', auth()->id())
                     ->orderBy('due_date')
                     ->orderBy('scheduled_time')
                     ->get();

        $categories = Category::where('user_id', auth()->id())->get();

        return view('tasks', compact('tasks', 'categories'));
    }

    /**
     * Enregistre une nouvelle tâche
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|in:0,1,2',
            'due_date' => 'nullable|date'
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'category_id' => $request->category_id,
            'priority' => $request->priority ?? 0,
            'due_date' => $request->due_date,
            'completed' => false,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Tâche enregistrée avec succès !');
    }

    /**
     * Modifier l'heure d'une tâche
     * (uniquement si elle n'est pas terminée)
     */
    public function updateTime(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Sécurité : on ne modifie pas une tâche terminée
        if ($task->completed) {
            return redirect()->back()->with('error', 'Impossible de modifier une tâche terminée.');
        }

        // Sécurité : vérifier que la tâche appartient à l'utilisateur connecté
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'scheduled_time' => 'required'
        ]);

        $task->scheduled_time = $request->scheduled_time;
        $task->save();

        return redirect()->back()->with('success', 'Heure de la tâche modifiée avec succès.');
    }

    /**
     * Marquer une tâche comme terminée / non terminée
     */
    public function toggle($id)
    {
        $task = Task::findOrFail($id);

        // Sécurité : vérifier que la tâche appartient à l'utilisateur connecté
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $task->completed = !$task->completed;
        $task->save();

        $status = $task->completed ? 'terminée' : 'non terminée';
        return redirect()->back()->with('success', "La tâche a été marquée comme {$status}.");
    }

    /**
     * Supprimer une tâche
     * (uniquement si elle est terminée)
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Sécurité : vérifier que la tâche appartient à l'utilisateur connecté
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$task->completed) {
            return redirect()->back()->with('error', 'Impossible de supprimer une tâche non terminée.');
        }

        $task->delete();

        return redirect()->back()->with('success', 'Tâche supprimée avec succès.');
    }
}