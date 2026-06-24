// controle do comportamento do formulário
$(document).ready(function(){
    $(".btnCriarConta").click(function(){
        $(".btnCriarConta").addClass("active");
        $(".btnJaTenhoConta").removeClass("active");
        $(".jaTenhoConta").fadeOut();
        $(".criarConta").fadeIn();
        $("#tituloForm").html("Criar sua conta na Pós UNISUAM");
    });
    $(".btnJaTenhoConta").click(function(){
        $(".btnJaTenhoConta").addClass("active");
        $(".jaTenhoConta").fadeIn();
        $(".criarConta").fadeOut();
        $(".btnCriarConta").removeClass("active");
        $("#tituloForm").html("Entre com sua conta na Pós UNISUAM");
    });
});