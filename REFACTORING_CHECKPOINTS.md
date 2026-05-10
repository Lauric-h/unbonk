# Refactoring Option 2 - Checkpoints Custom par Plan

## ✅ Changements Implémentés

### 1. Architecture du Modèle de Domaine

**Avant :**
```
Checkpoint (unique entity)
├── externalId (nullable)
└── appartient à ImportedRace
```

**Après :**
```
CheckpointInterface
├── AbstractCheckpoint (base class for Doctrine STI)
    ├── ImportedCheckpoint (immutable, belongs to ImportedRace)
    │   └── externalId, type, importedRace
    └── CustomCheckpoint (editable, belongs to NutritionPlan)
        └── nutritionPlan
```

### 2. Nouvelles Entités Créées

- ✅ `CheckpointInterface` - Interface commune
- ✅ `AbstractCheckpoint` - Classe abstraite de base (pour Doctrine ORM)
- ✅ `ImportedCheckpoint` - Checkpoints officiels importés (read-only)
- ✅ `CustomCheckpoint` - Checkpoints personnalisés de l'utilisateur (editable)

### 3. Modifications des Entités Existantes

#### `ImportedRace`
- ✅ Quasi-immutable
- ✅ Contient uniquement des `ImportedCheckpoint`
- ✅ Suppression de `removeCheckpoint()` et `getCheckpointAtDistance()`
- ✅ `addCheckpoint()` prend maintenant `ImportedCheckpoint`

#### `NutritionPlan`
- ✅ Devient l'agrégat racine pour les checkpoints custom
- ✅ Nouvelle collection : `customCheckpoints`
- ✅ Nouvelle méthode : `getAllCheckpoints()` - merge imported + custom, triés par distance
- ✅ Nouvelle méthode : `getCheckpointById()` - recherche dans les deux collections
- ✅ `addCustomCheckpoint()` modifié pour accepter `CustomCheckpoint`
- ✅ `rebuildSegments()` utilise maintenant `getAllCheckpoints()`

#### `Segment`
- ✅ `startCheckpoint` et `endCheckpoint` utilisent maintenant `CheckpointInterface`
- ✅ Méthodes `getDistance()`, `getAscent()`, `getDescent()` utilisent les getters de l'interface

### 4. Use Cases Adaptés

- ✅ `AddCheckpointCommandHandler` - crée des `CustomCheckpoint`
- ✅ `UpdateCheckpointCommandHandler` - utilise `getAllCheckpoints()`
- ✅ `RemoveCheckpointCommandHandler` - utilise `getAllCheckpoints()`

### 5. Factories Adaptées

- ✅ `ImportedRaceFactory` - crée des `ImportedCheckpoint`

### 6. ReadModels Adaptés

- ✅ `CheckpointReadModel` - ajoute `isEditable`
- ✅ `ImportedRaceReadModel` - utilise `CheckpointInterface`
- ✅ `NutritionPlanReadModel` - ajoute liste complète des `checkpoints`
- ✅ `SegmentReadModel` - utilise `CheckpointInterface`

### 7. Persistence (Doctrine ORM)

#### Single Table Inheritance
- ✅ Table unique `checkpoint` avec colonne discriminator `checkpoint_class`
- ✅ Mapping pour `AbstractCheckpoint` (base)
- ✅ Mapping pour `ImportedCheckpoint` (extends base)
  - Colonnes: `external_id`, `type`, `imported_race_id`
- ✅ Mapping pour `CustomCheckpoint` (extends base)
  - Colonnes: `nutrition_plan_id`

#### Mappings Mis à Jour
- ✅ `Checkpoint.orm.xml` → utilise STI
- ✅ `ImportedRace.orm.xml` → one-to-many vers `ImportedCheckpoint`
- ✅ `NutritionPlan.orm.xml` → one-to-many vers `CustomCheckpoint`
- ✅ `Segment.orm.xml` → many-to-one vers `AbstractCheckpoint`

#### Migration
- ✅ `Version20260508000000` créée
  - Ajoute `checkpoint_class` (discriminator)
  - Ajoute `nutrition_plan_id` (pour CustomCheckpoint)
  - Rend `external_id`, `type`, `imported_race_id` nullable
  - Ajoute contrainte FK pour `nutrition_plan_id`

### 8. Tests Adaptés

- ✅ `NutritionPlanTest` - utilise `CustomCheckpoint`
- ✅ `NutritionPlanTestFixture` - crée des `ImportedCheckpoint`
- ✅ Suppression du test obsolète (non-custom checkpoint)

---

## 🎯 Avantages de cette Solution

### Isolation Complète
✅ Chaque `NutritionPlan` a ses propres checkpoints custom
✅ Impossible qu'un checkpoint custom d'un plan apparaisse sur un autre

### Type Safety
✅ Impossible de créer un "faux" custom checkpoint par erreur
✅ Les ImportedCheckpoint sont immutables (readonly properties)
✅ Les CustomCheckpoint sont explicitement éditables

### Flexibilité
✅ Un utilisateur peut créer plusieurs plans avec des checkpoints différents pour la même course
✅ Plusieurs utilisateurs peuvent importer la même course sans conflit

### Performance
✅ Single Table Inheritance = une seule table, pas de joins complexes
✅ Index sur `nutrition_plan_id` et `imported_race_id`

---

## 📋 Prochaines Étapes (MVP)

### Phase 1 : Validation & Tests ✅ (FAIT)
- [x] Créer les entités
- [x] Adapter les use cases
- [x] Mettre à jour la persistence
- [x] Adapter les tests unitaires

