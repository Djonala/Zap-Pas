git # Projet WinWip - application Zap'Pas 
***

## Avant de modifier le projet (!) CHAQUE ETAPE EST ESSENTIELLE

###1. Placez vous ou assurez vous que vous êtes sur develop : 

pour vous en assurer : 

        git status
pour vous déplacer : 
        
        git checkout develop

###2. Recupérer le projet sur git  :  

        git pull

###3. Mettez à jour votre base de donnée : 

        symfony console do:mi:mi

###4. Creez votre branche :

        git checkout -b nom_de_votre_branche_votrePrenom 

Vous pouvez commencer à faire vos modifications
 
***

## Avant de pusher vos modifs du projet :  (!) CHAQUE ETAPE EST ESSENTIELLE

###1. Creez votre fichier de migration :

        symfony console create-migration
        
###2. Staged vos modifications : 

        git add <vos fichier> 
ou si vous êtes sûre de vos modifications : 
        
        git add *

###3. Commit vos fichiers : 

        git commit -m "La synthèse de vos modifs"

###4. Pusher vos fichier  : 

        git push
        
###5. Créez une pull request : 

Allez sur le projet sur Github, vous verrez s'afficher votre push, créez la pull request. 
Surtout ne mergez pas !!! On fera ça en groupe le lundi. 

###6. Une fois mergée : 
Pensez à supprimer la branche en local : 

Placez vous sur develop : 

        git checkout develop
        
Si vous ne vous souvenez plus du nom de votre branche : 

        git branch
        
Supprimer la branche :

        git branche -d nom_de_votre_branche
        
(si vous voulez supprimer une branche que vous n'avez pas pushée : 

        git branch -D nom_de_votre_branche
)
        