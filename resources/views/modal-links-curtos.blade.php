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
