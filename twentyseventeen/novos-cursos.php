<?php
/*
 * Template Name: Novos Cursos - Swiper - Lancamentos 2026
 *
 * Campos ACF principais (em Opcoes):
 * - lancamentos_2026_titulo (Text)
 * - card_lancamentos (Repeater)
 *   - imagem_topo_card (Imagem)
 *   - selo_card (True/False)
 *   - tipo_card (Text)
 *   - tags_card (Repeater)
 *     - tag_card (Text)
 *   - nome_do_curso_do_card (Text)
 *   - duracao_do_curso_do_card (Text)
 *   - cta_do_card (Text)
 *   - link_do_cta_do_card (Text)
 */
$is_direct_template = isset($GLOBALS['template']) && realpath((string) $GLOBALS['template']) === realpath(__FILE__);
if ($is_direct_template) {
  get_header();
}

$section_title = 'Lançamentos 2026';
if (function_exists('get_field')) {
  $title_option = get_field('lancamentos_2026_titulo', 'option');
  if (!empty($title_option)) {
    $section_title = $title_option;
  }
}

$resolve_chip_style = static function ($text, $style = '') {
  $style = sanitize_key((string) $style);
  if (in_array($style, array('green', 'purple', 'gray', 'orange', 'blue'), true)) {
    return $style;
  }

  $normalized = strtolower((string) $text);
  if (strpos($normalized, 'confirmada') !== false) {
    return 'green';
  }
  if (strpos($normalized, 'presencial') !== false) {
    return 'blue';
  }
  if (strpos($normalized, 'digital') !== false || strpos($normalized, 'ead') !== false || strpos($normalized, 'ao vivo') !== false) {
    return 'purple';
  }
  if (strpos($normalized, 'inicio') !== false) {
    return 'gray';
  }

  return 'green';
};

$cards_repeater = '';
$cards_scope = 'option';
$cards_candidates = array('card_lancamentos', 'lancamentos_2026_cards');

if (function_exists('have_rows')) {
  foreach ($cards_candidates as $cards_candidate) {
    if (have_rows($cards_candidate, 'option')) {
      $cards_repeater = $cards_candidate;
      break;
    }
  }

  if (empty($cards_repeater)) {
    $cards_scope = '';
    foreach ($cards_candidates as $cards_candidate) {
      if (have_rows($cards_candidate)) {
        $cards_repeater = $cards_candidate;
        break;
      }
    }
  }
}
?>

<section id="lancamentos-2026">
  <div class="lancamentos-wrap">
    <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>
    <div class="title-underline" aria-hidden="true"></div>

    <div class="swiper-shell">
      <div class="swiper" aria-label="Lancamentos 2026">
        <div class="swiper-wrapper">
          <?php
          $has_cards = false;
          if (function_exists('have_rows') && !empty($cards_repeater)) {
            $has_cards = ($cards_scope === 'option') ? have_rows($cards_repeater, 'option') : have_rows($cards_repeater);
          }
          ?>
          <?php if ($has_cards) : ?>
            <?php while (($cards_scope === 'option') ? have_rows($cards_repeater, 'option') : have_rows($cards_repeater)) : the_row(); ?>
              <?php
              $image = get_sub_field('imagem_topo_card');
              if (empty($image)) {
                $image = get_sub_field('card_imagem');
              }
              if (empty($image)) {
                $image = get_sub_field('imagem');
              }

              $image_url = '';
              $image_alt = '';
              if (is_array($image)) {
                $image_url = !empty($image['url']) ? $image['url'] : '';
                $image_alt = !empty($image['alt']) ? $image['alt'] : '';
              } elseif (is_numeric($image)) {
                $image_url = wp_get_attachment_image_url((int) $image, 'large');
              } elseif (is_string($image)) {
                $image_url = $image;
              }

              if (empty($image_url)) {
                $image_url = 'https://via.placeholder.com/640x360?text=Sem+imagem';
              }

              $show_ribbon = (bool) get_sub_field('selo_card');
              if (!$show_ribbon) {
                $show_ribbon = (bool) get_sub_field('card_selo_em_breve');
              }
              if (!$show_ribbon) {
                $show_ribbon = (bool) get_sub_field('selo_em_breve');
              }

              $term = trim((string) get_sub_field('tipo_card'));
              if (empty($term)) {
                $term = trim((string) get_sub_field('card_termo'));
              }
              if (empty($term)) {
                $term = trim((string) get_sub_field('termo'));
              }
              if (empty($term)) {
                $term = trim((string) get_sub_field('nivel'));
              }
              if (empty($term)) {
                $term = 'POS-GRADUACAO';
              }

              $chips = array();
              if (function_exists('have_rows') && have_rows('tags_card')) {
                while (have_rows('tags_card')) : the_row();
                  $chip_text = trim((string) get_sub_field('tag_card'));
                  if (!empty($chip_text)) {
                    $chips[] = array(
                      'text' => $chip_text,
                      'style' => $resolve_chip_style($chip_text),
                    );
                  }
                endwhile;
              }

              if (empty($chips) && function_exists('have_rows') && have_rows('card_elementos')) {
                while (have_rows('card_elementos')) : the_row();
                  $chip_text = trim((string) get_sub_field('chip_texto'));
                  $chip_style = get_sub_field('chip_cor');
                  if (!empty($chip_text)) {
                    $chips[] = array(
                      'text' => $chip_text,
                      'style' => $resolve_chip_style($chip_text, $chip_style),
                    );
                  }
                endwhile;
              }

              if (empty($chips)) {
                $fallback_chip_fields = array('modalidade', 'status', 'start_label');
                foreach ($fallback_chip_fields as $fallback_field) {
                  $chip_text = trim((string) get_sub_field($fallback_field));
                  if (!empty($chip_text)) {
                    $chips[] = array(
                      'text' => $chip_text,
                      'style' => $resolve_chip_style($chip_text),
                    );
                  }
                }
              }

              $course_name = trim((string) get_sub_field('nome_do_curso_do_card'));
              if (empty($course_name)) {
                $course_name = trim((string) get_sub_field('card_nome_curso'));
              }
              if (empty($course_name)) {
                $course_name = trim((string) get_sub_field('nome_curso'));
              }

              $course_duration = trim((string) get_sub_field('duracao_do_curso_do_card'));
              if (empty($course_duration)) {
                $course_duration = trim((string) get_sub_field('card_duracao'));
              }
              if (empty($course_duration)) {
                $course_duration = trim((string) get_sub_field('duracao'));
              }

              $course_duration_display = $course_duration;
              if (!empty($course_duration_display) && !preg_match('/^dura[cç][aã]o\s*:/iu', $course_duration_display)) {
                $course_duration_display = 'Duração: ' . $course_duration_display;
              }

              $button_text = trim((string) get_sub_field('cta_do_card'));
              if (empty($button_text)) {
                $button_text = trim((string) get_sub_field('card_botao_texto'));
              }
              if (empty($button_text)) {
                $button_text = trim((string) get_sub_field('botao_texto'));
              }
              if (empty($button_text)) {
                $button_text = 'QUERO SER AVISADO';
              }

              $button_link = get_sub_field('link_do_cta_do_card');
              if (empty($button_link)) {
                $button_link = get_sub_field('card_botao_link');
              }
              if (empty($button_link)) {
                $button_link = get_sub_field('botao_link');
              }

              $button_url = '';
              $button_target = '';
              if (is_array($button_link)) {
                $button_url = !empty($button_link['url']) ? $button_link['url'] : '';
                $button_target = !empty($button_link['target']) ? $button_link['target'] : '';
              } elseif (is_string($button_link)) {
                $button_url = trim($button_link);
              }

              $button_style = sanitize_key((string) get_sub_field('card_botao_estilo'));
              if (empty($button_style)) {
                $button_style = sanitize_key((string) get_sub_field('botao_tipo'));
              }
              if (empty($button_style)) {
                $button_style = (stripos($button_text, 'saber') !== false) ? 'orange' : 'green';
              }
              $button_class = ($button_style === 'orange') ? 'btn-outline-orange' : 'btn-outline-green';

              if (empty($image_alt)) {
                $image_alt = $course_name;
              }
              ?>

              <div class="swiper-slide">
                <article class="curso-card">
                  <div class="card-image">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">

                    <?php if ($show_ribbon) : ?>
                      <div class="corner-ribbon-svg" aria-hidden="true">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/selo-em-breve.svg'); ?>" alt="">
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="card-body">
                    <div class="labels">
                      <span class="chip chip-term"><?php echo esc_html($term); ?></span>
                      <?php foreach ($chips as $chip) : ?>
                        <span class="chip chip-pill chip-<?php echo esc_attr($chip['style']); ?>"><?php echo esc_html($chip['text']); ?></span>
                      <?php endforeach; ?>
                    </div>

                    <h3 class="card-title"><?php echo esc_html($course_name); ?></h3>

                    <?php if (!empty($course_duration_display)) : ?>
                      <p class="card-duration"><?php echo esc_html($course_duration_display); ?></p>
                    <?php endif; ?>

                    <a class="btn <?php echo esc_attr($button_class); ?>" href="<?php echo !empty($button_url) ? esc_url($button_url) : '#'; ?>" <?php echo !empty($button_target) ? 'target="' . esc_attr($button_target) . '" rel="noopener"' : ''; ?>>
                      <?php echo esc_html($button_text); ?>
                    </a>
                  </div>
                </article>
              </div>
            <?php endwhile; ?>
          <?php else : ?>
            <div class="swiper-slide">
              <article class="curso-card curso-card-empty">
                <div class="card-image card-image-empty"></div>
                <div class="card-body">
                  <h3 class="card-title">Cadastre cards no ACF em Opcoes</h3>
                  <p class="card-duration">Repeater: card_lancamentos</p>
                </div>
              </article>
            </div>
          <?php endif; ?>
        </div>

        <div class="swiper-pagination" aria-hidden="true"></div>
      </div>

      <div class="swiper-button-prev" aria-label="Anterior"></div>
      <div class="swiper-button-next" aria-label="Proximo"></div>
    </div>
  </div>
