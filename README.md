# Roarr — Application de gestion bancaire prévisionnelle

Roarr est une application web de gestion financière personnelle permettant de suivre ses comptes bancaires, catégoriser ses transactions et projeter ses soldes futurs sur plusieurs mois.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Langage | PHP 8.2 |
| Serveur | Apache 2.4 (`mod_rewrite`) |
| Base de données | PostgreSQL 15 |
| Paiement | Stripe PHP SDK `^20.1` |
| Mails | PHPMailer `^7.0` |
| Infrastructure | Docker + Docker Compose |
| Frontend | CSS natif (variables CSS, dark mode, responsive) |
| JavaScript | Vanilla JS inline (aucun framework) |

---

## Architecture MVC

Le projet implémente un framework MVC **from scratch**, sans dépendance externe à un framework PHP.

```
Requête HTTP
    │
    ▼
.htaccess  →  index.php  →  routes.yml
                │
                ├── Vérification de la méthode HTTP
                ├── Guard d'accès (guest / auth)
                │
                ▼
          Controller::action()
                │
                ├── Repository  →  Core/Database (PDO singleton)
                ├── Model       →  Entités PHP typées
                │
                ▼
          Core/Render::render()
                │
                ▼
          Views/Templates/  +  Views/*.php
```

### Routeur

Le fichier `www/routes.yml` définit les 39 routes de l'application. L'extension PECL `yaml` parse ce fichier à chaque requête dans `index.php`.

Format d'une route :

```yaml
/uri:
  controller: NomDuController
  action: nomDeLaMethode
  method: GET       # ou POST
  access: auth      # auth | guest (optionnel)
```

Les guards `access: auth` redirigent les non-connectés vers `/`. Les guards `access: guest` redirigent les utilisateurs connectés vers `/accounts`.

### Controller de base (`Controllers/Base.php`)

Classe abstraite dont héritent tous les controllers ET tous les repositories. Elle fournit :

- **Connexion DB** : instanciation du singleton `Core/Database`
- **Méthodes CRUD génériques** : `dbInsert`, `dbFindAll`, `dbFindBy`, `dbFindById`, `dbUpdate`, `dbUpdateBy`, `dbDelete` — toutes en prepared statements PDO
- **Whitelist de colonnes** : `$validColumns` + `validateColumns()` pour prévenir les injections de noms de colonnes
- **Rendu** : `renderPage(string $view, string $template, array $data)` qui instancie `Core/Render`
- **Session** : `setSessionData()`, `isAuth()`, `getCurrentUserId()`

### Repositories

Héritent de `Base.php` pour accéder aux helpers DB. Chaque repository est spécialisé sur une table :

| Repository | Table SQL | Clé primaire |
|---|---|---|
| `UserRepository` | `"user"` | `integer IDENTITY` |
| `BankAccountRepository` | `account` | `integer IDENTITY` |
| `SubscriptionRepository` | `subscription` | `integer IDENTITY` |
| `TransactionRepository` | `"transaction"` | `UUID` |
| `EmailVerificationRepository` | `email_verification` | `user_id integer` |

> Les noms `user` et `transaction` sont des mots réservés PostgreSQL — ils sont systématiquement encadrés de guillemets dans les requêtes.

### Modèles (`Models/`)

Objets valeur PHP sans ORM. Chaque modèle expose des getters/setters sur ses propriétés typées. Ils servent uniquement à structurer les données avant insertion.

| Modèle | Champs notables |
|---|---|
| `User` | `id, name, last_name, email, password, is_active, is_admin` |
| `Account` | `id, user_id, short_name, description, solde, annual_interest_rate, tax_rate` |
| `Transaction` | `id (UUID), account_id, type, short_name, category, frequency, interval_months, amount, start_date, end_date` |
| `Subscription` | `id, user_id, type (FREE/PLUS/PRO), stripe_customer_id, stripe_subscription_id, start_date, expiration_date` |
| `EmailVerification` | `user_id, token` |

### Services actifs (`Services/`)

Seul `StripeService.php` est utilisé en production. Il encapsule le SDK Stripe :

- `createCheckoutSession(priceId, userId, email, plan)` — crée une session Stripe Checkout en mode `subscription`
- `retrieveSession(sessionId)` — récupère une session après paiement pour vérifier `payment_status`
- `cancelSubscription(subscriptionId)` — annule un abonnement avec `cancel_at_period_end: true`

