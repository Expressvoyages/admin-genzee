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
                                        {{-- <th>Select</th> --}}
                                        <th>Name</th>
                                        <th>Images</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersPaginated as $user)
                                    <tr>
                                        {{-- <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input user-checkbox" name="selectedUser" id="checkbox{{ $loop->index }}" value="{{ $user['uid']['stringValue'] }}">
                                                <label class="form-check-label" for="checkbox{{ $loop->index }}"></label>
                                            </div>
                                        </td> --}}
                                        <td>{{ $user['name']['stringValue'] }}</td>
                                        <td>
                                            @if (isset($user['profileImage']['arrayValue']['values']))
                                                <div class="image-gallery">
                                                    @foreach ($user['profileImage']['arrayValue']['values'] as $image)
                                                        <div class="image-container">
                                                            <a href="#" class="image-link" data-src="{{ $image['stringValue'] }}">
                                                                <img src="{{ $image['stringValue'] }}" alt="User Image" class="user-image">
                                                            </a>
                                                            {{-- <form action="{{ route('delete.image', ['userId' => $user['uid']['stringValue'], 'imageUrl' => urlencode($image['stringValue'])]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="delete-icon" onclick="return confirm('Are you sure you want to delete this image?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form> --}}
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
<!-- Modal for displaying clicked image -->
<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <div class="modal-content">
        <img id="modalImage">
    </div>
</div>

<style>
    /* Style the modal */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 9999; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.9); /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    /* Close button */
    .close {
        color: white;
        position: absolute;
        top: 15px;
        right: 35px;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        z-index: 99999;
    }

    .close:hover,
    .close:focus {
        color: #999;
        text-decoration: none;
        cursor: pointer;
    }

    /* Image */
    #modalImage {
        max-width: 90%;
        max-height: 90%;
    }
</style>

<script>
    // Get the modal
    var modal = document.getElementById("imageModal");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on an image, open the modal
    var imageLinks = document.querySelectorAll('.image-link');
    imageLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            var src = this.getAttribute('data-src');
            document.getElementById("modalImage").src = src;
            modal.style.display = "flex";
        });
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
</script>

@include('dash.footer')
