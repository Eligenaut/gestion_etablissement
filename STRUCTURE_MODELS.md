# Structure Améliorée des Modèles - Gestion d'Établissement

## Vue d'ensemble des améliorations

### 1. Nouveau modèle `Niveau`
- **Objectif** : Gérer la hiérarchie des niveaux (3ème, 4ème, 5ème, etc.)
- **Avantages** : Évite les erreurs de saisie et standardise la nomenclature
- **Exemple** : Niveau "3ème" contient les classes "3A", "3B", "3C"

### 2. Relations corrigées et améliorées

## Structure des Relations

### Niveau (1) → (N) Classe
- Un niveau peut contenir plusieurs classes
- Une classe appartient à un seul niveau
- **Exemple** : Niveau "3ème" → Classes "3A", "3B", "3C"

### Classe (1) → (N) Élève
- Une classe peut contenir plusieurs élèves
- Un élève appartient à une seule classe

### Enseignant (N) ↔ (N) Matière
- **Relation many-to-many** : Un enseignant peut enseigner plusieurs matières
- Une matière peut être enseignée par plusieurs enseignants
- **Table pivot** : `enseignant_matiere` avec colonnes supplémentaires

### Classe (N) ↔ (N) Matière
- **Relation many-to-many** : Une classe peut avoir plusieurs matières
- Une matière peut être enseignée dans plusieurs classes
- **Table pivot** : `classe_matiere` avec enseignant assigné

## Modèles Améliorés

### 1. Niveau
```php
- id
- nom (ex: "3ème", "4ème")
- code (ex: "3", "4") 
- ordre (pour trier)
- description
- responsable_id (enseignant responsable du niveau)
```

### 2. Classe (modifié)
```php
- id
- nom (ex: "A", "B", "C")
- niveau_id (référence vers Niveau)
- responsable_id (enseignant responsable de classe)
- capacite_max
- description
```

### 3. Enseignant (amélioré)
```php
- Ajout de champs : specialite, diplome, date_embauche, salaire
- Relations : matieres(), classesResponsable(), niveauxResponsable()
```

### 4. Matière (amélioré)
```php
- Ajout de champs : description, couleur, icone, niveau_requis, statut
- Relations : enseignants(), classes(), emploisDuTemps()
```

## Tables de Liaison

### enseignant_matiere
```php
- enseignant_id
- matiere_id
- coefficient (coefficient de l'enseignant pour cette matière)
- date_debut, date_fin
```

### classe_matiere
```php
- classe_id
- matiere_id
- enseignant_id (enseignant assigné à cette matière dans cette classe)
- coefficient (coefficient de la matière dans cette classe)
```

## Avantages de cette Structure

1. **Hiérarchie claire** : Niveau → Classe → Élève
2. **Relations correctes** : Many-to-many entre Enseignant et Matière
3. **Flexibilité** : Un enseignant peut enseigner plusieurs matières
4. **Traçabilité** : Chaque matière dans une classe a un enseignant assigné
5. **Évite les erreurs** : Nomenclature standardisée des niveaux et classes

## Exemples d'utilisation

### Créer un niveau et ses classes
```php
$niveau = Niveau::create([
    'nom' => '3ème',
    'code' => '3',
    'ordre' => 3
]);

$classeA = Classe::create([
    'nom' => 'A',
    'niveau_id' => $niveau->id,
    'responsable_id' => $enseignant->id
]);
```

### Assigner un enseignant à une matière
```php
$enseignant->matieres()->attach($matiere->id, [
    'coefficient' => 1.5,
    'date_debut' => now()
]);
```

### Assigner une matière à une classe avec enseignant
```php
$classe->matieres()->attach($matiere->id, [
    'enseignant_id' => $enseignant->id,
    'coefficient' => 2.0
]);
```
