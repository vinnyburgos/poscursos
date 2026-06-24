<?php
// filepath: /opt/lampp/htdocs/novo-graduacao/wp-content/themes/twentyseventeen/quemFazRecomenda.php

// Busca o campo repetidor do ACF
$quemfaz = get_field('quem_faz', get_the_ID());
if (!is_array($quemfaz)) $quemfaz = [];

// Nao ha videos, evita renderizar o bloco por completo
if (empty($quemfaz)) {
    return;
}

// $botao_mais_depoimentos = isset($template_args['mais_depoimentos']) ? $template_args['mais_depoimentos'] : '';
?>

<div class="container wrapQuemFaz">
    <div class="row">
        <div class="centerLateral">
            <h2 class="comBarra">Quem fez recomenda</h2><br>
            <section class="VideosBottomDepoimentos">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($quemfaz as $index => $item): ?>
                            <div class="swiper-slide wow animate__animated animate__bounceIn">
                                <a href="#" class="abrir-modal-video" data-video="<?= esc_url($item['url_do_video']) ?>">

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            document.querySelectorAll('.abrir-modal-video').forEach(function(link) {
                                                link.addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    var url = this.getAttribute('data-video');
                                                    if (!url) return;
                                                    var videoId = '';
                                                    if (url.includes('youtu.be/')) {
                                                        videoId = url.split('youtu.be/')[1].split(/[?&]/)[0];
                                                    } else if (url.includes('youtube.com') && url.includes('v=')) {
                                                        videoId = url.split('v=')[1].split('&')[0];
                                                    }
                                                    var embed = videoId ? 'https://www.youtube.com/embed/' + videoId + '?autoplay=1' : url;
                                                    document.getElementById('iframeVideoDepoCustom').src = embed;
                                                    document.getElementById('modalVideoDepoCustom').style.display = 'flex';
                                                });
                                            });
                                            document.getElementById('closeModalVideoCustom').onclick = function() {
                                                document.getElementById('modalVideoDepoCustom').style.display = 'none';
                                                document.getElementById('iframeVideoDepoCustom').src = '';
                                            };
                                            document.getElementById('modalVideoDepoCustom').addEventListener('click', function(e) {
                                                if (e.target === this) {
                                                    this.style.display = 'none';
                                                    document.getElementById('iframeVideoDepoCustom').src = '';
                                                }
                                            });
                                        });
                                    </script>

                                    <?php if (!empty($item['imagem'])): ?>
                                        <img class="imgFez" src="<?= esc_url($item['imagem']) ?>" alt="<?= esc_attr($item['nome_do_aluno']) ?>" />
                                    <?php endif; ?>
                                    <?php if (!empty($item['nome_do_aluno'])): ?>
                                        <p class="nomeALuno"><?= esc_html($item['nome_do_aluno']) ?></p>
                                    <?php endif; ?>
                                    <!-- <?php if (!empty($item['graduacao'])): ?>
                                        <p><?= esc_html($item['graduacao']) ?></p>
                                    <?php endif; ?> -->
                                    <!-- <i class="fal fa-play-circle"></i> -->
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="swiper-pagination"></div>

                    <script>
                        $(document).ready(function(){
                           if($(".swiper-pagination-bullet").html() == "") {
                            //    $(".wrapQuemFaz").hide();
                               $(".wrap-metodologias").css("margin-top", "-20px");
                           };
                        });
                    </script>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .wrapQuemFaz{
        display: none !important;
    }
</style>


<!-- SwiperJS CSS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

<!-- SwiperJS JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 3,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            600: {
                slidesPerView: 3,
            }
        }
    });
</script>


<style>
p.nomeALuno {
    position: relative;
    top: -60px;
    /* color: #fff; */
}
.VideosBottomDepoimentos {
    width: 60% !important;
    margin-top: -20px !important;
}
.comBarra {
    margin-top: 50px;
}
.QuemFezRecomenda {
    padding: 30px 0;
    overflow: hidden;
}
.blocosComplementares {
    position: relative;
    height: 514px;
}
.blocosComplementares:before {
    content: "";
    position: absolute;
    width: 500px;
    height: 514px;
    left: -500px;
    z-index: -1;
    /* background: #F2F2F2; */
    top: 0;
}
.blocosComplementares:after {
    content: "";
    position: absolute;
    width: 500px;
    height: 514px;
    right: -500px;
    z-index: -1;
    /* background: #F2F2F2; */
    top: 0;
}
.QuemRecomendaVideos {
    text-align: left;
    padding: 0;
    margin: 0 auto;
    width: 60%;
    height: 230px;
    overflow: hidden;
    margin-left: 0;
}
/* .QuemRecomendaVideos li:last-child {
    display: none !important;
} */
.swiper-slide {
    width: 31% !important;
    float: none;
    display: inline-block;
}
.swiper-slide a img {
    width: 100%;
    /* background: transparent !important; */
    background-color: #F1F2F6;
}
.swiper-slide a {
    height: 250px !important;
}
.swiper-slide a i {
    font-size: 2.2em !important;
    margin-top: 50%;
    text-shadow: 1px 1px 10px #444;
}
.ConteudoCurso h2.h2QuemFaz {
    color: #343434 !important;
    text-align: left;
    position: relative;
  }
  .ConteudoCurso h2.h2QuemFaz:before {
    color: #343434 !important;
    text-align: left;
  }
  .subtituloquemfaz {
    color: #343434;
    text-align: left;
    font-family: Ubuntu;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 21px;
    position: relative;
    width: 100%;
  }
  .subtituloquemfaz span {
    display: inline-block;
  }