### Phase 2 : Installer et Tester 🔧
```bash
# 1. Installer les dépendances (si nécessaire)
composer install

# 2. Exécuter la migration
php bin/console doctrine:migrations:migrate

# 3. Lancer les tests
php bin/console test

# 4. Vérifier la génération du schema
php bin/console doctrine:schema:validate
```

### Phase 3 : Use Cases Manquants 📝
- [ ] `CreateNutritionPlanCommand` - créer un plan supplémentaire pour une race existante
- [ ] `UpdateNutritionPlanCommand` - renommer un plan
- [ ] Tests pour ces use cases

### Phase 4 : Controllers REST 🌐
- [ ] `POST /imported-races/{raceId}/nutrition-plans`
- [ ] `PATCH /nutrition-plans/{id}`
- [ ] Tests d'intégration

### Phase 5 : Adapter Réel de l'API 🔌
- [ ] Identifier l'API externe (LiveTrail, ITRA, UTMB?)
- [ ] Implémenter le vrai `ExternalRaceAdapter`
- [ ] Gestion des erreurs réseau/cache

### Phase 6 : Interface Utilisateur 🎨
- [ ] Liste des événements/courses
- [ ] Import d'une course
- [ ] Visualisation du plan avec tous les checkpoints
- [ ] Formulaire d'ajout de checkpoint custom
- [ ] Ajout de nutrition sur segments

---

## 🗄️ Structure de la Base de Données

### Table `checkpoint` (Single Table Inheritance)

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | VARCHAR(255) PK | ID unique |
| `checkpoint_class` | VARCHAR(255) | Discriminator: 'imported' ou 'custom' |
| `name` | VARCHAR(255) | Nom du checkpoint |
| `location` | VARCHAR(255) | Localisation |
| `distance_from_start` | INT | Distance en mètres |
| `ascent_from_start` | INT | Dénivelé positif cumulé |
| `descent_from_start` | INT | Dénivelé négatif cumulé |
| `assistance_allowed` | BOOLEAN | Assistance autorisée |
| `cutoff_time` | DATETIME | Temps limite (nullable) |
| `external_id` | VARCHAR(255) NULL | ID externe (ImportedCheckpoint only) |
| `type` | VARCHAR(255) NULL | Type de checkpoint (ImportedCheckpoint only) |
| `imported_race_id` | VARCHAR(255) NULL FK | Lien vers ImportedRace (ImportedCheckpoint only) |
| `nutrition_plan_id` | VARCHAR(255) NULL FK | Lien vers NutritionPlan (CustomCheckpoint only) |

**Constraints:**
- FK: `imported_race_id` → `imported_race(id)` ON DELETE CASCADE
- FK: `nutrition_plan_id` → `nutrition_plan(id)` ON DELETE CASCADE
- Index sur `nutrition_plan_id` et `imported_race_id`

---

## 📚 Exemples d'Utilisation

### Créer un checkpoint custom

```php
$nutritionPlan = $nutritionPlanRepository->get($nutritionPlanId);

$customCheckpoint = new CustomCheckpoint(
    id: $idGenerator->generate(),
    name: 'Ravitaillement Personnel',
    location: 'Col du Bonhomme',
    distanceFromStart: 42000, // 42km
    ascentFromStart: 2500,
    descentFromStart: 1800,
    cutoff: new Cutoff(new \DateTimeImmutable('2024-06-01 14:30:00')),
    assistanceAllowed: true,
    nutritionPlan: $nutritionPlan,
);

$segmentIds = generateSegmentIds($nutritionPlan);
$nutritionPlan->addCustomCheckpoint($customCheckpoint, $segmentIds);

$nutritionPlanRepository->save($nutritionPlan);
```

### Récupérer tous les checkpoints (imported + custom)

```php
$nutritionPlan = $nutritionPlanRepository->get($nutritionPlanId);

// Retourne tous les checkpoints triés par distance
$allCheckpoints = $nutritionPlan->getAllCheckpoints();

foreach ($allCheckpoints as $checkpoint) {
    echo $checkpoint->getName() . ' - ';
    echo $checkpoint->isEditable() ? 'Custom' : 'Imported';
    echo PHP_EOL;
}
```

### Distinguer les types dans un template

```php
// Dans un ReadModel ou template
$checkpoints = $nutritionPlan->getAllCheckpoints();

foreach ($checkpoints as $checkpoint) {
    if ($checkpoint instanceof ImportedCheckpoint) {
        // Afficher avec badge "Officiel"
        // Désactiver l'édition
    } elseif ($checkpoint instanceof CustomCheckpoint) {
        // Afficher avec badge "Personnalisé"
        // Permettre édition/suppression
    }
}
```

---

## 🔍 Points d'Attention

### Migration des Données Existantes
Si des données existent déjà en production :
1. Tous les checkpoints existants seront marqués comme `imported`
2. Aucun checkpoint custom n'existera initialement
3. Les utilisateurs pourront en ajouter après la migration

### Validation Métier
✅ Impossible d'ajouter un checkpoint custom à la même distance qu'un existant
✅ Impossible de supprimer/modifier des checkpoints importés
✅ Les segments sont automatiquement recalculés avec les nutrition items préservés

### Performance
- Les requêtes Doctrine chargeront automatiquement le bon type grâce au discriminator
- Un index sur `checkpoint_class` peut être ajouté si nécessaire
- Les jointures restent simples (pas de polymorphisme multi-tables)

---

## ✨ Conclusion

Cette implémentation de l'**Option 2** offre :
- ✅ Isolation complète des checkpoints custom par plan
- ✅ Type safety et immutabilité des données importées
- ✅ Flexibilité pour avoir plusieurs stratégies nutritionnelles
- ✅ Base solide pour le MVP

Prêt pour les tests et l'intégration ! 🚀
