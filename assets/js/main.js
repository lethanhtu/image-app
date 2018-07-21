$(document).ready(function() {

    var imageId = $('#like').attr('image-id');

    $('#like').on('click', function() {
        var data = {"image_id": imageId};
        $.post('/like', data, function(data){
            if(data['success']) {

            } else if(!data['success'] && data['error_info'] == 'required_login') {
                window.location.href = "/login?next="+window.location.pathname;
            } else {
                // you can implement more rules as you want
            }
        });
    });

    $('#download').on('click', function(){
        $.get('/download/'+imageId);
    });
});
