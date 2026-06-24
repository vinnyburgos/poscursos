<div class="wrap">
	<div id="" class="content-area">
		<main id="main" class="site-main">
<section class="ConteudoCurso certificacoes-intermediarias parcerias">
      <div class="container">
        <div class="row">
          <div class="centerLateral">
          <div class="col-md-12 Txtpadrao certificacoes"><br>

            <div class="left">
              <h2 class="comBarra"><?php echo get_field('titulo_certificacoes') ?></h2><br>

              <p class="conteudo-certificacoes textoAprender">
                <?php echo get_field('texto_certificacoes') ?>
                <!-- <div class="certPresenciais">
                  Atenção: No caso de alguns cursos (odontologia, por exemplo), com base em regulamentações específicas, não é permitida a prática profissional antes da conclusão da graduação.
                </div> -->
              </p>

              <!-- <a  href="<?php echo get_field('link_botao_inscreva') ?>"><div class="btnInscreva"><?php echo get_field('texto_botao_inscreva') ?></div></a> -->

            </div>

            <div class="right">
              <h3 class="tituloCertificacoesRight"><?php echo get_field('titulo_boxes_certificacoes') ?></h3>

              <?php 
                $selos_certificacoes = get_field('selos_certificacoes');
                foreach ($selos_certificacoes as $selo_certificacao) {
              ?>

              <div class="seloCertificacao">
                <?php echo $trofCert; ?>
                <p class="textoAprender"><?php echo $selo_certificacao['texto_do_selo']  ?></p>
              </div>

              
              <?php 
                }
              ?>
            </div>

          </div>
        </div>

        </div>
      </div>
</section>
</main></div></div>

<style>
  .certPresenciais {
    position: relative;
    display: none;
    font-size: 13px;
    font-weight: 600;
    line-height: 18px;
    color: #444;
  }
  .conteudo-certificacoes {
    width: 90%;
  }
  .tituloCertificacoesRight {
    color: #0F96AE;
    font-family: Ubuntu;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    text-align: left;
    margin-bottom: 40px;
    vertical-align: top;
/*     padding-top: 50px; */
  }
  .certificacoes .left {
    position: relative;
    display: inline-block;
    width: 100%;
    margin-right: 100px;
    vertical-align: top;
  }
  .certificacoes .right {
    position: relative;
    display: inline-block;
    width: 100%;
    vertical-align: top;
  }
  .seloCertificacao {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    border-radius: 5px;
    border: 2px solid #0F96AE;
    /* background: #FFF; */
    padding-left: 10px;
    padding-right:10px;
    padding-top: 20px;
    padding-bottom: 20px;
    width: 49%;
    max-width: 284px;
    height: 80px;
    margin-right: 20px;
    margin-bottom: 20px;
  }
  .seloCertificacao img {
    position: relative;
    width: 16%;
    height: auto;
    display: inline-block;
    transform: translateY(-8%);
    vertical-align: middle;
  }
  .seloCertificacao p {
    position: relative;
    width: 80%;
    display: inline-block;
    line-height: 14px;
    transform: translateY(10%);
    vertical-align: middle;
  }
  .certificacoes-intermediarias {
    padding-bottom: 50px;
  }
  .triCert {
    position: absolute;
    top: -3px;
  }
</style>

<script>
  const presencial = window.location.href; 
  if (presencial.match('-ead')) {} else {$(".certPresenciais").show();}

  if($(".seloCertificacao").length) {
  } else {
    $(".certificacoes-intermediarias").remove();
  }
</script>