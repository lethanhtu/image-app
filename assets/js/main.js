$(document).ready(function() {
    $('body').on('click', '#like', function() {
        $.ajax({
            url: '/like',
            method: 'POST',
            data: {
                image_id: $('#image-detail').attr('image-id')
            },
            success: function (result) {
                $('#like').replaceWith(result.data);
            },
        });
    });

    $('#download').on('click', function() {
        window.location = '/download/' + $('#image-detail').attr('image-id');
    });

    $('#delete').on('click', function() {
        $.ajax({
            url: '/image/' + $('#image-detail').attr('image-id'),
            method: "DELETE",
            success: function() {
                window.location = '/';
            }
        })
    });
});
