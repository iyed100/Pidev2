Projet de Gestion de Réservations et d'Assurances

Description

Ce projet est une application web développée avec Symfony pour la gestion de réservations (hôtels, espaces de coworking, transports) et d'assurances associées. L'application permet aux utilisateurs de créer, modifier, payer, et supprimer des réservations, ainsi que de souscrire à des assurances liées à ces réservations. Les administrateurs ont des privilèges étendus pour gérer toutes les données.

Fonctionnalités principales

Gestion des Réservations





Création de réservations pour des hôtels, espaces de coworking, ou transports.



Paiement sécurisé via Stripe pour les réservations.



Filtrage et pagination des réservations par type de service et statut.



Exportation des réservations au format PDF avec Dompdf.



Envoi d'emails de confirmation après la création d'une réservation via Symfony Mailer.

Gestion des Assurances





Création et association d'assurances à des réservations existantes.



Filtrage, tri, et recherche des assurances par type, statut, montant, et date d'expiration.



Exportation des assurances au format PDF.



Envoi d'emails de confirmation pour les nouvelles assurances.

Interface Utilisateur





Interface responsive avec Bootstrap et styles personnalisés.



Tableaux dynamiques pour l'affichage des données avec pagination et tri.



Badges colorés pour indiquer les statuts et types de services/assurances.



Gestion des erreurs et validation des formulaires avec Symfony Validator.

Sécurité





Authentification et gestion des sessions avec vérification des rôles (admin/utilisateur).



Protection CSRF pour les formulaires de suppression.



Restrictions d'accès basées sur les rôles et la propriété des données.

Prérequis





PHP >= 8.0



Composer



Symfony CLI (optionnel, pour le serveur local)



MySQL ou autre base de données compatible avec Doctrine



Compte Stripe pour les paiements



Compte SMTP (ex. Gmail) pour l'envoi d'emails



Node.js et npm (pour la gestion des assets front-end)
