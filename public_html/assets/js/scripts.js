$(document).ready(function () {
    $(".mask-cnpj").mask("99.999.999/9999-99");
    $(".mask-cpf").mask("999.999.999-99");
    $(".mask-rg").mask("99.999.999.99");
    $(".mask-telefone").mask("(99) 9999-9999");
    $(".mask-preco").mask("R$ 99.99");
});


$(document).ready(function () {

    $('#cooks-table').DataTable({
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

function buscarTipoBebidas(urlS) {
        var url = urlS;
        $.get(url, function (dataReturn) {
            $('#tipoBebida select').html(dataReturn); 
        });
}

function fazerPedido(urlS) {
        var url = urlS;
        $.get(url, function (dataReturn) {
            $('.modal-content #tabelinha').html(dataReturn); 
        });
}

function confirmarPedido(urlS) {
        var url = urlS;
        $.get(url, function (dataReturn) {
            $('.modal-content #tabelinha').html(dataReturn); 
        });
}
function buscarStatusPrato(urlS) {
        var url = urlS;
        $.get(url, function (dataReturn) {
            $('#statusPrato select').html(dataReturn);  //coloco na div o retorno da requisicao
        });
}

function buscarTipoPrato(urlS) {
        var url = urlS;
        $.get(url, function (dataReturn) {
            $('#tipoPrato select').html(dataReturn);  //coloco na div o retorno da requisicao
        });
}

function buscarTamanhoPrato(urlS) {
        var url = urlS;
        $.get(url, function (dataReturn) {
            $('#tamanhoPrato select').html(dataReturn);  //coloco na div o retorno da requisicao
        });
}

function adicionaAoCarrinho(url, div_id) {
    $.get(url, function (dataReturn) {
        $('#' + div_id).html(dataReturn);
    });
}

function removerDoCarrinho(url, div_id) {
    $.get(url, function (dataReturn) {
        $('#' + div_id).html(dataReturn);
    });
}

$(document).ready(function () {
    $("button#salvarTipo").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: Ajax.urlModalTipo,
            data: $('form#tipo').serialize(),
            success: function (msg) {
                $("#resposta").html(msg);
            },
            error: function () {
                alert("failure");
            }
        });
        return false;
    });
});

$('#fecharModalTipo').click(function (){
   $('#resposta').empty(); 
   $('input#tipo_descricao').val(''); 
});

$(document).ready(function () {
    $("button#salvarStatusPrato").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: Ajax.urlModalStatusPrato,
            data: $('form#statusPrato').serialize(),
            success: function (msg) {
                $("#respostaStatus").html(msg);
            },
            error: function () {
                alert("failure");
            }
        });
        return false;
    });
});

$('#fecharModalTipo, .close').click(function (){
   $('#respostaStatus').empty(); 
   $('input#status_prato_descricao').val(''); 
});

$(document).ready(function () {
    $("button#salvarTipoPrato").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: Ajax.urlModalTipoPrato,
            data: $('form#tipoPrato').serialize(),
            success: function (msg) {
                $("#respostaTipo").html(msg);
            },
            error: function () {
                alert("failure");
            }
        });
        return false;
    });
});

$('#fecharModalTipoPrato, .close').click(function (){
   $('#respostaTipo').empty(); 
   $('input#tipo_prato_descricao').val(''); 
});



$(document).ready(function () {
    $("button#salvarTamanhoPrato").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: Ajax.urlModalTamanhoPrato,
            data: $('form#tamanhoPrato').serialize(),
            success: function (msg) {
                $("#respostaTamanho").html(msg);
            },
            error: function () {
                alert("failure");
            }
        });
        return false;
    });
});

$('#fecharModalTamanhoPrato, .close').click(function (){
   $('#respostaTamanho').empty(); 
   $('input#tamanho_prato_descricao').val(''); 
});
