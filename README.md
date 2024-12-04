Ce projet est une application Symfony permettant de gérer des tâches avec une api rest

## Fonctionnalités
- **Créer une tâche** : `POST /tasks`
- **Modifier une tâche** : `PUT /tasks/{id}`
- **Supprimer une tâche** : `DELETE /tasks/{id}`


## Instructions pour lancer l'application

Avoir :
PHP : Version 8.1 ou supérieure
PostgreSQL : Base de données utilisée

Puis :
1)Clonez le dépôt 

2)Installez les dépendances 

3)Configurez la connexion  à la base de données dans le fichier .env 
DATABASE_URL="postgresql://[utilisateur]:[motdepasse]@127.0.0.1:5432/[nom_base]"

4)Créez et migrez la base de données 
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

5)Lancez le serveur local 


## Explication choix techniques:

Base de données : PostgreSQL a été choisi pour sa fiabilité et ses fonctionnalités avancées

Gestion d'erreur : les erreurs (ex : 404 Task not found, 400 Invalid data ) sont renvoyées sous forme de éponses JSONstandardisées 

Resqête : test des resquêtes avec l'extension thunder client, simplifie le processus de débogage et de validation des fonctionnalités de l'API directement sur visual studio code.

validation donnée : Symfony Validator est utilisé pour garantir que les données fournies par les utilisateurs respectent les exigences.