</section>

<style>
  #lancamentos-2026 {
    background: #edf0f3;
    padding: 64px 0 52px;
  }

  #lancamentos-2026 .lancamentos-wrap {
    max-width: 1180px;
    margin: 0 auto;
    padding: 0 22px;
  }

  #lancamentos-2026 .section-title {
    margin: 0;
    text-align: center;
    font-size: 52px;
    line-height: 1.06;
    font-weight: 800;
    color: #2e3644;
  }

  #lancamentos-2026 .title-underline {
    width: 62px;
    height: 4px;
    border-radius: 3px;
    background: #ef8b1f;
    margin: 14px auto 28px;
  }

  #lancamentos-2026 .swiper-shell {
    position: relative;
    padding: 8px 48px 0;
  }

  #lancamentos-2026 .swiper-slide {
    display: flex;
    justify-content: center;
    min-width: 310px !important;
    width: 310px;
    min-height: 460px;
  }

  #lancamentos-2026 .curso-card {
    width: 340px;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #dde3eb;
    background: #fff;
    box-shadow: 0 4px 16px rgba(18, 34, 56, 0.10);
    display: flex;
    flex-direction: column;
  }

  #lancamentos-2026 .curso-card-empty {
    min-height: 280px;
  }

  #lancamentos-2026 .card-image {
    height: 200px;
    position: relative;
    overflow: hidden;
    background: #dce3ea;
  }

  #lancamentos-2026 .card-image-empty {
    background: linear-gradient(135deg, #d8dee6 0%, #edf1f5 100%);
  }

  #lancamentos-2026 .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  #lancamentos-2026 .corner-ribbon-svg {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 120px;
    height: 120px;
    pointer-events: none;
    z-index: 2;
  }

  #lancamentos-2026 .corner-ribbon-svg img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  #lancamentos-2026 .card-body {
    padding: 16px 20px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  #lancamentos-2026 .labels {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
    align-items: center;
    margin-bottom: 5px;
  }

  #lancamentos-2026 .chip {
    border-radius: 999px;
    text-transform: uppercase;
    font-weight: 800;
    letter-spacing: 0.02em;
    line-height: 1;
    white-space: nowrap;
  }

  #lancamentos-2026 .chip-term {
    font-size: 10px;
    color: #09a7b8;
    padding: 0;
    margin-right: 3px;
    position: relative;
    top: 1px;
  }

  #lancamentos-2026 .chip-pill {
    font-size: 8.2px;
    color: #fff;
    padding: 5px 5px;
  }

  #lancamentos-2026 .chip-green { background: #13b978; }
  #lancamentos-2026 .chip-purple { background: #8f51e2; }
  #lancamentos-2026 .chip-gray { background: #9eaaba; }
  #lancamentos-2026 .chip-orange { background: #ec8e2f; }
  #lancamentos-2026 .chip-blue { background: #2391d6; }

  #lancamentos-2026 .card-title {
    padding-top: 9px;
    margin: 0 0 6px;
    font-size: 20px;
    line-height: 1.25;
    color: #303846;
    font-weight: 800;
    text-transform: uppercase;
  }

  #lancamentos-2026 .card-duration {
    margin: 0 0 auto;
    font-size: 15px;
    line-height: 1.4;
    color: #475061;
    padding-bottom: 16px;
  }

  #lancamentos-2026 .btn {
    display: block;
    width: 91% !important;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    font-weight: 800;
    line-height: 1;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    padding: 14px 12px;
    transition: background-color 0.2s ease, color 0.2s ease;
    margin-top: auto;
  }

  #lancamentos-2026 .btn-outline-green {
    border: 1.5px solid #09b66f;
    color: #09b66f;
    background: transparent;
  }

  #lancamentos-2026 .btn-outline-green:hover {
    background: #09b66f;
    color: #fff;
  }

  #lancamentos-2026 .btn-outline-orange {
    border: 1.5px solid #ef8b1f;
    color: #ef8b1f;
    background: transparent;
  }

  #lancamentos-2026 .btn-outline-orange:hover {
    background: #ef8b1f;
    color: #fff;
  }

  #lancamentos-2026 .swiper-button-next,
  #lancamentos-2026 .swiper-button-prev {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 1.5px solid #c7ced8;
    background: transparent;
    color: #98a5b7;
    top: 45%;
  }

  #lancamentos-2026 .swiper-button-prev { left: 0; }
  #lancamentos-2026 .swiper-button-next { right: 0; }

  #lancamentos-2026 .swiper-button-next::after,
  #lancamentos-2026 .swiper-button-prev::after {
    font-size: 14px;
    font-weight: 700;
  }

  #lancamentos-2026 .swiper-pagination {
    position: relative;
    margin-top: 22px;
  }

  #lancamentos-2026 .swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    background: transparent;
    border: 1.5px solid #b8c0ce;
    opacity: 1;
    margin: 0 6px;
  }

  #lancamentos-2026 .swiper-pagination-bullet-active {
    background: #ef8b1f;
    border-color: #ef8b1f;
  }

  @media (max-width: 1280px) {
    #lancamentos-2026 .section-title { font-size: 44px; }
  }

  @media (max-width: 1024px) {
    #lancamentos-2026 .section-title { font-size: 38px; }
    #lancamentos-2026 .curso-card { width: 320px; }
  }

  @media (max-width: 768px) {
    #lancamentos-2026 {
      padding: 52px 0 44px;
    }

    #lancamentos-2026 .section-title {
      font-size: 30px;
    }

    #lancamentos-2026 .swiper-shell {
      padding: 8px 0 0;
    }

    #lancamentos-2026 .swiper-button-next,
    #lancamentos-2026 .swiper-button-prev {
      display: none;
    }

    #lancamentos-2026 .curso-card {
      width: 340px;
      max-width: 90vw;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var root = document.querySelector('#lancamentos-2026');
  if (!root) {
    return;
  }

  var swiperEl = root.querySelector('.swiper');
  if (!swiperEl) {
    return;
  }

  var slidesCount = root.querySelectorAll('.swiper-slide').length;

  if (typeof Swiper !== 'undefined') {
    new Swiper(swiperEl, {
      slidesPerView: 3,
      spaceBetween: 20,
      centeredSlides: false,
      // loop: slidesCount > 3,
      watchOverflow: true,
      pagination: {
        el: root.querySelector('.swiper-pagination'),
        clickable: true
      },
      navigation: {
        nextEl: root.querySelector('.swiper-button-next'),
        prevEl: root.querySelector('.swiper-button-prev')
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          spaceBetween: 12
        },
        760: {
          slidesPerView: 2,
          spaceBetween: 16
        },
        1160: {
          slidesPerView: 3,
          spaceBetween: 20
        }
      }
    });
  } else {
    var wrapper = root.querySelector('.swiper-wrapper');
    if (wrapper) {
      wrapper.style.display = 'flex';
      wrapper.style.overflowX = 'auto';
      wrapper.style.gap = '16px';
      wrapper.style.paddingBottom = '12px';
      wrapper.querySelectorAll('.swiper-slide').forEach(function (slide) {
        slide.style.flex = '0 0 auto';
      });
    }
  }
});
</script>

