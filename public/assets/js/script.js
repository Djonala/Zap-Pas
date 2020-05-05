let dataUrl = document.querySelector('.js-url');
let url = dataUrl.dataset.url;

window.onload = () => {
    let elementCalendrier = document.getElementById("calendrier");
    // on recupere le calendrierZimbra
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = () => {
        if(xmlhttp.readyState === 4){
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
                        extraParams: {
                            custom_param1: 'something',
                            custom_param2: 'somethingelse'
                        }
                    },
                    eventRender: function(event, element, view) {

                    },

                    // on ajoute le type de rendu voulu
                    eventColor: '#dba005',
                    textColor: '#10385f',


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
