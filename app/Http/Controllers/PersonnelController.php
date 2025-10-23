<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PersonnelController extends Controller
{
    public function index()
    {
        return response()->json(Personnel::with('user', 'taches')->get(), 200);
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
                'cin' => 'required|string|unique:personnel,cin',
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:personnel,email',
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'photo' => 'nullable|string',
                'matricule' => 'required|string|unique:personnel,matricule',
                'statut' => 'nullable|string',
                'fonction' => 'required|string',
                'specialite' => 'nullable|string',
                'diplome' => 'nullable|string',
                'date_embauche' => 'nullable|date',
                'salaire' => 'nullable|numeric',
                'niveau_acces' => 'nullable|string',
                'responsabilites' => 'nullable|string',
            ]);

            $personnel = Personnel::create($data);
            return response()->json($personnel->load('user'), 201);

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
        $personnel = Personnel::with('user', 'taches')->find($id);
        if (!$personnel) return response()->json(['message' => 'Personnel non trouvé'], 404);
        return response()->json($personnel, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $personnel = Personnel::find($id);
            if (!$personnel) return response()->json(['message' => 'Personnel non trouvé'], 404);

            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'lieu_naissance' => 'nullable|string',
                'nationalite' => 'nullable|string',
                'cin' => 'sometimes|string|unique:personnel,cin,' . $id,
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:personnel,email,' . $id,
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'photo' => 'nullable|string',
                'matricule' => 'sometimes|string|unique:personnel,matricule,' . $id,
                'statut' => 'nullable|string',
                'fonction' => 'sometimes|string',
                'specialite' => 'nullable|string',
                'diplome' => 'nullable|string',
                'date_embauche' => 'nullable|date',
                'salaire' => 'nullable|numeric',
                'niveau_acces' => 'nullable|string',
                'responsabilites' => 'nullable|string',
            ]);

            $personnel->update($data);
            return response()->json($personnel->load('user'), 200);

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
        $personnel = Personnel::find($id);
        if (!$personnel) return response()->json(['message' => 'Personnel non trouvé'], 404);
        
        $personnel->delete();
        return response()->json(['message' => 'Personnel supprimé avec succès'], 200);
    }

    /**
     * Filtrer le personnel par fonction
     */
    public function parFonction($fonction)
    {
        $personnel = Personnel::parFonction($fonction)->with('user')->get();
        return response()->json($personnel, 200);
    }
}
