<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Créer la table des catégories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Ajouter les colonnes nécessaires
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('priority')->default(0); // 0=basse, 1=moyenne, 2=haute
            $table->timestamp('due_date')->nullable(); // Date d'échéance
            
            // Nouvelles colonnes pour les données converties
            $table->string('title')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->boolean('completed')->default(false);
        });
        
        // Copier les données des anciennes colonnes aux nouvelles
        DB::statement('UPDATE tasks SET title = titre, scheduled_date = date_tache, scheduled_time = heure_tache, completed = terminee');
        
        // Supprimer les anciennes colonnes
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['titre', 'date_tache', 'heure_tache', 'terminee']);
        });
    }

    public function down(): void
    {
        // Rétablir les anciennes colonnes
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('titre')->nullable();
            $table->date('date_tache')->nullable();
            $table->time('heure_tache')->nullable();
            $table->boolean('terminee')->default(false);
        });
        
        // Remettre les données dans les anciennes colonnes
        DB::statement('UPDATE tasks SET titre = title, date_tache = scheduled_date, heure_tache = scheduled_time, terminee = completed');
        
        // Supprimer les nouvelles colonnes
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['user_id', 'category_id', 'priority', 'due_date', 'title', 'scheduled_date', 'scheduled_time', 'completed']);
        });
        
        Schema::dropIfExists('categories');
    }
};