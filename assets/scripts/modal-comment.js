export default class ModalComment {
    constructor(btnComment){
        this.btnComment = btnComment;

        if(this.btnComment){
            this.init();
        }
    }

    init(){
        this.btnComment.map(element => {
            element.addEventListener('click', this.openModal)
        }) 
    }

    openModal() {
        //Ouvrir la fenêtre modale des commentaires
        const modal = document.querySelector(".modal-comment-" + this.dataset.idpost);  //On récupère le div lié au post idPost pour la modale
        const modalComponent = modal.querySelector(".modal");   //Fenêtre modale 
        modalComponent.style.display = "block";

        //Fermer la fenêtre modale des commentaires
        const btnCloseModal = document.querySelector(".close-modal");
        btnCloseModal.addEventListener("click", function(){
            modalComponent.style.display = "none";
        });

        //Fermer la fenêtre en click autour
        window.addEventListener('click', function(e){
            if (e.target == modalComponent){
                modalComponent.style.display = "none";
            }
        })
    }
}