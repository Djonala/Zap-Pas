

window.onload = () => {
    let elementCalendrier = document.getElementById("calendrier")

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

        // on ajoute la vue de l'heure qu'il est sur la vue semaine
        nowIndicator: true,
    })

    calendrierZap_pas.render()
}