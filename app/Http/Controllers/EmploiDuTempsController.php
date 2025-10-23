<?php

namespace App\Http\Controllers;

use App\Models\EmploiDuTemps;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmploiDuTempsController extends Controller
{
    public function index()
    {
        return response()->json(EmploiDuTemps::with(['classe','enseignant','matiere'])->get(),200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'classe_id' => 'required|exists:classes,id',
                'enseignant_id' => 'required|exists:enseignants,id',
                'matiere_id' => 'required|exists:matieres,id',
                'jour' => 'required|string|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',
                'salle' => 'nullable|string|max:100',
                'type_cours' => 'nullable|string|in:Cours,TP,TD,Examen,Autre',
                'duree' => 'nullable|integer|min:1', // Durée en minutes
                'semaine' => 'nullable|integer|min:1|max:52',
                'periode' => 'nullable|string|in:1er trimestre,2ème trimestre,3ème trimestre',
                'statut' => 'nullable|string|in:Actif,Annulé,Reporté',
                'observations' => 'nullable|string|max:500'
            ]);

            $emploi = EmploiDuTemps::create($data);
            return response()->json($emploi->load('classe','enseignant','matiere'),201);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $emploi = EmploiDuTemps::with(['classe','enseignant','matiere'])->find($id);
        if (!$emploi) return response()->json(['message'=>'Emploi du temps non trouvé'],404);
        return response()->json($emploi,200);
    }

    public function update(Request $request,$id)
    {
        $emploi = EmploiDuTemps::find($id);
        if (!$emploi) return response()->json(['message'=>'Emploi du temps non trouvé'],404);

        try {
            $data = $request->validate([
                'classe_id' => 'sometimes|exists:classes,id',
                'enseignant_id' => 'sometimes|exists:enseignants,id',
                'matiere_id' => 'sometimes|exists:matieres,id',
                'jour' => 'sometimes|string|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
                'heure_debut' => 'sometimes|date_format:H:i',
                'heure_fin' => 'sometimes|date_format:H:i|after:heure_debut',
                'salle' => 'nullable|string|max:100',
                'type_cours' => 'nullable|string|in:Cours,TP,TD,Examen,Autre',
                'duree' => 'nullable|integer|min:1',
                'semaine' => 'nullable|integer|min:1|max:52',
                'periode' => 'nullable|string|in:1er trimestre,2ème trimestre,3ème trimestre',
                'statut' => 'nullable|string|in:Actif,Annulé,Reporté',
                'observations' => 'nullable|string|max:500'
            ]);

            $emploi->update($data);
            return response()->json($emploi->load('classe','enseignant','matiere'),200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $emploi = EmploiDuTemps::find($id);
        if (!$emploi) return response()->json(['message'=>'Emploi du temps non trouvé'],404);

        try {
            $emploi->delete();
            return response()->json(['message'=>'Emploi du temps supprimé'],200);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }
}
