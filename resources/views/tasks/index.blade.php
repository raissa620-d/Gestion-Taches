<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des tâches</title>
    <style>
       

        body { 
            background-image: url('{{ asset("images/Liste.png") }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            flex-direction: column;
            color: white;
            text-shadow: 1px 1px 5px black;
            
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            padding-top: 80px; /* espace pour le header fixe */
            padding-bottom: 350px; /* espace avant le footer */
        }

    </style>
</head>
<body>

<h2> Gestion des tâches</h2>

<!-- Formulaire pour ajouter une tâche -->
<form method="POST" action="/task">
    @csrf
    <input type="text" name="titre" placeholder="Nom de la tâche" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="date" name="date_tache" required>
    <input type="time" name="heure_tache" required><br>
    <button type="submit">Ajouter</button>
</form>

<hr>

<!-- Liste des tâches -->
<ul>
@foreach($tasks as $task)
    <li class="{{ $task->terminee ? 'done' : '' }}">
        <strong>{{ $task->titre }}</strong><br>
        {{ $task->description }}<br>
        📅 {{ $task->date_tache }} ⏰ {{ $task->heure_tache }}<br><br>

        <!-- Bouton pour changer le statut -->
        <a href="/task/toggle/{{ $task->id }}">✔ Terminé / Non</a>

        <!-- Bouton pour supprimer -->
        <form method="POST" action="/task/{{ $task->id }}" style="display:inline">
            @csrf
            @method('DELETE')
            <button>❌ Supprimer</button>
        </form>
    </li>
@endforeach
</ul>

</body>
</html>
