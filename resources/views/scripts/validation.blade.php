<script>
    this.validationInitial = function() {
        let _this = this
        this.createNewElement = function(element, message) {
                element.classList.add('text-danger')
                element.innerHTML = '<i class="fa fa-times-circle"></i>&nbsp;' + message
                return element
            },
            this.validationCondition = function(target, validationType, value) {
                let message = {
                    required: 'This field is required',
                    email: 'Invalid email format',
                    confirmation: 'Confirm password did not match'

                };
                let createElement = document.createElement('span')
                if (value == '' && validationType.includes('required')) {
                    event.preventDefault()
                    target.append(_this.createNewElement(createElement, message.required))
                }
                if (validationType.includes('email') && value) {
                    let mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                    if (!value.match(mailformat)) {
                        event.preventDefault()
                        target.append(_this.createNewElement(createElement, message.email))
                    }
                }
                if (validationType.includes('confirm') && value) {
                    let confirmInput = document.querySelector('input[name="password_confirmation"]')
                    if (confirmInput) {
                        if (confirmInput.value) {
                            if (value != confirmInput.value) {
                                event.preventDefault()
                                target.append(_this.createNewElement(createElement, message.confirmation))
                            }
                        }
                    }
                }
            },
            this.helperFunction = function(selector) {
                selector.forEach(function(target, key) {
                    let input = target.querySelector('input') ?? target.querySelector('select')
                    if (input != null && input.hasAttribute('data-validation')) {
                        let validation = input.getAttribute('data-validation'),
                            validationType = validation.split('|')
                        value = input.value,
                            validationErrorMessage = target.querySelector('.text-danger')
                        if (validationErrorMessage || value && validationErrorMessage) {
                            validationErrorMessage.remove()
                        }
                        if (validationType) {
                            _this.validationCondition(target, validationType, value)
                        }
                    }

                })

            },
            this.validationeventListener = function(selector) {
                let formGroup = selector.querySelectorAll('.form-group')
                selector.addEventListener('submit', function(event) {
                    _this.helperFunction(formGroup)
                })
                selector.addEventListener('input', function(event) {
                    _this.helperFunction(formGroup)
                })
            },
            this.init = function() {
                let form = document.querySelector('.form-data')
                if (form) {
                    _this.validationeventListener(form)
                }
            }
    }
    let validationObj = new validationInitial()
    validationObj.init()
</script>