<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <div>
        <form id="form-checkout" >
            <input type="text" name="cardNumber" id="form-checkout__cardNumber" />
            <input type="text" name="cardExpirationMonth" id="form-checkout__cardExpirationMonth" />
            <input type="text" name="cardExpirationYear" id="form-checkout__cardExpirationYear" />
            <input type="text" name="cardholderName" id="form-checkout__cardholderName"/>
            <input type="email" name="cardholderEmail" id="form-checkout__cardholderEmail"/>
            <input type="text" name="securityCode" id="form-checkout__securityCode" />
            <select name="issuer" id="form-checkout__issuer"></select>
            <select name="identificationType" id="form-checkout__identificationType"></select>
            <input type="text" name="identificationNumber" id="form-checkout__identificationNumber"/>
            <select name="installments" id="form-checkout__installments"></select>
            <button type="submit" id="form-checkout__submit">Pagar</button>
            <progress value="0" class="progress-bar">Cargando...</progress>
        </form>
        </div>
     <!-- Add step #2 -->
   <script>
       const mp = new MercadoPago('APP_USR-f5f66768-e352-4c64-b855-d122a05da144');

       const cardForm = mp.cardForm({
            amount: "50",
            autoMount: true,
            form: {
                id: "form-checkout",
                cardholderName: {
                id: "form-checkout__cardholderName",
                placeholder: "Titular de la tarjeta",
                },
                cardholderEmail: {
                id: "form-checkout__cardholderEmail",
                placeholder: "E-mail",
                },
                cardNumber: {
                id: "form-checkout__cardNumber",
                placeholder: "N�mero de la tarjeta",
                },
                cardExpirationMonth: {
                id: "form-checkout__cardExpirationMonth",
                placeholder: "Mes de vencimiento",
                },
                cardExpirationYear: {
                id: "form-checkout__cardExpirationYear",
                placeholder: "A�o de vencimiento",
                },
                securityCode: {
                id: "form-checkout__securityCode",
                placeholder: "C�digo de seguridad",
                },
                installments: {
                id: "form-checkout__installments",
                placeholder: "Cuotas",
                },
                identificationType: {
                id: "form-checkout__identificationType",
                placeholder: "Tipo de documento",
                },
                identificationNumber: {
                id: "form-checkout__identificationNumber",
                placeholder: "N�mero de documento",
                },
                issuer: {
                id: "form-checkout__issuer",
                placeholder: "Banco emisor",
                },
            },
            callbacks: {
                onFormMounted: error => {
                if (error) return console.warn("Form Mounted handling error: ", error);
                console.log("Form mounted");
                },
                onSubmit: event => {
                    event.preventDefault();

                    const {
                        paymentMethodId: payment_method_id,
                        issuerId: issuer_id,
                        cardholderEmail: email,
                        amount,
                        token,
                        installments,
                        identificationNumber,
                        identificationType,
                    } = cardForm.getCardFormData();

                    fetch("./process_payment.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            token,
                            issuer_id,
                            payment_method_id,
                            transaction_amount: Number(amount),
                            installments: Number(installments),
                            description: "Descripci�n del producto",
                            payer: {
                                email,
                                identification: {
                                    type: identificationType,
                                    number: identificationNumber,
                                },
                            },
                        }),
                    /*}).then(function(response) {
                        return response.json();*/
                    }).then(result => {
                        alert(result.message)
                        /*document.getElementById("payment-status").innerText = result.status;
                        document.getElementById("payment-detail").innerText = result.message;
                        $('.container__payment').fadeOut(500);
                        setTimeout(() => { $('.container__result').show(500).fadeIn(); }, 500);*/
                })
                },
                onFetching: (resource) => {
                    console.log("Fetching resource: ", resource);

                    // Animate progress bar
                    const progressBar = document.querySelector(".progress-bar");
                    progressBar.removeAttribute("value");

                    return () => {
                        progressBar.setAttribute("value", "0");
                    };
                },
            },
            });
       // Add step #3
   </script>
</body>
</html>