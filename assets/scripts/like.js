export default class Like 
{
    constructor(likeElements) {
        this.likeElements = likeElements;

        if(this.likeElements){
            this.init();
        }
    }

    init() {
        this.likeElements.map(element => {
            element.addEventListener('click', this.onClick)
        })
    }

    onClick(event) {
        event.preventDefault();
        const url = this.href;

        fetch(url)
        .then(res => res.json())
        .then(res => {
            const nbLike = res.nbLike;
            const span = document.querySelector('span#show-likes-' + this.dataset.idpost);
            const modal = document.querySelector(".modal-like-" + this.dataset.idpost);
            const listLike = modal.querySelector("#list-like");
            const likeName = listLike.querySelector("li#" + res.userNom + ' ' + res.userPrenom);

            if (likeName) likeName.remove();
            else {
                //Ajout dynamique du nom du nouveau liker
                const li = listLike.appendChild(document.createElement("li"));
                li.className = "list-group-item";
                li.id = res.userNom + ' ' + res.userPrenom;
                li.innerHTML = res.userNom + ' ' + res.userPrenom;
            }

            //Màj du nombre de like AJAX
            this.dataset.nbLike = nbLike;
            span.innerHTML = nbLike < 2 ? nbLike + ' Like' : nbLike + ' Likes';

            this.classList.toggle('btn-primary');
            this.classList.toggle('btn-light');
        })
    }
}