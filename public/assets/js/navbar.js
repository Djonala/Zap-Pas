
    function afficheFormRetard(element){
        if (element.checked) {
            document.getElementById('form-retard').style.display = 'block';
        } else {
            document.getElementById('form-retard').style.display = 'none';

        }
    }

    function afficheFormAbsence(element) {
        if (element.checked) {
            document.getElementById('form-absence').style.display = 'block';
        } else {
            document.getElementById('form-absence').style.display = 'none';
        }
    }

