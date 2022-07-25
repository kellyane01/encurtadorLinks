@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="searchbar mt-0 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <form>
                        <div class="input-group">
                            <input
                                id="indexSearch"
                                type="text"
                                name="search"
                                placeholder="{{ __('crud.common.search') }}"
                                value="{{ $search ?? '' }}"
                                class="form-control"
                                autocomplete="off"
                            />
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon ion-md-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-primary" data-toggle="modal" data-target="#modalLink">
                        <i class="icon ion-md-add"></i> @lang('crud.links.create_title')
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-space-between">
                    <h4 class="card-title">@lang('crud.links.index_title')</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead>
                        <tr>
                            <th class="text-left">
                                @lang('crud.links.inputs.nome')
                            </th>
                            <th class="text-center">
                                Qtd. links curtos
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($links as $link)
                            <tr>
                                <td>{{ $link->nome ?? '-' }}</td>
                                <td class="text-center">{{ count($link->linksCurtos) }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-sm btn-light dropdown-toggle" id="dropdownActions"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        >
                                            MENU
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownActions">
                                            <a class="btn-sm dropdown-item" onclick="verLinksCurtos({{$link->id}})">
                                                Links curtos
                                            </a>
                                            <form
                                                action="{{ route('links.destroy', $link) }}" method="POST"
                                                onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                            >
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-sm dropdown-item">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="18">{!! $links->render() !!}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="modalLink" tabindex="-1" role="dialog" aria-labelledby="modalLink">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <h5 class="my-auto">@lang('crud.links.new_title')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: red;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('links.store') }}" method="POST">
                        @csrf @include('form-inputs')
                        <button class="btn btn-primary btn-sm float-right" type="submit">
                            Pronto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLinksCurtos" tabindex="-1" role="dialog" aria-labelledby="modalLinksCurtos">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <h5 class="my-auto">@lang('crud.linksCurtos.index_title')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: red;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="link_id" name="link_id"/>

                    <div class="mb-2 text-right">
                        <button
                            onclick="gerarLinkCurto()"
                            type="submit"
                            class="btn-sm btn-primary"
                        >
                            @lang('crud.linksCurtos.create_title')
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover">
                            <thead>
                            <tr>
                                <th class="text-left">
                                    @lang('crud.linksCurtos.inputs.link')
                                </th>
                                <th class="text-center">
                                    @lang('crud.linksCurtos.inputs.data_expiracao')
                                </th>
                                <th class="text-center">
                                    Situação
                                </th>
                                <th class="text-center">
                                    @lang('crud.common.actions')
                                </th>
                            </tr>
                            </thead>
                            <tbody id="tbody_links_curtos"></tbody>
                        </table>
                        <div>
                            <nav aria-label="...">
                                <ul class="pagination justify-content-center" id="paginacao_links_curtos">
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        function alerts(tipo, msg) {
            const notyf = new Notyf({dismissible: true});
            tipo == 'success' ? notyf.success(msg) : notyf.error(msg);
        }

        function abrirModalAddLink() {
            $('#modalLink').modal({show: true});
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
                    $.each(resposta.data, function (id, valor) {
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
                    if (retorno) {
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
    </script>
@endsection
