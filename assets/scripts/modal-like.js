export default class ModalLike {
    constructor(showLikes) {
        this.showLikes = showLikes;

        if(this.showLikes){
            this.init()
        }
    }

    init(){
        this.showLikes.map(element => {
            element.addEventListener("click", this.openModal);
        })
    }

    openModal(){
        const modal = document.querySelector('.modal-like-' + this.dataset.idpost);
        const modalComponent = modal.querySelector('.modal');
        modalComponent.style.display = "block";

        const span = modal.querySelector('.close-modal');
        span.addEventListener("click", function(){
            modalComponent.style.display = "none";
        });

        window.addEventListener("click", function(e){
            if (e.target == modalComponent){
                modalComponent.style.display = "none";
            }
        })
    }
}