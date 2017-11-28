$(document).ready(function () {
    $(document).on("click", "#task_solve_save", function(e){
        e.preventDefault();
        $.ajax({
            data: $(this).closest("form").serialize(),
            type: $(this).closest("form").attr('method'),
            url: $(this).closest("form").attr('action'),
            success: function(data) {
                var _msg = '';
                var _class = '';
                if (data['status'] == 1) {
                    _msg = 'Задача решена';
                    _class = 'result bg-success';
                } else {
                    _msg = 'Задача не решена, один из тестов не пройден';
                    _class = 'result bg-danger';
                }

                $('#output').html(data['output'].join('<br/>'));
                $('#output').show();
                // console.log(data['output'].join('\n'));
                $('#result').html(_msg);
                $('#result').attr('class', _class);
            }
        });
        return false;
    });
});