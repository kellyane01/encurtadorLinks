@extends('layouts.app')

@section('content')
    <div class="container">
        <input type="hidden" value=""/>
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

                @include('table-links')
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('modal-add-link')

    @include('modal-links-curtos')
@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            $('#modalLink').modal({show: true});
        </script>
    @endif

    <script src='/js/index.js'></script>
@endpush
