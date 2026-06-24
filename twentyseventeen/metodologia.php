<section class="ConteudoCurso wrap-metodologias"><br>
      <div class="container">
        <div class="row">
          <div class="col-md-12 Txtpadrao metodologias">

            <h2 class="comBarra colorWhite">Nossa Metodologia</h2>
            <div class="left">

              <!-- <p class="conteudo-metodologias"> -->
                <div class="matBoxes metPresencial"><br>

                    <?php
                    // Helper to generate the "ver mais" button and wrapper
                    function render_box_presencial($title, $text) {
                    ?>
                    <div class="box boxPresencial">
                      <h3><?php echo $title; ?></h3>
                      <div class="textoBox">
                      <?php echo $text; ?>
                      </div>
                      <div class="verMaisBtn" style="display:none;">
                      <span>▼ ver mais</span>
                      </div>
                    </div>
                    <?php
                    }
                    render_box_presencial(
                    'Ensino por competências',
                    'Na UNISUAM, nosso ensino vai além da simples transmissão de conhecimento. Baseamos nossa metodologia no “ensino por competências”, que combina Conhecimentos (conceitos teóricos e práticos), Habilidades (aplicação prática dos conceitos) e Atitudes (postura profissional proativa, ética e resolutiva), formando o nosso conhecido “CHA”.'
                    );
                    ?>
                    <div class="box boxPresencial boxPresencial2 escondeEad">
                      <h3>Currículo Modular</h3>
                      <div class="textoBox">
                        Diferentemente do ensino tradicional (disciplinar), em que as matérias são aprendidas separadamente, nosso currículo modular integra diversas disciplinas em módulos interconectados, o que reflete os desafios da prática do mercado de trabalho, em que os assuntos se misturam e os profissionais precisam aplicar seus conhecimentos de forma simultânea. Este modelo promove uma aprendizagem significativa e contextualizada, permitindo que os alunos percebam a utilidade prática daquilo que estão aprendendo e se motivem ainda mais pelo aprendizado. Além disso, este modelo fomenta o desenvolvimento de competências como trabalho em equipe, resolução de problemas complexos, inovação, postura multidisciplinar, comunicação interpessoal e comprometimento.
                      </div>
                      <div class="verMaisBtn" style="display:none;">
                        <span>▼ ver mais</span>
                      </div>
                    </div>
                    <?php
                    render_box_presencial(
                    'Metodologias Ativas',
                    'Utilizamos metodologias ativas, como a “sala de aula invertida”, onde os alunos estudam o conteúdo teórico de forma independente (com tutores disponíveis para auxiliar com eventuais dúvidas) e utilizam o tempo presencial em sala para debates e dinâmicas, o que os coloca no centro do processo de aprendizagem, como os principais protagonistas de suas próprias trajetórias acadêmicas.'
                    );
                    render_box_presencial(
                    'Projetos Integradores',
                    'Os alunos aplicam seus conhecimentos em situações reais, desenvolvendo projetos que exigem a integração de diversas competências, estimulando a criatividade e a colaboração.'
                    );
                    ?>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.box.boxPresencial').forEach(function(box) {
                      var maxHeight = 180;
                      var textoBox = box.querySelector('.textoBox');
                      var verMaisBtn = box.querySelector('.verMaisBtn');
                      if (textoBox && verMaisBtn) {
                      if (textoBox.scrollHeight > maxHeight) {
                        textoBox.style.maxHeight = maxHeight + 'px';
                        textoBox.style.overflow = 'hidden';
                        verMaisBtn.style.display = 'block';

                        verMaisBtn.addEventListener('click', function() {
                        var isExpanded = verMaisBtn.classList.contains('expanded');
                        if (!isExpanded) {
                          textoBox.style.maxHeight = textoBox.scrollHeight + 'px';
                          textoBox.style.overflow = 'visible';
                          verMaisBtn.classList.add('expanded');
                          verMaisBtn.querySelector('span').textContent = '▲ ver menos';
                        } else {
                          textoBox.style.maxHeight = maxHeight + 'px';
                          textoBox.style.overflow = 'hidden';
                          verMaisBtn.classList.remove('expanded');
                          verMaisBtn.querySelector('span').textContent = '▼ ver mais';
                        }
                        });
                      }
                      }
                    });
                    });
                    </script>
                    <style>
                      .box.boxPresencial .verMaisBtn {
                        margin-top: 10px;
                        cursor: pointer;
                        color: #0073aa;
                        font-weight: bold;
                        text-align: center;
                        font-size: 15px;
                      }
                      .box.boxPresencial .verMaisBtn span {
                        display: inline-block;
                        transition: color 0.2s;
                      }
                      .box.boxPresencial .verMaisBtn:hover span {
                        color: #005177;
                      }
                      .box.boxPresencial .textoBox {
                        transition: max-height 0.4s ease;
                      }

                      /* Cor personalizada para páginas "-digital" */
                      body.digital-metodologia .box.boxPresencial .verMaisBtn,
                      body.digital-metodologia .box.boxPresencial .verMaisBtn span {
                        color: #e5457a !important;
                      }
                      body.digital-metodologia .box.boxPresencial .verMaisBtn:hover span {
                        color: #e5457a !important;
                      }
                      .box.boxPresencial.boxPresencial2.escondeEad.deactive {
                        display: none !important;
                      }
                      </style>

                </div>
                <div class="matBoxes metEad">
                  <div class="box boxEad">
                    <h3>Ensino virtual interativo</h3>
                    <div class="textoBox">Na UNISUAM, nosso ensino digital foi concebido a partir de uma articulação harmoniosa entre o conhecimento acadêmico e a interação dinâmica entre alunos e professores-tutores. O material didático é cuidadosamente organizado em unidades temáticas, disponibilizado em múltiplas formas de entrega, como vídeos, textos, hipertextos e animações. Essas variadas formas são associadas a diferentes atividades de aplicação, incluindo quizzes, estudos de caso, exercícios discursivos e debates, o que proporciona uma experiência de aprendizado rica e diversificada.<br><br>
                    
                    Como base da nossa metodologia está o relacionamento contínuo entre os professores-tutores e os alunos de cada turma, que ocorre tanto através dos canais de comunicação do ambiente virtual de aprendizagem quanto durante os momentos de interação ao vivo, conhecidos como os famosos “encontros coruja”. Nossos professores, altamente qualificados, são doutores, mestres e especialistas atuantes no mercado de trabalho e no meio acadêmico, e estão prontos para apoiar o desenvolvimento das competências profissionais dos nossos alunos.<br><br>
                    </div>
                  </div>  
                </div>
              <!-- </p> -->

              <!-- <a  href="<?php echo esc_url('https://inscricao.unisuam.edu.br/'); ?>"><div class="btnInscreva">INSCREVA-SE</div></a> -->

            </div>

            <div class="right">
              <!-- <img src="<?php echo get_field('imagem_metodologia') ?>"> -->
