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
                <div style="display: flex; justify-content: space-between;">
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
                                        <button class="btn btn-sm btn-light dropdown-toggle " type="button"
                                                id="dropdownActions"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            MENU
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownActions">
                                            <a class="btn-sm dropdown-item" onclick="verLinksCurtos({{$link->id}})">Links
                                                curtos</a>
                                            <form
                                                action="{{ route('links.destroy', $link) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                            >
                                                @csrf @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn-sm dropdown-item"
                                                >
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
                    <form
                        action="{{ route('links.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <x-inputs.group class="col-12 px-0">
                            <x-inputs.text
                                name="nome"
                                label="Nome"
                                value=""
                                maxlength="255"
                                placeholder="Digite o nome"
                                required
                            ></x-inputs.text>
                        </x-inputs.group>

                        <x-inputs.group class="col-12 px-0">
                            <x-inputs.text
                                name="link"
                                label="Link"
                                value=""
                                maxlength="255"
                                placeholder="Cole o link aqui"
                                required
                            ></x-inputs.text>
                        </x-inputs.group>

                        <button
                            class="btn btn-primary btn-sm float-right"
                            type="submit"
                        >
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
                    <div class="mb-2 text-right">
{{--                        <form--}}
{{--                            action="{{ route('links-curtos.store') }}"--}}
{{--                            method="POST"--}}
{{--                            onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"--}}
{{--                        >--}}
{{--                            @csrf--}}
                            <input type="hidden" id="link_id" name="link_id"/>
                            <button
                                onclick="gerarLinkCurto()"
                                type="submit"
                                class="btn-sm btn-primary"
                            >
                                @lang('crud.linksCurtos.create_title')
                            </button>
{{--                        </form>--}}
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
                            <tfoot>
                            <tr id="paginacao_links_curtos"></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        function alertSuccess(msg) {
            const notyf = new Notyf({dismissible: true})
            notyf.success(msg)
        }

        function verLinksCurtos(link_id, pagina = 1) {
            paginacaoLinksCurtos(link_id, pagina);
            $('#link_id').val(link_id);
            $('#modalLinksCurtos').modal({show: true});
        }

        function paginacaoLinksCurtos(link_id, pagina) {
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

                    let ultima_pag = $(`#pag-${resposta.last_page}`);
                    console.log(pagina);

                    if (pagina == 1 || !ultima_pag) {
                        $('#paginacao_links_curtos').empty();

                        $('#paginacao_links_curtos').append(
                            '<nav aria-label="...">' +
                            '<ul class="pagination" id="link">'
                        );
                        for (let pag = 1; pag < resposta.last_page; pag++) {
                            console.log('pag' + pag);
                            $('#link').append(`<li class="page-item" id="pag-${pag}"><a onclick="paginacaoLinksCurtos(${link_id}, ${pag})" class="page-link">${pag}</a></li>`)
                        }
                        $('#paginacao_links_curtos').append(
                            `</ul>` +
                            `</nav>`
                        );
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
                    console.log(retorno);
                    paginacaoLinksCurtos(link_id);
                    alertSuccess('Link gerado!');
                }
            });
        }

        function copiarLink(link) {
            navigator.clipboard.writeText(link);
            alertSuccess('Link copiado!');
        }

        function desativarLinkCurto(link_id, link_curto_id) {
            $.ajax({
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: `/links-curtos/${link_curto_id}?link_curto_id=${link_curto_id}`,
                success: function () {
                    paginacaoLinksCurtos(link_id);
                    alertSuccess('Link desativado!');
                }
            });
        }
    </script>
@endsection
