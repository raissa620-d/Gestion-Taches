@extends('layouts.app')

@section('title', 'Calendrier | Gestion de Tâches')

@section('content')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Calendrier des Tâches</h1>
        <div class="btn-toolbar">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvelle tâche
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {
            url: '{{ route("calendar.events") }}',
            method: 'GET',
            extraParams: {},
            failure: function() {
                console.error('Erreur lors du chargement des événements');
            }
        },
        locale: 'fr',
        eventClick: function(info) {
            // Afficher les détails de la tâche dans une modale
            const task = info.event.extendedProps;
            let priorityBadge = '';
            
            switch(task.priority) {
                case 2:
                    priorityBadge = '<span class="badge bg-danger">Haute priorité</span>';
                    break;
                case 1:
                    priorityBadge = '<span class="badge bg-warning text-dark">Moyenne priorité</span>';
                    break;
                default:
                    priorityBadge = '<span class="badge bg-success">Basse priorité</span>';
            }
            
            const statusBadge = task.completed ? 
                '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Terminée</span>' : 
                '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> En attente</span>';
            
            const categoryBadge = task.category ? 
                `<span class="badge bg-info me-1">${task.category}</span>` : 
                '<span class="badge bg-secondary me-1">Non catégorisée</span>';
                
            const modalHtml = `
                <div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary-gradient text-white">
                                <h5 class="modal-title" id="taskDetailModalLabel">${info.event.title}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <p>${task.description || 'Aucune description'}</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Statut</label>
                                        <p>${statusBadge}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Priorité</label>
                                        <p>${priorityBadge}</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catégorie</label>
                                    <p>${categoryBadge}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date & Heure</label>
                                    <p><i class="fas fa-calendar me-2 text-primary"></i>${info.event.start.toLocaleString()}</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/tasks" class="btn btn-primary">Voir toutes les tâches</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Ajouter la modale au corps de la page
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Afficher la modale
            const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            modal.show();
            
            // Supprimer la modale du DOM quand elle est fermée
            document.getElementById('taskDetailModal').addEventListener('hidden.bs.modal', function () {
                this.remove();
            });
        },
        eventClassNames: function(args) {
            // Ajouter des classes CSS basées sur le statut de la tâche
            return args.event.extendedProps.className || [];
        }
    });
    
    calendar.render();
});
</script>

<style>
.fc-event-completed {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
}
.fc-event-high {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
}
.fc-event-medium {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #212529 !important;
}
.fc-event-low {
    background-color: #17a2b8 !important;
    border-color: #17a2b8 !important;
}
</style>
@endsection