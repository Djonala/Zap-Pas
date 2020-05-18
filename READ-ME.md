# Projet WinWip - application Zap'Pas  

Dans le cadre de la formation WebInPulse de l'école Centrale Nantes, il nous a été demandé de 
créer un agenda pour la formation continue.
Nous remercions Maîlys Veguer (porteuse du projet et cliente), Vincent Graillot pour l'appui technique
Et Centrale Nantes.
## Installation  

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
        
8.Base de données de démo
    
        symfony console do:fi:lo
- Vous pouez donc vous connecter avec :
centralenanteszappas@gmail.com et comme mot de passe : WIP2020
