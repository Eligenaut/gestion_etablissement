<?php

namespace App\Http\Controllers;

use App\Models\Parent_Tuteur;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Parent_TuteurController extends Controller
{
    public function index()
    {
        return response()->json(Parent_Tuteur::with('eleves')->get(), 200);
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
                'cin' => 'nullable|string|unique:parent_tuteurs,cin',
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:parent_tuteurs,email',
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'profession' => 'nullable|string',
                'employeur' => 'nullable|string',
                'salaire' => 'nullable|numeric',
                'niveau_etude' => 'nullable|string',
                'statut' => 'nullable|string',
                'type_parent' => 'nullable|string|in:Père,Mère,Tuteur,Grand-parent',
                'priorite' => 'nullable|integer|min:1|max:2',
                'photo' => 'nullable|string',
            ]);

            $parent = Parent_Tuteur::create($data);
            return response()->json($parent, 201);
        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $parent = Parent_Tuteur::with('eleves')->find($id);
        if (!$parent) return response()->json(['message'=>'Parent/Tuteur non trouvé'],404);
        return response()->json($parent,200);
    }

    public function update(Request $request, $id)
    {
        $parent = Parent_Tuteur::find($id);
        if (!$parent) return response()->json(['message'=>'Parent/Tuteur non trouvé'],404);

        try {
            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'sexe' => 'nullable|in:M,F,Autre',
                'date_naissance' => 'nullable|date',
                'lieu_naissance' => 'nullable|string',
                'nationalite' => 'nullable|string',
                'cin' => 'nullable|string|unique:parent_tuteurs,cin,' . $id,
                'date_delivrance_cin' => 'nullable|date',
                'lieu_delivrance_cin' => 'nullable|string',
                'email' => 'nullable|email|unique:parent_tuteurs,email,' . $id,
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'profession' => 'nullable|string',
                'employeur' => 'nullable|string',
                'salaire' => 'nullable|numeric',
                'niveau_etude' => 'nullable|string',
                'statut' => 'nullable|string',
                'type_parent' => 'nullable|string|in:Père,Mère,Tuteur,Grand-parent',
                'priorite' => 'nullable|integer|min:1|max:2',
                'photo' => 'nullable|string',
            ]);

            $parent->update($data);
            return response()->json($parent,200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $parent = Parent_Tuteur::find($id);
        if (!$parent) return response()->json(['message'=>'Parent/Tuteur non trouvé'],404);

        try {
            $parent->delete();
            return response()->json(['message'=>'Parent/Tuteur supprimé'],200);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }
}
