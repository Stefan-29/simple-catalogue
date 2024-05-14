$(document).ready(function() {
    $('#search-button').hide();
    // event when keyword is written
    $('#keyword').on('keyup', function() {
        //         $("#container").load('ajax/data.php?keyword=' + $('#keyword').val());
        //     });

        $.get('js/ajax/data.php?keyword=' + $(this).val(), function(data) {
            console.log($("#keyword").val());
            $('#container').html(data);
        });

    });
});