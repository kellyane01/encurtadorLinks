function alerts(tipo, msg) {
    const notyf = new Notyf({dismissible: true});
    tipo == 'success' ? notyf.success(msg) : notyf.error(msg);
}

function verLinksCurtos(link_id) {
    paginacaoLinksCurtos(link_id);
    $('#link_id').val(link_id);
    $('#modalLinksCurtos').modal({show: true});
}

function paginacaoLinksCurtos(link_id, pagina = 1) {
    $('#tbody_links_curtos').empty();
    $.ajax({
        url: `/links-curtos?link_id=${link_id}&page=${pagina}`,
        success: function (resposta) {
            // console.log('Brenaa');
            let acao, link;
            $.each(resposta.linksCurtos.data, function (id, valor) {
                if (valor.situacao == 'Ativo') {
                    link = `<td>${valor.link}` +
                        `<a class="ml-2 btn btn-sm btn-light" onclick="copiarLink('${valor.link}')"><i class="icon ion-md-copy"></i></a>` +
                        `</td>`;
                    acao = `<td class="text-center">` +
                        `<a class="ml-2 btn btn-sm btn-danger" onclick="desativarLinkCurto(${link_id}, ${valor.id})">Desativar</a>` +
                        `</td>`;
                } else {
                    link = `<td>${valor.link}</td>`;
                    acao = `<td class="text-center"> -- </td>`;
                }

                $("#tbody_links_curtos").append(
                    `<tr>` +
                    link +
                    `<td class="text-center">${valor.data_expiracao_}</td>` +
                    `<td class="text-center">${valor.situacao}</td>` +
                    acao +
                    `<tr>`
                );
            });

            let ultima_pag = $(`#pag-${resposta.last_page}`).text();

            if ((pagina == 1 || !ultima_pag) && resposta.last_page > 1) {
                $('#paginacao_links_curtos').empty();
                for (let pag = 1; pag <= resposta.last_page; pag++) {
                    $('#paginacao_links_curtos').append(`<li class="page-item" id="pag-${pag}"><a onclick="paginacaoLinksCurtos(${link_id}, ${pag})" class="page-link">${pag}</a></li>`)
                }
            }

            $('.page-item').removeClass('active');
            $(`#pag-${resposta.current_page}`).addClass('active');
        }
    });
}

function gerarLinkCurto() {
    let link_id = document.getElementById('link_id').value;
    $.ajax({
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: `/links-curtos?link_id=${link_id}`,
        success: function (retorno) {
            if (retorno.success) {
                paginacaoLinksCurtos(link_id);
                alerts('success', 'Link gerado!');
            } else {
                alerts('error', 'Erro ao gerar link!');
            }
        }
    });
}

function copiarLink(link) {
    navigator.clipboard.writeText(link);
    alerts('success', 'Link copiado!');
}

function desativarLinkCurto(link_id, link_curto_id) {
    $.ajax({
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: `/links-curtos/${link_curto_id}?link_curto_id=${link_curto_id}`,
        success: function () {
            paginacaoLinksCurtos(link_id);
            alerts('success', 'Link desativado!');
        }
    });
}
