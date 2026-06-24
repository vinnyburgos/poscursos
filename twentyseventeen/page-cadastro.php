<?php
/**
 * Template Name: Cadastro
 *
 * Este é o template específico para a página de cadastro.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="https://gmpg.org/xfn/11">
<!-- jQuery CDN via Google -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/cadastro.css">
<script src="<?php echo get_template_directory_uri(); ?>/cadastro.js"></script>
</head>

<body>
<?php $upload_dir = wp_upload_dir(); ?>

<div class="wrapMenuFluxo">
    <div class="center">
        <div class="wrapSides">
            <img class="logoTopo" src="<?php echo $upload_dir['baseurl']; ?>/2025/03/logo-unisuam-cadastro.png" alt="">
            <div class="wrapStep">
                <img src="<?php echo $upload_dir['baseurl']; ?>/2025/03/cadastro-stepes.png" alt="">
            </div>
        </div>
    </div>    
</div>

<div class="wrapContent">
    <div class="center">

        <div class="left wrapFormulario">
            <h3 id="tituloForm">Criar sua conta na Pós UNISUAM</h3>
            <div class="btnsConta">
                <div class="btnCriarConta active">Criar conta</div>
                <div class="btnJaTenhoConta">Já tenho conta</div>
            </div>
            <div class="criarConta active">
                <div class="btnGoogle">
                    <img src="<?php echo $upload_dir['baseurl']; ?>/2025/03/devicon_google.png" alt="">
                    <span>CADASTRAR COM GOOGLE</span>
                </div>

                <div class="cadastroEmail">
                    <p class="tituloCadastrar">Cadastrar com e-mail</p>

                    <form class="cadastrar" action="" id="cadastrar">
                        <input type="text" name="nome" placeholder="Nome Completo">
                        <input type="e-mail" name="email" placeholder="E-mail">
                        <input type="e-mail" name="confirmar-email" placeholder="Confirmar e-mail">
                        <input type="text" name="telefone" placeholder="Telefone">
                        <input type="password" name="novasenha" placeholder="Crie uma senha">
                        <input class="enviar" type="submit" value="ENVIAR">
                    </form>
                </div>

            </div>
            <div class="jaTenhoConta ">
                <div class="btnGoogle">
                    <img src="<?php echo $upload_dir['baseurl']; ?>/2025/03/devicon_google.png" alt="">
                    <span>ENTRAR COM GOOGLE</span>
                </div>
                <div class="cadastroEmail">
                    <p class="tituloCadastrar">Entrar com e-mail</p>

                    <form class="entrar" action="" id="entrar">
                        <input type="e-mail" name="email" placeholder="E-mail">
                        <input type="password" name="senha" placeholder="Senha">
                        <input class="enviar" type="submit" value="ENVIAR">
                    </form>
                </div>
            </div>
        </div>

        <div class="right wrapResumo">
            <div class="resumoBox">
                <p class="resmoTag">RESUMO DA COMPRA</p>
                <h2 id="nomeCurso">Pós-Graduação em Product Management</h2>
                <p id="resumoCurso">Capacitação em gestão e desenvolvimento de produtos, aliando técnica e dados que possam contribuir para os próximos passos de cada negócio.</p>
                <div class="wrapDetalhesCurso">
                    Modalidade: <span id="modalidade">Presencial</span><br>
                    Unidade: <span id="unidade">Bonsucesso</span><br>
                    Carga horária: <span id="cargaHoraria">360 horas</span><br>
                    Dias e Horários: <span id="diasHorarios">Sábados quinzenais das 8h às 17h</span><br>
                    Início: <span id="inicio">00/00/0000</span>
                </div>

                <p class="dePor">De </p> <p class="precoCheio">R$<span id="precoCheio">000,00</span></p><p class="por"> por</p>
                <p class="precoDesconto">R$<span id="precoDesconto">000,00</span></p>
                <p class="ateVezes atePresencial">Em até 18x sem juros</p>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recupera os dados do localStorage
    const dadosCurso = JSON.parse(localStorage.getItem('dadosCurso'));

    if (dadosCurso) {
        document.getElementById('nomeCurso').textContent = dadosCurso.nomeCurso;
        document.getElementById('resumoCurso').textContent = dadosCurso.sobre;
        document.getElementById('modalidade').textContent = dadosCurso.modalidade;
        document.getElementById('unidade').textContent = dadosCurso.unidade;
        document.getElementById('cargaHoraria').textContent = dadosCurso.cargaHoraria;
        document.getElementById('diasHorarios').textContent = dadosCurso.diasHorario;
        document.getElementById('inicio').textContent = dadosCurso.inicio;
        document.getElementById('precoDesconto').textContent = dadosCurso.valorCurso;
    }

    // Captura os dados do formulário e do resumo e envia para salvar_dados.php
    $('#cadastrar').submit(function(e) {
        e.preventDefault();

        const nome = $('input[name="nome"]').val();
        const email = $('input[name="email"]').val();
        const confirmarEmail = $('input[name="confirmar-email"]').val();
        const telefone = $('input[name="telefone"]').val();
        const novasenha = $('input[name="novasenha"]').val();

        // Captura os dados do resumo
        const nomeCurso = document.getElementById('nomeCurso').textContent;
        const resumoCurso = document.getElementById('resumoCurso').textContent;
        const modalidade = document.getElementById('modalidade').textContent;
        const unidade = document.getElementById('unidade').textContent;
        const cargaHoraria = document.getElementById('cargaHoraria').textContent;
        const diasHorarios = document.getElementById('diasHorarios').textContent;
        const inicio = document.getElementById('inicio').textContent;
        const precoCheio = document.getElementById('precoCheio').textContent;
        const precoDesconto = document.getElementById('precoDesconto').textContent;

        // Envia os dados para o servidor para salvar no banco de dados
        $.ajax({
            url: '<?php echo get_template_directory_uri(); ?>/salvar_dados.php',
            type: 'POST',
            data: {
                nome: nome,
                email: email,
                confirmarEmail: confirmarEmail,
                telefone: telefone,
                novasenha: novasenha,
                nomeCurso: nomeCurso,
                resumoCurso: resumoCurso,
                modalidade: modalidade,
                unidade: unidade,
                cargaHoraria: cargaHoraria,
                diasHorarios: diasHorarios,
                inicio: inicio,
                precoCheio: precoCheio,
                precoDesconto: precoDesconto
            },
            success: function(response) {
                console.log('Dados salvos com sucesso:', response);
            },
            error: function(error) {
                console.error('Erro ao salvar os dados:', error);
            }
        });
    });
});
</script>

</body>    
</html>

<?php
// get_footer();