<!--               <div class="wrapMetodo">
                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/met1.png" alt="">
              </div>
              <div class="wrapMetodo">
                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/met2.png" alt="">
              </div>
              <div class="wrapMetodo">
                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/met3.png" alt="">
              </div>
              <div class="wrapMetodo">
                <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/met4.png" alt="">
              </div> -->
            </div>

          </div>

        </div> 
      </div>
</section>

<style>
    .box.boxEad {
      position: relative;
      left: 0;
    }
    .colorWhite {
      position: relative;
      z-index: 10;
    }
    .matBoxes .box {
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0 !important;
        background-color: transparent !important;
    }
    .matBoxes .boxPresencial {
        display: inline-block !important;
    }
    .wrap-metodologias {
      position: relative;
      height: auto !important;
      background-color: #F1F2F6;
      margin-top: 50px;
      padding-bottom: 0 !important;
    }
    .wrap-metodologias:before {
      content: '';
      position: absolute;
      left: -630px;
      width: 620%;
      height: 100%;
      background-color: #F1F2F6;
    }
  @media(min-width:769px) {
    .btnVerMaisMat {
      z-index: 99999;
      position: relative;
      width: 100%;
      cursor: pointer;
      transition: all .4s;
      text-align: right;
      margin-top: -60px;
      padding-right: 37%;
      margin-bottom: 40px;
      z-index: 100;
    }
    .verMaisBox2 {
      display: block;
    }
    .verMenosBox2 {
      display: none;
      right: -8px;
    }
    .btnVerMaisMat:hover {
      opacity: .8;
      transition: all .4s;
    }
    .matBoxes .boxPresencial {
      position: relative;
      display: inline-block;
      vertical-align: top;
      width: 46%;
      left:-48px;
      margin-right: 10px;
      margin-bottom: 70px;
      overflow: hidden;
      transition: all .4s;
    }
    .matBoxes .boxPresencial.active {
      height: auto;
      transition: all .4s;
    }
  }
  .matBoxes {
    /* display: none; */
    margin-bottom: 20px;
  }
  .matBoxes h3 {
    font-size: 18px;
    font-weight: 600;
  }
  .textoBox {
    line-height: 20px;
  }
  .wrapMetodo {
    position: relative;
    width: 25%;
    margin-right: 0;
    float: left;
  }
  .wrapMetodo img {
    width: 100%;
  }
  .ConteudoCurso h2.h2Metodo {
    margin: 60px auto 0 auto;
    color: #343434 !important;
    text-align: center;
    margin: 0 auto;
  }
  .metodologias .left {
    position: relative;
    display: block;
    width: 60%;
    margin-right: 100px;
    vertical-align: middle;
  }
  .matBoxes.metPresencial {
    margin-left: 50px;
  }
  .metodologias .right {
    position: relative;
    display: block;
    width: 50%;
    vertical-align: middle;
    text-align: left;
  }
  .metodologias .right img {
    width: 75%;
  }
  .conteudo-metodologias {
    margin-bottom: 40px;
  }
  .wrap-metodologias {
    padding-bottom: 50px;
    height: 514px;
  }
  .metodologias .right {
    text-align: center;
  }
  @media(max-width:768px) {
    .wrap-metodologias {
      height: auto !important;
    }
    .boxPresencial {
      margin-top: 40px;
    }
    .btnVerMaisMat {
      display: none;
    }
  }
</style>

<script>
  const metodo = window.location.href; 
  if (metodo.match('-digital')) { 
    console.log("digital");
    document.body.classList.add('digital-metodologia');
    $(".h2Metodo").html("Nossa Metodologia de Ensino a Distância");
    $(".metPresencial").hide();
    $(".boxEad").css("display","inline-block");
    $(".matBoxes.metEad").css("display","inline-block");
    $(".escondeEad").addClass("deactive");
    $(".textChangeEadPresencial").html("Por que fazer um curso Digital (EaD) na UNISUAM?");
  } else {
    document.body.classList.remove('digital-metodologia');
    $(".h2Metodo").html("Nossa Metodologia de Ensino Presencial");
    $(".metPresencial").show();
  }

  $(".verMaisBox2").click(function(){
    $(".boxPresencial2").addClass("active");
    $(".verMaisBox2").hide();
    $(".verMenosBox2").show()
  });
  $(".verMenosBox2").click(function(){
    $(".boxPresencial2").removeClass("active");
    $(".verMenosBox2").hide();
    $(".verMaisBox2").show()
  });
</script>