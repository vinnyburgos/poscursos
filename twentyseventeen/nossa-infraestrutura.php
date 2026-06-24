<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<section class="ConteudoCurso nossa-infraestrutura">
    <div class="container">
        <div class="row">
            
            <div class="wrap">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main">
          <div class="centerLateral">
          <div class="col-md-12 Txtpadrao area-conteudo"><br>

            <h2 class="comBarra" style="color:#fff">Nossa Infraestrutura</h2><br><br>

            <div class="swiper swiperInfra">
                  <div class="swiper-wrapper">
            <?php 
              $galeria = get_field('galeria_infra');
              if (empty($galeria)) {
                echo '<style>.nossa-infraestrutura{display:none !important;}</style>';
              } else {
                foreach ($galeria as $infra) {
            ?>
                  <div class="swiper-slide innerInfra">
                    <div class="box-infra">
                      <img class="foto-infra" src="<?php echo $infra['imagem_infra']  ?>">
                    </div>
                  </div>
            <?php 
                }
              }
            ?>
            </div>

              <!-- <div class="swiper-next"><img src="../wp-content/uploads/2024/rule-right.png"></div>
              <div class="swiper-prev"><img src="../wp-content/uploads/2024/rule-left.png"></div> -->
              <div class="swiper-pagination-infra"></div>

          </div>

        </div>

      </div>
      </div>
</section>

<div class="wrapImgInfra">
    <div class="innerImgInfra">
        <div class="btnCloseInfra">X</div>
        <img id="imgInfraZoom" src="" alt="">
    </div>
</div>  
</main><!-- #main -->
            </div><!-- #primary -->
            </div><!-- .wrap -->

<script>
  $(".swiperInfra").find(".swiper-slide").click(function(){
    let linkImagem = $(this).find(".box-infra").find(".foto-infra").attr("src");
    $("#imgInfraZoom").attr("src", linkImagem);
    $(".wrapImgInfra").addClass("active");
  });
  $(".btnCloseInfra").click(function(){
    $(".btnClosePop").trigger("click");
    $(".wrapImgInfra").removeClass("active");
  });
  document.onkeydown = function(e) {
    if(e.key === 'Escape') {
      $(".btnClosePop").trigger("click");
      $(".wrapImgInfra").removeClass("active");
    };
  };
</script>

<script>
  imagensInfra = $(".swiperInfra").find(".swiper-wrapper").html();
  if(imagensInfra == false) {
    $(".nossa-infraestrutura").remove();
  } 
</script>

<style>
  #imgInfraZoom {
    border: 2px solid #00d084;
    border-radius: 5px;
  }
  .wrapImgInfra {
    position: fixed;
    top: -1000px;
    bottom: auto;
    left: 0;
    right: 0;
    z-index: 999999999999;
    transition: all .4s;
    background: rgba(0, 0, 0, .8);
  }
  .btnCloseInfra {
    position: absolute;
    right: -35px;
    top: -35px;
    color: #fff;
    font-size: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: all .4s;
  }
  .btnCloseInfra:hover {
    transition: all .4s;
    opacity: .8;
  }
  .innerImgInfra {
    position: relative;
    max-width: 800px;
    width: 85%;
    margin: 10% auto;
  }
  .innerImgInfra img {
    width: 100%;
  }
  .wrapImgInfra.active {
    top: 0;
    bottom: 0;
    transition: all .4s;
  }
  .nossa-infraestrutura {
    background: #343434;
    padding-bottom: 40px;
  }
  .ConteudoCurso h2.nossaInfra {
    margin: 20px auto 0 auto;
    color: #fff !important;
  }
  .bloco {
    width: 30%;
    display: inline-block;
    vertical-align: middle;
    height: 100.909px;
    padding: 20px;
    line-height: 20px;
    border-radius: 5px;
    background: #F2F2F2;
    margin-right: 2%;
    margin-bottom: 30px;
  }
  .texto-final {
    color: #FFF;
    text-align: center;
    font-family: Ubuntu;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    text-align: left;
  }
  @media(max-width:450px) {
    .innerImgInfra {
      width: 75%;
      margin: 15% auto;
    }
    .foto-infra {
      height: 140px !important;
    }
    .QuemRecomendaVideos {
      text-align: center !important;
    }
  }
</style>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
 <script>
    var swiper = new Swiper(".swiperInfra", {
      slidesPerView: 4,
      spaceBetween: 1,
      centeredSlides: false,
      initialSlide: 3,
      loop: true,
      // mousewheel: true,
      breakpoints: {
        // when window width is >= 320px
        320: {
          slidesPerView: 1,
          spaceBetween: 1
        },
        // when window width is >= 640px
        769: {
          slidesPerView: 4,
          spaceBetween: 1
        }
      },
      autoplay: {
        delay: 2500,
        disableOnInteraction: true,
      },
      pagination: {
        el: ".swiper-pagination-infra",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-next",
        prevEl: ".swiper-prev",
      },
    });
</script>

<style>
  .swiper-slide {
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .swiper-slide img {
    object-fit: cover;
    width:130px;
    height: 130px;
    border-radius: 5px;
    border: 2px solid #07AD6A;
    background: rgba(0, 0, 0, 0.8);
    transition: all .4s;
    cursor: pointer;
  }
  .swiper-slide.active {
    opacity: .6;
    transition: all .4s;
  }
  .swiper-slide.active:after {
    content: url('../wp-content/uploads/2024/zoom.png');
    position: absolute;
    margin: 0 auto;
    z-index: 9999999;
    transition: all .4s;
    cursor: pointer;
  }
  .swiper-next {
    margin-top: -90px;
    margin-right: -30px;
    float: right;
    color: transparent !important;
  }
  .swiper-prev {
    margin-top: -90px;
    margin-left: -30px;
    float: left;
    color: transparent !important;
  }
  .swiper-pagination-infra {
    position: relative;
    margin: 0 auto;
    width: 100% !important;
    left: auto !important;
    right: auto !important;
    text-align: center;
  }
  .swiper-pagination-infra {
    margin-bottom: -20px;
    margin-top: 30px;
  }
  .swiper-pagination-infra .swiper-pagination-bullet {
    background: #07AD6A
  }
  @media(max-width:768px) {
    .swiper-next {
      margin-right: 20px;
      margin-top: -90px;
    }
    .swiper-prev {
      margin-left: 20px;
      margin-top: -90px;
    }
    .swiper-slide img {
      width: 80%;
    }
    .swiper-pagination-infra {
      margin-top: 10px;
    }
    .swiper {
      margin-top: -20px;
    }
  }
</style>

<script>
  $(".innerInfra").mouseover(function(){
    $(".swiper-slide").removeClass("active");
    $(this).addClass("active");
  });
</script>