
    $("document").ready(function () {
        $("body").click(function () {
            var click = event.target.id;
            switch (click) {

                case ("nameTester"):
                    $("#nameTester").val("");
                    break;

                case ("runTest"):


                    var data = [];
                    data["nameTester"] = $("#nameTester").val();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/response.php',
                        data: {
                            nameTester: data ["nameTester"]
                        },
                        success: function (data) {
                            $('#results').html(data);
                        }
                    });
                    $("#image").attr("src", '');
                    break;
            }
        });
    });