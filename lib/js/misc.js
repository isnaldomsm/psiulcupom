prevent_ajax_view = true;

$(document).ready(function () {

    modallink = null;
    $('#modal').on('hidden.bs.modal', function (e) {
        $(e.target).removeData('bs.modal');
        modalelement = this;
        $('.modal .modal-content').html('<div style="text-align:center"><img src="http://lazyphp.com.br/template/default/images/loading.gif"></div>');
        if ($(modalelement).attr('data-href')) {
            $(modalelement).attr('href', '#');
        }
        if (typeof recarregar !== 'undefined') {
            if (recarregar) {
                location.reload();
            }
        }
        //modallink.attr('data-href', modallink.attr('href'));
        //modallink.attr('href', '#');
    });

});

