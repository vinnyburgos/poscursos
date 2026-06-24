<?php
  
  // $msg = "Em breve";

  // $curso     = $template_args['curso'];
  $api_curso = $data;

  // var_dump($data)
  // $docente   = $template_args['docente'];
  // $ingresso  = $template_args['ingresso'];

  // $quero_me_matricular =  get_field('link_para_inscricao_graduacao', 'options');
  
  // global $wpdb, $post;
?>


<div class="wrap">
  <div id="" class="content-area">
    <main id="main" class="site-main">
      <section class="parcerias">
        <div class="center">

<section class="ConteudoCurso coordenador-docente bgCoordenacao" id="coordenador-docente">
  <div class="container">
    <div class="row">
  <div class="centerLateral" style="margin-left: 14px;">
  <h2 class="tituloCoordenador comBarra" style="color:#fff">Conheça a coordenação do curso</h2><br>

          



  <div class="wrapVideo" style="display:block">


    <?php 
      $coordenadores = [];
      if (isset($api_curso['resumo']['coordenadores']) && is_array($api_curso['resumo']['coordenadores'])) {
        $coordenadores = $api_curso['resumo']['coordenadores'];
      }

      $primeiro_coordenador = !empty($coordenadores) ? reset($coordenadores) : ['nome' => 'Em breve', 'funcao' => ''];
      $nome_primeiro_coordenador = $primeiro_coordenador['nome'] ?? 'Em breve';
      $nome_primeiro_coordenador = str_replace('\u00aa.', '&ordf;', $nome_primeiro_coordenador);

      $funcao_coordenador = '';
      foreach ($coordenadores as $coordenador) {
        if (!empty($coordenador['funcao'])) {
          $funcao_coordenador = $coordenador['funcao'];
          break;
        }
      }
    ?>

    <div class="temVideo">
      <div class="wrapTitleVideoCoordenador">
        <h4 class="panel-title titleDocenteCoordenador">Coordenação do curso</h4>
        <span id="coordFuncao" style="position:absolute;opacity:0;z-index:-99999;"><?php echo $funcao_coordenador;?></span>
        <p class="nomeCoordenador"><b><?php echo $nome_primeiro_coordenador;?></b></p>
      </div>


    <?php
      $video_padrao_digital = 'https://www.youtube.com/embed/9u797GhmTps?si=t_l4XxA7nJ-WqwCX';
      $video = get_field('id_video_coordenador');

      $normalizar_texto_modalidade = static function ($valor) {
        $valor = is_string($valor) ? trim($valor) : '';
        if ($valor === '') {
          return '';
        }

        if (function_exists('remove_accents')) {
          $valor = remove_accents($valor);
        }

        return strtolower($valor);
      };

      $possiveis_modalidades = array(
        $api_curso['modalidade'] ?? '',
        $api_curso['resumo']['modalidade'] ?? '',
        $api_curso['resumo']['categoria'] ?? '',
      );

      $eh_digital = false;
      foreach ($possiveis_modalidades as $modalidade_bruta) {
        $modalidade_normalizada = $normalizar_texto_modalidade($modalidade_bruta);
        if ($modalidade_normalizada === '') {
          continue;
        }

        if (
          strpos($modalidade_normalizada, 'digital ao vivo') !== false ||
          strpos($modalidade_normalizada, 'semipresencial') !== false ||
          strpos($modalidade_normalizada, 'aovivo') !== false
        ) {
          $eh_digital = false;
          break;
        }

        if (
          strpos($modalidade_normalizada, 'digital') !== false ||
          strpos($modalidade_normalizada, 'ead') !== false
        ) {
          $eh_digital = true;
        }
      }

      if (!$eh_digital) {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? strtolower((string) $_SERVER['REQUEST_URI']) : '';
        if (
          $request_uri !== '' &&
          strpos($request_uri, '-digital') !== false &&
          strpos($request_uri, '-aovivo') === false
        ) {
          $eh_digital = true;
        }
      }

      if ($eh_digital) {
        $video = $video_padrao_digital;
      }

      if ($video) {

          
          if (strpos($video, 'youtube.com/embed/') === false) {
              
              preg_match(
                  '%(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([^&?/]+)%i',
                  $video,
                  $match
              );

              if (!empty($match[1])) {
                  $video = 'https://www.youtube.com/embed/' . $match[1];
              }
          }
      }
      ?>

      <?php if (!empty($video)) : ?>
      <iframe
        class="iYoutube"
        width="100%"
        height="330"
        src="<?php echo esc_url($video); ?>"
        title="coordenador"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        allowfullscreen>
      </iframe>
    <?php endif; ?>
    
    
    
    
    </div>



    <div class="coordenadorInfo" style="margin-bottom:-35px;margin-top:30px">
      <h4 class="panel-title titleDocenteCoordenador">Coordenação do curso</h4>
      <div class="wrapDocentes wrapDocCoordenador active" style="width:100%">
        <div class="lineCoordenador"> 
           <div class="innerDocentes coordenador" style="color:#fff;">

            <div class="left">
              <b><?php echo $nome_primeiro_coordenador;?></b>
            </div>
            
          </div>
        </div>
      </div>
      
    </div>


  </div>


