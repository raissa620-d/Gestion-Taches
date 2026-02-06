<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Catégorie créée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Sécurité : vérifier que la catégorie appartient à l'utilisateur connecté
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Catégorie mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Sécurité : vérifier que la catégorie appartient à l'utilisateur connecté
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Empêcher la suppression si la catégorie contient des tâches
        if ($category->tasks()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer une catégorie contenant des tâches.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée avec succès !');
    }
}