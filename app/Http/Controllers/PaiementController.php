<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use App\Models\Parent_Tuteur;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaiementController extends Controller
{
    public function index()
    {
        return response()->json(Paiement::with(['eleve', 'parent', 'recuPar'])->get(), 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'eleve_id' => 'required|exists:eleves,id',
                'parent_id' => 'required|exists:parent_tuteurs,id',
                'type_paiement' => 'required|string|in:Scolarité,Transport,Cantine,Activités,Fournitures,Autres',
                'montant' => 'required|numeric|min:0',
                'montant_paye' => 'nullable|numeric|min:0',
                'date_paiement' => 'nullable|date',
                'date_echeance' => 'required|date',
                'statut' => 'nullable|string|in:En attente,Payé,En retard,Annulé',
                'mode_paiement' => 'nullable|string|in:Espèces,Chèque,Virement,Carte',
                'reference' => 'nullable|string',
                'observations' => 'nullable|string',
                'recu_par' => 'nullable|exists:personnel,id',
            ]);

            // Calculer le montant payé si non fourni
            if (!isset($data['montant_paye'])) {
                $data['montant_paye'] = 0;
            }

            // Définir le statut si non fourni
            if (!isset($data['statut'])) {
                $data['statut'] = $data['montant_paye'] >= $data['montant'] ? 'Payé' : 'En attente';
            }

            $paiement = Paiement::create($data);
            return response()->json($paiement->load(['eleve', 'parent', 'recuPar']), 201);

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
        $paiement = Paiement::with(['eleve', 'parent', 'recuPar'])->find($id);
        if (!$paiement) return response()->json(['message' => 'Paiement non trouvé'], 404);
        return response()->json($paiement, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $paiement = Paiement::find($id);
            if (!$paiement) return response()->json(['message' => 'Paiement non trouvé'], 404);

            $data = $request->validate([
                'eleve_id' => 'sometimes|exists:eleves,id',
                'parent_id' => 'sometimes|exists:parent_tuteurs,id',
                'type_paiement' => 'sometimes|string|in:Scolarité,Transport,Cantine,Activités,Fournitures,Autres',
                'montant' => 'sometimes|numeric|min:0',
                'montant_paye' => 'nullable|numeric|min:0',
                'date_paiement' => 'nullable|date',
                'date_echeance' => 'sometimes|date',
                'statut' => 'nullable|string|in:En attente,Payé,En retard,Annulé',
                'mode_paiement' => 'nullable|string|in:Espèces,Chèque,Virement,Carte',
                'reference' => 'nullable|string',
                'observations' => 'nullable|string',
                'recu_par' => 'nullable|exists:personnel,id',
            ]);

            // Mettre à jour le statut si le montant payé change
            if (isset($data['montant_paye'])) {
                $montantTotal = $data['montant'] ?? $paiement->montant;
                if ($data['montant_paye'] >= $montantTotal) {
                    $data['statut'] = 'Payé';
                } elseif ($data['montant_paye'] > 0) {
                    $data['statut'] = 'En attente';
                }
            }

            $paiement->update($data);
            return response()->json($paiement->load(['eleve', 'parent', 'recuPar']), 200);

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
        $paiement = Paiement::find($id);
        if (!$paiement) return response()->json(['message' => 'Paiement non trouvé'], 404);
        
        $paiement->delete();
        return response()->json(['message' => 'Paiement supprimé avec succès'], 200);
    }

    /**
     * Obtenir les paiements en retard
     */
    public function enRetard()
    {
        $paiements = Paiement::enRetard()->with(['eleve', 'parent'])->get();
        return response()->json($paiements, 200);
    }

    /**
     * Obtenir les paiements payés
     */
    public function payes()
    {
        $paiements = Paiement::payes()->with(['eleve', 'parent'])->get();
        return response()->json($paiements, 200);
    }

    /**
     * Obtenir les paiements d'un élève
     */
    public function parEleve($eleveId)
    {
        $paiements = Paiement::where('eleve_id', $eleveId)->with(['parent', 'recuPar'])->get();
        return response()->json($paiements, 200);
    }

    /**
     * Enregistrer un paiement partiel
     */
    public function paiementPartiel(Request $request, $id)
    {
        try {
            $paiement = Paiement::find($id);
            if (!$paiement) return response()->json(['message' => 'Paiement non trouvé'], 404);

            $data = $request->validate([
                'montant_paye' => 'required|numeric|min:0',
                'mode_paiement' => 'required|string|in:Espèces,Chèque,Virement,Carte',
                'reference' => 'nullable|string',
                'recu_par' => 'required|exists:personnel,id',
            ]);

            $nouveauMontantPaye = $paiement->montant_paye + $data['montant_paye'];
            $paiement->update([
                'montant_paye' => $nouveauMontantPaye,
                'date_paiement' => now(),
                'mode_paiement' => $data['mode_paiement'],
                'reference' => $data['reference'],
                'recu_par' => $data['recu_par'],
                'statut' => $nouveauMontantPaye >= $paiement->montant ? 'Payé' : 'En attente'
            ]);

            return response()->json($paiement->load(['eleve', 'parent', 'recuPar']), 200);

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
}
