$(document).ready(function() {

    $('body').on('click','#like', function() {

        var imageId = $('#like').attr('image-id');
        var action =  $('#like').attr('action');

        $.post('/like', {"image_id": imageId, 'action': action}, function(result){
            if(result['success']) {
                $('#like').parent().html(result['html']);
                console.log(result['html']);
            } else if(!result['success'] && result['error_info'] == 'required_login') {
                window.location.href = "/login?next="+window.location.pathname;
            } else {
                alert(result['error_info']);
            }
        });
    });

    $('#download').on('click', function(){
        $.get('/download/'+imageId);
    });
});
