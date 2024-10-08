@extends('layouts.default')
@section('content')
<section class="inner-section-wrapper">
    <div class="bg-white menu">
        <div class="title-wrapper flex">
            <div class="page-title">Faculty Lists</div>
            <div class="flex align-items-center">
                <button class="btn btn--primary btn--icon ml-24" id="addFacultyBtn">
                    <span>Add Faculty</span>
                </button>
            </div>
        </div>

        <div class="container">
            <div class="table-wrapper">
                <table id="facultyTable" class="display" style="width:100%">
                    <thead>
                        <tr class="table-header">
                            <th>S.no</th>
                            <th>Grade</th>
                            <th>Faculty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="facultyModal" class="modal">
        <div class="modal-content modal-faculty">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Faculty</h5>
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
                        <select name="grade_id" id="grade_id" required>
                            <option value="">Select the Grade</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" data-can-have-faculty="{{ $grade->can_have_faculty }}">{{ $grade->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title" class="input-label required">Faculty</label>
                        <input type="text" name="title" id="facultyTitle" class="form-control" placeholder="Faculty Name" required>
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
        var table = $('#facultyTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.faculty') }}",
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
            $('#modalTitle').text(editing ? 'Edit Faculty' : 'Add Faculty');
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
                url: "{{ route('admin.faculty.edit', ':id') }}".replace(':id', facultyId),
                method: 'GET',
                success: function(response) {
                    $('#grade_id').val(response.grade_id);
                    $('#facultyTitle').val(response.title);
                },
                error: function(xhr) {
                    swal('Error', 'Failed to fetch faculty data', 'error');
                }
            });
        }
        // faculty modal change
        // $('#grade_id').on('change', updateFormFields);

        // function updateFormFields() {
        //     var selectedOption = $('#grade_id').find('option:selected');
        //     var gradeValue = parseInt(selectedOption.text().match(/\d+/));

        //     if (gradeValue >= 10) {
        //         $('#facultyTitle').prop('disabled', false).show();
        //         $('label[for="facultyTitle"]').show();
        //     } else {
        //         $('#facultyTitle').prop('disabled', true).hide();
        //         $('label[for="facultyTitle"]').hide();
        //     }
        // }

        function submitForm() {
            // var selectedGrade = $('#grade_id option:selected');
            // var gradeValue = parseInt(selectedGrade.text().match(/\d+/));
            // var formData = {
            //     grade_id: $('#grade_id').val(),
            //     title: gradeValue >= 10 ? $('#facultyTitle').val() : null
            // };


            var url = isEditing ?
                "{{ route('admin.faculty.update', ':id') }}".replace(':id', $('#facultyId').val()) :
                "{{ route('admin.faculty.store') }}";

            var method = isEditing ? 'post' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $('#facultyForm').serialize(),
                success: function(response) {
                    closeModal();
                    table.ajax.reload();
                    swal('Success', isEditing ? 'Faculty updated successfully!' : 'Faculty added successfully!', 'success');
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
                url: "{{ route('admin.faculty.delete', ':id') }}".replace(':id', facultyId),
                method: 'GET',
                success: function(response) {
                    table.ajax.reload();
                    swal('Success', 'Faculty deleted successfully!', 'success');
                },
                error: function(xhr) {
                    swal('Error', 'Failed to delete faculty', 'error');
                }
            });
        }
    });
</script>
@endpush