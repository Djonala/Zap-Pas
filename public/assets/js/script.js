let dataUrl = document.querySelector('.js-url');
let url = dataUrl.dataset.url;

window.onload = () => {
    let elementCalendrier = document.getElementById("calendrier");
    // on recupere le calendrierZimbra
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = () => {
        if(xmlhttp.readyState === 4){
            if(xmlhttp.status === 200) {
                let evenements = JSON.parse(xmlhttp.responseText);
                // on s'instancie le calendrier
                let calendrierZap_pas = new FullCalendar.Calendar(elementCalendrier, {
                    // on appelle  les composants
                    plugins : ['dayGrid', 'timeGrid', 'list'],
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
                    events : evenements,
                    // on ajoute la vue de l'heure qu'il est sur la vue semaine
                    nowIndicator: true,
                });

                calendrierZap_pas.render()

            }
        }
    };

    xmlhttp.open('get',url,true);
    //findreplace(à voir) gulp, grunt, parcel 
    xmlhttp.send(null)

};
