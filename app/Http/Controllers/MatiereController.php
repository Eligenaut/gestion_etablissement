<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MatiereController extends Controller
{
    public function index()
    {
        return response()->json(Matiere::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255|unique:matieres,nom',
                'code' => 'nullable|string|unique:matieres,code',
                'coefficient' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'couleur' => 'nullable|string',
                'icone' => 'nullable|string',
                'niveau_requis' => 'nullable|integer|min:1',
                'statut' => 'nullable|string|in:active,inactive'
            ]);

            $matiere = Matiere::create($data);
            return response()->json($matiere, 201);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $matiere = Matiere::find($id);
        if (!$matiere) return response()->json(['message'=>'Matière non trouvée'],404);
        return response()->json($matiere,200);
    }

    public function update(Request $request, $id)
    {
        $matiere = Matiere::find($id);
        if (!$matiere) return response()->json(['message'=>'Matière non trouvée'],404);

        try {
            $data = $request->validate([
                'nom' => 'sometimes|string|max:255|unique:matieres,nom,' . $id,
                'code' => 'nullable|string|unique:matieres,code,' . $id,
                'coefficient' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'couleur' => 'nullable|string',
                'icone' => 'nullable|string',
                'niveau_requis' => 'nullable|integer|min:1',
                'statut' => 'nullable|string|in:active,inactive'
            ]);

            $matiere->update($data);
            return response()->json($matiere,200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $matiere = Matiere::find($id);
        if (!$matiere) return response()->json(['message' => 'Matière non trouvée'], 404);
        
        $matiere->delete();
        return response()->json(['message' => 'Matière supprimée avec succès'], 200);
    }
}
