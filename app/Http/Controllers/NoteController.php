<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    public function index()
    {
        return response()->json(Note::with(['eleve','matiere','enseignant'])->get(),200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'eleve_id' => 'required|exists:eleves,id',
                'matiere_id' => 'required|exists:matieres,id',
                'enseignant_id' => 'required|exists:enseignants,id',
                'note' => 'required|numeric|min:0|max:20',
                'type' => 'nullable|string|in:Contrôle,Devoir,Examen,Composition,TP,Projet',
                'date_evaluation' => 'nullable|date',
                'coefficient' => 'nullable|numeric|min:0',
                'appreciation' => 'nullable|string|max:500',
                'statut' => 'nullable|string|in:Validée,En attente,Annulée'
            ]);

            $note = Note::create($data);
            return response()->json($note->load('eleve','matiere','enseignant'),201);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $note = Note::with(['eleve','matiere','enseignant'])->find($id);
        if (!$note) return response()->json(['message'=>'Note non trouvée'],404);
        return response()->json($note,200);
    }

    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) return response()->json(['message'=>'Note non trouvée'],404);

        try {
            $data = $request->validate([
                'eleve_id' => 'sometimes|exists:eleves,id',
                'matiere_id' => 'sometimes|exists:matieres,id',
                'enseignant_id' => 'sometimes|exists:enseignants,id',
                'note' => 'sometimes|numeric|min:0|max:20',
                'type' => 'nullable|string|in:Contrôle,Devoir,Examen,Composition,TP,Projet',
                'date_evaluation' => 'nullable|date',
                'coefficient' => 'nullable|numeric|min:0',
                'appreciation' => 'nullable|string|max:500',
                'statut' => 'nullable|string|in:Validée,En attente,Annulée'
            ]);

            $note->update($data);
            return response()->json($note->load('eleve','matiere','enseignant'),200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $note = Note::find($id);
        if (!$note) return response()->json(['message'=>'Note non trouvée'],404);

        try {
            $note->delete();
            return response()->json(['message'=>'Note supprimée'],200);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }
}
