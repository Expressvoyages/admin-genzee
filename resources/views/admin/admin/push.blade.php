@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active"> Notification</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body collapse show">
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <h4 class="card-title"> Send Notification</h4>
           
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Send Push Notification</h4>
                        <form action="{{route('bulksend')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Title</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Notification Title" name="title">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Message</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Notification Description" name="body" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Image Url</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter image link" name="img">
                            </div>
                            <button type="submit" class="btn btn-primary">Send Notification</button>
                        </form>
                        <script>
                            function loadPhoto(event) {
                                var reader = new FileReader();
                                reader.onload = function () {
                                    var output = document.getElementById('photo');
                                    output.src = reader.result;
                                };
                                reader.readAsDataURL(event.target.files[0]);
                            }
                        </script>
                        
                        
                    </div>
                </div>
            </div>
        </div>

     
    </div>
    <!-- End Container fluid  -->
</div>



@include('dash.footer')
