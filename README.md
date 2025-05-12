Projet de Gestion de Réservations et d'Assurances 🏨✈️

📋 Description

Ce projet est une application web développée avec Symfony pour gérer des réservations (hôtels, espaces de coworking, transports) et des assurances associées. Elle permet aux utilisateurs de créer, modifier, payer et supprimer des réservations, ainsi que de souscrire à des assurances. Les administrateurs ont des privilèges étendus pour gérer l'ensemble des données.

🚀 Fonctionnalités principales

Gestion des Réservations





Création de réservations pour hôtels, coworking ou transports 🛏️💼🚗



Paiement sécurisé via Stripe 💳



Filtrage et pagination des réservations par type de service et statut 🔍



Exportation des réservations en PDF avec Dompdf 📄



Envoi d'emails de confirmation après création 📧

Gestion des Assurances





Création et association d'assurances à des réservations 🛡️



Filtrage, tri et recherche par type, statut, montant et date d'expiration 📊



Exportation des assurances en PDF 📄



Envoi d'emails de confirmation pour les nouvelles assurances 📧

Interface Utilisateur





Interface responsive avec Bootstrap et styles personnalisés 📱



Tableaux dynamiques avec pagination et tri 📈



Badges colorés pour indiquer statuts et types de services/assurances 🎨



Gestion des erreurs et validation des formulaires avec Symfony Validator ✅

Sécurité





Authentification et gestion des sessions avec vérification des rôles 🔐



Protection CSRF pour les formulaires de suppression 🛡️



Restrictions d'accès basées sur les rôles et la propriété des données 🚫

🛠️ Prérequis





PHP >= 8.0 🐘



Composer 📦



Symfony CLI (optionnel, pour le serveur local) 🖥️



MySQL ou autre base de données compatible avec Doctrine 🗄️



Compte Stripe pour les paiements 💳



Compte SMTP (ex. Gmail) pour l'envoi d'emails 📧



Node.js et npm pour les assets front-end 🌐

📦 Installation





Cloner le projet 📥

git clone <url-du-depot>
cd <nom-du-projet>



Installer les dépendances PHP 🛠️

composer install



Installer les dépendances front-end 🌐

npm install
npm run build



Configurer l'environnement ⚙️





Copier le fichier .env en .env.local :

cp .env .env.local



Modifier .env.local pour configurer :





Connexion à la base de données (DATABASE_URL) 🗄️



Clé API Stripe (STRIPE_SECRET_KEY) 💳



Paramètres SMTP (MAILER_DSN) 📧



Créer la base de données et exécuter les migrations 🗃️

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate



Démarrer le serveur 🚀

symfony server:start

Ou, sans Symfony CLI :

php -S 127.0.0.1:8000 -t public

🖥️ Utilisation





Accéder à l'application 🌐





Ouvrez http://127.0.0.1:8000 dans votre navigateur.



Connectez-vous avec un compte utilisateur ou admin 🔑



Créer une réservation 🛏️





Allez dans "Réservations" et cliquez sur "Nouveau".



Sélectionnez un hôtel, coworking ou transport via URL (ex. ?idhotel=1).



Remplissez et soumettez le formulaire 📝



Payer une réservation 💳





Depuis la liste des réservations, cliquez sur "Payer".



Suivez le processus de paiement via Stripe.



Créer une assurance 🛡️





Allez dans "Assurances" et cliquez sur "Nouveau".



Associez une réservation via URL (ex. ?reservation_id=1).



Remplissez et soumettez les détails.



Exporter des données 📄





Cliquez sur "Exporter en PDF" dans les sections Réservations ou Assurances.

📂 Structure du Projet





src/Controller/ : Contrôleurs (ReservationController.php, AssuranceController.php) 🎮



src/Entity/ : Entités Doctrine (Reservation.php, Assurance.php) 🗄️



src/Form/ : Formulaires Symfony (ReservationType.php, AssuranceType.php) 📝



src/Repository/ : Repositories pour requêtes personnalisées 🔍



templates/ : Templates Twig (reservation/, assurance/, admin/) 📄



public/ : Ressources statiques (CSS, JS, images) 🖼️

📚 Dépendances Principales





.Concurrent Framework : Gestion de l'application 🏗️



Doctrine ORM : Gestion de la base de données 🗄️



Stripe PHP : Intégration des paiements 💳



Dompdf : Génération de PDF 📄



Symfony Mailer : Envoi d'emails 📧



KnpPaginatorBundle : Pagination des résultats 📊



Bootstrap : Styles et composants front-end 🎨

⚠️ Problèmes Connus





Les emails peuvent échouer si les paramètres SMTP sont mal configurés 📧



Les images dans les emails (ex. logo) nécessitent un serveur accessible publiquement 🖼️



La clé API Stripe est codée en dur (à sécuriser via variables d'environnement) 🔐

🤝 Contribution





Forkez le projet 🍴



Créez une branche (git checkout -b feature/nouvelle-fonctionnalite) 🌿



Commitez vos changements (git commit -m "Ajout de XYZ") 📝



Poussez votre branche (git push origin feature/nouvelle-fonctionnalite) 🚀



Créez une Pull Request 📬

👤 Auteurs





[Votre Nom] - Développeur principal 🧑‍💻



Contacter : [votre.email@example.com] 📧

📜 Licence

Ce projet est sous licence MIT - voir le fichier LICENSE pour plus de détails 📜
