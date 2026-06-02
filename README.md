# JAGUAR SECURITY SERVICES APP

Application web Laravel dédiée à la gestion des activités d’une société de sécurité et de services associés. Le projet regroupe un site public multilingue et un espace d’administration pour piloter les ressources humaines, les clients, la facturation, les paiements, la logistique et les recrutements.

## Aperçu

Le projet s’appuie sur Laravel 10, Livewire, Tailwind CSS et Sanctum. Il expose plusieurs parcours métiers visibles dans les routes du projet :

- site public avec pages d’accueil, à propos, services, équipe, boutiques et contact
- dépôt de candidatures et module de recrutement
- espace admin avec tableaux de bord et rôles métiers
- gestion des employés, clients, factures, paiements et dotations
- gestion de la logistique, des équipements, des affectations et des absences
- génération de documents et impressions PDF
- QR codes pour documents et usages internes

## Fonctionnalités principales

- authentification et gestion des accès
- gestion des employés et des candidats
- gestion commerciale avec clients, factures et paiements
- suivi RH avec affectations, suspensions, licenciements et congés
- gestion de matériel, de logistique et de dotations
- vues publiques en français avec prise en charge de la langue
- génération de rapports et documents imprimables

## Stack technique

- PHP 8.1+
- Laravel 10
- Livewire 3
- Tailwind CSS
- Vite
- Laravel Sanctum
- FPDF / QR Code

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure ensuite la base de données dans le fichier `.env`, puis lance les migrations :

```bash
php artisan migrate
```

## Lancement en local

Dans deux terminaux séparés :

```bash
php artisan serve
```

```bash
npm run dev
```

## Compilation front-end

```bash
npm run build
```

## Structure fonctionnelle

- `routes/web.php` : pages publiques et dépôt de candidature
- `routes/admin.php` : espace d’administration et modules métiers
- `routes/recrutement.php` : parcours de recrutement
- `app/Models/` : modèles métier de l’application
- `resources/views/` : vues Blade

## Description GitHub

Application Laravel de gestion pour une société de sécurité : clients, employés, recrutement, facturation, paiements, logistique, dotations et documents PDF.

## Topics GitHub

`laravel`, `php`, `security-services`, `hr-management`, `recruitment`, `billing`, `payments`, `logistics`, `pdf-generation`, `qr-code`, `livewire`, `tailwind-css`

## Licence

Ce projet est distribué sous licence MIT.
