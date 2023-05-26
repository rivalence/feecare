# feecare

-Généralisation des màj des créneaux pour l'éducateur (CRUD sauf update).
    • Utilisation de javascript normal pour le dynamisme du formulaire (Webpack Encore Bundle)
    • Lancer webpack : npm run watch (pour le developpement).
-Prise de RDV (Ajouter et Supprimer).
-Envoi de notifications par mail lors de la prise et de la suppression d'un RDV. 
    • Utilisation du serveur SMTP de mailtrap.
    • Configurer le serveur : rajouter le mailer_dsn fourni par mailtrap pour l'intégration dans le .env du projet.
-Mise en place d'une map.
    • Utilisation de la librairie Leaflet pour gérer la map (affichage, contrôle, création d'itinéraire, marqueurs, etc.)
    • La map utilisée est celle d'openStreetMap.
    • Système de géolocalisation pour tracer l'itinéraure directement depuis sa position actuelle.

-Difficulté rencontrée : Faire des requêtes programmées (Envoyer une notification à une heure précise).
