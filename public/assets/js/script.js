

window.onload = () => {
    let elementCalendrier = document.getElementById("calendrier")
    // on recupere le calendrierZimbra
    let evenements = [{
        title: "cours HTML",
        start:"2020-02-15 09:00:00",
        end : "2020-02-15 12:00:00",
        extendedProps: {
            departement:"44",
        },
        "description": "essai"
    }]


    // let xmlhttp = new XMLHttpRequest();
    // xmlhttp.onreadystatechange = function() {
    //     if (this.readyState === 4 && this.status === 200) {
    //         let evenement = JSON.parse(xmlhttp.responseText);
    //
    //     }
    // };
    // xmlhttp.open("GET", "https://webmail.ec-nantes.fr/home/mailys.veguer@ec-nantes.fr/Web%20in-pulse%20%231.json", true);
    // xmlhttp.send();



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
    })

    calendrierZap_pas.render()
}