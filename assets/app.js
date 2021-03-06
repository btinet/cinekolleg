/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// Import Twitter Boostrap
import * as bootstrap from 'bootstrap';


// start the Stimulus application
import './bootstrap';
import Chocolat from "chocolat";


(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
    let images = document.querySelectorAll('img')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })



            Array.prototype.slice.call(images)
                .forEach(function (image) {
                    image.classList.add('img-fluid')
                })




})()



document.addEventListener("DOMContentLoaded", function(event) {
    Chocolat(document.querySelectorAll('.chocolat-parent .chocolat-image'))
})
