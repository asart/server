$(document).ready(function () {
    $("#form").submit(function() {
        $.ajax({
            data: $(this).serialize(),
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            success: function(data) {
                var _msg = '';
                var _class = '';
                if (data['result'] == true) {
                    _msg = 'Задача решена';
                    _class = 'result bg-success';
                } else {
                    _msg = 'Задача не решена, один из тестов не пройден';
                    _class = 'result bg-danger';
                }

                $('#result').html(_msg);
                $('#result').attr('class', _class);
            }
        });
        return false;
    });
});