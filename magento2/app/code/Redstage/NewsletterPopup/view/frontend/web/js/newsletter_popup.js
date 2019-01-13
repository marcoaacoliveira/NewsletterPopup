require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function(
        $,
        modal
    ) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            buttons: [{
                text: $.mage.__('Send now'),
                class: 'mymodal1',
                click: function () {
                    var email = $("#email").val();
                    var name = $("#name").val();
                    var phone = $("#phone").val();
                    var formKey = $("input[name=form_key]").val();
                    $.ajax({
                        url: "/newsletterpopup/newsletter/save",
                        method: "POST",
                        data: {
                            email: email,
                            name: name,
                            phone: phone,
                            form_key: formKey
                        }
                    }).done(function (response) {
                        $("#response_ajax").text(response.message);
                        if(response.saved==="true") {
                            setTimeout(function () {
                                $("#popup-modal").modal("closeModal");
                            },3000);
                        }
                    });
                }
            }]
        };

        var popup = modal(options, $('#popup-modal'));
        $(document).ready(function(){
            $("#popup-modal").modal("openModal");
        });
    }
);