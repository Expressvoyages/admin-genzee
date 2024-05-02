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
                @if(session('failureCount') > 0)
                <div class="alert alert-danger">
                    {{ session('failureCount') }} notifications failed to send.
                </div>
            @endif
            
            @if(session('successCount') > 0)
                <div class="alert alert-success">
                    {{ session('successCount') }} notifications sent successfully.
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
                        <form action="{{ route('bulksend') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter Notification Title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <input type="text" class="form-control" id="message" placeholder="Enter Notification Description" name="body" required>
                            </div>
                            <div class="form-group">
                                <label for="image-url">Image URL</label>
                                <input type="url" class="form-control" id="image-url" placeholder="Enter image URL" name="img">
                            </div>
                            <button type="submit" class="btn btn-primary">Send Notification</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        
     
    </div>
    <!-- End Container fluid  -->
</div>





@include('dash.footer')