<!-- HUBSPOT FORM MODAL START -->
<style>
  /* Modal styles: personalize as needed */
  .hs-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 99999;
  }
  .hs-modal[aria-hidden="false"] { display: flex; }
  .hs-modal__overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
  }
  .hs-modal__dialog {
    position: relative;
    z-index: 1;
    background: #fff;
    border-radius: 10px;
    width: min(94%, 760px);
    max-height: 100vh;
    overflow: auto;
    box-shadow: 0 14px 48px rgba(10,10,10,0.36);
    padding: 20px;
  }
  .hs-modal__close {
    position: absolute;
    right: 12px;
    top: 8px;
    background: #fff;
    border: 0;
    font-size: 26px;
    line-height: 1;
    cursor: pointer;
    color: #444;
  }
  .hs-modal__header { margin-bottom: 8px; }
  .hs-modal__header h2 { margin: 20px auto -60px auto; font-size: 20px; text-align:center; }
  .hs-modal__body { margin-top: 80px; padding-top: 6px; }
  .hs-form-placeholder { color: #666; padding: 28px; text-align: center; }
  @media (max-width: 420px) {
    .hs-modal__dialog { padding: 14px; }
    .hs-modal__header h2 {
      margin: 80px auto -101px auto;
    }
  }
</style>

<!-- Modal markup (HubSpot target placeholder) -->
<div id="hs-modal" class="hs-modal" aria-hidden="true" role="dialog" aria-labelledby="hs-modal-title" aria-modal="true">
  <div class="hs-modal__overlay" data-hs-close></div>
  <div class="hs-modal__dialog" role="document">
    <button class="hs-modal__close" aria-label="Fechar" data-hs-close>&times;</button>
    <div class="hs-modal__header"><h2 id="hs-modal-title">Deseja ser avisado?</h2></div>
    <div class="hs-modal__body">
      <div id="hs-form-target">
        <!-- Formulário customizado HubSpot - campos conforme print 2 -->
        <form id="hubspot-custom-form" class="hubspot-custom-form" autocomplete="on" style="max-width:480px;margin:0 auto;">
          <div style="display:flex;gap:16px;flex-wrap:wrap;">
            <div style="flex:1 1 180px;min-width:140px;">
              <label for="hs-nome">Nome<span style="color:#f60">*</span></label>
              <input type="text" id="hs-nome" name="firstname" required autocomplete="given-name" style="width:100%">
            </div>
            <div style="flex:1 1 180px;min-width:140px;">
              <label for="hs-sobrenome">Sobrenome<span style="color:#f60">*</span></label>
              <input type="text" id="hs-sobrenome" name="lastname" required autocomplete="family-name" style="width:100%">
            </div>
          </div>
          <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:16px;">
            <div style="flex:1 1 180px;min-width:140px;">
              <label for="hs-email">Email<span style="color:#f60">*</span></label>
              <input type="email" id="hs-email" name="email" required autocomplete="email" style="width:100%">
            </div>
            <div style="flex:1 1 180px;min-width:140px;">
              <label for="hs-celular">Celular<span style="color:#f60">*</span></label>
              <input type="tel" id="hs-celular" name="mobilephone" required autocomplete="tel" style="width:100%" placeholder="+55">
            </div>
          </div>
          <div style="margin-top:16px;">
            <!-- <label for="hs-modalidade">Modalidade<span style="color:#f60">*</span></label> -->
            <!-- Select mantido por compatibilidade, mas oculto visualmente. Valores são também copiados para inputs hidden para envio ao HubSpot -->
            <select id="hs-modalidade" name="modalidade" required style="width:100%;display:none;">
              <option value="">Selecione</option>
              <option value="Presencial">Presencial</option>
              <option value="Digital (EaD)">Digital (EaD)</option>
              <option value="Digital ao vivo">Digital ao vivo</option>
            </select>
            <input type="hidden" id="hs-modalidade-hidden" name="modalidade" value="">
          </div>
          <div style="margin-top:16px;">
            <!-- <label for="hs-curso">Curso de Interesse<span style="color:#f60">*</span></label> -->
            <!-- Select mantido por compatibilidade, mas oculto visualmente. Valores são também copiados para inputs hidden para envio ao HubSpot -->
            <select id="hs-curso" name="curso_de_interesse" required style="width:100%;display:none;">
              <option value="">Selecione</option>
              <option value="Design de Interiores: Pensamento e Produção do Espaço">Design de Interiores: Pensamento e Produção do Espaço</option>
              <!-- Adicione mais opções conforme necessário -->
            </select>
            <input type="hidden" id="hs-curso-hidden" name="curso_de_interesse" value="">
          </div>
          <div style="margin:18px 0 0 0;font-size:13px;color:#444;line-height:1.4;">
            Ao enviar o formulário você concorda com a Política de Privacidade da UNISUAM.<br>
            Acesse a Política de Privacidade da UNISUAM <a href="https://www.unisuam.edu.br/politica-de-privacidade/" target="_blank" rel="noopener">aqui</a>.
          </div>
          <div style="margin-top:22px;text-align:center;">
            <button type="submit" style="background:#ff9800;color:#fff;font-weight:700;font-size:18px;padding:10px 38px;border:none;border-radius:4px;cursor:pointer;">ENVIAR</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>








<script>
/*
  HubSpot Modal (documentado)

  Como usar (simples):
  - Se tiver o embed do HubSpot: cole-o dentro de <div id="hs-form-target">.
  - Se preferir o carregamento programático, edite as constantes abaixo:
      HUBSPOT_PORTAL_ID = 'SEU_PORTAL_ID';
      HUBSPOT_FORM_ID   = 'SEU_FORM_ID';
  - O modal abre automaticamente quando um link com classe `.btn` dentro de
    `.curso-card` tem `href="#"` (ou vazio / javascript:void(0)).
  - Para abrir programaticamente: use `window.HSModal.open()`.
*/
(function () {
  // === CONFIGURE AQUI ===
  // Portal e Form ID atualizados conforme solicitado
  var HUBSPOT_PORTAL_ID = '3462868'; // portal
  var HUBSPOT_FORM_ID = 'd996f1df-3404-4d13-8248-69eafdb893e0'; // novo form ID
  // =======================

  var modal = document.getElementById('hs-modal');
  var lastFocused = null;
  var lastClickedCardData = null;

  // Title Case em português (reutilizável) - mantém conectores em minúsculas
  function titleCasePortuguese(input) {
    if (!input || typeof input !== 'string') return '';
    var smallWords = ['a','e','o','as','os','um','uma','uns','umas','do','da','dos','das','de','em','no','na','nos','nas','por','para','com','sem','sob','sobre','entre','até','ao','aos','às','à','ou','pelo','pela','pelos','pelas','da','di'];
    var cap = function(w){ return w.charAt(0).toUpperCase() + w.slice(1).toLowerCase(); };
    input = input.trim().replace(/\s+/g, ' ');
    var parts = input.split(' ');
    for (var i = 0; i < parts.length; i++) {
      var w = parts[i];
      if (!w) continue;
      if (w.indexOf('-') !== -1) {
        parts[i] = w.split('-').map(function(p, idx){ var lp = p.toLowerCase(); if (i === 0 || smallWords.indexOf(lp) === -1) return cap(lp); return lp; }).join('-');
        continue;
      }
      var lw = w.toLowerCase();
      if (i === 0 || smallWords.indexOf(lw) === -1) {
        parts[i] = cap(lw);
      } else {
        parts[i] = lw;
      }
    }
    return parts.join(' ');
  }

  // Normaliza os rótulos de modalidade para os 3 formatos esperados no funil
  function normalizeModalidadeLabel(input) {
    var raw = (input || '').toString().trim();
    if (!raw) return '';
    var txt = raw.toLowerCase();
    if (txt.indexOf('presencial') !== -1) return 'Presencial';
    if (txt.indexOf('ao vivo') !== -1 || txt.indexOf('aovivo') !== -1 || txt.indexOf('semipres') !== -1 || txt.indexOf('semipresencial') !== -1 || txt.indexOf('webconferencia') !== -1) return 'Digital ao vivo';
    if (txt.indexOf('ead') !== -1 || txt.indexOf('online') !== -1 || txt.indexOf('digital') !== -1) return 'Digital (EaD)';
    return raw;
  }

  // Resolve qual campo de curso deve receber valor conforme a modalidade
  function resolveHubspotCursoInteresseField(modalidadeLabel) {
    var mode = normalizeModalidadeLabel(modalidadeLabel).toLowerCase();
    if (mode.indexOf('presencial') !== -1) return 'pos___curso_de_interesse___presencial';
    if (mode.indexOf('ao vivo') !== -1) return 'pos___curso_de_interesse___webconferencia';
    return 'pos___curso_de_interesse___ead';
  }

  function normalizePhoneDigits(value) {
    return (value || '').toString().replace(/\D/g, '').slice(0, 11);
  }

  function formatPhoneBR(value) {
    var digits = normalizePhoneDigits(value);
    if (!digits) return '';
    var ddd = digits.slice(0, 2);
    var partA = '';
    var partB = '';
    if (digits.length <= 10) {
      partA = digits.slice(2, 6);
      partB = digits.slice(6, 10);
    } else {
      partA = digits.slice(2, 7);
      partB = digits.slice(7, 11);
    }
    if (!ddd) return partA + (partB ? '-' + partB : '');
    if (!partA) return '(' + ddd + ')';
    return '(' + ddd + ') ' + partA + (partB ? '-' + partB : '');
  }

  function attachPhoneMask() {
    var phoneInput = document.getElementById('hs-celular');
    if (!phoneInput || phoneInput.__hsMaskAttached) return;
    phoneInput.__hsMaskAttached = true;

    var applyMask = function () {
      phoneInput.value = formatPhoneBR(phoneInput.value);
    };

    phoneInput.addEventListener('input', applyMask);
    phoneInput.addEventListener('blur', applyMask);
  }

  function initModal() {
    if (!modal) return;
    modal.querySelectorAll('[data-hs-close]').forEach(function (el) {
      el.addEventListener('click', closeModal);
    });
    // overlay click
    var overlay = modal.querySelector('.hs-modal__overlay');
    if (overlay) overlay.addEventListener('click', closeModal);
    // ESC key
    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
        closeModal();
      }
    });
    // basic focus trap
    modal.addEventListener('keydown', function (ev) {
      if (ev.key !== 'Tab') return;
      var focusable = modal.querySelectorAll('a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])');
      if (!focusable.length) return;
      focusable = Array.prototype.slice.call(focusable);
      var first = focusable[0], last = focusable[focusable.length - 1];
      if (ev.shiftKey && document.activeElement === first) {
        last.focus(); ev.preventDefault();
      } else if (!ev.shiftKey && document.activeElement === last) {
        first.focus(); ev.preventDefault();
      }
    });
  }

  function openModal() {
    if (!modal) return;
    lastFocused = document.activeElement;
    modal.setAttribute('aria-hidden', 'false');
    // load form if necessary
    maybeLoadHubspotForm();
    // focus close button for accessibility
    var closeBtn = modal.querySelector('.hs-modal__close');
    if (closeBtn) closeBtn.focus();
  }

  function closeModal() {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'true');
    if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
  }

  function maybeLoadHubspotForm() {
    var target = document.getElementById('hs-form-target');
    if (!target) return;
    // if user already pasted embed or form created, skip creating another
    if (target.querySelector('iframe, form, script')) {
      // ainda assim tentamos anexar metadados se o form já existir
      var existing = target.querySelector('form');
      if (existing) try { ensureFormHasMetadataInputs(existing); updateAttachedHubspotFormMetadata(); } catch (e) {}
      return;
    }

    if (HUBSPOT_PORTAL_ID && HUBSPOT_FORM_ID) {
      // load hubspot forms lib once and attach onFormReady
      function render() {
        try {
          var attachReady = function() {
            // tenta anexar caso o form já esteja disponível
            var f = document.querySelector('#hs-form-target form');
            if (f) { ensureFormHasMetadataInputs(f); updateAttachedHubspotFormMetadata(); }
          };

          if (window.hbspt && window.hbspt.forms) {
            window.hbspt.forms.create({
              portalId: HUBSPOT_PORTAL_ID,
              formId: HUBSPOT_FORM_ID,
              target: '#hs-form-target',
              onFormReady: function(form) {
                var theForm = form && form.nodeName === 'FORM' ? form : (document.querySelector('#hs-form-target form') || null);
                if (theForm) { ensureFormHasMetadataInputs(theForm); updateAttachedHubspotFormMetadata(); }
              }
            });
            attachReady();
          } else {
            var s = document.createElement('script');
            s.src = 'https://js.hsforms.net/forms/v2.js';
            s.async = true;
            s.onload = function () {
              try {
                window.hbspt.forms.create({
                  portalId: HUBSPOT_PORTAL_ID,
                  formId: HUBSPOT_FORM_ID,
                  target: '#hs-form-target',
                  onFormReady: function(form) {
                    var theForm = form && form.nodeName === 'FORM' ? form : (document.querySelector('#hs-form-target form') || null);
                    if (theForm) { ensureFormHasMetadataInputs(theForm); updateAttachedHubspotFormMetadata(); }
                  }
                });
                attachReady();
              } catch (err) {
                target.innerHTML = '<p class="hs-form-placeholder">Erro ao inicializar o formulário HubSpot.</p>';
              }
            };
            s.onerror = function () {
              target.innerHTML = '<p class="hs-form-placeholder">Erro ao carregar a biblioteca do HubSpot.</p>';
            };
            document.head.appendChild(s);
          }
        } catch (e) {
          target.innerHTML = '<p class="hs-form-placeholder">Erro ao carregar o formulário HubSpot.</p>';
        }
      }
      render();

      // observe alterações no target (caso o usuário cole um embed que cria o form)
      try {
        var mo = new MutationObserver(function() {
          var f = target.querySelector('form');
          if (f) { ensureFormHasMetadataInputs(f); updateAttachedHubspotFormMetadata(); }
        });
        mo.observe(target, { childList: true, subtree: true });
      } catch (e) {}
    } else {
      // keep placeholder (instruções)
    }
  }

  // Delegated click handler: intercept .curso-card .btn anchors with href '#'
  function delegatedClickHandler(ev) {
    var el = ev.target;
    while (el && el !== document.body && el.tagName !== 'A') el = el.parentElement;
    if (!el || el.tagName !== 'A') return;
    if (!el.classList.contains('btn')) return;
    var href = (el.getAttribute('href') || '').trim();
    if (href === '#' || href === '' || href === 'javascript:void(0)') {
      ev.preventDefault();
      // coleta metadados do card que originou o clique (se houver)
      try {
        var originCard = el.closest ? el.closest('.curso-card') : null;
        var cardTitle = '';
        var cardTerm = '';
        var cardTags = [];
        if (originCard) {
          var t = originCard.querySelector('.card-title');
          cardTitle = t ? (t.textContent || '').trim() : '';
          var tr = originCard.querySelector('.chip-term');
          cardTerm = tr ? (tr.textContent || '').trim() : '';
          var pills = originCard.querySelectorAll('.chip-pill');
          if (pills && pills.length) {
            pills.forEach(function(p) {
              var txt = (p.textContent || '').trim();
              if (txt) cardTags.push(txt);
            });
          }
        }
        lastClickedCardData = { title: cardTitle, term: cardTerm, tags: cardTags };
        window.__hsLastCard = lastClickedCardData;
        try { updateAttachedHubspotFormMetadata(); } catch (e) {}
      } catch (e) {
        // silencioso
      }
      openModal();
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    initModal();
    attachPhoneMask();
    // attach to container to avoid global noise
    var container = document.querySelector('#lancamentos-2026') || document;
    container.addEventListener('click', delegatedClickHandler);
    // attach custom form submit handler (se existir)
    try { if (typeof attachCustomFormSubmitHandler === 'function') attachCustomFormSubmitHandler(); } catch (e) {}
  });

  // public API
  window.HSModal = {
    open: openModal,
    close: closeModal,
    loadHubspotForm: maybeLoadHubspotForm,
    // Retorna um array compatível com o payload do HubSpot (fields: [{name,value},...])
    collectCardFields: function() {
      var last = window.__hsLastCard || lastClickedCardData || { title: '', term: '', tags: [] };
      var out = [];
      // Nomes de campos HubSpot - altere aqui se seus campos tiverem outros nomes
      var FIELD_COURSE = 'descricao_curso'; // corresponde ao título do card
      var FIELD_TYPE = 'curso_tipo'; // corresponde ao .chip-term
      var FIELD_TAGS = 'curso_tags'; // tags combinadas (CSV)
      // normaliza título (Title Case pt-BR)
      var courseNormalized = titleCasePortuguese(last.title || '');
      if (courseNormalized) out.push({ name: FIELD_COURSE, value: courseNormalized });
      if (last.term) out.push({ name: FIELD_TYPE, value: last.term });
      if (Array.isArray(last.tags) && last.tags.length) {
        out.push({ name: FIELD_TAGS, value: last.tags.join(', ') });
        last.tags.forEach(function(tag, idx) {
          out.push({ name: 'curso_tag_' + (idx + 1), value: tag });
        });
      }

      // heurística para Modalidade (a mesma usada ao popular o modal)
      var modalCandidate = '';
      if (Array.isArray(last.tags) && last.tags.length) {
        for (var i = 0; i < last.tags.length; i++) {
          var txt = (last.tags[i] || '').toString().toLowerCase();
          if (txt.indexOf('ao vivo') !== -1 || txt.indexOf('aovivo') !== -1 || txt.indexOf('semipres') !== -1 || txt.indexOf('semipresencial') !== -1) { modalCandidate = 'Digital ao vivo'; break; }
          if (txt.indexOf('digital') !== -1 || txt.indexOf('ead') !== -1 || txt.indexOf('online') !== -1) { modalCandidate = 'Digital (EaD)'; break; }
          if (txt.indexOf('presencial') !== -1) { modalCandidate = 'Presencial'; break; }
        }
        if (!modalCandidate) modalCandidate = normalizeModalidadeLabel((last.tags[0] || '').toString());
      }
      if (!modalCandidate && last.term) modalCandidate = normalizeModalidadeLabel(last.term);
      modalCandidate = normalizeModalidadeLabel(modalCandidate);

      // adiciona campos adicionais esperados: modalidade e curso_de_interesse
      if (modalCandidate) {
        out.push({ name: 'modalidade', value: modalCandidate });
        out.push({ name: 'pos_modalidade', value: modalCandidate });
      }
      if (courseNormalized) {
        out.push({ name: 'curso_de_interesse', value: courseNormalized });
        // Encaminha para o campo correto e também preenche os 3 campos para atender validações do form
        var mappedField = resolveHubspotCursoInteresseField(modalCandidate);
        out.push({ name: mappedField, value: courseNormalized });
        ['pos___curso_de_interesse___presencial','pos___curso_de_interesse___ead','pos___curso_de_interesse___webconferencia'].forEach(function(fieldName) {
          if (fieldName !== mappedField) out.push({ name: fieldName, value: courseNormalized });
        });
      }
      return out;
    }
  };

  // --- helpers locais para injetar campos ocultos no formulário HubSpot ---
  function ensureFormHasMetadataInputs(form) {
    if (!form || form.nodeName !== 'FORM') return;
    var container = form.querySelector('.hs-card-metadata');
    if (!container) {
      container = document.createElement('div');
      container.className = 'hs-card-metadata';
      container.style.display = 'none';
      form.appendChild(container);
    }
    var names = ['descricao_curso','curso_tipo','curso_tags','modalidade','curso_de_interesse'];
    names.forEach(function(n) {
      if (!container.querySelector('input[name="' + n + '"]')) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = n;
        container.appendChild(inp);
      }
    });
    Array.prototype.slice.call(container.querySelectorAll('input[name^="curso_tag_"]')).forEach(function(i){ i.remove(); });
  }

  function updateAttachedHubspotFormMetadata() {
    var target = document.getElementById('hs-form-target');
    if (!target) return;
    var form = target.querySelector('form');
    if (!form) return;
    ensureFormHasMetadataInputs(form);
    var container = form.querySelector('.hs-card-metadata');
    if (!container) return;
    var last = window.__hsLastCard || lastClickedCardData || { title: '', term: '', tags: [] };

    var courseNormalized = titleCasePortuguese(last.title || '');

    // heurística para Modalidade (a mesma usada ao popular o modal)
    var modalCandidate = '';
    if (Array.isArray(last.tags) && last.tags.length) {
      for (var mi = 0; mi < last.tags.length; mi++) {
        var txt = (last.tags[mi] || '').toString().toLowerCase();
        if (txt.indexOf('ao vivo') !== -1 || txt.indexOf('aovivo') !== -1 || txt.indexOf('semipres') !== -1 || txt.indexOf('semipresencial') !== -1) { modalCandidate = 'Digital ao vivo'; break; }
        if (txt.indexOf('digital') !== -1 || txt.indexOf('ead') !== -1 || txt.indexOf('online') !== -1) { modalCandidate = 'Digital (EaD)'; break; }
        if (txt.indexOf('presencial') !== -1) { modalCandidate = 'Presencial'; break; }
      }
      if (!modalCandidate) modalCandidate = normalizeModalidadeLabel((last.tags[0] || '').toString());
    }
    if (!modalCandidate && last.term) modalCandidate = normalizeModalidadeLabel(last.term);
    modalCandidate = normalizeModalidadeLabel(modalCandidate);

    var setVal = function(name, value) {
      var el = container.querySelector('input[name="' + name + '"]');
      if (!el) {
        el = document.createElement('input'); el.type = 'hidden'; el.name = name; container.appendChild(el);
      }
      el.value = value || '';
    };
    // usa versão capitalizada do título do curso
    setVal('descricao_curso', courseNormalized);
    setVal('curso_tipo', last.term || '');
    setVal('curso_tags', Array.isArray(last.tags) ? last.tags.join(', ') : '');
    // garante envio de Modalidade e Curso de Interesse (campos ocultos)
    setVal('modalidade', modalCandidate || '');
    setVal('curso_de_interesse', courseNormalized || '');
    if (Array.isArray(last.tags)) {
      last.tags.forEach(function(tag, idx) {
        var name = 'curso_tag_' + (idx + 1);
        var inp = container.querySelector('input[name="' + name + '"]');
        if (!inp) { inp = document.createElement('input'); inp.type = 'hidden'; inp.name = name; container.appendChild(inp); }
        inp.value = tag || '';
      });
    }

    // Também popula selects/custom fields do formulário customizado (se houver)
    try {
      var targetFormCustom = document.querySelector('#hs-form-target #hubspot-custom-form') || document.querySelector('#hs-form-target form') || document.querySelector('#hubspot-custom-form');
      if (targetFormCustom) {
        // Reutiliza valores já computados acima
        var courseCandidate = courseNormalized;

        // Função utilitária para setar select (procura por id/text/value; cria option se não existir)
        function setSelectValue(selectEl, value) {
          if (!selectEl || !value) return;
          var v = value.toString();
          // procura opção por value ou texto
          var found = false;
          for (var j = 0; j < selectEl.options.length; j++) {
            var opt = selectEl.options[j];
            if ((opt.value && opt.value.toString() === v) || (opt.text && opt.text.toString() === v)) {
              selectEl.selectedIndex = j; found = true; break;
            }
          }
          if (!found) {
            try {
              var newOpt = document.createElement('option');
              newOpt.value = v;
              newOpt.text = value;
              selectEl.appendChild(newOpt);
              selectEl.value = v;
            } catch (e) { /* silencioso */ }
          }
          // dispara evento change
          try { selectEl.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) {}
        }

        // tenta localizar selects/inputes comuns
        var selModal = targetFormCustom.querySelector('#hs-modalidade') || targetFormCustom.querySelector('[name="modalidade"]');
        var selCurso = targetFormCustom.querySelector('#hs-curso') || targetFormCustom.querySelector('[name="curso_de_interesse"]') || targetFormCustom.querySelector('[name="curso"]');

        if (selModal && modalCandidate) setSelectValue(selModal, modalCandidate);
        if (selCurso && courseCandidate) setSelectValue(selCurso, courseCandidate);

        // também garante que os inputs hidden no formulário custom existam e estejam atualizados
        try {
          var hidModal = targetFormCustom.querySelector('#hs-modalidade-hidden') || targetFormCustom.querySelector('input[name="modalidade"]');
          if (!hidModal) { hidModal = document.createElement('input'); hidModal.type = 'hidden'; hidModal.name = 'modalidade'; hidModal.id = 'hs-modalidade-hidden'; targetFormCustom.appendChild(hidModal); }
          hidModal.value = modalCandidate || '';
          var hidCurso = targetFormCustom.querySelector('#hs-curso-hidden') || targetFormCustom.querySelector('input[name="curso_de_interesse"]');
          if (!hidCurso) { hidCurso = document.createElement('input'); hidCurso.type = 'hidden'; hidCurso.name = 'curso_de_interesse'; hidCurso.id = 'hs-curso-hidden'; targetFormCustom.appendChild(hidCurso); }
          hidCurso.value = courseCandidate || '';
        } catch (er) { /* silencioso */ }
      }
    } catch (e) { /* silencioso */ }
  }
  // Função utilitária: mostra mensagem de sucesso no modal (substitui o conteúdo do target)
  function showHubspotSuccessMessage(msg) {
    msg = msg || 'Informações enviadas com sucesso!';
    var target = document.getElementById('hs-form-target');
    if (!target) return;
    var html = '<div class="hs-form-success" style="padding:28px;text-align:center;max-width:520px;margin:0 auto;">'
      + '<h3 style="margin:0 0 8px">' + msg + '</h3>'
      + '<p style="margin:0 0 12px;color:#555">Obrigado pelo interesse — entraremos em contato em breve.</p>'
      + '<div><button class="hs-success-close" style="background:#ff9800;color:#fff;border:none;padding:8px 18px;border-radius:4px;cursor:pointer">Fechar</button></div>'
      + '</div>';
    target.innerHTML = html;
    var btn = target.querySelector('.hs-success-close');
    if (btn) btn.addEventListener('click', function(){ closeModal(); });
  }

  // Anexa handler de submit ao formulário customizado para enviar ao HubSpot via API
  function attachCustomFormSubmitHandler() {
    var form = document.getElementById('hubspot-custom-form');
    if (!form) return;
    if (form.__hsAttached) return; // evita múltiplos listeners
    form.__hsAttached = true;
    form.addEventListener('submit', function(ev) {
      ev.preventDefault();
      try { updateAttachedHubspotFormMetadata(); } catch (e) {}
      var submitBtn = form.querySelector('button[type="submit"]');
      var originalBtnText = submitBtn ? submitBtn.textContent : '';
      if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Enviando...'; }

      // coleta campos do form
      var elements = form.querySelectorAll('input, select, textarea');
      var fields = [];
      var seen = {};
      for (var i = 0; i < elements.length; i++) {
        var el = elements[i];
        if (!el.name) continue;
        if ((el.type === 'checkbox' || el.type === 'radio') && !el.checked) continue;
        var val = el.value;
        if (typeof val === 'string' && val.trim() === '') continue;
        fields.push({ name: el.name, value: val });
        seen[el.name] = true;
      }

      // garante mapeamento explícito para os 3 campos de curso por modalidade
      var modalidadeNode = form.querySelector('#hs-modalidade-hidden') || form.querySelector('#hs-modalidade') || form.querySelector('[name="modalidade"]');
      var cursoNode = form.querySelector('#hs-curso-hidden') || form.querySelector('#hs-curso') || form.querySelector('[name="curso_de_interesse"]');
      var modalidadeValue = normalizeModalidadeLabel(modalidadeNode ? (modalidadeNode.value || '') : '');
      var cursoValue = titleCasePortuguese(cursoNode ? (cursoNode.value || '') : '');
      var phoneDigits = normalizePhoneDigits((form.querySelector('#hs-celular') ? form.querySelector('#hs-celular').value : ''));

      if (modalidadeValue) {
        if (!seen.modalidade) {
          fields.push({ name: 'modalidade', value: modalidadeValue });
          seen.modalidade = true;
        }
        if (!seen.pos_modalidade) {
          fields.push({ name: 'pos_modalidade', value: modalidadeValue });
          seen.pos_modalidade = true;
        }
      }
      if (cursoValue) {
        var mappedCursoField = resolveHubspotCursoInteresseField(modalidadeValue);
        // Campo específico por modalidade (ex.: Digital ao vivo -> webconferencia)
        if (!seen[mappedCursoField]) {
          fields.push({ name: mappedCursoField, value: cursoValue });
          seen[mappedCursoField] = true;
        }
        // Mantém os 3 campos preenchidos para evitar falhas por regra obrigatória dinâmica no HubSpot
        ['pos___curso_de_interesse___presencial','pos___curso_de_interesse___ead','pos___curso_de_interesse___webconferencia'].forEach(function(fieldName) {
          if (!seen[fieldName]) {
            fields.push({ name: fieldName, value: cursoValue });
            seen[fieldName] = true;
          }
        });
      }

      // Campo telefone obrigatório no form atual do HubSpot
      if (phoneDigits && !seen['0-2/phone']) {
        fields.push({ name: '0-2/phone', value: phoneDigits });
        seen['0-2/phone'] = true;
      }

      // campos extras do card
      try {
        var extra = (window.HSModal && typeof window.HSModal.collectCardFields === 'function') ? window.HSModal.collectCardFields() : [];
        extra.forEach(function(f) { if (!seen[f.name]) { fields.push(f); seen[f.name] = true; } });
      } catch (e) {}

      var body = { submittedAt: Date.now(), fields: fields, context: { pageUri: location.href, pageName: document.title } };
      var url = 'https://api.hsforms.com/submissions/v3/integration/submit/' + HUBSPOT_PORTAL_ID + '/' + HUBSPOT_FORM_ID;
      window.fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }).then(function(resp) {
        if (resp && resp.ok) {
          try { form.reset(); } catch (e) {}
          showHubspotSuccessMessage();
        } else {
          return resp.text().then(function(txt){
            var msg = txt || ('status ' + (resp && resp.status));
            try {
              var parsed = JSON.parse(txt || '{}');
              if (parsed && parsed.errors && parsed.errors.length && parsed.errors[0].message) {
                msg = parsed.errors[0].message;
              }
            } catch (e) {}
            throw new Error(msg);
          });
        }
      }).catch(function(err) {
        try {
          var errEl = form.querySelector('.hs-form-error');
          if (!errEl) { errEl = document.createElement('div'); errEl.className = 'hs-form-error'; errEl.style.color = '#c00'; form.insertBefore(errEl, form.firstChild); }
          errEl.textContent = 'Erro ao enviar. Verifique os dados e tente novamente.';
        } catch (e) {}
        try { console.error('HubSpot submit error:', err); } catch (e) {}
      }).finally(function() {
        if (submitBtn) { submitBtn.disabled = false; if (originalBtnText) submitBtn.textContent = originalBtnText; }
      });
    });
  }

  // Intercepta fetch para o endpoint do HubSpot, injeta campos do card automaticamente
  try {
    var __origFetch = window.fetch;
    if (typeof __origFetch === 'function') {
      window.fetch = function(input, init) {
        var url = typeof input === 'string' ? input : (input && input.url) ? input.url : '';
        var isHubspot = (typeof url === 'string' && url.indexOf('api.hsforms.com/submissions/v3/integration/submit') !== -1);
        try {
          if (isHubspot) {
            if (init && init.body && typeof init.body === 'string') {
              var obj = JSON.parse(init.body);
              if (obj && Array.isArray(obj.fields)) {
                var extra = (window.HSModal && typeof window.HSModal.collectCardFields === 'function') ? window.HSModal.collectCardFields() : [];
                var existing = obj.fields.map(function(f){ return f.name; });
                extra.forEach(function(f){ if (existing.indexOf(f.name) === -1) obj.fields.push(f); });
                init = Object.assign({}, init, { body: JSON.stringify(obj) });
              }
            }
          }
        } catch (e) { /* silencioso */ }
        var promise = __origFetch.call(this, input, init);
        try {
          if (isHubspot && promise && typeof promise.then === 'function') {
            promise = promise.then(function(resp) {
              try { if (resp && resp.ok) showHubspotSuccessMessage(); } catch (e) {}
              return resp;
            });
          }
        } catch (e) {}
        return promise;
      };
    }
  } catch (e) { /* silencioso */ }
})();
</script>

