import axios from "axios";

class Likes {
    constructor() {
        if(document.querySelector(".like-box")){
            axios.defaults.headers.common['X-WP-Nonce'] = universityData.nonce;
            this.events();
        }
    }

    events() {
        document.addEventListener("click", (e) => {
            if(e.target.classList.contains("like-box") || e.target.closest(".like-box")){
                this.clickDispatcher(e);
            }
        });
    }

    clickDispatcher(e) {
        var likeBox = e.target.closest(".like-box");
        var isLiked = likeBox.dataset.exists == "yes";
        if(isLiked){
            this.unlikePost(likeBox);
        } else {
            this.likePost(likeBox);
        }
    }

    likePost(likeBox){
        axios.post(universityData.root_url + '/wp-json/university/v1/manageLike', {professorId: likeBox.dataset.professor})
        .then(response => {
            var likeCount = parseInt(likeBox.querySelector(".like-count").innerText);
            likeCount++;
            likeBox.querySelector(".like-count").innerText = likeCount;
            likeBox.dataset.exists = "yes";
            likeBox.dataset.like = response.data;
        })
        .catch(error => {
            alert(error.response.data);
            // console.log("There was an error liking the post: ", error);
        });
    }

    unlikePost(likeBox){
        axios.delete(universityData.root_url + '/wp-json/university/v1/manageLike', {data: {like: likeBox.dataset.like}})
        .then(response => {
            var likeCount = parseInt(likeBox.querySelector(".like-count").innerText);
            likeCount--;
            likeBox.querySelector(".like-count").innerText = likeCount;
            likeBox.dataset.exists = "no";
            likeBox.dataset.like = "";
        })
        .catch(error => {
            alert(error.response.data);
            // console.log("There was an error unliking the post: ", error);
        });
    }
}

export default Likes;