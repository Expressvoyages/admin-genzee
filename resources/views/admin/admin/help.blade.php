@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                    <li class="breadcrumb-item active">Help</li>
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
                <h4 class="card-title">Users Help Ticket From website</h4>
            </div>
        </div>
        <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-body collapse show">
                      <div class="table-responsive">
                          <table class="table product-overview">
                              <thead>
                                  <tr>
                                    <th>Id</th>
                                      <th>Name</th>
                                      <th>Email</th>
                                      <th>message </th>
                                      
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach($helpData as $help)
                                  <tr>
                                    <td>{{ $help->id }}</td>
                                      <td>{{ $help->name }}</td>
                                      <td>{{ $help->email }}</td>
                                      <td>{{ $help->message }}</td>
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
