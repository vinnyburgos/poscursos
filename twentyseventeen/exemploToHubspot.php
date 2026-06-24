<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
</head>
<body>

    <form id="cadastro-form">
        <input type="text" id="nome" name="nome" placeholder="Nome Completo" required><br>
        <input type="text" id="cpf" name="cpf" placeholder="CPF" required><br>
        <input type="email" id="email" name="email" placeholder="E-mail" required><br>
        <input type="email" id="confirmar_email" name="confirmar_email" placeholder="Confirmar E-mail" required><br>
        <input type="text" id="telefone" name="telefone" placeholder="Telefone" required><br>

        <label>Já concluiu o Grau?</label><br>
        <input type="radio" name="grau" value="Sim" required> Sim
        <input type="radio" name="grau" value="Não" required> Não
        <br><br>

        <!-- CAPTCHA -->
        <div class="g-recaptcha" data-sitekey="SUA_CHAVE_SITE_RECAPTCHA"></div>

        <!-- Google Login -->
        <div id="g_id_onload" data-client_id="SUA_CLIENT_ID_GOOGLE" data-callback="handleGoogleLogin"></div>
        <div class="g_id_signin" data-type="standard"></div>

        <!-- Apple Login -->
        <div id="appleid-signin" data-mode="center-align"></div>

        <button type="submit">Cadastrar</button>
    </form>

    <script>
        document.getElementById("cadastro-form").addEventListener("submit", function(event) {
            event.preventDefault();

            // Validação do CPF
            const cpf = document.getElementById("cpf").value;
            if (!validaCPF(cpf)) {
                alert("CPF inválido!");
                return;
            }

            // Validação do E-mail
            const email = document.getElementById("email").value;
            const confirmarEmail = document.getElementById("confirmar_email").value;
            if (email !== confirmarEmail) {
                alert("Os e-mails não coincidem!");
                return;
            }

            var formData = new FormData(this);
            formData.append("action", "processa_cadastro_usuario");

            fetch(window.location.href, {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        });

        function handleGoogleLogin(response) {
            var jwtData = JSON.parse(atob(response.credential.split('.')[1]));
            sendToServer(jwtData.name, "", jwtData.email, "", "", "google");
        }

        function sendToServer(nome, cpf, email, telefone, grau, metodo) {
            var formData = new FormData();
            formData.append("nome", nome);
            formData.append("cpf", cpf);
            formData.append("email", email);
            formData.append("telefone", telefone);
            formData.append("grau", grau);
            formData.append("social_login", metodo);

            fetch(window.location.href, {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }

        function validaCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
            let soma = 0, resto;
            for (let i = 1; i <= 9; i++) soma += parseInt(cpf[i - 1]) * (11 - i);
            resto = (soma * 10) % 11;
            if ((resto === 10) || (resto === 11)) resto = 0;
            if (resto !== parseInt(cpf[9])) return false;
            soma = 0;
            for (let i = 1; i <= 10; i++) soma += parseInt(cpf[i - 1]) * (12 - i);
            resto = (soma * 10) % 11;
            if ((resto === 10) || (resto === 11)) resto = 0;
            return resto === parseInt(cpf[10]);
        }
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST['nome'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $grau = $_POST['grau'] ?? '';
        $captcha = $_POST['g-recaptcha-response'] ?? '';

        // Validação do CAPTCHA
        $secretKey = "SUA_CHAVE_SECRETA_RECAPTCHA";
        $captchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
        $captchaResponse = json_decode($captchaVerify);

        if (!$captchaResponse->success) {
            echo json_encode(["status" => "error", "message" => "Erro: CAPTCHA inválido."]);
            exit;
        }

        // Envio para página externa
        $urlExterna = "https://exemplo.com/recebe_dados.php";
        $dados = ["nome" => $nome, "cpf" => $cpf, "email" => $email, "telefone" => $telefone, "grau" => $grau];

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $urlExterna);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($dados));

        // Envio para o HubSpot
        $hubspotAPIKey = "SUA_CHAVE_HUBSPOT";
        $hubspotUrl = "https://api.hubapi.com/crm/v3/objects/contacts?hapikey=$hubspotAPIKey";
        $hubspotData = json_encode(["properties" => [
            "firstname" => $nome,
            "email" => $email,
            "phone" => $telefone,
            "cpf" => $cpf,
            "grau" => $grau
        ]]);

        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $hubspotUrl);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $hubspotData);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        $mh = curl_multi_init();
        curl_multi_add_handle($mh, $ch1);
        curl_multi_add_handle($mh, $ch2);
        do { curl_multi_exec($mh, $running); } while ($running > 0);
        curl_multi_close($mh);

        echo json_encode(["status" => "success", "message" => "Usuário cadastrado com sucesso!"]);
        exit;
    }
    ?>

</body>
</html>
