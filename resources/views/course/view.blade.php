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
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row row-gap">
                        <div class="card" style="width: 47%;">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Course Title : {{$course->title}}</li>
                                <li class="list-group-item">Grade Title : {{$detail->grade->title}}</li>
                                <li class="list-group-item">Subject Title : {{$detail->subject->title}}</li>
                                <li class="list-group-item flex-row">
                                    <div class="description-label">Description:</div>
                                    <div class="description-content">{{$course->description}}</div>
                                </li>
                            </ul>
                        </div>

                        <div class="card" style="width: 47%;">
                            <div class="image-group" style="display: flex; gap:5px;">
                                @if($course->image_url)
                                <img src="{{ $course->image_url }}" alt="Current Course Image" class="img-fluid mb-2" style="height: 200px; width:50%;margin-top:16px;border-radius:10px;">
                                @endif
                            </div>
                        </div>

                        <div class="card" style="width:97.5%;">
                            <button type="button" class="btn btn--primary btn--icon ml-24" id="addNoteBtn">
                                <span>Add Note</span>
                            </button>
                            <ul class="list-group list-group-flush" id="notesList">
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
                    <form id="noteForm" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="noteId" name="noteId">
                        <div class="form-group">
                            <label for="noteTitle" class="input-label required">Note</label>
                            <input type="text" name="title" id="noteTitle" class="form-control" placeholder="Note Name" required>
                        </div>
                        <div class="form-group">
                            <label for="images" class="input-label required">Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple>
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
        const courseId = {{$course->id}};
        let isEditMode = false;

        $('#addNoteBtn').on('click', function() {
            isEditMode = false;
            $('#noteModal').show();
            $('#noteForm')[0].reset();
            $('#imagePreview').empty();
            $('#noteId').val('');
            $('#modalTitle').text('Add Notes');
            $('#submitButton').text('Submit');
        });

        $('.close, .btn-cancel').on('click', function() {
            $('#noteModal').hide();
        });

        // Image preview
        $('#images').on('change', function(e) {
            $('#imagePreview').empty();
            let files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').append('<img src="' + e.target.result + '" class="img-thumbnail mr-2 mb-2" style="height: 40px;">');
                }
                reader.readAsDataURL(files[i]);
            }
        });

        // Fetch notes
        function fetchNotes() {
            $.ajax({
                url: '{{ route("admin.note.getNotes") }}',
                type: 'GET',
                data: {
                    course_id: courseId
                },
                success: function(response) {
                    let notesHtml = '';
                    if (response.notes && response.notes.length > 0) {
                        $('#addNoteBtn').hide();
                        response.notes.forEach(function(note) {
                            notesHtml += `
                            <li class="list-group-item flex-cols">
                                <button class="btn btn--primary btn--icon editNoteBtn" data-id="${note.id}" data-title="${note.title}" data-description="${note.description}" data-images='${JSON.stringify(note.images)}' style="align-self: flex-start; margin-bottom: 10px;">
                                    <span>Edit Note</span>
                                </button>
                                <h5> ${note.title}</h5>
                                <div class="note-images">
                                 ${note.images && note.images.length > 0 ? 
                                  note.images.map(images => `<img src="/${images.image_path}" alt="Note Image" style="width: 18%; height: 200px; object-fit: cover; margin-right: 5px;">`).join('')
                                 : 'No images for this note'}
                                </div>
                                <div class="note-description">
                                <p> ${note.description}</p>
                                </div>
                            </li>
                        `;
                        });
                    } else {
                        notesHtml = '<li class="list-group-item">No notes available</li>';
                    }
                    $('#notesList').html(notesHtml);
                },
                error: function(error) {
                    console.error('Error fetching notes:', error);
                    $('#notesList').html(`
                    <li class="list-group-item">Error fetching notes</li>
                `);
                }
            });
        }

        fetchNotes();

        $('#notesList').on('click', '.editNoteBtn', function() {
            isEditMode = true;
            const noteId = $(this).data('id');
            const noteTitle = $(this).data('title');
            const noteDescription = $(this).data('description');
            const noteImages = $(this).data('images');

            $('#noteId').val(noteId);
            $('#noteTitle').val(noteTitle);
            $('#description').val(noteDescription);
            $('#imagePreview').empty();
            if (noteImages && noteImages.length > 0) {
                noteImages.forEach(image => {
                    $('#imagePreview').append(`<img src="/${image.image_path}" class="img-thumbnail mr-2 mb-2" style="height: 40px;">`);
                });
            }
            $('#modalTitle').text('Edit Note');
            $('#submitButton').text('Update');
            $('#noteModal').show();
        });

        $('#noteForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('course_id', courseId);

            let ajaxUrl;
            let ajaxType;

            if (isEditMode) {
                ajaxUrl = '{{ route("admin.note.update", ":id") }}'.replace(':id', $('#noteId').val());
                ajaxType = 'POST';
                formData.append('_method', 'PUT');
            } else {
                ajaxUrl = '{{ route("admin.note.store") }}';
                ajaxType = 'POST';
            }
            // alert(ajaxUrl)

            $.ajax({
                url: ajaxUrl,
                type: ajaxType,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        swal('Success', isEditMode ? 'Note updated successfully!' : 'Note added successfully!', 'success');
                        // alert('test')
                        $('#noteModal').hide();
                        $('#noteForm')[0].reset();
                        $('#imagePreview').empty();
                        fetchNotes();
                        $('#addNoteBtn').hide();
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