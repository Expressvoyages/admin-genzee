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
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add New Gift</h4>

                        <form class="form-material m-t-40" action="{{ route('gifts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="gift_cost">Gift Cost (In Credits)</label>
                                <input type="number" class="form-control @error('cost') is-invalid @enderror" id="gift_cost" name="cost" value="3" />
                                @error('cost')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mt-2">
                                <label for="gift_image">Gift Image File</label>
                                <div class="input-group">
                                    <div class="custom-file m-3">
                                        <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="image" name="image" />
                                        @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Gifts</h4>
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th class="text-left">Id</th>
                                        <th>Gift Image</th>
                                        <th>Cost</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gifts as $gift)
                                    <tr data-id="30">
                                        <th scope="row">{{ $gift->id }}</th>
                                        <td>
                                            @if ($gift->image)
                                            <img src="{{ asset('storage/' . $gift->image) }}" alt="Sticker Image" style="max-width: 100px;" />
                                            @else No Image @endif
                                        </td>
                                        <td>{{$gift->cost}}</td>
                                        <td>{{ $gift->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <form action="{{ route('gifts.destroy', $gift->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
