<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClasseController extends Controller
{
    public function index()
    {
        return response()->json(Classe::with(['eleves', 'responsable', 'niveau', 'matieres'])->get(), 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'niveau_id' => 'required|exists:niveaux,id',
                'responsable_id' => 'nullable|exists:enseignants,id',
                'capacite_max' => 'nullable|integer|min:1',
                'description' => 'nullable|string',
            ]);

            $classe = Classe::create($data);
            
            // Assigner les matières si fournies
            if ($request->has('matieres')) {
                $matieres = $request->input('matieres', []);
                foreach ($matieres as $matiereData) {
                    $classe->matieres()->attach($matiereData['matiere_id'], [
                        'enseignant_id' => $matiereData['enseignant_id'],
                        'coefficient' => $matiereData['coefficient'] ?? 1.0
                    ]);
                }
            }
            
            return response()->json($classe->load(['niveau', 'responsable', 'matieres']), 201);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $classe = Classe::with(['eleves', 'responsable', 'niveau', 'matieres.enseignants'])->find($id);
        if (!$classe) return response()->json(['message'=>'Classe non trouvée'],404);
        return response()->json($classe,200);
    }

    public function update(Request $request, $id)
    {
        $classe = Classe::find($id);
        if (!$classe) return response()->json(['message'=>'Classe non trouvée'],404);

        try {
            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'niveau' => 'sometimes|string|max:255',
                'responsable_id' => 'nullable|exists:enseignants,id',
            ]);

            $classe->update($data);
            return response()->json($classe,200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $classe = Classe::find($id);
        if (!$classe) return response()->json(['message'=>'Classe non trouvée'],404);

        try {
            $classe->delete();
            return response()->json(['message'=>'Classe supprimée'],200);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }
}
