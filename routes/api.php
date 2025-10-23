<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\Parent_TuteurController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\DirecteurController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\PaiementController;

/*
|--------------------------------------------------------------------------
| API Routes personnalisées
|--------------------------------------------------------------------------
*/

// Élèves
Route::get('eleves/list', [EleveController::class, 'index']);
Route::post('eleves/add', [EleveController::class, 'store']);
Route::get('eleves/view/{id}', [EleveController::class, 'show']);
Route::put('eleves/edit/{id}', [EleveController::class, 'update']);
Route::delete('eleves/delete/{id}', [EleveController::class, 'destroy']);

// Parents / Tuteurs
Route::get('parents/list', [Parent_TuteurController::class, 'index']);
Route::post('parents/add', [Parent_TuteurController::class, 'store']);
Route::get('parents/view/{id}', [Parent_TuteurController::class, 'show']);
Route::put('parents/edit/{id}', [Parent_TuteurController::class, 'update']);
Route::delete('parents/delete/{id}', [Parent_TuteurController::class, 'destroy']);

// Classes
Route::get('classes/list', [ClasseController::class, 'index']);
Route::post('classes/add', [ClasseController::class, 'store']);
Route::get('classes/view/{id}', [ClasseController::class, 'show']);
Route::put('classes/edit/{id}', [ClasseController::class, 'update']);
Route::delete('classes/delete/{id}', [ClasseController::class, 'destroy']);

// Enseignants
Route::get('enseignants/list', [EnseignantController::class, 'index']);
Route::post('enseignants/add', [EnseignantController::class, 'store']);
Route::get('enseignants/view/{id}', [EnseignantController::class, 'show']);
Route::put('enseignants/edit/{id}', [EnseignantController::class, 'update']);
Route::delete('enseignants/delete/{id}', [EnseignantController::class, 'destroy']);

// Matières
Route::get('matieres/list', [MatiereController::class, 'index']);
Route::post('matieres/add', [MatiereController::class, 'store']);
Route::get('matieres/view/{id}', [MatiereController::class, 'show']);
Route::put('matieres/edit/{id}', [MatiereController::class, 'update']);
Route::delete('matieres/delete/{id}', [MatiereController::class, 'destroy']);

// Notes
Route::get('notes/list', [NoteController::class, 'index']);
Route::post('notes/add', [NoteController::class, 'store']);
Route::get('notes/view/{id}', [NoteController::class, 'show']);
Route::put('notes/edit/{id}', [NoteController::class, 'update']);
Route::delete('notes/delete/{id}', [NoteController::class, 'destroy']);

// Absences
Route::get('absences/list', [AbsenceController::class, 'index']);
Route::post('absences/add', [AbsenceController::class, 'store']);
Route::get('absences/view/{id}', [AbsenceController::class, 'show']);
Route::put('absences/edit/{id}', [AbsenceController::class, 'update']);
Route::delete('absences/delete/{id}', [AbsenceController::class, 'destroy']);

// Emplois du temps
Route::get('emplois/list', [EmploiDuTempsController::class, 'index']);
Route::post('emplois/add', [EmploiDuTempsController::class, 'store']);
Route::get('emplois/view/{id}', [EmploiDuTempsController::class, 'show']);
Route::put('emplois/edit/{id}', [EmploiDuTempsController::class, 'update']);
Route::delete('emplois/delete/{id}', [EmploiDuTempsController::class, 'destroy']);

// Niveaux
Route::get('niveaux/list', [NiveauController::class, 'index']);
Route::post('niveaux/add', [NiveauController::class, 'store']);
Route::get('niveaux/view/{id}', [NiveauController::class, 'show']);
Route::put('niveaux/edit/{id}', [NiveauController::class, 'update']);
Route::delete('niveaux/delete/{id}', [NiveauController::class, 'destroy']);
Route::get('niveaux/{id}/classes', [NiveauController::class, 'classes']);
Route::get('niveaux/{id}/statistiques', [NiveauController::class, 'statistiques']);

// Directeurs
Route::get('directeurs/list', [DirecteurController::class, 'index']);
Route::post('directeurs/add', [DirecteurController::class, 'store']);
Route::get('directeurs/view/{id}', [DirecteurController::class, 'show']);
Route::put('directeurs/edit/{id}', [DirecteurController::class, 'update']);
Route::delete('directeurs/delete/{id}', [DirecteurController::class, 'destroy']);

// Personnel
Route::get('personnel/list', [PersonnelController::class, 'index']);
Route::post('personnel/add', [PersonnelController::class, 'store']);
Route::get('personnel/view/{id}', [PersonnelController::class, 'show']);
Route::put('personnel/edit/{id}', [PersonnelController::class, 'update']);
Route::delete('personnel/delete/{id}', [PersonnelController::class, 'destroy']);
Route::get('personnel/fonction/{fonction}', [PersonnelController::class, 'parFonction']);

// Salles
Route::get('salles/list', [SalleController::class, 'index']);
Route::post('salles/add', [SalleController::class, 'store']);
Route::get('salles/view/{id}', [SalleController::class, 'show']);
Route::put('salles/edit/{id}', [SalleController::class, 'update']);
Route::delete('salles/delete/{id}', [SalleController::class, 'destroy']);
Route::get('salles/disponibles', [SalleController::class, 'disponibles']);
Route::get('salles/type/{type}', [SalleController::class, 'parType']);

// Paiements
Route::get('paiements/list', [PaiementController::class, 'index']);
Route::post('paiements/add', [PaiementController::class, 'store']);
Route::get('paiements/view/{id}', [PaiementController::class, 'show']);
Route::put('paiements/edit/{id}', [PaiementController::class, 'update']);
Route::delete('paiements/delete/{id}', [PaiementController::class, 'destroy']);
Route::get('paiements/en-retard', [PaiementController::class, 'enRetard']);
Route::get('paiements/payes', [PaiementController::class, 'payes']);
Route::get('paiements/eleve/{eleveId}', [PaiementController::class, 'parEleve']);
Route::post('paiements/{id}/paiement-partiel', [PaiementController::class, 'paiementPartiel']);
