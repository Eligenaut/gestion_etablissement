<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NiveauController extends Controller
{
    public function index()
    {
        return response()->json(Niveau::with('responsable', 'classes')->ordered()->get(), 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'code' => 'required|string|unique:niveaux,code',
                'ordre' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'responsable_id' => 'nullable|exists:enseignants,id',
            ]);

            $niveau = Niveau::create($data);
            return response()->json($niveau->load('responsable'), 201);

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
        $niveau = Niveau::with(['responsable', 'classes.responsable', 'classes.eleves'])->find($id);
        if (!$niveau) return response()->json(['message' => 'Niveau non trouvé'], 404);
        return response()->json($niveau, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $niveau = Niveau::find($id);
            if (!$niveau) return response()->json(['message' => 'Niveau non trouvé'], 404);

            $data = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'code' => 'sometimes|string|unique:niveaux,code,' . $id,
                'ordre' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'responsable_id' => 'nullable|exists:enseignants,id',
            ]);

            $niveau->update($data);
            return response()->json($niveau->load('responsable'), 200);

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
        $niveau = Niveau::find($id);
        if (!$niveau) return response()->json(['message' => 'Niveau non trouvé'], 404);
        
        // Vérifier s'il y a des classes associées
        if ($niveau->classes()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer ce niveau car il contient des classes'
            ], 422);
        }
        
        $niveau->delete();
        return response()->json(['message' => 'Niveau supprimé avec succès'], 200);
    }

    /**
     * Obtenir les classes d'un niveau
     */
    public function classes($id)
    {
        $niveau = Niveau::with('classes.responsable', 'classes.eleves')->find($id);
        if (!$niveau) return response()->json(['message' => 'Niveau non trouvé'], 404);
        
        return response()->json($niveau->classes, 200);
    }

    /**
     * Obtenir les statistiques d'un niveau
     */
    public function statistiques($id)
    {
        $niveau = Niveau::with('classes.eleves')->find($id);
        if (!$niveau) return response()->json(['message' => 'Niveau non trouvé'], 404);

        $totalClasses = $niveau->classes()->count();
        $totalEleves = $niveau->classes()->withCount('eleves')->get()->sum('eleves_count');
        $classesAvecResponsable = $niveau->classes()->whereNotNull('responsable_id')->count();

        return response()->json([
            'niveau' => $niveau,
            'statistiques' => [
                'total_classes' => $totalClasses,
                'total_eleves' => $totalEleves,
                'classes_avec_responsable' => $classesAvecResponsable,
                'classes_sans_responsable' => $totalClasses - $classesAvecResponsable
            ]
        ], 200);
    }
}
