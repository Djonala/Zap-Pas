# Projet WinWip - application Zap'Pas  

Dans le cadre de la formation WebInPulse de l'école Centrale Nantes, il nous a été demandé de 
créer un agenda pour la formation continue.
Nous remercions Maîlys Veguer (porteuse du projet et cliente), Vincent Graillot pour l'appui technique
Et Centrale Nantes.
 

##Pré-requis : 
- Installer PHP :  https://doc.ubuntu-fr.org/php

        $ sudo apt-get install php7

- Installer Apache : https://doc.ubuntu-fr.org/apache2
- Installation de Symfony :https://symfony.com/doc/current/setup.html
- Installation de PostgreSQL : (ou un autre SGBD)
https://doc.ubuntu-fr.org/postgresql

        $ sudo apt-get install postgres

- Installer GIT
https://doc.ubuntu-fr.org/git

        $ sudo apt-get install git

- Installation de composer : 
Placez vous à la racine du projet puis suivez la documentation suivante : 
https://getcomposer.org/download/

        $ sudo apt-get install composer

- Installer Symfony CLI 
https://symfony.com/download

        $ composer require symfony/console

##Installation 

1. Preparez votre workspace :  

        mkdir workspaceWinWip

2. Placez vous dans le dossier

        cd workspaceWinWip

3. Pour récupérer le projet :  


### SSH : 
        git clone git@github.com:Djonala/Zap-Pas.git
### HTTPS : 
        git clone https://github.com/Djonala/Zap-Pas.git
        
4.Installer composer (placer vous dans votre dossier) et les dépendances
  - composer install
  - composer require tattali/calendar-bundle
  - composer require symfony/swiftmailer-bundle 

5.Dupliquer et renommer le fichier .env (nouveau nom : .env.local)
  
    
6.Modifier le fichier .env.local 
- copier la ligne : postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=11&charset=utf8
- coller juste après : DATABASE_URL= (en remplaçant la ligne : mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7)
- remplaçer le paramètre 'db_user' par 'votre user de bdd'
- remplacer le paramètre 'db_password' par 'votre mot de passe' 
- remplacer le paramètre 'db_name' par le nom de votre base de donnée. Nom de votre choix. 

7.Initialiser la database

        symfony console doctrine:database:create
Et aplliquer les migrations grâce à 
            
        symfony console do:mi:mi
Forcer l’update  en réalisant les commandes suivantes : 

        symfony console doctrine:schema:update --dump-sql
        symfony console doctrine:schema:update --force
        
8.Base de données de démo
    
        symfony console do:fi:lo
- Vous pouez donc vous connecter avec :
centralenanteszappas@gmail.com et comme mot de passe : WIP2020

9.Pour lancer le projet, effectuer la commande
        
        symfony server:start
      
Et allez sur la page http://localhost:8000/

#Installation pour déploiement rapide avec Docker
1. Checkout du projet avec GIT

        $ cd 
        $ mkdir app
        $ cd app
        $ git clone https://github.com/Djonala/Zap-Pas.git

2. Installation de Docker

    https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository

3. Deploiement du projet

       $ cd ~/app/Zap-Pas/
       $ sudo docker-compose build
       $ sudo docker-compose up -d
       
4. Initialisation de la base de données 

        $ sudo docker-compose exec -u root php bin/console do:da:cr
        $ sudo docker-compose exec -u root php bin/console doctrine:schema:update --dump-sql
        $ sudo docker-compose exec -u root php bin/console doctrine:schema:update --force
        
5. Peuplement de la base de données de démo
                
        $ sudo docker-compose exec -u root php bin/console do:fi:lo
        
6. Accès au site

    http://localhost:8080/
    
    identifiant de connexion : 
    
    user -> centralenanteszappas@gmail.com
    
    mdp ->  WIP2020
