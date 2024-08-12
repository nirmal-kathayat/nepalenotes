@extends('layouts.default')
@section('content')
<section class="inner-section-wrapper">
    <div class="bg-white menu">
        <div class="title-wrapper flex">
            <div class="page-title">Course Lists</div>
            <div class="flex align-items-center">
                <button class="btn btn--primary btn--icon ml-24" id="addFacultyBtn">
                    <span>Add Course</span>
                </button>
            </div>
        </div>

        <div class="container">
            <div class="table-wrapper">
                <table id="courseTable" class="display" style="width:100%">
                    <thead>
                        <tr class="table-header">
                            <th>S.no</th>
                            <th>Grade</th>
                            <th>Subject</th>
                            <th>Course</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="facultyModal" class="modal">
        <div class="modal-content modal-course">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Courses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="facultyForm">
                    @csrf
                    <input type="hidden" id="facultyId" name="facultyId">
                    <div class="form-group">
                        <label for="grade_id" class="input-label required">Grade</label>
                        <select name="grade_id" id="grade_id">
                            <option value="">Select the Grade</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject_id" class="input-label required">Subject</label>
                        <select name="subject_id" id="subject_id">
                            <option value="">Select the subject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title" class="input-label required">Course</label>
                        <input type="text" name="title" id="facultyTitle" class="form-control" placeholder="Course Name" required>
                    </div>

                    <div class="form-group">
                        <label class="input-label">Cover Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <img id="currentImage" src="" alt="Current Image" style="max-width: 50px; display: none; height: 30px;">
                    </div>

                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" style="height: 100px;" id="description" placeholder="Course Description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn-action-primary" id="submitButton">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('scripts.validation')
<script>
    $(document).ready(function() {
        var isEditing = false;

        // Initialize DataTable
        var table = $('#courseTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.course') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'grade_title',
                    name: 'grades.title',
                    orderable: false
                },
                {
                    data: 'subject_title',
                    name: 'subjects.title',
                    orderable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: false
                },
                {
                    data: 'description',
                    name: 'courses.description',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {

                        var viewButton = '<a href="' + "{{ route('admin.course.view', ':id') }}".replace(':id', full.id) + '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';

                        var editButton = '<button class="btn btn-secondary btn-sm edit-faculty" data-id="' + full.id + '"><i class="fas fa-edit"></i></button>';
                        var deleteButton = '<button class="btn btn-danger btn-sm delete-faculty" data-id="' + full.id + '"><i class="fa fa-trash"></i></button>';
                        return '<div style="display:flex;">' + viewButton + ' ' + editButton + ' ' + deleteButton + '</div>';
                    }
                }
            ]
        });

        // course
        $('#grade_id').on('change', function() {
            var gradeId = $(this).val();
            if (gradeId) {
                $.ajax({
                    url: "{{ route('admin.get-subjects') }}",
                    type: 'GET',
                    data: {
                        grade_id: gradeId
                    },
                    success: function(data) {
                        $('#subject_id').empty();
                        $('#subject_id').append('<option value="">Select the subject</option>');
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id + '">' + value.title + '</option>');
                        });
                        $('#subject_id').prop('disabled', false);
                    }
                });
            } else {
                $('#subject_id').empty();
                $('#subject_id').append('<option value=""></option>');
                $('#subject_id').prop('disabled', true);
            }
        });


        // Open modal for adding 
        $('#addFacultyBtn').on('click', function() {
            openModal(false);
        });

        // Open modal for editing
        $(document).on('click', '.edit-faculty', function() {
            var facultyId = $(this).data('id');
            openModal(true, facultyId);
        });

        // Delete 
        $(document).on('click', '.delete-faculty', function() {
            var facultyId = $(this).data('id');
            deleteFaculty(facultyId);
        });

        // Form submission (both add and edit)
        $('#facultyForm').on('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        // Close modal functions
        $('.close, .btn-cancel').on('click', closeModal);
        $(window).on('click', function(event) {
            if ($(event.target).is('#facultyModal')) {
                closeModal();
            }
        });

        function openModal(editing, facultyId = null) {
            isEditing = editing;
            $('#modalTitle').text(editing ? 'Edit Course' : 'Add Course');
            $('#submitButton').text(editing ? 'Update' : 'Submit');
            $('#facultyId').val(facultyId);
            $('#facultyForm')[0].reset();

            if (editing) {
                fetchFacultyData(facultyId);
            }

            $('#facultyModal').show();
        }

        function closeModal() {
            $('#facultyModal').hide();
            $('#facultyForm')[0].reset();
        }

        
        function fetchFacultyData(facultyId) {
            $.ajax({
                url: "{{ route('admin.course.edit', ':id') }}".replace(':id', facultyId),
                method: 'GET',
                success: function(response) {
                    $('#grade_id').val(response.grade_id);
                    $('#facultyTitle').val(response.title);
                    $('#description').val(response.description);

                    // Fetch subjects for the selected grade
                    $.ajax({
                        url: "{{ route('admin.get-subjects') }}",
                        type: 'GET',
                        data: {
                            grade_id: response.grade_id
                        },
                        success: function(subjects) {
                            $('#subject_id').empty();
                            $('#subject_id').append('<option value="">Select the subject</option>');
                            $.each(subjects, function(key, value) {
                                $('#subject_id').append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                            $('#subject_id').prop('disabled', false);

                            // Set the selected subject after the dropdown is populated
                            $('#subject_id').val(response.subject_id);
                        }
                    });

                    // Display the current image
                    if (response.image_url) {
                        $('#currentImage').attr('src', response.image_url).show();
                    } else {
                        $('#currentImage').hide();
                    }
                },
                error: function(xhr) {
                    swal('Error', 'Failed to fetch course data', 'error');
                }
            });
        }



        function submitForm() {
            var url = isEditing ?
                "{{ route('admin.course.update', ':id') }}".replace(':id', $('#facultyId').val()) :
                "{{ route('admin.course.store') }}";

            var method = isEditing ? 'POST' : 'POST';

            var formData = new FormData($('#facultyForm')[0]);

            if (isEditing) {
                formData.append('_method', 'POST');
            }
            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    closeModal();
                    table.ajax.reload();
                    swal('Success', isEditing ? 'Course updated successfully!' : 'Course added successfully!', 'success');
                },
                error: function(xhr) {
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    swal('Error', errorMessage, 'error');
                }
            });
        }

        function deleteFaculty(facultyId) {
            $.ajax({
                url: "{{ route('admin.course.delete', ':id') }}".replace(':id', facultyId),
                method: 'GET',
                success: function(response) {
                    table.ajax.reload();
                    swal('Success', 'Course deleted successfully!', 'success');
                },
                error: function(xhr) {
                    swal('Error', 'Failed to delete Course', 'error');
                }
            });
        }

    });
</script>
@endpush