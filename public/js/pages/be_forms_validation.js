/*
 *  Document   : be_forms_validation.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Forms Validation Page
 */

// Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
class pageFormsValidation {
    /*
     * Init Validation functionality
     *
     */
    static initValidation() {
        // Load default options for jQuery Validation plugin
        Dashmix.helpers('validation');

        // Init Form Validation
        jQuery('.js-validation').validate({
            ignore: [],
            rules: {
                'name': {
                    required: true,
                    minlength: 3
                },
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 5
                },
                'confirm-password': {
                    required: true,
                    equalTo: '#password'
                },
                'suggestions': {
                    required: true,
                    minlength: 5
                },
                'address': {
                    required: true,
                    minlength: 5
                },
                'skill': {
                    required: true
                },
                'currency': {
                    required: true,
                    currency: ['$', true]
                },
                'website': {
                    required: true,
                    url: true
                },
                'telno': {
                    required: true,
                    phoneUS: true
                },
                'digits': {
                    required: true,
                    digits: true
                },
                'number': {
                    required: true,
                    number: true
                },
                'range': {
                    required: true,
                    range: [1, 5]
                },
                'terms': {
                    required: true
                },
                'select2': {
                    required: true
                },
                'select2-multiple': {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                'name': {
                    required: 'Please enter a name',
                    minlength: 'Your name must consist of at least 3 characters'
                },
                'email': 'Please enter a valid email address',
                'password': {
                    required: 'Please provide a password',
                    minlength: 'Your password must be at least 5 characters long'
                },
                'confirm-password': {
                    required: 'Please provide a password',
                    minlength: 'Your password must be at least 5 characters long',
                    equalTo: 'Please enter the same password as above'
                },
                'select2': 'Please select a value!',
                'select2-multiple': 'Please select at least 2 values!',
                'suggestions': 'What can we do to become better?',
                'address': 'Please enter your address!',
                'skill': 'Please select a skill!',
                'currency': 'Please enter a price!',
                'website': 'Please enter your website!',
                'telno': 'Please enter a US phone!',
                'digits': 'Please enter only digits!',
                'number': 'Please enter a number!',
                'range': 'Please enter a number between 1 and 5!',
                'terms': 'You must agree to the service terms!'
            }
        });

        // Init Validation on Select2 change
        jQuery('.js-select2').on('change', e => {
            jQuery(e.currentTarget).valid();
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidation();
    }
}

// Initialize when page loads
jQuery(() => { pageFormsValidation.init(); });
