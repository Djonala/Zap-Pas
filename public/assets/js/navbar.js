
    // fonction en javascript me permettant d'afficher au clique le formulaire de retard
    function afficheFormRetard(element){
    //si l'élèment est coché alors
        if (element.checked) {
            //j'affiche l'élment
            document.getElementById('form-retard').style.display = 'block';
        } else {
            // sinon non
            document.getElementById('form-retard').style.display = 'none';

        }
    }
    // fonction en javascripte me permettant au clique d'afficher le formulaire d'abence
    function afficheFormAbsence(element) {
    // si l'élèment est coché alors
        if (element.checked) {
            //j'affiche l'élèment
            document.getElementById('form-absence').style.display = 'block';
        } else {
            // sinon non
            document.getElementById('form-absence').style.display = 'none';
        }
    }

