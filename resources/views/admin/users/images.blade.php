@include('dash.head')
@include('dash.nav')
@include('dash.header')
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">All users</li>
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
                <h4 class="card-title">All User Image</h4>
         
            </div>
        </div>

    
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-b-0">All Users</h4>
                           
                        </div>
                        
        
                    </div>
                    <div class="card-body collapse show">
                        <div class="table-responsive">
                            <table class="table product-overview">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Name</th>
                                        <th>Images</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersPaginated as $user)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input user-checkbox" name="selectedUser" id="checkbox{{ $loop->index }}" value="{{ $user['uid']['stringValue'] }}">
                                                <label class="form-check-label" for="checkbox{{ $loop->index }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $user['name']['stringValue'] }}</td>
                                        <td>
                                            @if (isset($user['profileImage']['arrayValue']['values']))
                                                <div class="image-gallery">
                                                    @foreach ($user['profileImage']['arrayValue']['values'] as $image)
                                                        <div class="image-container">
                                                            <a href="{{ $image['stringValue'] }}" data-lightbox="user-images">
                                                                <img src="{{ $image['stringValue'] }}" alt="User Image" class="user-image">
                                                            </a>
                                                            <a href="{{ route('delete.image', ['userId' => $user['uid']['stringValue'], 'imageUrl' => urlencode($image['stringValue'])]) }}" class="delete-icon" onclick="return confirm('Are you sure you want to delete this image?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                            
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                No images
                                            @endif
                                        </td>
                                        
                                        
                                        
                            
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-center">
                        
                            {{ $usersPaginated->links('vendor.pagination.bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>

@include('dash.footer')
