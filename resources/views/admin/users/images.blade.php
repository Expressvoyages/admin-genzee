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

     
    
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-b-0">All Users</h4>
                            <button id="exportrBtn" class="btn btn-primary">Export to CSV</button>
                        </div>
                        
        
                    </div>
                  
                    @foreach($imageUrls as $folder => $images)
                    <h2>{{ ucfirst($folder) }}</h2>
                    @foreach($images as $imageUrl)
                        <img src="{{ $imageUrl }}" alt="Image">
                    @endforeach
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
</div>





@include('dash.footer')


