$('button#addlink').on('click', function () {
    var object2 = $('input#inputObject2').val();
    $.ajax({
        method:'POST',
        url:url,
        data: { object1id:object1id, linktype: $('select#inputLinkType').val(),object2number:$('input#inputObject2').val(), _token:token },
        success: function(data){
            $('div#errors').hide();
            $('table#table-modal').empty().append('<tr><th>Тип связи</th><th>Номер</th><th>ФИО/Кличка</th><th>Обновлено</th></tr><tr><td>'+$('select#inputLinkType').val()+'</td><td>'+data.number2.number+'</td><td>'+data.number2.object.fio+'</td><td>'+data.number2.object.updated_at+'</td></tr>');
            $('#addlink-modal').modal();
        },
        error: function(data){
            var errors = data.responseJSON;
            $('div#errors').find('.alert.alert-danger').find('ul').find('li').empty().append(errors.object2number);
            $('div#errors').show();
        }
    })
});