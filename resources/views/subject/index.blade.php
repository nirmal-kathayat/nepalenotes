@extends('layouts.default')
@section('content')
<section class="inner-section-wrapper">
    <div class="bg-white menu">
        <div class="title-wrapper flex">
            <div class="page-title">Subject Lists</div>
            <div class="flex align-items-center">
                <button class="btn btn--primary btn--icon ml-24" id="addFacultyBtn">
                    <svg class="icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.5 4.16663V15.8333" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.66669 10H16.3334" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Add Subject</span>
                </button>
            </div>
        </div>

        <div class="container">
            <div class="table-wrapper">
                <table id="subjectTable" class="display" style="width:100%">
                    <thead>
                        <tr class="table-header">
                            <th>S.no</th>
                            <th>Grade</th>
                            <th>Faculty</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="facultyModal" class="modal">
        <div class="modal-content modal-subject">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Subject</h5>
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
                        <label for="faculty_id" class="input-label required">Faculty</label>
                        <select name="faculty_id" id="faculty_id">
                            <option value="">Select the Faculty</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title" class="input-label required">Subject</label>
                        <input type="text" name="title" id="facultyTitle" class="form-control" placeholder="Subject Name" required>
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
        var table = $('#subjectTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.subject') }}",
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
                    name: 'grade_title',
                    orderable: false
                },
                {
                    data: 'faculty_title',
                    name: 'faculty_title',
                    orderable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        var editButton = '<button class="btn btn-secondary btn-sm edit-faculty" data-id="' + full.id + '"><i class="fas fa-edit"></i></button>';
                        var deleteButton = '<button class="btn btn-danger btn-sm delete-faculty" data-id="' + full.id + '"><i class="fa fa-trash"></i></button>';
                        return '<div style="display:flex;">' + editButton + ' ' + deleteButton + '</div>';
                    }
                }
            ]
        });

        // faculty
        function populateFaculties(gradeId) {
            $.ajax({
                url: "{{ route('admin.faculties-by-grade', ':gradeId') }}".replace(':gradeId', gradeId),
                method: 'GET',
                success: function(response) {
                    var facultySelect = $('#faculty_id');
                    facultySelect.empty();
                    facultySelect.append('<option value="">Select the Faculty</option>');
                    $.each(response, function(key, value) {
                        facultySelect.append('<option value="' + value.id + '">' + value.title + '</option>');
                    });
                },
                error: function(xhr) {
                    swal('Error', 'Failed to load faculties', 'error');
                }
            });
        }

        $('#grade_id').on('change', function() {
            var gradeId = $(this).val();
            if (gradeId) {
                populateFaculties(gradeId);
            } else {
                $('#faculty_id').empty().append('<option value="">Select the Faculty</option>');
            }
        });
        // Open modal for adding new faculty
        $('#addFacultyBtn').on('click', function() {
            openModal(false);
        });

        // Open modal for editing faculty
        $(document).on('click', '.edit-faculty', function() {
            var facultyId = $(this).data('id');
            openModal(true, facultyId);
        });

        // Delete faculty
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
            $('#modalTitle').text(editing ? 'Edit Subject' : 'Add Subject');
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
                url: "{{ route('admin.subject.edit', ':id') }}".replace(':id', facultyId),
                method: 'GET',
                success: function(response) {
                    $('#grade_id').val(response.grade_id).trigger('change');
                    setTimeout(function() {
                        $('#faculty_id').val(response.faculty_id);
                    }, 500);
                    $('#facultyTitle').val(response.title);
                },
                error: function(xhr) {
                    swal('Error', 'Something went wrong', 'error');
                }
            });
        }

        function submitForm() {
            var url = isEditing ?
                "{{ route('admin.subject.update', ':id') }}".replace(':id', $('#facultyId').val()) :
                "{{ route('admin.subject.store') }}";

            var method = isEditing ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $('#facultyForm').serialize(),
                success: function(response) {
                    closeModal();
                    table.ajax.reload();
                    swal('Success', isEditing ? 'Subject updated successfully!' : 'Subject added successfully!', 'success');
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
                url: "{{ route('admin.subject.delete', ':id') }}".replace(':id', facultyId),
                method: 'GET',
                success: function(response) {
                    table.ajax.reload();
                    swal('Success', 'Subject deleted successfully!', 'success');
                },
                error: function(xhr) {
                    swal('Error', 'Failed to delete subject', 'error');
                }
            });
        }
    });
</script>
@endpush