<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Parent_Tuteur;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EleveController extends Controller
{
    public function index()
    {
        $eleves = Eleve::with([
            'classe.niveau',
            'classe.emploisDuTemps.matiere',
            'classe.emploisDuTemps.enseignant',
            'parentPrincipal',
            'parentSecondaire',
            'parents',
            'notes.matiere',
            'notes.enseignant',
            'absences',
            'paiements'
        ])->get();
        return response()->json($eleves, 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'classe_id' => 'nullable|exists:classes,id',
                'parent_principal_id' => 'nullable|exists:parent_tuteurs,id',
                'parent_secondaire_id' => 'nullable|exists:parent_tuteurs,id',
                'matricule' => 'required|string|unique:eleves,matricule',
                'email' => 'nullable|email|unique:eleves,email',
                'telephone_parent' => 'sometimes|string|max:20',
                'telephone' => 'nullable|string',
                "lieu_naissance" => 'nullable|string',
            ]);

            $eleve = Eleve::create($data);
            return response()->json($eleve, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation échouée',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $eleve = Eleve::with([
                'classe.emploisDuTemps.matiere',
                'classe.emploisDuTemps.enseignant',
                'parent',
                'notes.matiere',
                'notes.enseignant',
                'absences'
            ])->findOrFail($id);

            return response()->json($eleve, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Mettre à jour

    public function update(Request $request, $id)
    {
        $eleve = Eleve::find($id);
        if (!$eleve) {
            return response()->json(['message' => 'Élève non trouvé'], 404);
        }

        try {
            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'nationalite' => 'nullable',
                'classe_id' => 'nullable|exists:classes,id',
                'parent_id' => 'nullable|exists:parent_tuteurs,id',
                'matricule' => 'sometimes|string|unique:eleves,matricule,' . $id,
                'email' => 'nullable|email|unique:eleves,email,' . $id,
                'telephone_parent' => 'sometimes|string|max:20',
                'telephone' => 'sometimes|string|max:20',
                "lieu_naissance" => 'sometimes|string',
                "nombre_absences" => 'string|max:20',
                "numero_piece_identite" => 'string|max:20',

            ]);

            $eleve->update($data);
            return response()->json($eleve, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation échouée',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $eleve = Eleve::find($id);
        if (!$eleve) {
            return response()->json(['message' => 'Élève non trouvé'], 404);
        }

        try {
            $eleve->delete();
            return response()->json(['message' => 'Élève supprimé'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
