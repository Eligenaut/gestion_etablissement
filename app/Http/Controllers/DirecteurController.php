<?php

namespace App\Http\Controllers;

use App\Models\Directeur;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DirecteurController extends Controller
{
    public function index()
    {
        return response()->json(Directeur::with('user', 'decisions')->get(), 200);
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
                'cin' => 'required|string|unique:directeurs,cin',
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:directeurs,email',
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'photo' => 'nullable|string',
                'matricule' => 'required|string|unique:directeurs,matricule',
                'statut' => 'nullable|string',
                'specialite' => 'nullable|string',
                'diplome' => 'nullable|string',
                'date_embauche' => 'nullable|date',
                'salaire' => 'nullable|numeric',
                'fonction' => 'nullable|string',
                'niveau_acces' => 'nullable|string',
            ]);

            $directeur = Directeur::create($data);
            return response()->json($directeur->load('user'), 201);

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
        $directeur = Directeur::with('user', 'decisions')->find($id);
        if (!$directeur) return response()->json(['message' => 'Directeur non trouvé'], 404);
        return response()->json($directeur, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $directeur = Directeur::find($id);
            if (!$directeur) return response()->json(['message' => 'Directeur non trouvé'], 404);

            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'lieu_naissance' => 'nullable|string',
                'nationalite' => 'nullable|string',
                'cin' => 'sometimes|string|unique:directeurs,cin,' . $id,
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:directeurs,email,' . $id,
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'photo' => 'nullable|string',
                'matricule' => 'sometimes|string|unique:directeurs,matricule,' . $id,
                'statut' => 'nullable|string',
                'specialite' => 'nullable|string',
                'diplome' => 'nullable|string',
                'date_embauche' => 'nullable|date',
                'salaire' => 'nullable|numeric',
                'fonction' => 'nullable|string',
                'niveau_acces' => 'nullable|string',
            ]);

            $directeur->update($data);
            return response()->json($directeur->load('user'), 200);

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
        $directeur = Directeur::find($id);
        if (!$directeur) return response()->json(['message' => 'Directeur non trouvé'], 404);
        
        $directeur->delete();
        return response()->json(['message' => 'Directeur supprimé avec succès'], 200);
    }
}
