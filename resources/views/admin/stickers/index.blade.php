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
                        @if($stickerUrlsPaginated->count() > 0)
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th>Sticker Image</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stickerUrlsPaginated as $url)
                                    <tr>
                                        <td>
                                            <img src="{{ $url }}" alt="Sticker Image" style="max-width: 100px;" />
                                        </td>
                                        {{-- <td>
                                            <form action="{{ route('stickers.destroy', ['url' => $url]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $stickerUrlsPaginated->links('vendor.pagination.bootstrap-4') }}
                        </div>
                        @else
                        <p>No stickers found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        
        
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