<h4 class="panel-title titleDocente">Corpo docente (professores)</h4>

<div class="wrapDocentes apenasDocentes">
    <div class="lineProf">                
      <?php $docentes = isset($api_curso['docentes']) && is_array($api_curso['docentes']) ? $api_curso['docentes'] : []; ?>
      <?php foreach ($docentes as $prof) { ?>
          <div class="innerDocentes" style="color:#fff;">
            <div class="left"><span id="existe"></span>
            <b><?php echo $prof['professor'];?></b><?php if ($prof['titulo'] != '') { echo " | ".$prof['titulo']; } ;?></div>
            
            <div class="right">
            <span>Lattes</span>
            <a href="<?php echo $prof['lattes']==''?'javascript:;':$prof['lattes'];?>" target="_blank"><?php echo $iconLattes; ?></a></div>
          </div>
        <?php } ?>              
                   
      </div>       
</div>
      <div class="btnVerMais"><span class="maisMenos">Ver mais</span><br><?php echo $verMaisLattes; ?></div>
</div>
</div>
</div>





<script>
  if ($("#coordFuncao").text() === "Coordenador Adjunto") {
    $(".titleDocenteCoordenador").text("Coordenação adjunta do curso");
  } else if ($("#coordFuncao").text().trim() === "" && $(".lineProf").text().trim() === "") {
    // $("#coordenador-docente").remove();
  }
</script>

<script>
  // Oculta .coordenador-docente com display:none !important se não houver nome de coordenador (incluindo 'Em breve') nem vídeo do YouTube
  $(window).on('load', function() {
    var nome1 = $.trim($('.nomeCoordenador b').text() || '');
    var nome2 = $.trim($('.lineCoordenador .innerDocentes .left b').text() || '');
    var nome = nome1 || nome2;
    var isNomeVazioOuEmBreve = (nome === '' || nome.toLowerCase() === 'em breve');

    var temVideo = false;
    $('#coordenador-docente iframe').each(function() {
      var src = ($(this).attr('src') || '').toLowerCase();
      if (src.indexOf('youtube.com/embed/') !== -1 || src.indexOf('youtube.com/watch') !== -1 || src.indexOf('youtu.be/') !== -1) {
        temVideo = true;
      }
    });

    if (isNomeVazioOuEmBreve && !temVideo) {
      $('#coordenador-docente').attr('style', 'display: none !important;');
    } else {
      $('#coordenador-docente').removeAttr('style');
    }
  });
  // Oculta .coordenador-docente com display:none !important se não houver nome de coordenador (incluindo 'Em breve') nem vídeo do YouTube
</script>


</div>

<script>
  const docentes = document.querySelectorAll(".innerDocentes");
  if(docentes.length < 8) {
    setTimeout(function(){
      $(".btnVerMais").trigger("click");
      $(".btnVerMais").hide();
      $(".wrapDocentes:after").hide();
    }, 2000);
  };
</script>

<style>
  .nomeCoordenador {
    color: #07AD6A;
    font-family: Ubuntu;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 20px;
    text-align: left;
    margin-top: 20px;
    margin-bottom: 30px;
  }
  .temVideo {display:block}
  .iYoutube {display:block !important}
  .iYoutube.active {display:none}
  .coordenadorInfo {display:none}
  .coordenadorInfo.active {display:block}
