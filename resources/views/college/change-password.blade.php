@extends('college.layout.app')

@section('content')
<style>
    .btn-theme {
        background-color: #306060;
        border-color: #306060;
        color: #fff;
    }
    .btn-theme:hover {
        background-color: #254d4d;
        border-color: #254d4d;
        color: #fff;
    }
    .card-header-theme {
        background-color: #306060;
        color: #fff;
    }
    .form-control:focus {
        border-color: #306060;
        box-shadow: 0 0 0 0.2rem rgba(48, 96, 96, 0.2);
    }
    .card-theme {
        border: 1.5px solid #306060 !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card card-theme shadow-sm rounded-3">
                <div class="card-header card-header-theme py-4 px-5 rounded-top-3">
                    <h4 class="mb-0 fw-semibold">Change Password</h4>
                    <p class="small mb-0 mt-1" style="color: rgba(255,255,255,0.75);">You must set a new password before continuing.</p>
                </div>

                <div class="card-body px-5 py-4">

                    @if(session('error'))
                        <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('college.password.update') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="new_password" class="form-label fw-medium">New Password</label>
                            <input type="password"
                                   id="new_password"
                                   name="new_password"
                                   class="form-control form-control-lg"
                                   placeholder="Min. 8 characters"
                                   required
                                   minlength="8">
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-medium">Confirm Password</label>
                            <input type="password"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   class="form-control form-control-lg"
                                   placeholder="Re-enter your password"
                                   required>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-theme btn-lg w-100">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection