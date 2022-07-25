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
                            <a class="btn-sm dropdown-item cursor-point" onclick="verLinksCurtos({{$link->id}})">
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
