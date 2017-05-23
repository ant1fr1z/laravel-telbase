$('button#addlink').on('click', function () {
    var object2 = $('input#inputObject2').val();
    $.ajax({
        method:'POST',
        url:url,
        data: { object1id:object1id, linktype: $('input#inputLinkType').val(),object2number:$('input#inputObject2').val(), _token:token },
        success: function(data){
            $('div#errors').hide();
            $('#addlink-modal').modal();
        },
        error: function(data){
            var errors = data.responseJSON;
            $('div#errors').find('.alert.alert-danger').find('ul').find('li').empty().append(errors.object2number);
            $('div#errors').show();
        }
    })
});