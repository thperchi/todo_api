### Todolist App

Projet d'API de Todolist pour le test technique de Mon Petit Placement.

### Pré requis

* PHP 7.4
* Symfony CLI 4.26
* Composer
* Docker
* Docker-compose

### Lancement

* composer install
* docker-compose up -d
* symfony serve -d
* symfony console doctrine:database:create
* symfony console doctrine:migrations:migrate

### Connexion

* http://localhost:8000/api

### Fonctionnalitées

* Possibilité de créer un utilisateur
* Authentification via JWT
* Possibilité de créer une todolist et des taches associées
* Gestion de la todolist et des taches associcées par l'utilisateur parent seulement
* Possibilité de filtrer les todolistes par nom
* Possibilité de filtrer les taches validées ou non

### Librairies utilisées

* Api Platform pour la gestion de l'api
* lexik/LexikJWTAuthenticationBundle pour la gestion de l'authentification via JWT