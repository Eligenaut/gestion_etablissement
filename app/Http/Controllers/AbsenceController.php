<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AbsenceController extends Controller
{
    public function index()
    {
        return response()->json(Absence::with('eleve')->get(),200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'eleve_id' => 'required|exists:eleves,id',
                'date' => 'required|date',
                'motif' => 'nullable|string|max:500',
                'justifie' => 'nullable|boolean',
                'duree' => 'nullable|integer|min:1', // Durée en heures
                'type_absence' => 'nullable|string|in:Maladie,Retard,Sortie anticipée,Autre',
                'piece_justificative' => 'nullable|string',
                'contact_parent' => 'nullable|boolean',
                'observations' => 'nullable|string|max:1000'
            ]);

            $absence = Absence::create($data);
            return response()->json($absence->load('eleve'),201);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        $absence = Absence::with('eleve')->find($id);
        if (!$absence) return response()->json(['message'=>'Absence non trouvée'],404);
        return response()->json($absence,200);
    }

    public function update(Request $request, $id)
    {
        $absence = Absence::find($id);
        if (!$absence) return response()->json(['message'=>'Absence non trouvée'],404);

        try {
            $data = $request->validate([
                'eleve_id' => 'sometimes|exists:eleves,id',
                'date' => 'sometimes|date',
                'motif' => 'nullable|string|max:500',
                'justifie' => 'nullable|boolean',
                'duree' => 'nullable|integer|min:1',
                'type_absence' => 'nullable|string|in:Maladie,Retard,Sortie anticipée,Autre',
                'piece_justificative' => 'nullable|string',
                'contact_parent' => 'nullable|boolean',
                'observations' => 'nullable|string|max:1000'
            ]);
            
            $absence->update($data);
            return response()->json($absence->load('eleve'),200);

        } catch (ValidationException $e) {
            return response()->json(['message'=>'Validation échouée','errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $absence = Absence::find($id);
        if (!$absence) return response()->json(['message'=>'Absence non trouvée'],404);

        try {
            $absence->delete();
            return response()->json(['message'=>'Absence supprimée'],200);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Erreur serveur','error'=>$e->getMessage()],500);
        }
    }
}
