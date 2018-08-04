$(document).ready(function() {
    $('body').on('click', '#like', function() {
        var action = $('#like').attr('action');

        $.post('/like', {
            image_id: $('#image-detail').attr('image-id'),
            action: action
        }, function(result) {
            if (result['success']) {
                $('#like').parent().html(result['html']);
            } else if (!result['success'] && result['error_info'] == 'required_login') {
                window.location.href = "/login?next=" + window.location.pathname;
            } else {
                // implement order error handler
            }
        });
    });

    $('#download').on('click', function() {
        window.location = '/download/' + $('#image-detail').attr('image-id');
    });

    $('#delete').on('click', function() {
        $.ajax({
            url: '/image/'+$('#image-detail').attr('image-id'),
            method: "DELETE",
            success: function() {
                window.location = '/';
            }

        })
    });
});
