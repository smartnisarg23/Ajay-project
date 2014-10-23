$(document).ready(function () {
    $(".validate").validate({
        errorPlacement: function (error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            }
            else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        },
        rules: {
            email: {
                required: true
            },
            password: {                
                minlength: 8
            }
        },
        messages: {
            email: {
                required: "Please Enter Email or Username"
            },
            firstname: {
                required: "Please Enter Firstname"
            },
            lastname: {
                required: "Please Enter Lastname"
            },
            username: {
                required: "Please Enter Username"
            },
            userEmail: {
                required: "Please Enter Email",
                email: "Please Enter Email",
            },
            role: {
                required: "Please Enter Role"
            },
            status: {
                required: "Please Enter Status"
            },
            password: {
                required: "Please Enter Password",
                minlength: "Please Enter Password with 8 Characters"
            },
            address1: {
                required: "Please Enter Address",
            },
            city: {
                required: "Please Enter City",
            },
            state: {
                required: "Please Enter State",
            },
            zipcode: {
                required: "Please Enter Zipcode",
            },
            phone1: {
                required: "Please Enter Phone",
            }
        }
    });

    $(function () {
        
        $('#example1').dataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": false
        });
    });
});