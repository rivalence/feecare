export default class ShowPosts {
    constructor(postContainers){
        this.postContainers = postContainers;

        if(this.postContainers.length > 0){
            this.init();
        }
    }

    init() {
        const postToShow = this.postContainers.slice(0,2);
        postToShow.map(post => {
            post.style.display = "block";
            post.dataset.hidden = "false";
        });

        const loadMore = document.querySelector("#load-more");
        loadMore.addEventListener('click', function(){
            const postContainers = [].slice.call(document.querySelectorAll('div[data-hidden="true"]'));

            if(postContainers.length > 0){
                const activePosts = postContainers.slice(0, 2);
                activePosts.map(post => {
                    post.style.display = "block";
                    post.dataset.hidden = "false";
                });
            } else {
                this.style.display = "none";
            }
        })
    }
}