</style>
<script>
  videoCoordenador = $("iframe").attr('src');
  if(videoCoordenador == "") {
    $(".coordenadorInfo").addClass("active");
    $(".iYoutube").addClass("active");
    $(".wrapVideo").addClass("menor");
    $(".temVideo").css("display", "none");
  };
  conteudoDocentes = $(".lineProf").html();
  if(conteudoDocentes.match('innerDocentes')) {
  } else {
    $(".apenasDocentes").hide();
    $(".btnVerMais").hide();
    $(".titleDocente").hide();
  }
  coordenadorCurso = $(".lineCoordenador").find(".innerDocentes").find(".left").find("b").html();
  if(coordenadorCurso == "") {
    $(".lineCoordenador").find(".innerDocentes").find(".left").find("b").html("Em breve");
  }

  // Verificar se não tem coordenador nem professores e ocultar div nocoordenacao
  const temCoordenador = coordenadorCurso && coordenadorCurso.trim() !== "" && coordenadorCurso.trim() !== "Em breve";
  const temProfessores = $(".lineProf").find(".innerDocentes").length > 0;
  
  if (!temCoordenador && !temProfessores) {
    $(".nocoordenacao").hide();
  }
</script>


  </section>

  <style>
    .wrapVideo {
      position: relative;
      display: block;
      width: 83%;
      max-width: 1000px;
      height: auto;
      padding-bottom: 40px;
      text-align: center;
    }
    ul.formas_ingresso_interna ul
    {
      margin-right: 25px;
    }

    ul.formas_ingresso_interna li
    {
      float:left;
      display:inline-block;
      width:20%;
    }

    ul.formas_ingresso_interna li img
    {
      width:100%;
    }

    ul.formas_ingresso_interna li h4
    {
      font-size: 1.5em;
      color: #fff;
      position: relative;
      top: -153px;
      border: 0px;
      padding-right: 0px;
      text-align: center;
      font-family: 'uni_sansheavy_caps';
    }

    @media (max-width: 768px) {

      ul.formas_ingresso_interna li
      {
        float:left;
        display:inline-block;
        width:100%;
        height:270px;
      }

      ul.formas_ingresso_interna li h4
      {
        top:-168px;
      }
      .wrapVideo {
        height: 460px !important;
      }
      .wrapVideo.menor {
        height: auto !important;
      }

    }

  </style>


<style>
  <?php echo $class_name;?> {
    height: 100%;
    width: 100%;
    position: fixed;
    top:0px;
    z-index: 99999;
    display: none;
    overflow:scroll;
  }

  body { overflow:auto; }
</style>

<script>
  
  var $ = jQuery;
  $(document).ready(function(){
    $(".swiper-container").hover(function() {
        (this).swiper.autoplay.stop();
    }, function() {
        (this).swiper.autoplay.start();
    });
  });

</script>

<script>
  const url = window.location.href; 
    if (url.match('-ead')) { 
      $(".docenteVin").hide();
    }
</script>

<style> 
  .titleDocente {
    color: #9A9A9A;
    font-family: Ubuntu;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    text-align: left;
  }
  .titleDocenteCoordenador {
    color: #9A9A9A;
    font-family: Ubuntu;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    text-align: left;
  }
    .btnVerMais {
      position: relative;
      text-align: center;
      z-index: 999;
      color: #0F96AE;
      font-family: Ubuntu;
      font-size: 14px;
      font-style: normal;
      font-weight: 700;
      line-height: 20px; /* 142.857% */
      cursor: pointer;
      transition: all .4s;
      top: -40px;
      transition: all .4s;
      width: 90%;
    }
    .btnVerMais:hover {
      opacity: .8;
      transition: all .4s;
    }
    .setaVermais.active {
      transform: rotate(180deg);
      transition: all .4s;
    }
    .wrapDocentes {
      padding: 20px;
      border-radius: 5px;
      border: 2px solid #6F6F6F;
      background: #1E1E1E;
      position: relative;
      width: 93%;
      max-width: 1000px;
      margin: 40px 0;
      border-bottom: none;
      max-height: 300px;
      overflow: hidden;
      transition: all .4s;
    }
    .wrapDocentes.active {
      transition: all .4s;
      overflow-y: auto;
      max-height: 100%;
      border-bottom: 2px solid #6F6F6F;
      width: 90%;
    }
    .wrapDocentes:after {
      content: ' ';
      width: 130%;
      position: absolute;
      z-index: 999;
      background-image: linear-gradient(to bottom, rgba(255,0,0,0), #343434);
      bottom: 0;
      left: -5px;
      right: -5px;
      height: 200px;
    }
    .wrapDocentes.active:after {
      display: none;
      z-index: -999999999;
    }
    .wrapDocCoordenador:after {
      display: none;
      z-index: -999999999;
    }
    .wrapDocentes .left {
      position: relative;
      display: inline-block;
      width: 75%;
      color: #0F96AE;
      font-family: Ubuntu;
      font-size: 16px;
      font-style: normal;
      font-weight: 700;
      line-height: 20px; /* 125% */
    }
    .wrapDocentes .right {
      position: relative;
      display: inline-block;
      width: 20%;
      text-align: right;
    }
    .coordenador-docente {
      background: #343434;
      padding-top: 50px;
      padding-bottom: 50px;
    }
    .ConteudoCurso h2.tituloCoordenador {
      color: #fff !important;
      margin-bottom: 30px;
      text-align: left;
      position: relative;
      /* left: 10%; */
    }
  
  /* desabilida area de modulos dos cursos */
    .panel-modulos {
      display: none;
    }
   /* desabilida area de modulos dos cursos */
   
    .SlideModulosGrad {
      position:  relative;
      display: block;
      margin-top: 20px;
      width: 100%;
      overflow: hidden;
    }
    .SlideModulosGrad .swiper-slide h1 {
      font-size: 16px !important;
      font-weight: 100;
      text-align: center !important;
      margin-bottom: 20px;
    }
    .Accordion .panel-body {
      display:  block !important;
    }
    .Accordion .panel-body .Txtpadrao {
      display:  block;
      width:  100%;
    }
    .TxtVin {
      display: block;
      width: 100%;
    }
    .BoxModuloPos {
      background-color: #f2f2f2;
      text-align: center;
      padding: 20px;
      min-height: auto !important;
    }
    /* .innerNomesVin {
      position: relative;
      display:  block;
      border-bottom: 1px solid #000;
    } */
    .nomesVin {
      position: relative;
      display: block !important;
      float: none !important;
      border-bottom: 1px solid #ccc;
      padding-bottom: 6px;
      text-align: left;
      font-size: 12px;
      line-height: 16px !important;
    }
    .nomesVin:last-child {
      border-bottom: none !important;
    }
    .swiper-button-next {
      color: #0F96AE !important;
      opacity: .3 !important;
      right:  0 !important;
    }
    .swiper-button-prev {
      color: #0F96AE !important;
      opacity: .3 !important;
      left:  0 !important;
    }
    .innerDocentes {
      border-bottom: 2px solid #6F6F6F;
      padding-top: 15px;
      padding-bottom: 15px;
      padding-left: 20px;
    }
    .innerDocentes.coordenador .left {
      width: 100%;
    }
    .innerDocentes:last-child {
      border-bottom: 0 !important;
    }
    .innerDocentes .right span {
      color: #6b6b6b;
    }
</style>     

<script>
  $(".btnVerMais").click(function(){
    $(".wrapDocentes").toggleClass("active");
    $(".wrapDocentes::after").toggle();
    $(".setaVermais").toggleClass("active");
    const textoBtn = $(".maisMenos").html();
      if(textoBtn == "Ver mais") {
        $(".maisMenos").html("Ver menos");
      } else if(textoBtn == "Ver menos") {
        $(".maisMenos").html("Ver mais");
      }
    $(".btnVerMais").css("top", "-20px");
  });
</script> 

<script>
    if (url.match('psicologia') || url.match('gastronomia')) { 
      $(".panel-modulos").css("display", "block");
    }
    if($(".wrap-modulo").length) {
    } else {
      $(".conteudo-cursar").remove();
    }
</script>

<script>
  if($(".iYoutube").html() == "") {
   $(".iYoutube").hide()
  }
</script>
</script>