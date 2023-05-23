export default class ShowRecurrenceCreneau {
    constructor(type_creneau){
        this.type_creneau = type_creneau

        if(this.type_creneau){
            this.init()
        }
    }

    init(){
        //Recupération du champ de recurrence de créneau
        const recurrence = document.querySelector("#rec_creneau");

        //Dès le chargment de la page on vérifie s'il faut l'afficher 
        this.type_creneau.value == "Jour" ? recurrence.style.display = 'none' : recurrence.style.display = 'block';

        //Changement dynamique du formulaire
        this.type_creneau.addEventListener('change', ()=> {
            if (this.type_creneau.value == 'Semaine') {
                const recurrence = document.querySelector("#rec_creneau");
                recurrence.style.display = 'block';
            }
            else {
                const recurrence = document.querySelector("#rec_creneau");
                recurrence.style.display = 'none';
            }
        })
    }
}