> `AuthService.php` et `EmailVerificationService.php` sont des couches historiques (raw SQL) qui ont été remplacées par le pattern Repository. Elles ne sont plus appelées par les controllers.

### Rendu (`Core/Render.php`)

Prend en entrée un nom de vue et un nom de template. Utilise `extract($data)` pour exposer les variables PHP dans la vue, puis inclut le template qui lui-même inclut la vue via `$this->pathView`.

Deux templates disponibles :
- `Templates/headerFooter.php` — layout complet avec header sticky, navigation, dark mode, burger menu
- `Templates/noHeader.php` — layout nu pour les pages d'authentification (login, signup, reset password)

---

## Structure du projet

```
ProjetAnnuel3IW2/
├── Dockerfile                    # Image PHP 8.2 + Apache
├── docker-compose.yml            # 4 services : web, db, pgadmin, mailpit
├── php.ini                       # Config PHP (memory, upload, errors)
├── .env                          # Variables d'environnement (non versionné)
├── Table/
│   └── schema.sql                # Schéma complet PostgreSQL
└── www/
    ├── .htaccess                 # Réécriture vers index.php
    ├── index.php                 # Point d'entrée + routeur
    ├── routes.yml                # Définition des 39 routes
    ├── composer.json
    ├── Controllers/
    │   ├── Base.php
    │   ├── Auth.php
    │   ├── BankAccount.php
    │   ├── EmailVerification.php
    │   ├── Subscription.php
    │   └── Transaction.php
    ├── Core/
    │   ├── Database.php          # Singleton PDO
    │   └── Render.php            # Moteur de templates
    ├── Models/
    │   ├── User.php
    │   ├── Account.php
    │   ├── Transaction.php
    │   ├── Subscription.php
    │   └── EmailVerification.php
    ├── Repository/
    │   ├── UserRepository.php
    │   ├── BankAccountRepository.php
    │   ├── SubscriptionRepository.php
    │   ├── TransactionRepository.php
    │   └── EmailVerificationRepository.php
    ├── Services/
    │   ├── StripeService.php     # Actif
    │   ├── BaseService.php
    │   ├── AuthService.php       # Legacy (non utilisé)
    │   └── EmailVerificationService.php  # Legacy (non utilisé)
    ├── Views/
    │   ├── Templates/
    │   │   ├── headerFooter.php
    │   │   └── noHeader.php
    │   ├── home.php
    │   ├── login.php
    │   ├── signup.php
    │   ├── accounts.php
    │   ├── accountDetails.php
    │   ├── manageAccounts.php
    │   ├── userProfil.php
    │   ├── abonnement.php
    │   ├── transactions.php
    │   ├── transactionSummary.php
    │   ├── resetPassword.php
    │   ├── modifyPassword.php
    │   ├── allUser.php
    │   └── 404.php
    └── Public/
        └── css/
            └── stylefo.css       # Feuille de style unique (~3 600 lignes)
```

---

## Base de données

### ENUMs PostgreSQL

```sql
CREATE TYPE frequency_type    AS ENUM ('ONE_TIME', 'RECURRING');
CREATE TYPE subscription_type AS ENUM ('FREE', 'PLUS', 'PRO');
CREATE TYPE transaction_type  AS ENUM ('expense', 'income');
```

### Tables

#### `"user"`
```sql
id          SERIAL PRIMARY KEY
name        VARCHAR(255)
last_name   VARCHAR(255)
email       VARCHAR(320) NOT NULL UNIQUE
password    TEXT NOT NULL
is_active   BOOLEAN DEFAULT FALSE
is_admin    BOOLEAN DEFAULT FALSE
created_at  TIMESTAMP DEFAULT NOW()
```

#### `email_verification`
```sql
user_id     INTEGER PRIMARY KEY REFERENCES "user"(id) ON DELETE CASCADE
token       VARCHAR(255) NOT NULL
created_at  TIMESTAMP
```

#### `subscription`
```sql
id                    SERIAL PRIMARY KEY
user_id               INTEGER NOT NULL REFERENCES "user"(id) ON DELETE CASCADE
type                  subscription_type DEFAULT 'FREE'
stripe_customer_id    TEXT
stripe_subscription_id TEXT
start_date            TIMESTAMP
expiration_date       TIMESTAMP
created_at            TIMESTAMP DEFAULT NOW()
```

