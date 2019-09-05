<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
</head>
<form action="" id="card-form">

</form>
<body>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
    <script>
        Conekta.setPublicKey("{{env('CONEKTA_API_PUBLIC')}}");

        var conektaSuccessResponseHandler = function (token) {

            
            let $form = $("#card-form");
            
            //Inserta el token_id en la forma para que se envíe al servidor
            $form.append($('<input type="hidden" name="conektaTokenId" id="conektaTokenId">').val(token.id));
            $form.get(0).submit(); //Hace submit
        };
        var conektaErrorResponseHandler = function (response) {
            var $form = $("#card-form");
            $form.find(".card-errors").text(response.message_to_purchaser);
            $form.find("button").prop("disabled", false);
        };

        //jQuery para que genere el token después de dar click en submit
        $(function () {
            $("#card-form").submit(function (event) {
                var $form = $(this);
                // Previene hacer submit más de una vez
                $form.find("button").prop("disabled", true);
                Conekta.Token.create($form, conektaSuccessResponseHandler, conektaErrorResponseHandler);
                return false;
            });
        });
    </script>
</body>

</html>