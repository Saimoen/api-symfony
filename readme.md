
# Installation

Initialiser les dépendances du projet :

```javascript
composer install
```

Migrer la base de données :

```javascript
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

Lancer le projet en local :
```javascript
symfony:server:start
```

Repository GitHub :
https://github.com/Saimoen/api-symfony