#### `account`
```sql
id                   SERIAL PRIMARY KEY
user_id              INTEGER NOT NULL REFERENCES "user"(id) ON DELETE CASCADE
short_name           VARCHAR(100) NOT NULL
description          TEXT
creation_date        DATE NOT NULL
annual_interest_rate NUMERIC(5,2) DEFAULT 0
tax_rate             NUMERIC(5,2) DEFAULT 0
solde                DECIMAL(15,2) DEFAULT 0.00
registered_at        TIMESTAMP DEFAULT NOW()
```

#### `"transaction"`
```sql
id              UUID DEFAULT uuid_generate_v4() PRIMARY KEY
account_id      INTEGER NOT NULL REFERENCES account(id) ON DELETE CASCADE
type            transaction_type NOT NULL
short_name      VARCHAR(100) NOT NULL
description     TEXT
category        VARCHAR(50)
frequency       frequency_type NOT NULL
interval_months INTEGER
amount          NUMERIC(12,2) NOT NULL CHECK (amount >= 0)
start_date      DATE NOT NULL
end_date        DATE
created_at      TIMESTAMP DEFAULT NOW()
```

> Requiert l'extension `uuid-ossp` : `CREATE EXTENSION IF NOT EXISTS "uuid-ossp";`

---

## Variables d'environnement

Le fichier `.env` à la racine est lu par Docker Compose et injecté dans le container `web` :

```env
POSTGRES_USER=
POSTGRES_PASSWORD=
POSTGRES_DB=
POSTGRES_PORT=5432

PGADMIN_DEFAULT_EMAIL=
PGADMIN_DEFAULT_PASSWORD=

STRIPE_PUBLISHABLE_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...
STRIPE_PRICE_PLUS=price_...
STRIPE_PRICE_PRO=price_...
```

Les clés Stripe sont lues dans le code via `getenv('STRIPE_SECRET_KEY')` — elles ne sont jamais écrites en dur dans le code source.

---

## Installation et démarrage

### Prérequis

- Docker Desktop
- Docker Compose v2

### Lancer l'environnement

```bash
# Copier et remplir le fichier d'environnement
cp .env.example .env

# Construire et démarrer les conteneurs
docker compose up -d --build

# Installer les dépendances PHP
docker exec -it php-apache composer install

# Créer le schéma de base de données
# Via pgAdmin à l'adresse http://localhost:1003
# ou en exécutant Table/schema.sql dans le container db
```

### Accès aux services

| Service | URL |
|---|---|
| Application | `http://localhost:1001` |
| pgAdmin | `http://localhost:1003` |
| Mailpit (interface mails) | `http://localhost:1004` |
| PostgreSQL (direct) | `localhost:1002` |

---

## Routes

### Authentification et profil

| Méthode | URI | Action | Accès |
|---|---|---|---|
| GET | `/` | Page d'accueil | guest |
| GET | `/signup` | Formulaire inscription | guest |
| POST | `/addUser` | Créer un compte | guest |
| GET | `/login` | Formulaire connexion | guest |
| POST | `/signinUser` | Connexion | guest |
| POST | `/logout` | Déconnexion | auth |
| GET | `/profil` | Page profil | auth |
| POST | `/updateUser` | Modifier profil | auth |
| POST | `/deleteUser` | Supprimer compte | auth |
| GET | `/resetPassword` | Formulaire reset MDP | — |
| POST | `/sendNewPassword` | Envoyer email reset | — |
| GET | `/modifyPassword` | Formulaire nouveau MDP | — |
| POST | `/updatePassword` | Sauvegarder nouveau MDP | — |
| GET | `/activation` | Activer le compte (lien email) | — |

### Comptes bancaires

| Méthode | URI | Action | Accès |
|---|---|---|---|
| GET | `/accounts` | Liste des comptes | auth |
| GET | `/manageAccounts` | Gérer / créer un compte | auth |
| POST | `/createBankAccount` | Créer un compte | auth |
| GET | `/accountDetails` | Détail d'un compte + transactions | auth |

### Transactions

| Méthode | URI | Action | Accès |
|---|---|---|---|
| GET | `/transactions` | Liste des transactions | auth |
| POST | `/transactions/create` | Créer une transaction | auth |
| GET | `/transactions/edit` | Formulaire édition | auth |
| POST | `/transactions/update` | Mettre à jour | auth |
| POST | `/transactions/delete` | Supprimer | auth |
| GET | `/transactions/summary` | Résumé par période | auth |

### Abonnements

