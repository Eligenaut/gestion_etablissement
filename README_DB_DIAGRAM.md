# 🗄️ Schéma de Base de Données - DBdiagram.io

## 🎯 **Comment utiliser ce fichier**

### 1️⃣ **Aller sur DBdiagram.io**
- Ouvrez votre navigateur
- Allez sur : https://dbdiagram.io/
- Créez un compte gratuit (optionnel)

### 2️⃣ **Importer le schéma**
- Cliquez sur "Import" ou "New Diagram"
- Copiez le contenu du fichier `database_schema.dbml`
- Collez-le dans l'éditeur
- Cliquez sur "Generate"

### 3️⃣ **Visualiser votre base de données**
- Vous verrez toutes vos tables avec leurs relations
- Zoom, déplacez, organisez comme vous voulez
- Exportez en PNG, PDF, ou partagez le lien

---

## 📊 **Contenu du Schéma**

### 🏗️ **Tables Principales (13 tables)**

#### **👥 Personnel**
- `niveaux` - Niveaux scolaires (6ème, 5ème, etc.)
- `classes` - Classes (6èmeA, 6èmeB, etc.)
- `enseignants` - Professeurs
- `directeurs` - Direction
- `personnel` - Secrétaires, surveillants, etc.
- `parent_tuteurs` - Parents et tuteurs
- `eleves` - Élèves

#### **📚 Pédagogique**
- `matieres` - Matières enseignées
- `notes` - Notes des élèves
- `absences` - Absences des élèves
- `emplois_du_temps` - Planning des cours

#### **🏠 Infrastructure**
- `salles` - Salles et espaces
- `paiements` - Gestion financière

#### **🔐 Authentification**
- `users` - Utilisateurs du système

### 🔗 **Tables de Liaison (3 tables)**
- `enseignant_matiere` - Quelles matières enseigne chaque prof
- `classe_matiere` - Quelles matières dans chaque classe
- `eleve_parent` - Relations élèves-parents

---

## 🎨 **Fonctionnalités du Schéma**

### ✅ **Relations Complètes**
- Toutes les clés étrangères
- Relations many-to-many avec tables pivot
- Relations polymorphiques pour les utilisateurs

### ✅ **Contraintes**
- Clés primaires et uniques
- Index pour les performances
- Contraintes de suppression (cascade, set null)

### ✅ **Types de Données**
- Types appropriés (varchar, decimal, date, etc.)
- Valeurs par défaut
- Champs nullable/not null

---

## 🚀 **Avantages de ce Schéma**

### 📊 **Visualisation**
- **Vue d'ensemble** de toute la base de données
- **Relations claires** entre les tables
- **Hiérarchie** des données

### 🔧 **Développement**
- **Documentation** vivante de votre base
- **Communication** avec l'équipe
- **Planification** des modifications

### 🎯 **Maintenance**
- **Compréhension** rapide de la structure
- **Détection** des problèmes de relations
- **Optimisation** des performances

---

## 📝 **Comment Modifier le Schéma**

### 🔄 **Ajouter une Table**
```sql
Table nouvelle_table {
  id bigint [pk, increment]
  nom varchar(255) [not null]
  // ... autres champs
}
```

### 🔗 **Ajouter une Relation**
```sql
Ref: table1.champ_id > table2.id [delete: cascade]
```

### 📊 **Modifier un Champ**
```sql
nom varchar(255) [not null, unique] // Ajouter unique
```

---

## 🎓 **Exemples d'Utilisation**

### 📈 **Pour les Développeurs**
- Comprendre la structure avant de coder
- Vérifier les relations avant les requêtes
- Planifier les migrations

### 👥 **Pour l'Équipe**
- Présenter la structure au client
- Former les nouveaux développeurs
- Documenter les changements

### 🔧 **Pour la Maintenance**
- Identifier les tables les plus utilisées
- Optimiser les performances
- Planifier les sauvegardes

---

## 🎯 **Prochaines Étapes**

1. **Importer** le schéma dans DBdiagram.io
2. **Visualiser** votre structure
3. **Partager** avec votre équipe
4. **Modifier** selon vos besoins
5. **Exporter** pour la documentation

---

## 💡 **Conseils**

### 🎨 **Personnalisation**
- Changez les couleurs des tables
- Organisez par groupes logiques
- Ajoutez des commentaires

### 📊 **Export**
- PNG pour les présentations
- PDF pour la documentation
- Lien partagé pour l'équipe

### 🔄 **Mise à Jour**
- Modifiez le fichier `.dbml`
- Re-importez dans DBdiagram.io
- Versionnez avec Git

Votre schéma de base de données est maintenant prêt pour DBdiagram.io ! 🎓