<!-- HUBSPOT FORM MODAL END -->

<!-- =====================================================================
  REGRA TEMPORÁRIA — Correção do nome do curso "Nutrição esportiva, Estética e Emagrecimento"
  Motivo: titleCasePortuguese() capitaliza "esportiva" → "Esportiva", mas o
          HubSpot/CRM precisa receber o nome exatamente como abaixo.
  Remover quando: o título do card for ajustado para coincidir com o nome
                  esperado, ou quando a lógica de titleCase for corrigida.
  Criado em: <?php echo date('Y-m-d'); ?>
====================================================================== -->
<script>
(function () {
  /* Mapeamento: chave = qualquer variação que titleCase pode gerar,
     valor = string exata a enviar ao HubSpot */
  var CURSO_NAME_OVERRIDES = {
    'nutrição esportiva, estética e emagrecimento': 'Nutrição esportiva, Estética e Emagrecimento',
    'nutrição esportiva estética e emagrecimento':  'Nutrição esportiva, Estética e Emagrecimento'
  };

  /* Campos de curso que podem receber o nome */
  var CURSO_FIELDS = [
    'descricao_curso',
    'curso_de_interesse',
    'pos___curso_de_interesse___presencial',
    'pos___curso_de_interesse___ead',
    'pos___curso_de_interesse___webconferencia'
  ];

  function applyOverrides(fields) {
    if (!Array.isArray(fields)) return fields;
    return fields.map(function (f) {
      if (CURSO_FIELDS.indexOf(f.name) === -1) return f;
      var normalized = (f.value || '').toLowerCase().replace(/\s+/g, ' ').trim();
      if (CURSO_NAME_OVERRIDES[normalized]) {
        return { name: f.name, value: CURSO_NAME_OVERRIDES[normalized] };
      }
      return f;
    });
  }

  /* Intercepta o fetch para o endpoint do HubSpot e aplica a correção */
  var __origFetchOverride = window.fetch;
  if (typeof __origFetchOverride === 'function') {
    window.fetch = function (input, init) {
      var url = typeof input === 'string' ? input : (input && input.url ? input.url : '');
      var isHubspot = (typeof url === 'string' && url.indexOf('api.hsforms.com/submissions/v3/integration/submit') !== -1);
      if (isHubspot && init && init.body && typeof init.body === 'string') {
        try {
          var obj = JSON.parse(init.body);
          if (obj && Array.isArray(obj.fields)) {
            obj.fields = applyOverrides(obj.fields);
            init = Object.assign({}, init, { body: JSON.stringify(obj) });
          }
        } catch (e) { /* silencioso */ }
      }
      return __origFetchOverride.call(this, input, init);
    };
  }
})();
</script>
<!-- FIM DA REGRA TEMPORÁRIA -->

<?php
if ($is_direct_template) {
  get_footer();
}
?>
