@extends('admin.master_layout')
@section('title')
    <title>{{ __('Aminity List') . ' - ' . $setting->app_name ?? 'AppeyTech' }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Aminity List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Aminity List') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Aminity List') }}</h4>
                                <div>
                                    @adminCan('listing.aminity.create')
                                        <a class="btn btn-primary" href="{{ route('admin.listing.aminity.create') }}"><i
                                                class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($aminities as $aminity)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $aminity->title }}</td>
                                                    <td>
                                                        <input id="status_toggle" data-toggle="toggle"
                                                            data-on="{{ __('Active') }}" data-off="{{ __('Inactive') }}"
                                                            data-onstyle="success" data-offstyle="danger" type="checkbox"
                                                            onchange="changeStatus({{ $aminity->id }})"
                                                            {{ $aminity->status ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            @adminCan('listing.aminity.edit')
                                                                <a class="m-1 text-white btn btn-sm btn-warning"
                                                                    href="{{ route('admin.listing.aminity.edit', [
                                                                        'listing_aminity' => $aminity->id,
                                                                        'code' => getSessionLanguage(),
                                                                    ]) }}"
                                                                    title="Edit">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            @endadminCan
                                                            @adminCan('listing.aminity.delete')
                                                                <x-admin.delete-button :id="$aminity->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Aminity')" route="admin.listing.aminity.create"
                                                    create="yes" :message="__('No data found!')" colspan="5">
                                                </x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $aminities->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict";

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/listing-aminity/') }}" + "/" + id)
        }

        function changeStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 1 }}"
            if (isDemo == 0) {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/listing-aminity/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    </script>
@endpush

@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
