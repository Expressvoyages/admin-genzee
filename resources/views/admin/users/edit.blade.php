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
                    <li class="breadcrumb-item active">Edit users Settings</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h4 class="card-title">Edit user Profile details </h4>
               
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                       
                   

                        <form method="POST" action="{{ route('users.update', $user['uid']['stringValue']) }}">
                            @method('PUT')
                            @csrf
                
                                <div class="row m-4">
                                    <!-- First Column -->
                                    <div class="col-md-5 m-2">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $user['name']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="about">About</label>
                                            <textarea class="form-control" name="about" id="about" rows="5">{{ $user['about']['stringValue'] }}</textarea>
                                        </div>
                                        
                                        <div class="form-group m-2">
                                            <label for="age">Age</label>
                                            <input type="text" class="form-control" name="age" id="age" value="{{ $user['age']['stringValue'] }}">
                                        </div>
                                    
                                        <div class="form-group m-2">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" name="city" id="city" value="{{ $user['city']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" name="country" id="country" value="{{ $user['country']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" value="{{ $user['email']['stringValue'] }}" readonly>
                                        </div>
                                        
                                        <div class="form-group m-2">
                                            <label for="dob">Date of Birth</label>
                                            <input type="text" class="form-control" name="dob" id="dob" value="{{ $user['dob']['stringValue'] }}">
                                        </div>
                                     
                                        
                                 
                                        
                                        
                                        <div class="form-group m-2">
                                            <label for="genotype">Genotype</label>
                                            <input type="text" class="form-control" name="genotype" id="genotype" value="{{ $user['genotype']['stringValue'] }}">
                                        </div>
                                     
                                   
                                    </div>
                                    
                                    <!-- Second Column -->
                                    <div class="col-md-5 m-2">
                                     
                                      
                                        <div class="form-group m-2">
                                            <label for="paid">Payment status ~ Yes = Odogwu-Classy | No = Trenches</label>
                                            <select style="width: 180px;" class="form-control" name="paid" id="paid">
                                                <option value="true" {{ isset($user['paid']['booleanValue']) && $user['paid']['booleanValue'] == true ? 'selected' : '' }}>Yes</option>
                                                <option value="false" {{ isset($user['paid']['booleanValue']) && $user['paid']['booleanValue'] == false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group m-2">
                                            <label for="phoneNumber">Phone Number</label>
                                            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="{{ $user['phoneNumber']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="preference">Preference</label>
                                            <input type="text" class="form-control" name="preference" id="preference" value="{{ $user['preference']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="state">State</label>
                                            <input type="text" class="form-control" name="state" id="state" value="{{ $user['state']['stringValue'] }}">
                                        </div>
                                        {{-- <div class="form-group m-2">
                                            <label for="status">Relationship Status</label>
                                            <input type="text" class="form-control" name="status" id="status" value="{{ $user['status']['stringValue'] }}">
                                        </div> --}}
                                        {{-- <div class="form-group m-2">
                                            <label for="university">University</label>
                                            <input type="text" class="form-control" name="university" id="university" value="{{ $user['university']['stringValue'] }}">
                                        </div> --}}
                                     
                                        <div class="form-group m-2">
                                            <label for="weight">Weight</label>
                                            <input type="text" class="form-control" name="weight" id="weight" value="{{ $user['weight']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="height">Height</label>
                                            <input type="text" class="form-control" name="height" id="height" value="{{ $user['height']['stringValue'] }}">
                                        </div>
                                        <div class="form-group m-2">
                                            <label for="height">UID</label>
                                            <input type="text" class="form-control" name="uid" id="uid" value="{{ $user['uid']['stringValue'] }}" readonly>
                                        </div>
                                        
                                        @foreach($user['profileImage']['arrayValue']['values'] as $image)
                                        <div class="image-container">
                                            <img src="{{ $image['stringValue'] }}" alt="Existing Image" class="existing-image">
                                            <button type="button" class="close-icon" onclick="removeImage(this, '{{ $image['stringValue'] }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                    
                                    <input type="hidden" id="deletedImages" name="deletedImages" value="">


                                    </div>
                                </div>
                                
                                
                              
                                <button type="submit" class="btn-sm btn-info m-3">Update</button>
                            </form>
                            
                        


                      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->


 @include('dash.footer')
 <style>
    .image-container {
    position: relative;
    display: inline-block;
    margin-right: 10px;
}

.existing-image {
    width: 100px; /* Adjust as needed */
    height: auto; /* Maintain aspect ratio */
    border-radius: 5px;
}

.close-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

 </style>
<script>
    function removeImage(button, imageUrl) {
        var imageContainer = button.parentElement;
        imageContainer.remove();

        // Update the hidden input field with the URL of the removed image
        var deletedImagesInput = document.getElementById('deletedImages');
        var deletedImages = deletedImagesInput.value.split(',');
        deletedImages.push(imageUrl);
        deletedImagesInput.value = deletedImages.join(',');
    }
</script>
</div>






{{-- 







<div class="container">
  <div class="row justify-content-center mt-4">
      <div class="col-md-12">
          <div class="card">
              <div class="card-header bg-info text-white m-3">Edit User Data</div>
              @if(session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif
          
          <form method="POST" action="{{ route('users.update', $user['uid']['stringValue']) }}">
            @method('PUT')
            @csrf

                <div class="row m-4">
                    <!-- First Column -->
                    <div class="col-md-5 m-2">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ $user['name']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="about">About</label>
                            <input type="text" class="form-control" name="about" id="about" value="{{ $user['about']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="age">Age</label>
                            <input type="text" class="form-control" name="age" id="age" value="{{ $user['age']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="children">Children</label>
                            <input type="text" class="form-control" name="children" id="children" value="{{ $user['children']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" id="city" value="{{ $user['city']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" name="country" id="country" value="{{ $user['country']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ $user['email']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="dob">Date of Birth</label>
                            <input type="text" class="form-control" name="dob" id="dob" value="{{ $user['dob']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="gender">Gender</label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="male" {{ $user['gender'] == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user['gender'] == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="form-group m-2">
                            <label for="genotype">Genotype</label>
                            <input type="text" class="form-control" name="genotype" id="genotype" value="{{ $user['genotype']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="height">Height</label>
                            <input type="text" class="form-control" name="height" id="height" value="{{ $user['height']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="height">UID</label>
                            <input type="text" class="form-control" name="uid" id="uid" value="{{ $user['uid']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="hideAccount">Hide Account</label>
                            <select class="form-select" name="hideAccount" id="hideAccount">
                                <option value="true" {{ isset($user['hideAccount']['stringValue']) && $user['hideAccount']['stringValue'] == 'true' ? 'selected' : '' }}>Yes</option>
                                <option value="false" {{ isset($user['hideAccount']['stringValue']) && $user['hideAccount']['stringValue'] == 'false' ? 'selected' : '' }}>No</option>
                                
                            </select>
                        </div>
                    </div>
                    
                    <!-- Second Column -->
                    <div class="col-md-5 m-2">
                     
                        <div class="form-group m-2">
                            <label for="online">Online</label>
                            <select class="form-select" name="online" id="online">
                                <option value="true" {{ isset($user['online']['stringValue']) && $user['online']['stringValue'] == 'true' ? 'selected' : '' }}>Yes</option>
                                <option value="false" {{ isset($user['online']['stringValue']) && $user['online']['stringValue'] == 'false' ? 'selected' : '' }}>No</option>
                                
                            </select>
                        </div>
                        <div class="form-group m-2">
                            <label for="paid">Payment status ~ Yes = Odogwu-Classy | No = Trenches</label>
                            <select class="form-select" name="paid" id="paid">
                                <option value="true" {{ isset($user['paid']['stringValue']) && $user['paid']['stringValue'] == 'true' ? 'selected' : '' }}>Yes</option>
                                <option value="false" {{ isset($user['paid']['stringValue']) && $user['paid']['stringValue'] == 'false' ? 'selected' : '' }}>No</option>
                                
                            </select>
                        </div>
                        <div class="form-group m-2">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="{{ $user['phoneNumber']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="preference">Preference</label>
                            <input type="text" class="form-control" name="preference" id="preference" value="{{ $user['preference']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="state">State</label>
                            <input type="text" class="form-control" name="state" id="state" value="{{ $user['state']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="status">Relationship Status</label>
                            <input type="text" class="form-control" name="status" id="status" value="{{ $user['status']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="university">University</label>
                            <input type="text" class="form-control" name="university" id="university" value="{{ $user['university']['stringValue'] }}">
                        </div>
                        <div class="form-group m-2">
                            <label for="verified">Verified</label>
                            <select class="form-select" name="verified" id="verified">
                                <option value="true" {{ isset($user['verified']['stringValue']) && $user['verified']['stringValue'] == 'true' ? 'selected' : '' }}>Yes</option>
                                <option value="false" {{ isset($user['verified']['stringValue']) && $user['verified']['stringValue'] == 'false' ? 'selected' : '' }}>No</option>

                            </select>
                        </div>
                        <div class="form-group m-2">
                            <label for="weight">Weight</label>
                            <input type="text" class="form-control" name="weight" id="weight" value="{{ $user['weight']['stringValue'] }}">
                        </div>
                    </div>
                </div>
                
                
              
                <button type="submit" class="btn-sm btn-info m-3">Update</button>
            </form>
            
            
          </div>
        </div>
    </div>
</div>
</div>


@include('dash.footer') --}}