| Méthode | URI | Action | Accès |
|---|---|---|---|
| GET | `/abonnement` | Page des plans | — |
| POST | `/subscribe` | Initier un paiement Stripe | auth |
| GET | `/subscribeSuccess` | Callback Stripe (succès) | auth |
| POST | `/unsubscribe` | Annuler l'abonnement | auth |

---

## Flux principaux

### Inscription et activation

1. L'utilisateur soumet le formulaire `/addUser`
2. `Auth::signup()` valide les données, hash le mot de passe (`PASSWORD_DEFAULT`), crée un `User` et un `Subscription(FREE)` via les repositories
3. `EmailVerification::sendVerificationMail()` génère un token, l'enregistre en base et envoie un mail via PHPMailer (SMTP : Mailpit en dev, serveur réel en prod)
4. L'utilisateur clique sur le lien d'activation → `/activation?email=...&token=...`
5. `EmailVerification::activateAccount()` vérifie le token et passe `is_active = true` sur l'utilisateur

### Connexion

1. `Auth::signin()` vérifie l'email, `password_verify()` sur le hash, contrôle `is_active`
2. Si l'abonnement `expiration_date` est dépassée, le type est rétrogradé à `FREE` en base
3. Les données utilisateur et son type d'abonnement sont stockés en `$_SESSION`

### Paiement Stripe

1. `Subscription::subscribe()` appelle `StripeService::createCheckoutSession()` avec le `priceId` du plan choisi
2. L'utilisateur est redirigé vers Stripe Checkout
3. Après paiement, Stripe redirige vers `/subscribeSuccess?session_id=...`
4. `Subscription::subscribeSuccess()` récupère la session, vérifie `payment_status === 'paid'`, met à jour la subscription en base avec `start_date = NOW()` et `expiration_date = NOW() + 1 mois`

### Transactions et solde

À chaque création ou suppression de transaction, `BankAccountRepository::adjustSolde()` met à jour le solde du compte concerné avec un `SET solde = solde + :delta` — les entrées ajoutent, les sorties soustraient.

---

## Frontend

### Feuille de style unique

Tout le CSS est centralisé dans `www/Public/css/stylefo.css`. Aucun framework CSS n'est utilisé.

Système de design en variables CSS :

```css
:root {
  --ivory:       #f5f1e8;
  --cream:       #faf6ec;
  --white-soft:  #fefcf7;
  --ink:         #1a1a17;
  --forest:      #05482f;
  --amber:       #d35220;
  --gold:        #d4a541;
  --plum:        #4a2438;

  --display: "Fraunces", serif;      /* Titres */
  --sans:    "Geist", system-ui;     /* Corps */
  --mono:    "Geist Mono", monospace;/* Labels, badges */

  --text-size-h1: 5.5rem;
  --text-size-m:  1.1rem;
  --text-size-s:  0.78rem;
}
```

### Dark mode

Activé via l'attribut `data-theme="dark"` sur `<html>`. Le bloc `[data-theme="dark"]` redéfinit les variables neutres (ivory, cream, white-soft, ink) en leurs équivalents sombres. Les couleurs de marque (forest, amber, gold, plum) restent inchangées.

La préférence est persistée dans `localStorage` et appliquée immédiatement au chargement via un script inline dans `<head>` (avant le CSS) pour éviter le flash.

### Responsive

Trois breakpoints principaux :

| Breakpoint | Comportement |
|---|---|
| `≤ 1150px` | Padding de page réduit (`0 2.5%`) |
| `≤ 1100px` | Header passe en burger menu (masquage nav desktop) |
| `≤ 1000px` | Grilles réduites, titres plus petits |
| `≤ 600px` | Mise en page colonne, table transactions simplifiée (2 colonnes au lieu de 4) |

---

## Dépendances PHP

```json
{
  "require": {
    "phpmailer/phpmailer": "^7.0",
    "stripe/stripe-php": "^20.1"
  }
}
```

L'autoloading est géré manuellement via `spl_autoload_register` dans `index.php` — aucune configuration PSR-4 dans `composer.json`.

---

## Infrastructure Docker

```yaml
services:
  web:       # PHP 8.2 + Apache — port 1001
  db:        # PostgreSQL 15 — port 1002
  pgadmin:   # pgAdmin 4 — port 1003
  mailpit:   # Intercepteur SMTP — ports 1004 (UI) et 1025 (SMTP)
```

Le container `web` monte `./www` vers `/var/www/html`. Le fichier `.env` est monté en lecture seule dans le container. Les extensions PHP requises (`pdo_pgsql`, `yaml`) sont installées dans le `Dockerfile`.
