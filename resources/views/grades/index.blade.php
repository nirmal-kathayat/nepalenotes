@extends('layouts.default')
@section('content')
<section class="inner-section-wrapper">
    <div class="bg-white menu">
        <div class="title-wrapper flex">
            <div class="page-title">Grade Lists</div>
            <div class="flex align-items-center">
                <button class="btn btn--primary btn--icon ml-24" id="addGradeBtn">
                    <svg class="icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.5 4.16663V15.8333" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.66669 10H16.3334" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Add Grade</span>
                </button>
            </div>
        </div>

        <div class="container">
            <div class="table-wrapper">
                <table id="gradeTable" class="display" style="width:100%">
                    <thead>
                        <tr class="table-header">
                            <th>S.no</th>
                            <th>Grade</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="gradeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="gradeForm">
                    @csrf
                    <input type="hidden" id="gradeId" name="gradeId">
                    <div class="form-group">
                        <label for="title" class="input-label required">Grade</label>
                        <input type="number" name="title" id="gradeTitle" class="form-control" placeholder="Grade Name" required>
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
        var table = $('#gradeTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.grade') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return meta.row + 1;
                    }
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
                        var editButton = '<button class="btn btn-secondary btn-sm edit-grade" data-id="' + full.id + '"><i class="fas fa-edit"></i></button>';
                        var deleteButton = '<button class="btn btn-danger btn-sm delete-grade" data-id="' + full.id + '"><i class="fa fa-trash"></i></button>';
                        return '<div style="display:flex;">' + editButton + ' ' + deleteButton + '</div>';
                    }
                }
            ]
        });

        // Open modal for adding new grade
        $('#addGradeBtn').on('click', function() {
            openModal(false);
        });

        // Open modal for editing grade
        $(document).on('click', '.edit-grade', function() {
            var gradeId = $(this).data('id');
            openModal(true, gradeId);
        });

        // Delete grade
        // Replace the existing delete grade event listener with this:
        $(document).on('click', '.delete-grade', function() {
            var gradeId = $(this).data('id');
            deleteGrade(gradeId);
        });

        // Update the deleteGrade function
        function deleteGrade(gradeId) {
            $.ajax({
                url: "{{ route('admin.grade.delete', ':id') }}".replace(':id', gradeId),
                method: 'GET',
                success: function(response) {
                    table.ajax.reload();
                    // Using SweetAlert for success message
                    swal('Success', 'Grade deleted successfully!', 'success');
                },
                error: function(xhr) {
                    // Using SweetAlert for error message
                    swal('Error', 'Failed to delete grade', 'error');
                }
            });
        }

        // Form submission (both add and edit)
        $('#gradeForm').on('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        // Close modal functions
        $('.close, .btn-cancel').on('click', closeModal);
        $(window).on('click', function(event) {
            if ($(event.target).is('#gradeModal')) {
                closeModal();
            }
        });

        function openModal(editing, gradeId = null) {
            isEditing = editing;
            $('#modalTitle').text(editing ? 'Edit Grade' : 'Add Grade');
            $('#submitButton').text(editing ? 'Update' : 'Submit');
            $('#gradeId').val(gradeId);
            $('#gradeForm')[0].reset();

            if (editing) {
                fetchGradeData(gradeId);
            }

            $('#gradeModal').show();
        }

        function closeModal() {
            $('#gradeModal').hide();
            $('#gradeForm')[0].reset();
        }

        function fetchGradeData(gradeId) {
            $.ajax({
                url: "{{ route('admin.grade.edit', ':id') }}".replace(':id', gradeId),
                method: 'GET',
                success: function(response) {
                    $('#gradeTitle').val(response.title);
                },
                error: function(xhr) {
                    swal('Error', 'Failed to fetch grade data', 'error');
                }
            });
        }

        function submitForm() {
            var url = isEditing ?
                "{{ route('admin.grade.update', ':id') }}".replace(':id', $('#gradeId').val()) :
                "{{ route('admin.grade.store') }}";

            var method = isEditing ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $('#gradeForm').serialize(),
                success: function(response) {
                    closeModal();
                    table.ajax.reload();
                    swal('Success', isEditing ? 'Grade updated successfully!' : 'Grade added successfully!', 'success');
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

        
    });
</script>
@endpush