#seloabmes {
    width: 121px;
    top: -15px;
    position: relative;
}

.swiper-slide a span {
    top: 160px;
    text-shadow: 1px 1px 10px #444;
}


@media(max-width:1010px) {
    .swiper-slide a p {
        font-size: 12px;
    }
}

/* tira quem faz recomenda do mobile  */
@media(max-width:768px) {
    /*.QuemFezRecomenda {
        display: none;
    }*/
    .swiper-slide {
        width: 80%;
    }
}

/* tira quem faz recomenda do mobile  */

@media screen and (min-width: 1024px) {
    .swiper-slide a {
        height: 330px;
        font-size: 13px;
    }

    .swiper-slide a p {
        line-height: 1;
    }
}

@media screen and (min-width: 1366px) {
    .swiper-slide a {
        height: 510px;
    }

    .swiper-slide a p {
        font-size: 18px;
    }

    .swiper-slide a span {
        font-size: 23px;
    }
}

@media screen and (min-width: 1920px) {
    .swiper-slide a {
        height: 615px;
    }

    .swiper-slide a p {
        font-size: 18px;
    }

    .swiper-slide a span {
        font-size: 38px;
    }

}

@media screen and (min-width: 2560px) {
    .swiper-slide a {
        height: 710px;
    }

    .swiper-slide a p {
        font-size: 18px;
    }

    .swiper-slide a span {
        font-size: 38px;
    }

}

@media(max-width:600px) {
    .VideosBottomDepoimentos {
        width: 100% !important;
    }
    .swiper-slide {
        width: 100% !important;
    }
}
</style>

<!-- Modal para vídeo -->
<div id="modalVideoDepo" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.8);align-items:center;justify-content:center;">
    <div id="modalVideoContent" style="position:relative;width:90vw;max-width:600px;height:56vw;max-height:338px;margin:auto;top:10vh;">
        <span id="closeModalVideo" style="position:absolute;top:-30px;right:0;font-size:2em;color:#fff;cursor:pointer;z-index:10001;">&times;</span>
        <iframe id="iframeVideoDepo" src="" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ao clicar no link do depoimento
    document.querySelectorAll('.QuemRecomendaVideos li a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('rel');
            if (!url) return;
            // Tenta identificar se é YouTube ou Vimeo e montar embed com autoplay
            var embed = '';
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                var videoId = '';
                if (url.includes('youtu.be/')) {
                    videoId = url.split('youtu.be/')[1].split(/[?&]/)[0];
                } else if (url.includes('v=')) {
                    videoId = url.split('v=')[1].split('&')[0];
                }
                embed = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
            } else if (url.includes('vimeo.com')) {
                var vimeoId = url.split('vimeo.com/')[1].split(/[?&]/)[0];
                embed = 'https://player.vimeo.com/video/' + vimeoId + '?autoplay=1';
            } else {
                embed = url; // fallback
            }
            document.getElementById('iframeVideoDepo').src = embed;
            document.getElementById('modalVideoDepo').style.display = 'flex';
        });
    });
    // Fechar modal
    document.getElementById('closeModalVideo').onclick = function() {
        document.getElementById('modalVideoDepo').style.display = 'none';
        document.getElementById('iframeVideoDepo').src = '';
    };
    // Fechar ao clicar fora do vídeo
    document.getElementById('modalVideoDepo').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            document.getElementById('iframeVideoDepo').src = '';
        }
    });
});
</script>


<!-- Modal customizado para vídeo -->
<div id="modalVideoDepoCustom" style="display:none;position:fixed;z-index:2147483647;top:0;left:0;width:100vw;height:100vh;min-width:100vw;min-height:100vh;background:rgba(0,0,0,0.8);align-items:center;justify-content:center;overflow:auto;">
    <div id="modalVideoContentCustom" style="position:relative;width:90vw;max-width:600px;aspect-ratio:16/9;background:#000;border-radius:8px;box-shadow:0 0 30px #000;margin:auto;top:10vh;display:flex;flex-direction:column;align-items:center;justify-content:center;">
        <span id="closeModalVideoCustom" style="position:absolute;top:-30px;right:0;font-size:2em;color:#fff;cursor:pointer;z-index:10001;">&times;</span>
        <iframe id="iframeVideoDepoCustom" src="" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style="border-radius:8px;"></iframe>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Esconde bloco se não houver depoimentos
        if($(".swiper-slide").length === 0) {
            $(".VideosBottomDepoimentos").parent().parent().parent().remove();
            $(".wrap-metodologias").css("margin-top", "0");
        }
    });
</script>

</div>