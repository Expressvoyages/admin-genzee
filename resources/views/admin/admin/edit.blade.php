@include('dash.head') @include('dash.nav') @include('dash.header')

<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">Administrator</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admins.update') }}">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required />
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" />
                            <small class="form-text text-muted">Leave this field blank to keep the existing password.</small>
                        </div>
                        <div class="form-group">
                            <label for="user_role">User Role:</label>
                            <select class="form-control" id="user_role" name="user_role" required>
                                <option value="1" {{ $admin->user_role == 1 ? 'selected' : '' }}>CEO</option>
                                <option value="2" {{ $admin->user_role == 2 ? 'selected' : '' }}>COO</option>
                                <option value="3" {{ $admin->user_role == 3 ? 'selected' : '' }}>Customer Care</option>
                                <option value="4" {{ $admin->user_role == 4 ? 'selected' : '' }}>Accountant</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Edit Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dash.footer')
