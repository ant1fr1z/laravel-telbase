//открытие модального окна если номера есть в базе
$('button#checklink').on('click', function () {
    var object2 = $('input#inputObject2').val();
    $.ajax({
        method:'POST',
        url:url,
        data: { object2number:$('input#inputObject2').val(), description:$('textarea#inputDescription').val(), _token:token },
        success: function(data){
            var object2id = data.number2.object.id;
            $('div#errors').hide();
            $('table#table-modal').empty().append('<tr><th>Тип связи</th><th>Номер</th><th>ФИО/Кличка</th><th>Обновлено</th></tr><tr><td>'+$('select#inputLinkType').val()+'</td><td>'+data.number2.number+'</td><td>'+data.number2.object.fio+'</td><td>'+data.number2.object.updated_at+'</td></tr>');
            $('#addlink-modal').modal();

            $('#addlink-modal').on('hidden.bs.modal', function () {
                object2id = undefined;
            })

            //обработка событий самого модального окна
            $('button#addlink').on('click', function () {
                    $.ajax({
                        method:'PUT',
                        url:url,
                        data: { object1id:object1id, object2id:object2id, linktype:$('select#inputLinkType').val(), description:$('textarea#inputDescription').val(), _token:token },
                        success: function () {
                            $('button#addlink').attr('class', 'btn btn-success');
                            setTimeout(function() {
                                $(location).attr('href',url); }, 1500);
                        }
                    })
            });
            //
        },
        error: function(data){
            var errors = data.responseJSON;
            errorsHtml = '';
            $.each( errors , function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
            });
            $('div#errors').find('.alert.alert-danger').find('ul').empty().append(errorsHtml);
            $('div#errors').show();
        }
    })
});
