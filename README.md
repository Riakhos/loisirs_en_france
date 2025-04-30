# Loisirs en France

Bienvenue dans le projet **Loisirs en France**, une plateforme Symfony dédiée à la gestion et à la réservation d'activités, d'événements, et d'offres spéciales en France.

## 🛠️ Fonctionnalités

- **Gestion des utilisateurs** : Inscription, connexion, réinitialisation de mot de passe.
- **Réservation d'activités** : Ajout d'activités, d'offres exclusives et d'événements spéciaux au panier.
- **Système de notation** : Les utilisateurs peuvent noter et commenter les activités, événements et partenaires.
- **Administration** : Gestion des catégories, sous-catégories, activités, événements, offres et utilisateurs via un tableau de bord.
- **Pagination et filtres** : Recherche et tri des avis et activités.
- **Génération de PDF** : Création d'invitations personnalisées au format PDF.

## 📂 1. Structure du projet

Voici un aperçu de la structure principale du projet :

```bash
.env config/ migrations/ public/ src/ templates/ tests/ translations/
```

## 🚀 2. Installation

### 2.1. Clonez le dépôt

```bash
git clone https://github.com/votre-utilisateur/loisirs-en-france.git
cd loisirs-en-france
```

### 2.2.Installez les dépendances PHP et JavaScript

```bash
composer install
npm install
```

### 2.3. Configurez les variables d'environnement : Copiez le fichier .env et modifiez les valeurs nécessaires

```bash
cp .env .env.local
```

### 2.4. Créez la base de données et appliquez les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 2.5. Compilez les assets

```bash
npm run dev
```

### 2.6. Lancez le serveur Symfony

```bash
symfony server:start
```

Accédez à l'application via [http://localhost:8000](http://localhost:8000).

## 🧪 3. Tests

Pour exécuter les tests, utilisez la commande suivante :

```bash
cp .env .env.local
```

## 📜 4. Licence

Ce projet est sous licence MIT. Consultez le fichier LICENSE pour plus d'informations.

## 🤝 5. Contribuer

Les contributions sont les bienvenues ! Veuillez soumettre une pull request ou ouvrir une issue pour discuter des changements.

## 📧 6. Contact

Pour toute question ou suggestion, contactez-nous à [contact@loisirsenfrance.com](mailto:contact@loisirsenfrance.com).
