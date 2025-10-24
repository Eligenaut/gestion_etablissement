<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SalleController extends Controller
{
    public function index()
    {
        return response()->json(Salle::with('responsable', 'emploisDuTemps', 'reservations')->get(), 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'numero' => 'required|string|unique:salles,numero',
                'type' => 'required|string|in:Classe,Laboratoire,Bibliothèque,Bureau,Amphithéâtre,Salle de sport',
                'capacite' => 'required|integer|min:1',
                'equipements' => 'nullable|array',
                'description' => 'nullable|string',
                'etage' => 'nullable|integer|min:0',
                'batiment' => 'nullable|string',
                'statut' => 'nullable|string|in:Disponible,Occupée,En maintenance',
                'responsable_id' => 'nullable|exists:personnels,id',
            ]);

            $salle = Salle::create($data);
            return response()->json($salle->load('responsable'), 201);

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
        $salle = Salle::with('responsable', 'emploisDuTemps.enseignant', 'emploisDuTemps.matiere', 'reservations')->find($id);
        if (!$salle) return response()->json(['message' => 'Salle non trouvée'], 404);
        return response()->json($salle, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $salle = Salle::find($id);
            if (!$salle) return response()->json(['message' => 'Salle non trouvée'], 404);

            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'numero' => 'sometimes|string|unique:salles,numero,' . $id,
                'type' => 'sometimes|string|in:Classe,Laboratoire,Bibliothèque,Bureau,Amphithéâtre,Salle de sport',
                'capacite' => 'sometimes|integer|min:1',
                'equipements' => 'nullable|array',
                'description' => 'nullable|string',
                'etage' => 'nullable|integer|min:0',
                'batiment' => 'nullable|string',
                'statut' => 'nullable|string|in:Disponible,Occupée,En maintenance',
                'responsable_id' => 'nullable|exists:personnels,id',
            ]);

            $salle->update($data);
            return response()->json($salle->load('responsable'), 200);

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
        $salle = Salle::find($id);
        if (!$salle) return response()->json(['message' => 'Salle non trouvée'], 404);
        
        $salle->delete();
        return response()->json(['message' => 'Salle supprimée avec succès'], 200);
    }

    /**
     * Obtenir les salles disponibles
     */
    public function disponibles()
    {
        $salles = Salle::disponibles()->with('responsable')->get();
        return response()->json($salles, 200);
    }

    /**
     * Filtrer les salles par type
     */
    public function parType($type)
    {
        $salles = Salle::parType($type)->with('responsable')->get();
        return response()->json($salles, 200);
    }
}
