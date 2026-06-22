<!-- resources/views/admin/career_path_links.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header text-white text-center" style="background-color: #306060;">
                        <h4 class="mb-0">Manage Career Links</h4>
                    </div>
                    <div class="card-body">

                        <!-- Add Career Link Form -->
                        <form action="{{ route('admin.career_link.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label for="parent_career_id" class="form-label">Parent Career</label>
                                    <select name="parent_career_id" id="parent_career_id" class="form-control" required>
                                        <option value="">Select Parent Career</option>
                                        @foreach($careerNodes as $node)
                                            <option value="{{ $node->id }}">{{ $node->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label for="child_career_id" class="form-label">Child Career</label>
                                    <select name="child_career_id" id="child_career_id" class="form-control" required>
                                        <option value="">Select Child Career</option>
                                        @foreach($careerNodes as $node)
                                            <option value="{{ $node->id }}">{{ $node->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success w-100">Add Link</button>
                                </div>
                            </div>
                        </form>

                        <!-- Career Links Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Parent Career</th>
                                        <th>Child Career</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($careerLinks as $link)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $link->parent?->title ?? '-' }}</td>
                                            <td>{{ $link->child?->title ?? '-' }}</td>

                                            <td>
                                                <a href="{{ route('admin.career_link.edit', $link->id) }}"
                                                    class="btn btn-primary btn-sm">Edit</a>

                                                <form action="{{ route('admin.career_link.destroy', $link->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this link?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No career links found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection