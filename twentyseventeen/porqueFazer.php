<section class="ConteudoCurso wrap-porque">
      <div class="container">
        <div class="row">
          <div class="col-md-12 Txtpadrao porque"><br>

            <div class="left">
              <h2 class="textChangeEadPresencial comBarra" style="porqueFazer">Por que fazer UNISUAM</h2><br>

              <p class="texto textoBox textoSemiPreE">
                Na UNISUAM, você é a prioridade! Celebramos a diversidade e promovemos a inovação, apoiando cada aluno para que seja protagonista de sua história. Com mais de 50 anos de experiência e o reconhecimento máximo do MEC (nota 5), oferecemos um ambiente que vai além do aprendizado em sala de aula, inspirando ações concretas para construir hoje um "amanhã melhor".
              </p>


              <div class="presencial">

                <div class="wrapLineItem">
                  <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon-presen1.svg"></div> -->
                  <div class="texto textoBox">Escolha entre as unidades Bonsucesso, Bangu ou Campo Grande, de acordo com a disponibilidade do curso desejado.</div>
                </div>
                
                <div class="wrapLineItem">
                  <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon2.svg"></div> -->
                  <div class="texto textoBox">Desenvolva relacionamentos pessoais e construa uma boa rede de contatos.</div>
                </div>

                <div class="wrapLineItem">
                  <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon3.svg"></div> -->
                  <div class="texto textoBox">Desfrute de atividades extracurriculares e práticas em bibliotecas, laboratórios modernos e espaços multifuncionais bem estruturados.</div>
                </div>

                <div class="wrapLineItem">
                  <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon-presen4.svg"></div> -->
                  <div class="texto textoBox">Participe de eventos acadêmicos que promoverão habilidades intelectuais e sociais e contato com profissionais de diversas áreas.</div>
                </div>
              </div>


            <div class="ead">
              <div class="wrapLineItem">
                <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon-ead1.svg"></div> -->
                <div class="texto textoBox" style="font-size:13px">Através da nossa plataforma virtual, participe de encontros ao vivo e esteja em contato com profissionais de mercado.</div>
              </div>

              <div class="wrapLineItem">
                <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon-ead2.svg"></div> -->
                <div class="texto textoBox" style="font-size:13px">Conte sempre que precisar com suporte online dedicado e interação direta com professores de alto nível.</div>
              </div>

              <div class="wrapLineItem" style="font-size:13px">
                <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon-ead3.svg"></div> -->
                <div class="texto">Desfrute de laboratórios virtuais e recursos imersivos muito modernos para uma experiência digital tão eficaz e envolvente quanto a presencial.</div>
              </div>

              <div class="wrapLineItem">
                <!-- <div class="icon"><img src="../wp-content/uploads/2024/icon-ead4.svg"></div> -->
                <div class="texto textoBox" style="font-size:13px">A sensação de acolhimento dos alunos é prioridade e potencializada por uma jornada exclusiva de ambientação e participação em comunidades de integração com outros alunos e coordenação do curso desde o primeiro momento.</div>
              </div>
            </div>

            </div>

          </div>

        </div>
      </div>
</section>

<style>
  .texto.textoBox {
    color: #414141;
    font-family: Ubuntu;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 28px;
  }
  .porque {
    /* color: #9A9A9A;
    font-family: Ubuntu;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal; */
  }
  .wrapLineItem {
    position: relative;
    display: block;
    margin-bottom: 50px !important;
    height: 80px;
  }
  .icon {
    position: relative;
    display: inline-block;
    width: 15%;
    vertical-align: middle;
    margin-right: 10px;
  }
  .icon img {
    width: 100%;
  }
  .texto {
    position: relative;
    display: inline-block;
    width: 75%;
    vertical-align: middle;
    color: #343434;
    font-family: Ubuntu;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px; /* 185.714% */
  }
  .ConteudoCurso h2.porqueFazer {
    margin: 60px auto 0 auto;
    color: #343434 !important;
    text-align: center;
    margin: 0 auto;
  }
  .porque .left {
    position: relative;
    display: inline-block;
    width: 54%;
    margin-right: 50px;
    margin-bottom: 50px;
    vertical-align: top;
  }
  .porque .left .presencial {
    position: relative;
    display: none;
    margin-top: 30px;
  }
  .porque .left .ead {
    position: relative;
    display: none;
    margin-top: 30px;
  }
  .wrapLineItem {
    position: relative;
    float: left;
    width: 43%;
    margin-right: 30px;
    margin-bottom: 40px;
  }
  .wrapLineItem .icon {
    position: relative;
    width:20%;
    display: inline-block;
    vertical-align: top;
    margin-bottom: 10px;
  }
  .wrapLineItem .texto {
    position: relative;
    display: inline-block;
    vertical-align: top;
    width: 75%;
    font-size: 14px;
    line-height: 20px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const preEad = window.location.href.toLowerCase();
    const modalidadeBox = document.getElementById('modalidadeBox');
    const modalidadeValue = modalidadeBox ? modalidadeBox.textContent.trim().toLowerCase() : '';
    const semipresencialText = "Aqui, você é a prioridade. Com mais de 55 anos de tradição e nota máxima no MEC, a instituição oferece uma formação de qualidade, incentivando cada aluno a ser protagonista da própria trajetória.<br><br>No modelo Semipresencial, você estuda grande parte do conteúdo online, com flexibilidade para organizar sua rotina, e participa de encontros presenciais na unidade, com atividades práticas e troca de experiências com professores e colegas. Assim, você une autonomia nos estudos a uma formação conectada com o mercado.";

    if (modalidadeValue === 'digital ao vivo') {
      $(".textChangeEadPresencial").html("Por que fazer um curso Semipresencial na UNISUAM?");
      $(".textoSemiPreE").html(semipresencialText);
      $(".wrapLineItem").css("height","auto");
      return;
    }

    if (preEad.includes('-digital')) {
      $(".ead").show();
      $(".textChangeEadPresencial").html("Por que fazer um curso Digital (EaD) na UNISUAM?");
      $(".wrapLineItem").css("height","auto");
    } else {
      $(".presencial").show();
      $(".textChangeEadPresencial").html("Por que fazer um curso Presencial na UNISUAM?");
    }
  });
</script>