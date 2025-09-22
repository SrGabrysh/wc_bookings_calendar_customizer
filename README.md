# WooCommerce Bookings Calendar Customizer

Plugin WordPress pour personnaliser l'apparence du calendrier WooCommerce Bookings avec des styles modernes, en particulier le style **Google Calendar Inspired**.

## 📋 Description

Ce plugin transforme l'apparence du calendrier de réservation WooCommerce Bookings en appliquant des styles modernes et une expérience utilisateur améliorée. Il est conçu selon une architecture modulaire respectant les meilleures pratiques WordPress.

## ✨ Fonctionnalités

- **Style Google Calendar Inspired** : Design authentique inspiré de Google Calendar
- **Architecture modulaire** : Code organisé selon les principes SOLID
- **Responsive Design** : Optimisé pour tous les appareils
- **Animations modernes** : Transitions fluides et interactions améliorées
- **Accessibilité** : Support complet des standards d'accessibilité
- **Compatible** : WooCommerce 5.0+ et WooCommerce Bookings 1.15+

## 🛠️ Installation

1. **Télécharger** le plugin dans le dossier `wp-content/plugins/`
2. **Activer** le plugin dans l'administration WordPress
3. **Configurer** via WooCommerce > Calendar Customizer

## 📦 Prérequis

- WordPress 5.0+
- WooCommerce 5.0+
- WooCommerce Bookings 1.15+
- PHP 7.4+

## 🎨 Styles Disponibles

### Google Calendar Inspired (Recommandé)

- Variables de couleurs Google authentiques
- Gradients et animations
- Indicateur "aujourd'hui" jaune
- Effets de shimmer sur les créneaux

## 🏗️ Architecture

```
src/
├── Core/
│   ├── Plugin.php          # Orchestration principale
│   └── Logger.php          # Système de logs
├── Modules/
│   ├── Calendar/           # Module calendrier
│   │   ├── CalendarManager.php
│   │   ├── CalendarHandler.php
│   │   └── CalendarValidator.php
│   └── Admin/              # Module administration
│       ├── AdminManager.php
│       ├── AdminRenderer.php
│       └── AdminAjaxHandler.php
└── Helpers/                # Utilitaires
```

## ⚙️ Configuration

Le plugin se configure via **WooCommerce > Calendar Customizer** :

- **Style** : Sélection du style de calendrier
- **Paramètres avancés** : Options de personnalisation

## 🔧 Développement

### Règles de développement

- **Responsabilité unique** : Chaque classe a une seule responsabilité
- **Max 250 lignes** par classe
- **Max 50 lignes** par méthode
- **Architecture modulaire** obligatoire

### Structure des modules

Chaque module contient :

- **Manager** : Orchestration et hooks
- **Handler** : Logique métier
- **Validator** : Validation des données

## 📱 Responsive

Le plugin est optimisé pour :

- **Desktop** : Expérience complète
- **Tablet** : Interface adaptée
- **Mobile** : Interactions tactiles améliorées

## 🚀 Performance

- **Assets minifiés** en production
- **Chargement conditionnel** (uniquement sur les pages produit/panier/checkout)
- **Désactivation des styles natifs** pour éviter les conflits

## 🔍 Débogage

Les logs sont disponibles via WooCommerce > État > Journaux (source: `wc-bookings-customizer`)

## 📞 Support

- **Auteur** : TB-Web
- **Version** : 1.0.0
- **Licence** : GPL v2 or later

## 🔄 Mises à jour

Le plugin respecte les standards WordPress pour les mises à jour automatiques et la compatibilité avec les futures versions de WooCommerce Bookings.

---

**Développé par TB-Web avec ❤️ pour une meilleure expérience de réservation**
