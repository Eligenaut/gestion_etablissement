# Documentation API - Gestion d'Établissement

## 🚀 Endpoints Disponibles

### 📚 **Niveaux**
```
GET    /api/niveaux/list                    # Liste tous les niveaux
POST   /api/niveaux/add                     # Créer un niveau
GET    /api/niveaux/view/{id}               # Voir un niveau
PUT    /api/niveaux/edit/{id}               # Modifier un niveau
DELETE /api/niveaux/delete/{id}             # Supprimer un niveau
GET    /api/niveaux/{id}/classes            # Classes d'un niveau
GET    /api/niveaux/{id}/statistiques       # Statistiques d'un niveau
```

### 🏫 **Classes**
```
GET    /api/classes/list                    # Liste toutes les classes
POST   /api/classes/add                     # Créer une classe
GET    /api/classes/view/{id}               # Voir une classe
PUT    /api/classes/edit/{id}               # Modifier une classe
DELETE /api/classes/delete/{id}             # Supprimer une classe
```

### 👨‍🎓 **Élèves**
```
GET    /api/eleves/list                     # Liste tous les élèves
POST   /api/eleves/add                      # Créer un élève
GET    /api/eleves/view/{id}                # Voir un élève
PUT    /api/eleves/edit/{id}              # Modifier un élève
DELETE /api/eleves/delete/{id}              # Supprimer un élève
```

### 👨‍👩‍👧‍👦 **Parents/Tuteurs**
```
GET    /api/parents/list                    # Liste tous les parents
POST   /api/parents/add                     # Créer un parent
GET    /api/parents/view/{id}               # Voir un parent
PUT    /api/parents/edit/{id}               # Modifier un parent
DELETE /api/parents/delete/{id}             # Supprimer un parent
```

### 👨‍🏫 **Enseignants**
```
GET    /api/enseignants/list                # Liste tous les enseignants
POST   /api/enseignants/add                 # Créer un enseignant
GET    /api/enseignants/view/{id}          # Voir un enseignant
PUT    /api/enseignants/edit/{id}           # Modifier un enseignant
DELETE /api/enseignants/delete/{id}         # Supprimer un enseignant
```

### 📖 **Matières**
```
GET    /api/matieres/list                   # Liste toutes les matières
POST   /api/matieres/add                    # Créer une matière
GET    /api/matieres/view/{id}              # Voir une matière
PUT    /api/matieres/edit/{id}              # Modifier une matière
DELETE /api/matieres/delete/{id}             # Supprimer une matière
```

### 🏢 **Direction**
```
GET    /api/directeurs/list                 # Liste tous les directeurs
POST   /api/directeurs/add                  # Créer un directeur
GET    /api/directeurs/view/{id}            # Voir un directeur
PUT    /api/directeurs/edit/{id}            # Modifier un directeur
DELETE /api/directeurs/delete/{id}          # Supprimer un directeur
```

### 👥 **Personnel**
```
GET    /api/personnel/list                  # Liste tout le personnel
POST   /api/personnel/add                   # Créer un membre du personnel
GET    /api/personnel/view/{id}             # Voir un membre du personnel
PUT    /api/personnel/edit/{id}             # Modifier un membre du personnel
DELETE /api/personnel/delete/{id}           # Supprimer un membre du personnel
GET    /api/personnel/fonction/{fonction}   # Personnel par fonction
```

### 🏠 **Salles**
```
GET    /api/salles/list                     # Liste toutes les salles
POST   /api/salles/add                      # Créer une salle
GET    /api/salles/view/{id}                # Voir une salle
PUT    /api/salles/edit/{id}                # Modifier une salle
DELETE /api/salles/delete/{id}              # Supprimer une salle
GET    /api/salles/disponibles              # Salles disponibles
GET    /api/salles/type/{type}              # Salles par type
```

### 💰 **Paiements**
```
GET    /api/paiements/list                  # Liste tous les paiements
POST   /api/paiements/add                   # Créer un paiement
GET    /api/paiements/view/{id}             # Voir un paiement
PUT    /api/paiements/edit/{id}             # Modifier un paiement
DELETE /api/paiements/delete/{id}           # Supprimer un paiement
GET    /api/paiements/en-retard             # Paiements en retard
GET    /api/paiements/payes                 # Paiements payés
GET    /api/paiements/eleve/{eleveId}       # Paiements d'un élève
POST   /api/paiements/{id}/paiement-partiel # Paiement partiel
```

## 📝 **Exemples d'Utilisation**

### Créer un niveau
```json
POST /api/niveaux/add
{
    "nom": "3ème",
    "code": "3",
    "ordre": 3,
    "description": "Niveau de troisième",
    "responsable_id": 1
}
```

### Créer une classe
```json
POST /api/classes/add
{
    "nom": "A",
    "niveau_id": 1,
    "responsable_id": 2,
    "capacite_max": 30,
    "description": "Classe de 3ème A",
    "matieres": [
        {
            "matiere_id": 1,
            "enseignant_id": 3,
            "coefficient": 2.0
        }
    ]
}
```

### Créer un élève
```json
POST /api/eleves/add
{
    "nom": "Dupont",
    "prenom": "Jean",
    "sexe": "M",
    "date_naissance": "2008-05-15",
    "classe_id": 1,
    "parent_principal_id": 1,
    "parent_secondaire_id": 2,
    "matricule": "ELEV001",
    "email": "jean.dupont@email.com"
}
```

### Créer un paiement
```json
POST /api/paiements/add
{
    "eleve_id": 1,
    "parent_id": 1,
    "type_paiement": "Scolarité",
    "montant": 500.00,
    "date_echeance": "2024-12-31",
    "mode_paiement": "Chèque",
    "recu_par": 1
}
```

## 🔐 **Authentification**

Tous les endpoints nécessitent une authentification. Utilisez le header :
```
Authorization: Bearer {token}
```

## 📊 **Réponses API**

### Succès
```json
{
    "data": {...},
    "message": "Opération réussie"
}
```

### Erreur de validation
```json
{
    "message": "Validation échouée",
    "errors": {
        "nom": ["Le champ nom est requis"]
    }
}
```

### Erreur serveur
```json
{
    "message": "Erreur serveur",
    "error": "Message d'erreur détaillé"
}
```

## 🎯 **Fonctionnalités Avancées**

### Relations incluses automatiquement
- **Classes** : niveau, responsable, élèves, matières
- **Élèves** : classe, parents, notes, absences, paiements
- **Enseignants** : matières, classes responsables
- **Paiements** : élève, parent, reçu par

### Filtres et recherches
- Personnel par fonction
- Salles par type
- Paiements en retard
- Classes par niveau

### Statistiques
- Niveau : nombre de classes, élèves, responsables
- Paiements : montants, échéances, statuts
