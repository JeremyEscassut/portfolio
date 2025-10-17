# Portfolio CDA - Plan de projet

## 1️⃣ Support et UX

| Support    | Comportement                                                                                                                                                      |
| ---------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Mobile** | Interface minimaliste : texte court, lien vers CV, lien mail (`mailto:`), liens LinkedIn/Indeed. Pas d’animation ni formulaire.                                   |
| **PC**     | Version complète avec choix “Pro/Fun”. “Pro” → accès direct. “Fun” → animation d’intro optionnelle (bouton “Ignorer”). Fallback prévu si JS ou animation bloquée. |

## 2️⃣ Contenu principal

- Page d’accueil : présentation, valeurs, objectifs.
- Page projets : liens GitLab.
- Formulaire de contact (optionnel) : envoi direct vers Gmail, validation front/back, confirmation utilisateur.
- Lien vers CV (PDF).
- Liens vers LinkedIn et Indeed.
- Footer : mentions légales et politique de confidentialité minimaliste.

## 3️⃣ Tech stack & contraintes

| Élément         | Choix                                                   |
| --------------- | ------------------------------------------------------- |
| Backend         | Laravel ou HTML/CSS/JS simplifié                        |
| CSS             | TailwindCSS (+ shadcn/ui si souhaité)                   |
| JS              | Alpine.js ou minimal                                    |
| Animations      | Framer Motion / GSAP (optionnel)                        |
| Base de données | Pas nécessaire initialement                             |
| Envoi mail      | SMTP Gmail via `.env` ou service externe                |
| SEO             | `<title>`, `<meta>`, balises alt, texte accessible      |
| Accessibilité   | Contraste, navigation clavier, responsive               |
| Sécurité        | Validation formulaire, CSRF, honeypot, vérification W3C |
| Analytics       | Optionnel, dernière étape                               |

## 4️⃣ Tests & qualité

- Tests unitaires/fonctionnels sur formulaire et navigation.
- Vérification W3C pour conformité HTML/CSS et sécurité minimale.
- Pas de code IA identifiable.

## 5️⃣ Déploiement & suivi

- Hébergement : Vercel ou équivalent compatible Laravel.
- Suivi : Jira Scrum, 1 sprint de 5–7 jours.
- Priorisation : contenu statique → formulaire → animation → tests → déploiement → analytics.
