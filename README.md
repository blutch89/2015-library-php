# Description
Petit projet "carte de visite" de gestion de livres développé avec Symfony2 avec un design minimaliste.

# Installation
1. Copier les fichiers sur un serveur PHP
2. Créer une base de données
3. Relier l'application SF2 à la base de données en modifiant le fichier "app/config/parameters.yml"
4. Créer les tables en exécutant cette commande : "php app/console doctrine:schema:update --force"
5. Remplir les tables en exécutant cette commande (fixtures) : "php app/console doctrine:fixtures:load"
6. Tester l'application en se rendant sur "http://nom_serveur/web/fr/"
