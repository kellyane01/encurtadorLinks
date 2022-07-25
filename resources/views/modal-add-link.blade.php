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
