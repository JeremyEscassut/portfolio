# TECHNICAL_DEBT - Portfolio

## Regles

- Ce fichier recense la dette technique connue et priorisee.
- Chaque item doit avoir un impact, une priorite et un plan de reduction.

## Backlog priorise

## D-001 - Deprecations PHP 8.5 pendant les tests

- Impact : bruit dans les runs de tests, signal faible degrade.
- Cause : deprecations upstream Laravel/vendor (`PDO::MYSQL_ATTR_SSL_CA`, `ReflectionMethod::setAccessible`).
- Priorite : medium.
- Plan : suivre versions Laravel/collision compatibles PHP 8.5, valider apres upgrade.

## D-002 - ContactRequest non utilise

- Impact : duplication potentielle des regles de validation, risque de divergence.
- Cause : validation inline dans `ContactController`.
- Priorite : medium.
- Plan : migrer la validation vers `ContactRequest` et couvrir par tests.

## D-003 - Routage localise complexe

- Impact : risque de regression 404 sur routes localisees.
- Cause : combinaison prefix optional + fallback + redirects compatibilite.
- Priorite : high.
- Plan : documenter clairement les scenarios et renforcer les tests Feature des routes critiques.

## D-004 - Couverture tests front cross-browser insuffisante

- Impact : regressions UI possibles selon navigateur/appareil.
- Cause : absence de suite automatisee cross-browser.
- Priorite : high.
- Plan : definir une matrice de verification manuelle puis etudier Playwright.
