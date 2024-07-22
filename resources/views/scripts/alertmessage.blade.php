<script>
    @if(Session::has('message') && Session::get('type') == 'success')
    swal(
        'Success',
        '{{Session::get("message")}}',
        'success'
    )
    @endif

    @if(Session::has('message') && Session::get('type') == 'error')
    swal(
        'Error',
        '{{Session::get("message")}}',
        'error'
    )
    @endif

    this.deleteActionInitial = function() {
        let _this = this
        this.apiRequest = function(id, url, target) {
                swal({
                    title: "Delete?",
                    text: "Please ensure and then confirm!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: !0
                }).then(function(e) {
                    if (e.value === true) {
                        $.ajax({
                            url: url,
                            type: 'get',
                            data: {
                                id: id
                            },
                            beforeSend: function() {
                                // document.querySelector('.ajax-loader')
                                //       .style.display = 'flex';
                            },
                            success: function(response) {
                                // setTimeout(function() {
                                //       $('.ajax-loader').hide()
                                // }, 1500)
                                setTimeout(function() {
                                    if (response.type ===
                                        'success') {
                                        target.parent()
                                            .parent()
                                            .parent()

                                            .remove()
                                        swal("Done!",
                                            response
                                            .message,
                                            "success"
                                        )
                                    } else {
                                        swal("Error!",
                                            'Something went wrong',
                                            "error"
                                        );
                                    }
                                }, 1550)
                            }
                        })
                    } else {
                        e.dismiss;
                    }
                }, function(dismiss) {
                    return false;
                })

            },
            this.deleteEventListener = function() {
                $(document).on('click', '.deleteAction', function(event) {
                    event.preventDefault()
                    let url = $(this).attr('href'),
                        id = url.split('/').pop()
                    console.log(url)
                    _this.apiRequest(id, url, $(this))
                })

            },
            this.init = function() {
                _this.deleteEventListener()
            }
    }
    let deleteActionObj = new deleteActionInitial()
    deleteActionObj.init()
</script>