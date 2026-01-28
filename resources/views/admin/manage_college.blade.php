<div class="modal fade" id="editCollegeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form method="POST" enctype="multipart/form-data" id="editCollegeForm">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_college_id">

                    {{-- BASIC DETAILS --}}
                    <h5 class="mb-3">Basic College Information</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">College Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Street *</label>
                            <input type="text" name="street" id="edit_street" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">District *</label>
                            <input type="text" name="district" id="edit_district" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">State *</label>
                            <input type="text" name="state" id="edit_state" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rating</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating" id="edit_rating" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" id="edit_phone" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" id="edit_website" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">About College</label>
                            <textarea name="about" rows="4" id="edit_about" class="form-control"></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- IMAGES --}}
                    <h5 class="mb-3">College Images</h5>

                    <div class="mb-3">
                        <input type="file" id="edit_imageInput" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Select additional images</small>
                    </div>

                    <div id="edit_imagePreview" class="row g-3"></div>

                    <hr class="my-4">

                    {{-- FACILITIES --}}
                    <h5 class="mb-3">Facilities</h5>
                    <div id="edit-facility-wrapper"></div>
                    <button type="button" class="btn btn-outline-primary mb-3" onclick="addEditFacility()">Add Facility</button>

                    <hr class="my-4">

                    {{-- COURSES --}}
                    <h5 class="mb-3">Courses</h5>
                    <div id="edit-course-wrapper"></div>
                    <button type="button" class="btn btn-outline-primary mb-4" onclick="addEditCourse()">Add Course</button>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update College</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const location = this.dataset.location.split(',');

            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_street').value = location[0]?.trim() || '';
            document.getElementById('edit_district').value = location[1]?.trim() || '';
            document.getElementById('edit_state').value = location[2]?.trim() || '';
            document.getElementById('edit_rating').value = this.dataset.rating;
            document.getElementById('edit_phone').value = this.dataset.phone;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_website').value = this.dataset.website;
            document.getElementById('edit_about').value = this.dataset.about;

            document.getElementById('editCollegeForm').action =
                "{{ route('admin.college.update', ':id') }}".replace(':id', id);

            // Clear previous facilities & courses
            document.getElementById('edit-facility-wrapper').innerHTML = '';
            document.getElementById('edit-course-wrapper').innerHTML = '';

            // Fetch existing facilities & courses via route
            fetch("{{ route('admin.college.edit-json', ':id') }}".replace(':id', id))
                .then(res => res.json())
                .then(data => {
                    data.facilities.forEach(f => addEditFacility(f.facility));
                    data.courses.forEach(c => addEditCourse(c.name));
                });
        });
    });

    // Image preview
    document.getElementById('edit_imageInput').addEventListener('change', function () {
        const preview = document.getElementById('edit_imagePreview');
        preview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail mb-1 me-1';
                img.style.width = '60px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

});

// Add facility input
function addEditFacility(value = '') {
    const wrapper = document.getElementById('edit-facility-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="facilities[]" class="form-control" value="${value}">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Remove</button>
    `;
    wrapper.appendChild(div);
}

// Add course input
function addEditCourse(value = '') {
    const wrapper = document.getElementById('edit-course-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="courses[]" class="form-control" value="${value}">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Remove</button>
    `;
    wrapper.appendChild(div);
}
</script>
