# CLAUDE.md

Les regles de travail de ce depot sont dans les fichiers de gouvernance. Les lire avant toute modification et les appliquer :

- `.github/copilot-instructions.md` : comportement attendu de tout assistant IA (marqueur de conformite en tete de reponse, pre-verifications, discipline de travail, bilinguisme).
- `docs/governance/ARCHITECTURE.md`, `docs/governance/Guidelines.md`, `docs/governance/roadmap.md`, `docs/governance/TECHNICAL_DEBT.md`.
- `docs/reports/INCIDENT_LOG.md` (avant toute correction de bug) et `docs/reports/SOLUTIONS_LOG.md`.

A chaque lot : roadmap synchronisee avec l'etat reel, `CHANGELOG.md` mis a jour, traductions FR/EN verifiees (`node tools/check-missing-translations.cjs`), tests (`php artisan test`) et build (`npm run build`) au vert.

Pour comprendre le code : `docs/PARCOURS_DE_LECTURE.md`.
