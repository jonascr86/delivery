//(function ($) {
//
//    var Delivery = {
//        
//        form : null,
//        div_return : null,
//        
//        init: function () {
//            this.form = $('#formulario');
//            this.div_return = $('#alert');
//            console.log("teste");
//            this.form.submit(function(){
//console.log("teste001");
//                $.get(Ajax.url,{'action' : 'pessoa', 'ajax' : 1}, function(dados){
//                   $('#message').html(dados);
//                   return false;
//               })
//return false;
//            });
//        }
//    }
//    
//    $(document).ready(function (){
//        console.log("teste002");
//        Delivery.init();
//    })
//console.log("teste003");
//})(jQuery);

$(document).ready(function () {
    $(".mask-cnpj").mask("99.999.999/9999-99");
    $(".mask-cpf").mask("999.999.999-99");
    $(".mask-rg").mask("99.999.999.99");
    $(".mask-telefone").mask("(99) 9999-9999");
});

jQuery(document).ready(function ($) {

    $('.cooks-table').DataTable({
        language: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });
});

function buscarCidades(url, div_id) {
    var estado = $('#s_estado').val();  //codigo do estado escolhido
    //se encontrou o estado
    if (estado) {
        var url = url + '&estado=' + estado;
        //caminho do arquivo php que irá buscar as cidades no BD
        $.get(url, function (dataReturn) {
            $('#' + div_id).html(dataReturn);  //coloco na div o retorno da requisicao
        });
    }
}
//
//function salvarPessoa(url, div_id) {
//    //caminho do arquivo php que irá buscar as cidades no BD
//    console.log(url);
//    $.get(url, function (dataReturn) {
//        $('#' + div_id).html(dataReturn);  //coloco na div o retorno da requisicao
//    });
//}

//function salvarPessoa2(url, form_id) {
//    jQuery(document).ready(function () {
//        jQuery('#' + form_id).submit(function () {
//            var dados = jQuery(this).serialize();
//
//            jQuery.ajax({
//                type: "POST",
//                url: url,
//                data: dados,
//                success: function (data)
//                {
//                    alert(data);
//                }
//            });
//
//            return false;
//        });
//    });
//}

//function salvarPessoa3(action_url, element_response_id, args){
//    $.ajax({
//        type:  'POST',
//        async: true,
//        url:   action_url,
//        cache: true,
//        dataType: 'json',
//        data:  args,
//        success: function(data){
//            console.lo(action_url);
//            if ( data.success ){
//                $('#' + element_response_id).html(data.result);
//            }
//        },
//        error: function(jqXHR, textStatus, errorThrown){            
//            var error = $.parseJSON(jqXHR.responseText);
//            var content = error.content;
//            console.log(content.message);
//            if(content.display_exceptions)
//                console.log(content.exception.xdebug_message);
//        }
//    });
//}