import $ from 'jquery';

class Like {
    constructor(){
        this.events();//this adds our event listeners as soon as the page loads 
    }

    events(){
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    //methods 
    ourClickDispatcher(e){
        var currentLikeBox = $(e.target).closest(".like-box");//whatever element got clicked on find its closest ancistor that matches this selector

        if(currentLikeBox.data('exists') == 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'POST',
            data: {'professorID': currentLikeBox.data('professor')},
            success: (response) => {
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });  
    }

    deleteLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'DELETE',
            success: (response) => {
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
}

export default Like;