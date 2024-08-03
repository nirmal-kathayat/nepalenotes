@extends('layouts.default')

@section('content')
<section class="inner-section-wrapper">
    <div class="bg-white menu">
        <div class="title-wrapper flex">
            <div class="page-title">Course Details</div>
            <div class="flex align-items-center">
                <a href="{{ route('admin.course') }}">
                    <button class="btn btn--primary btn--icon ml-24">
                        <span>Back To Course</span>
                    </button>
                </a>
            </div>
        </div>

        <div class="container">
            <div class="course-details">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row row-gap">
                        <div class="card" style="width: 48%;">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Course Title : {{$course->title}}</li>
                                <li class="list-group-item">Grade Title : {{$detail->grade->title}}</li>
                                <li class="list-group-item">Subject Title : {{$detail->subject->title}}</li>
                                <li class="list-group-item">Description : {{$course->description}}</li>
                            </ul>
                        </div>

                        <div class="card" style="width: 48%;">
                            <div class="image-group" style="display: flex; gap:5px;">
                                @if($course->image_url)
                                <img src="{{ $course->image_url }}" alt="Current Course Image" class="img-fluid mb-2" style="height: 200px; width:50%;margin-top:16px;border-radius:10px;">
                                @endif
                                @if($course->image_url)
                                <img src="{{ $course->image_url }}" alt="Current Course Image" class="img-fluid mb-2" style="height: 200px; width:50%;margin-top:16px;border-radius:10px;">
                                @endif
                            </div>
                        </div>

                        <div class="card" style="width: 48%;">
                            <ul class="list-group list-group-flush">
                                <button type="button" class=" btn btn--primary btn--icon ml-24" id="addNoteBtn">
                                    <span>Add Note</span>
                                </button>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- modal -->
        <div id="noteModal" class="modal" style="display: none;">
            <div class="modal-content modal-notes">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="noteForm">
                        @csrf
                        <input type="hidden" id="noteId" name="noteId">
                        <div class="form-group">
                            <label for="noteTitle" class="input-label required">Note</label>
                            <input type="text" name="title" id="noteTitle" class="form-control" placeholder="Note Name" required>
                        </div>
                        <div class="form-group">
                            <label for="images" class="input-label required">Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple required>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="input-label required">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn-action-primary" id="submitButton">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('scripts.validation')
<script>
    $(document).ready(function() {
        $('#addNoteBtn').on('click', function() {
            $('#noteModal').show();
        });

        $('.close, .btn-cancel').on('click', function() {
            $('#noteModal').hide();
        });

        tinymce.init({
            selector: '#description',
            height: 200,
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
        });
        // image
        $('#images').on('change', function(e) {
            $('#imagePreview').empty();
            let files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').append('<img src="' + e.target.result + '" class="img-thumbnail mr-2 mb-2" style="height: 50px;">');
                }
                reader.readAsDataURL(files[i]);
            }
        });
        $('#noteForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '{{ route("admin.note.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.success) {
                        swal('Success', 'Note added successfully!', 'success');
                        $('#noteModal').hide();
                        $('#noteForm')[0].reset();
                        $('#imagePreview').empty();
                    } else {
                        swal('Error', 'Something went wrong!', 'error');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                    swal('Error', 'Something went wrong!', 'error');
                }
            });
        });
    });
</script>
@endpush