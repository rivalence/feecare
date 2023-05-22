export default class Comment {
    constructor(commentElements){
        this.commentElements = commentElements;

        if(this.commentElements){
            this.init();
        }
    }

    init(){
        this.commentElements.map(element => {
            element.addEventListener('submit', this.onSubmit)
        })
    }

    onSubmit(event){
        event.preventDefault();
        
        fetch(this.action, {
            body: new FormData(event.target),
            method: 'POST'
        })
        .then(response => response.json())
        .then(json => {
            const modal = document.querySelector(".modal-comment-" + json.idPost);  //On récupère le div lié au post idPost pour la modale
            const newComment = modal.querySelector("#comment-content");
            const span = document.querySelector('span#new-comment-' + json.idPost);

            const p = newComment.appendChild(document.createElement("p"));
            p.innerHTML = json.auteur_name + ' ' + json.auteur_firstName + ' : ' + json.contenu;
            span.innerHTML = json.auteur_name + ' ' + json.auteur_firstName + ' : ' + json.contenu;
        })
        //const contenu = this.querySelector('textarea');

         
    }
}