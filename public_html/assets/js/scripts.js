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

function buscarBairros(url, div_id) {

    var cidade = $('#s_cidade').val();  //codigo do estado escolhido
    //se encontrou o estado
    if (cidade) {
        var url = url + '&cidade=' + cidade;
//caminho do arquivo php que irá buscar as cidades no BD
        $.get(url, function (dataReturn) {
            $('#' + div_id ).html(dataReturn);  //coloco na div o retorno da requisicao
        });
    }
}