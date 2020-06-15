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
        
        // the js .data function only looks at the data-attribute only once when the page loads so if we want our user to toggle back and forth b/w liking anf unliking so this method wont work fine so we need to use the attr function   
        if(currentLikeBox.attr('data-exists') == 'yes') {
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
                currentLikeBox.attr('data-exists','yes');
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(),10);
                likeCount++;
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like',response);
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
            data: {
                'like' : currentLikeBox.attr('data-like')
            },  
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists','no');
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(),10);
                likeCount--;
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like','');
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
}

export default Like;