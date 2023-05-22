export default class ShowImg {
    constructor(showImg){
        this.showImg = showImg;

        if(this.showImg){
            this.init()
        }
    }

    init() {
        this.showImg.map(element => {
            element.addEventListener('click', this.openImg)
        })
    }

    openImg(){
        const modal = document.querySelector(".modal-img-" + this.dataset.idpost);
        const modalComponent = modal.querySelector(".modal");
        modalComponent.style.display = "block";

        const closeBtn = modal.querySelector(".close-modal");
        closeBtn.addEventListener("click", () => {
            modalComponent.style.display = "none";
        });

        window.addEventListener("click", (e) => {
            if (e.target == modalComponent){
                modalComponent.style.display = "none";
            }
        })
    }
}