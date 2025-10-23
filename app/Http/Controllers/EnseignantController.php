<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EnseignantController extends Controller
{
    public function index()
    {
        return response()->json(Enseignant::with('matieres','niveauResponsable')->get(),200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'lieu_naissance' => 'nullable|string',
                'nationalite' => 'nullable|string',
                'cin' => 'required|string|unique:enseignants,cin',
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:enseignants,email',
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'photo' => 'nullable|string',
                'matricule' => 'required|string|unique:enseignants,matricule',
                'statut' => 'nullable|string',
                'specialite' => 'nullable|string',
                'diplome' => 'nullable|string',
                'date_embauche' => 'nullable|date',
                'salaire' => 'nullable|numeric',
                'matieres' => 'nullable|array',
                'matieres.*.matiere_id' => 'required|exists:matieres,id',
                'matieres.*.coefficient' => 'nullable|numeric',
                'matieres.*.date_debut' => 'nullable|date',
                'matieres.*.date_fin' => 'nullable|date'
            ]);

            $enseignant = Enseignant::create($data);

            // Assigner les matières si fournies
            if ($request->has('matieres')) {
                $matieres = [];
                foreach ($request->matieres as $matiereData) {
                    $matieres[$matiereData['matiere_id']] = [
                        'coefficient' => $matiereData['coefficient'] ?? 1.0,
                        'date_debut' => $matiereData['date_debut'] ?? null,
                        'date_fin' => $matiereData['date_fin'] ?? null
                    ];
                }
                $enseignant->matieres()->sync($matieres);
            }

            return response()->json($enseignant->load(['matieres', 'classesResponsable', 'niveauxResponsable']), 201);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $enseignant = Enseignant::with('matieres','niveauResponsable')->find($id);
        if (!$enseignant) return response()->json(['message'=>'Enseignant non trouvé'],404);
        return response()->json($enseignant,200);
    }

    public function update(Request $request,$id)
    {
        $enseignant = Enseignant::find($id);
        if (!$enseignant) return response()->json(['message'=>'Enseignant non trouvé'],404);

        try {
            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'lieu_naissance' => 'nullable|string',
                'nationalite' => 'nullable|string',
                'cin' => 'sometimes|string|unique:enseignants,cin,' . $id,
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:enseignants,email,' . $id,
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'photo' => 'nullable|string',
                'matricule' => 'sometimes|string|unique:enseignants,matricule,' . $id,
                'statut' => 'nullable|string',
                'specialite' => 'nullable|string',
                'diplome' => 'nullable|string',
                'date_embauche' => 'nullable|date',
                'salaire' => 'nullable|numeric',
                'matieres' => 'nullable|array',
                'matieres.*.matiere_id' => 'required|exists:matieres,id',
                'matieres.*.coefficient' => 'nullable|numeric',
                'matieres.*.date_debut' => 'nullable|date',
                'matieres.*.date_fin' => 'nullable|date'
            ]);

            $enseignant->update($data);

            // Mettre à jour les matières si fournies
            if ($request->has('matieres')) {
                $matieres = [];
                foreach ($request->matieres as $matiereData) {
                    $matieres[$matiereData['matiere_id']] = [
                        'coefficient' => $matiereData['coefficient'] ?? 1.0,
                        'date_debut' => $matiereData['date_debut'] ?? null,
                        'date_fin' => $matiereData['date_fin'] ?? null
                    ];
                }
                $enseignant->matieres()->sync($matieres);
            }

            return response()->json($enseignant->load(['matieres', 'classesResponsable', 'niveauxResponsable']), 200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $enseignant = Enseignant::find($id);
        if (!$enseignant) return response()->json(['message' => 'Enseignant non trouvé'], 404);
        
        $enseignant->delete();
        return response()->json(['message' => 'Enseignant supprimé avec succès'], 200);
    }
}
