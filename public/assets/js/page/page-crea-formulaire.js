/**
 Ajoute un <li> dans le <ul> d'id sId
 **/

function add_li(sId) {
    let oUl = document.getElementById(sId); // récupération de la liste
    let iLength = oUl.getElementsByTagName("li").length; // longueur de la liste (nombre d'items)
    let nom = document.getElementById("nomEleve").value;
    let prenom = document.getElementById("prenomEleve").value;

    let oLi = document.createElement("li"); // on cré un nouveau noeud item de liste
    let oText = document.createTextNode(nom + " "+ prenom); // on cré un noeud texte

    oLi.appendChild(oText); // on attache le noeud texte au noeud item de liste
    oUl.appendChild(oLi); // on attache le noeud item de liste au noeud liste

    return oLi;
}


/*********************Advanced Functions*********************/

var IE = !!(window.attachEvent && !window.opera);

/**
 ac(e1, e2):
 Ajoute en tant que noeud enfant à \o e1 le noeud \o e2

 ct(e, t):
 Crée un noeud texte \s t et l'ajoute en tant que noeud enfant de \o e

 ce(p):
 Crée un nouvel élément \s p
 **/

function ac(e1, e2) {
    e1.appendChild(e2);
    return e1;
};
function ct(e, t) {
    ac(e, document.createTextNode(t));
    return e;
};
function ce(p) {
    let elem = document.createElement(p);
    if(arguments.length > 1) {
        for(let i in arguments[1]) {
            elem.setAttribute(i, arguments[1][i]);
        }
    }
    return elem;
};

