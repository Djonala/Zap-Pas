let dataUrl = document.querySelector('.js-url');
let url = dataUrl.dataset.url;

// Au lancement de la page
window.onload = () => {
    // on recupère l'élement donc l'id est calendrier
    let elementCalendrier = document.getElementById("calendrier");
    // on instancie un objet de type XMLHttpRequest
    let xmlhttp = new XMLHttpRequest();
    // S'il y a un changement de statut de la page
    xmlhttp.onreadystatechange = () => {
        // si l'opération d'exctation est terminée
        if(xmlhttp.readyState === 4){
            // si le code d'etat http est égale à 200 soit qu'il s'agit d'une réponse réussi
            if(xmlhttp.status === 200) {
                let calendrierZap_pas = new FullCalendar.Calendar(elementCalendrier, {
                    // on appelle  les navbar
                    plugins : ['dayGrid', 'timeGrid', 'list'],
                    height : 'auto',
                    defaultView: 'timeGridWeek',
                    locale: 'fr',
                    header: {
                        left : 'prev today next',
                        center: 'title',
                        right:'dayGridMonth, timeGridWeek, list',
                    },

                    // on passe en francais les bouttons
                    buttonText:{
                        today:"Aujourd'hui",
                        month:"Mois",
                        week:'Semaine',
                        list:"Liste des cours"
                    },
                    // on affiche les evenements
                    events : {
                        url: url,
                    },
                    eventRender: function(event, element, view) {

                    },

                    // on ajoute le type de rendu voulu
                    eventColor: '#10385f',
                    eventTextColor: '#dba005',

                    // on ajoute la vue de l'heure qu'il est sur la vue semaine
                    nowIndicator: true,
                });

                calendrierZap_pas.render()
            }
        }
    };
    // instancie une nouvelle requête en méthode get sur l'url donnée
    xmlhttp.open('get',url,true);
    //findreplace(à voir) gulp, grunt, parcel

    // on envoi la requête au serveur
    xmlhttp.send(null)

};
