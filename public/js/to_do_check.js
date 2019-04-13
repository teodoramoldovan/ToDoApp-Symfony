$(document).ready(function(){
    $('.js-like-article').on('change',function(e){
        e.preventDefault();
        var $link=$(e.currentTarget);

        $.ajax({
            method:'POST',
            url:$link.attr('href')
        }).done(function(data){
            $('.js-like-article-count').html(data.done);
            alert("done");
            //console.log("damn");
        });

    });
});