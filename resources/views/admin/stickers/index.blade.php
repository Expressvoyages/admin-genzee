@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">Gifts</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body collapse show">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <h4 class="card-title">All stickers</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Stickers</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th>Sticker Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stickers as $sticker)
                                    <tr data-id="30">
                                        <td>
                                            <img src="{{ $sticker['images']['original']['url'] }}" alt="Sticker Image" style="max-width: 100px;" />
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $stickers->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
