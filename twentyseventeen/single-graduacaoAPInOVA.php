<?php
/**
 * The template for displaying graduacao
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

$current_post_id = get_queried_object_id();
$upload_base_url = wp_upload_dir()['baseurl'] ?? content_url('uploads');
$hero_bg_fallback_url = trailingslashit($upload_base_url) . '2025/09/bgHome.jpg';

if ($current_post_id && !has_post_thumbnail($current_post_id)) {
	$fallback_attachment_id = attachment_url_to_postid($hero_bg_fallback_url);
	if ($fallback_attachment_id) {
		set_post_thumbnail($current_post_id, $fallback_attachment_id);
	}
}

get_header(); 

$hero_bg_url = get_the_post_thumbnail_url($current_post_id, 'full');
if (!$hero_bg_url) {
	$hero_bg_url = $hero_bg_fallback_url;
}

$subtitulo_curso = get_post_meta($current_post_id, 'sub_titulo', true);
// if (!is_string($subtitulo_curso) || trim($subtitulo_curso) === '') {
// 	$subtitulo_curso = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
// }

// Iniciar sessão se não estiver iniciada
// if (!session_id()) {
//     session_start();
// }

// Detectar parâmetros de forma de ingresso na URL
$forma_ingresso_detectada = null;
$formas_ingresso_validas = ['enem', 'segunda_graduacao', 'transferencia'];

foreach ($formas_ingresso_validas as $forma) {
    if (isset($_GET[$forma]) || strpos($_SERVER['REQUEST_URI'], $forma) !== false) {
        $forma_ingresso_detectada = $forma;
        $_SESSION['forma_ingresso'] = $forma;
        break;
    }
}

// Se não encontrou na URL mas tem na sessão, usar da sessão
if (!$forma_ingresso_detectada && isset($_SESSION['forma_ingresso'])) {
    $forma_ingresso_detectada = $_SESSION['forma_ingresso'];
}
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/graduacao.css">
<script>
window.DEFAULT_COURSE_HERO_BG = <?php echo wp_json_encode($hero_bg_url); ?>;
</script>
<script src="<?php echo get_template_directory_uri(); ?>/graduacao.js"></script>

<!-- HubSpot Form Script -->
<!-- Formulários customizados HubSpot carregados inline -->

<?php
	$mneumonico = get_post_meta(get_the_ID(), 'mneumonico', true);
	if (!is_string($mneumonico) || trim($mneumonico) === '') {
		$mneumonico = get_post_meta(get_the_ID(), 'mnemonico', true);
	}
?>

<?php include 'icons.php' ?>

<!-- CDN jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		if ( is_sticky() && is_home() ) :
			echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
		endif;
		?>
	<header class="entry-header headerCurso">

		<div class="breadcrumb">
            <?php if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
            } ?>
        </div>
		<div class="wrapModalidade">
			<h4>GRADUAÇÃO</h4> 
			<div class="innerMod" id="innerMod">
				
			</div>
		</div>
		<?php
		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta">';
				if ( is_single() ) {
					twentyseventeen_posted_on();
				} else {
					echo twentyseventeen_time_link();
					twentyseventeen_edit_link();
				}
				echo '</div><!-- .entry-meta -->';
			}
			if ( is_single() ) {
				the_title( '<h2 class="entry-title">', '</h2>' );
			} elseif ( is_front_page() && is_home() ) {
				the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
			?>

		<div class="ineerDescr">
			<p><?php echo esc_html($subtitulo_curso); ?>
				
			</p>
		</div>

	</header>

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				/* translators: %s: Post title. Only visible to screen readers. */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<script>
document.addEventListener('DOMContentLoaded', function () {
	const replacements = [
		[/\bGRADUACAO\b/g, 'POS-GRADUACAO'],
		[/\bGRADUAÇÃO\b/g, 'POS-GRADUAÇÃO'],
		[/\bGraduacao\b/g, 'Pos-graduacao'],
		[/\bGraduação\b/g, 'Pos-graduação'],
		[/\bgraduacao\b/g, 'pos-graduacao'],
		[/\bgraduação\b/g, 'pos-graduação']
	];

	function replaceText(value) {
		let text = value;
		replacements.forEach(function (pair) {
			text = text.replace(pair[0], pair[1]);
		});
		return text;
	}

	function normalizeVisibleTexts(root) {
		if (!root) return;

		function isInsideFooter(element) {
			if (!element || !element.closest) return false;
			return !!element.closest('#footer, footer.footer, .site-footer');
		}

		if (root.nodeType === 1 && isInsideFooter(root)) {
			return;
		}

		const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
			acceptNode: function (node) {
				if (!node || !node.parentElement) return NodeFilter.FILTER_REJECT;
				if (isInsideFooter(node.parentElement)) return NodeFilter.FILTER_REJECT;
				const tag = node.parentElement.tagName;
				if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'NOSCRIPT') return NodeFilter.FILTER_REJECT;
				if (!node.nodeValue || node.nodeValue.trim() === '') return NodeFilter.FILTER_REJECT;
				return NodeFilter.FILTER_ACCEPT;
			}
		});

		const textNodes = [];
		let current = walker.nextNode();
		while (current) {
			textNodes.push(current);
			current = walker.nextNode();
		}

		textNodes.forEach(function (node) {
			const updated = replaceText(node.nodeValue);
			if (updated !== node.nodeValue) {
				node.nodeValue = updated;
			}
		});

		root.querySelectorAll('[aria-label], [title], [placeholder]').forEach(function (el) {
			if (isInsideFooter(el)) return;
			['aria-label', 'title', 'placeholder'].forEach(function (attr) {
				const original = el.getAttribute(attr);
				if (!original) return;
				const updated = replaceText(original);
				if (updated !== original) {
					el.setAttribute(attr, updated);
				}
			});
		});
	}

	normalizeVisibleTexts(document.body);

	const observer = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			mutation.addedNodes.forEach(function (node) {
				if (node && node.nodeType === 1) {
					normalizeVisibleTexts(node);
				}
			});
		});
	});

	observer.observe(document.body, { childList: true, subtree: true });
});
</script>

	<!-- dados da API  -->
	<?php include 'getAPI_interna.php' ?>

	<?php
	if (!function_exists('formatar_modalidade_label')) {
		function formatar_modalidade_label($valor) {
			if (!is_string($valor)) {
				return '';
			}
			$limpo = trim($valor);
			if ($limpo === '') {
				return '';
			}
			$normalizado = function_exists('remove_accents') ? remove_accents($limpo) : $limpo;
			$normalizado = strtolower($normalizado);
			if (
				strpos($normalizado, 'asemipres') !== false ||
				strpos($normalizado, 'semipres') !== false ||
				strpos($normalizado, 'semi presencial') !== false ||
				strpos($normalizado, 'webconfer') !== false ||
				strpos($normalizado, 'ao vivo') !== false
			) {
				return 'Digital ao Vivo';
			}
			if (strpos($normalizado, 'digital') !== false || strpos($normalizado, 'ead') !== false || strpos($normalizado, 'online') !== false) {
				return 'Digital (EaD)';
			}
			return 'Presencial';
		}
	}

	if (!function_exists('normalizar_modalidade_slug')) {
		function normalizar_modalidade_slug($valor) {
			if (!is_string($valor)) {
				return 'presencial';
			}
			$normalizado = function_exists('remove_accents') ? remove_accents($valor) : $valor;
			$normalizado = strtolower($normalizado);
			if (
				strpos($normalizado, 'asemipres') !== false ||
				strpos($normalizado, 'semipres') !== false ||
				strpos($normalizado, 'semi presencial') !== false ||
				strpos($normalizado, 'webconfer') !== false ||
				strpos($normalizado, 'ao vivo') !== false
			) {
				return 'semipresencial';
			}
			if (strpos($normalizado, 'digital') !== false || strpos($normalizado, 'ead') !== false || strpos($normalizado, 'online') !== false) {
				return 'digital';
			}
			return 'presencial';
		}
	}

	if (!function_exists('modalidade_slug_para_label')) {
		function modalidade_slug_para_label($slug_modalidade) {
			if ($slug_modalidade === 'digital') {
				return 'Digital (EaD)';
			}
			if ($slug_modalidade === 'semipresencial') {
				return 'Digital ao Vivo';
			}
			return 'Presencial';
		}
	}

	if (!function_exists('obter_modalidade_slug_da_url_atual')) {
		function obter_modalidade_slug_da_url_atual() {
			$uri = $_SERVER['REQUEST_URI'] ?? '';
			if (!is_string($uri) || $uri === '') {
				return '';
			}

			$path = wp_parse_url($uri, PHP_URL_PATH);
			if (!is_string($path) || $path === '') {
				return '';
			}

			$path = untrailingslashit($path);
			if (preg_match('/-aovivo$/', $path)) {
				return 'semipresencial';
			}
			if (preg_match('/-digital$/', $path)) {
				return 'digital';
			}

			return '';
		}
	}

	$modalidade_api_label = '';
	if (!empty($data) && is_array($data)) {
		$modalidade_candidates = [];
		if (!empty($data['modalidade']) && is_string($data['modalidade'])) {
			$modalidade_candidates[] = $data['modalidade'];
		}
		if (!empty($data['resumo']['modalidade']) && is_string($data['resumo']['modalidade'])) {
			$modalidade_candidates[] = $data['resumo']['modalidade'];
		}
		if (!empty($data['curso']['modalidade']) && is_string($data['curso']['modalidade'])) {
			$modalidade_candidates[] = $data['curso']['modalidade'];
		}
		foreach ($modalidade_candidates as $candidate) {
			$formatado = formatar_modalidade_label($candidate);
			if ($formatado !== '') {
				$modalidade_api_label = $formatado;
				break;
			}
		}
		if ($modalidade_api_label === '' && !empty($data['modalidades']) && is_array($data['modalidades'])) {
			foreach ($data['modalidades'] as $modalidade_info) {
				if (is_string($modalidade_info)) {
					$formatado = formatar_modalidade_label($modalidade_info);
					if ($formatado !== '') {
						$modalidade_api_label = $formatado;
						break;
					}
				}
				if (is_array($modalidade_info)) {
					foreach (['nome', 'name', 'label'] as $chave_modalidade) {
						if (!empty($modalidade_info[$chave_modalidade])) {
							$formatado = formatar_modalidade_label($modalidade_info[$chave_modalidade]);
							if ($formatado !== '') {
								$modalidade_api_label = $formatado;
								break 2;
							}
						}
					}
				}
			}
		}
		if ($modalidade_api_label === '' && !empty($data['investimentos']) && is_array($data['investimentos'])) {
			foreach ($data['investimentos'] as $investimento_modalidade) {
				if (is_array($investimento_modalidade) && !empty($investimento_modalidade['modalidade'])) {
					$formatado = formatar_modalidade_label($investimento_modalidade['modalidade']);
					if ($formatado !== '') {
						$modalidade_api_label = $formatado;
						break;
					}
				}
			}
		}
	}

	$course_categories_cache = get_the_category();
	$modalidade_category_label = '';
	if (!empty($course_categories_cache) && !is_wp_error($course_categories_cache)) {
		foreach ($course_categories_cache as $cat) {
			if (empty($cat->name)) {
				continue;
			}
			$formatado = formatar_modalidade_label($cat->name);
			if ($formatado !== '') {
				$modalidade_category_label = $formatado;
				break;
			}
		}
	}

	$modalidade_slug_url = obter_modalidade_slug_da_url_atual();
	$modalidade_box_value = $modalidade_category_label ?: $modalidade_api_label;
	if ($modalidade_slug_url !== '') {
		$modalidade_box_value = modalidade_slug_para_label($modalidade_slug_url);
	}
	if ($modalidade_box_value === '') {
		$modalidade_box_value = 'Presencial';
	}

	$modalidade_para_cor = $modalidade_slug_url !== '' ? $modalidade_box_value : ($modalidade_api_label ?: $modalidade_box_value);
	$page_modalidade_slug = normalizar_modalidade_slug($modalidade_para_cor);
	$page_primary_color = '#0F96AE';
	if ($page_modalidade_slug === 'digital') {
		$page_primary_color = '#E5457A';
	} elseif ($page_modalidade_slug === 'semipresencial') {
		$page_primary_color = '#7D378D';
	}
	$gradient_background = 'linear-gradient(90deg, #0F96AE, #E5457A, #0F96AE)';
	if ($page_modalidade_slug === 'digital') {
		$gradient_background = 'linear-gradient(90deg, #E5457A, #E5457A, #E5457A)';
	} elseif ($page_modalidade_slug === 'semipresencial') {
		$gradient_background = 'linear-gradient(90deg, #7D378D, #B187BB, #7D378D)';
	}
	?>

	<!-- Dados já puxados da API -->
	
		<?php foreach ($data['investimentos'] as $investimento) { 
		// $modalidade2 = get_post_meta(get_the_ID(), 'modalidade_type', true);
		$modalidade = "graduacao";
		if ($modalidade) {
			$valorSemDesconto = ceil($investimento['valor'] * 2);
			
		?>

		<section class="box azul" id="box">

			<!-- menu btn -->
			<?php
				// Define a cor do texto conforme a modalidade
				$corTexto = (strpos($_SERVER['REQUEST_URI'], '-digital') !== false) ? '#57606F' : '#57606F';
			?>
			<p div="esconde169" style="font-size:14px;text-align:center;margin-top:-22px;margin-bottom: 18px;line-height:18px; color: <?php echo $corTexto; ?>;">
				Veja os valores por forma de ingresso.
			</p>
			
			<!-- Linha animada com degradê -->
		
			<style>
				@keyframes gradientShift {
					0% {
						background-position: 0% 50%;
					}
					50% {
						background-position: 100% 50%;
					}
					100% {
						background-position: 0% 50%;
					}
				}
			</style>
			<div class="wrap-modalidade-menu esconde169">





			<div class="innerBox">
				<span id="valorSaga" style="position:absolute;none;opacity:0;z-index:-999999;"><?php echo number_format($investimento['valor'], 2, ',', '.'); ?></span>

				<div class="apenasRespostaCalculo">
					<p style="color:#6c7684;font-size:20px;margin-bottom:0;">Resultado do seu cálculo</p>
					<p style="font-weight:bold;color:<?php echo esc_attr($page_primary_color); ?>;font-size:28px;margin-top:0;margin-bottom:0;">
						Seu desconto é de <span id="resultadoCalc">percentual</span>%.
					</p>
				</div>
				
				<div class="apenasRespostaCalculo" style="color:#6c7684;font-size:20px;margin-bottom:-30px;">
					<p>Você poderá pagar: *</p>
				</div>

				<div class="apenasRespostaCalculo">
					<p class="dePor">A partir de: <span>
					R$</span> <span id="valorSDesconto"><?php echo number_format($valorSemDesconto, 2, ',', '.'); ?></span></p>

					<p class="valorParcela"><span class="dePorNovo">Por </span><span>R$</span><span id="valorRespostaCalculoBox">valor que vai pagar</span><span class="ateFinal"> até o Final do curso</span></p>
				</div>
				
				<p class="nameCurso">
					<?php
						if ( is_single() ) {
							the_title( '<span class="entry-title">', '</span>' );
						}
					?>
				</p>


				<div class="versaoGlobal">
					<p class="dePor valorVersaoSemi">
					R$</span> <span id="valorSDesconto"><?php echo number_format($valorSemDesconto, 2, ',', '.'); ?></span></p>
					<p class="dePor dePorVersaoSemi" style="margin-bottom: -25px;">A partir de: <p>

					<p class="valorParcela"><span class="dePorNovo"></span><span>R$</span><span id="valorCDesconto" data-valor-base="<?php echo esc_attr($investimento['valor']); ?>"><?php echo number_format($investimento['valor'], 2, ',', '.'); ?></span><br><span class="ateFinal"> até o Final do curso</span></p>
				</div>

				<script>
				(function() {
					document.addEventListener('DOMContentLoaded', function() {
						var modalidadeBox = document.getElementById('modalidadeBox');
						if (!modalidadeBox) {
							return;
						}
						var modalidadeSlug = (modalidadeBox.dataset.modalidadeNormalizada || modalidadeBox.textContent || '').toLowerCase();

						// Regra fixa para Digital (EaD): mesma regra dos 99 da home.
						if (modalidadeSlug === 'digital') {
							var valorCheio = document.getElementById('valorSDesconto');
							var valorComDesconto = document.getElementById('valorCDesconto');
							if (valorCheio) {
								valorCheio.textContent = '2.400,00';
							}
							if (valorComDesconto) {
								valorComDesconto.textContent = '99,00';
							}
							return;
						}

						if (modalidadeSlug !== 'semipresencial') {
							return;
						}

						var semipresencialLabel = 'Semipresencial';
						var innerMod = document.getElementById('innerMod');
						if (innerMod) {
							innerMod.textContent = semipresencialLabel;
						}
						if (modalidadeBox.textContent.trim() !== semipresencialLabel) {
							modalidadeBox.textContent = semipresencialLabel;
						}

						var legenda = document.querySelector('.dePorVersaoSemi');
						var valor = document.querySelector('.valorVersaoSemi');
						if (legenda && valor) {
							legenda.textContent = 'De:';
							if (legenda.nextElementSibling !== valor) {
								valor.parentNode.insertBefore(legenda, valor);
								legenda.style.marginBottom = '0';
								valor.style.marginBottom = '-25px';
							}
						}

						['valorCDesconto'].forEach(function(id) {
							var alvo = document.getElementById(id);
							if (alvo) {
								alvo.textContent = '399,00';
							}
						});

						document.querySelectorAll('.ateFinal').forEach(function(item) {
							item.style.display = 'block';
							item.style.marginBottom = '-11px';
							if (!item.dataset.textoOriginal) {
								item.dataset.textoOriginal = (item.innerHTML || '').trim() || 'até o Final do curso';
							}
						});
					});
				})();
				</script>

                <style>
					.ateFinal {
						display: block;
						margin-bottom: -11px;
					}
                    span.dePorNovo {
                        font-size: 15px;
                        color: #747d8c;
                    }
					.dePor {
                        color: #747d8c !important;
                    }
                </style>
				
			</div>


		</section>

		<?php 
			} elseif ($investimento['parcelas'] == 12) {
				$valorSemDesconto = ceil($investimento['valor'] * 2);
		?>

		<?php 
	
		} 
	}
		?>
	<!-- Dados já puxados da API -->
	
</main><!-- #main -->
</div><!-- #primary -->
</div><!-- .wrap -->


<div class="clear">

<?php
$titulo_sobre = get_post_meta(get_the_ID(), 'titulo_sobre', true);
if (!is_string($titulo_sobre) || trim($titulo_sobre) === '') {
	$titulo_sobre = 'O que você vai aprender';
}

$conteudo_sobre = get_post_meta(get_the_ID(), 'conteudo_sobre', true);
if (!is_string($conteudo_sobre) || trim(wp_strip_all_tags($conteudo_sobre)) === '') {
	$conteudo_sobre = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
}

$titulo_para_voce = 'Este curso é para você?';
$conteudo_para_voce = get_post_meta(get_the_ID(), 'conteudo_para_voce', true);

$conteudo_para_raw = function_exists('get_field')
	? get_field('conteudo_para', get_the_ID())
	: get_post_meta(get_the_ID(), 'conteudo_para', true);

$itens_para_voce = [];

if (is_array($conteudo_para_raw) && !empty($conteudo_para_raw)) {
	foreach ($conteudo_para_raw as $item) {
		if (is_array($item) && isset($item['point_para']) && is_string($item['point_para'])) {
			$texto = trim($item['point_para']);
			if ($texto !== '') {
				$itens_para_voce[] = $texto;
			}
			continue;
		}

		if (is_string($item)) {
			$texto = trim($item);
			if ($texto !== '') {
				$itens_para_voce[] = $texto;
			}
		}
	}
}

$tem_itens_validos = !empty($itens_para_voce);

// Usa padrão apenas se não houver itens válidos
if (!$tem_itens_validos) {
	$itens_para_voce = [
		'Deseja desenvolver habilidades praticas com visao estrategica e foco em resultados?',
		'Busca aprofundar conhecimentos tecnicos para atuar com mais seguranca no mercado?',
		'Quer ampliar sua capacidade de analise e tomada de decisao em cenarios complexos?',
		'Pretende evoluir profissionalmente com uma formacao aplicada e atualizada?'
	];
}
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<section class="aprender">
				<div class="center">
					<h2 class="vaiAprender comBarra"><?php echo esc_html($titulo_sobre); ?></h2>
					<p class="textoAprender">
						<?php echo wp_kses_post($conteudo_sobre); ?>
					</p>

					<div class="wrapParaAll" style="margin-top: 44px;">
						<h2 class="paraVc comBarra"><?php echo esc_html($titulo_para_voce); ?></h2>
						<ul class="wrapBlocksPara">
							<?php foreach ($itens_para_voce as $item_para_voce): ?>
								<?php if (is_string($item_para_voce) && trim($item_para_voce) !== ''): ?>
									<li><?php echo esc_html($item_para_voce); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>

				</div>
			</section>
			</main><!-- #main -->
			</div><!-- #primary -->
			</div><!-- .wrap -->

	<div class="clear"></div>

	<?php include 'nossa-infraestrutura.php' ?>

<div class="clear"></div>

<div class="">
	<div class="wrap">
	<div id="" class="content-area">
		<main id="main" class="site-main">
			<section class="cursar">
				<div class="center">
				<h2 class="vaiCursar comBarra">Quais conteúdos você vai cursar</h2>

				<!-- loop de modulos -->
				<?php
				// Copia os módulos para um novo array para ordenação
				$modulos = $data['estrutura']['grupos'];

				// Se for -digital, ordena pelo número extraído do 'descricao'
                // Verifica se há conteúdo útil nos módulos (descrição ou disciplinas)
                $hasContent = false;
                if (!empty($modulos) && is_array($modulos)) {
                    foreach ($modulos as $m) {
                        $descricaoOk = !empty($m['descricao']) && trim($m['descricao']) !== '';
                        $disciplinasOk = !empty($m['disciplinas']) && is_array($m['disciplinas']) && count(array_filter($m['disciplinas'], function($d){
                            return !empty($d['disciplina']) && trim($d['disciplina']) !== '';
                        })) > 0;
                        if ($descricaoOk || $disciplinasOk) {
                            $hasContent = true;
                            break;
                        }
                    }
                }

                // Se não tiver conteúdo, esconde o container .cursar.parent.parent.parent no front-end e evita o loop
                if (!$hasContent) {
                    echo '<script>document.addEventListener("DOMContentLoaded",function(){var c=document.querySelector(".cursar"); if(c && c.parentElement && c.parentElement.parentElement && c.parentElement.parentElement.parentElement){ c.parentElement.parentElement.parentElement.style.display="none"; }});</script>';
                    $modulos = []; // garante que o foreach não execute
                } else {
                    // Se for -digital, ordena pelo número extraído do 'descricao'
					if ($page_modalidade_slug === 'digital') {
                        usort($modulos, function($a, $b) {
                            preg_match('/\d+/', $a['descricao'], $ma);
                            preg_match('/\d+/', $b['descricao'], $mb);
                            $numA = isset($ma[0]) ? intval($ma[0]) : 0;
                            $numB = isset($mb[0]) ? intval($mb[0]) : 0;
                            return $numA - $numB;
                        });
                    }
                }

				foreach ($modulos as $modulo) {
				?>
					<div class="wrapModulo">
						<div class="ruleModulo"><?php echo $ruleModulo ?></div>
						<?php
						// Verifica se a URL contém '-digital'
						if ($page_modalidade_slug === 'digital') {
							// Exibe o que chega direto pela API
							if (!empty($modulo['descricao'])) {
								echo '<p class="tagModulo">' . esc_html($modulo['descricao']) . '</p>';
							}
							// Não exibe .titleModulo para digital
						} else {
							// Estrutura padrão
							static $modulo_num = 1;
							echo '<p class="titleModulo"><b>Módulo:</b> ' . esc_html($modulo['descricao']) . '</p>';
						}
						?>
						<?php
							foreach ($modulo['disciplinas'] as $disciplina) {
						?>
							<p class="contentModulo"><?php echo esc_html($disciplina['disciplina']); ?></p>
						<?php
							}
						?>
					</div>
				<?php
				}
				?>
				<!-- loop de modulos -->
				</div>
			</section>
		</main><!-- #main -->
	</div><!-- #primary -->
	</div><!-- .wrap -->
</div>


<div class="clear"></div>

<?php include 'certificacoes.php' ?>

<div class="clear"></div>

<?php include 'coordenacao-graduacao.php' ?>

<div class="clear"></div>

	
	<?php
	// Detecta se é a página de Odontologia (slug ou título)
	$post = get_post();
	$is_odontologia = false;
	if ($post) {
		$slug = isset($post->post_name) ? $post->post_name : '';
		$title = isset($post->post_title) ? strtolower($post->post_title) : '';
		if (
			strpos($slug, 'odontologia') !== false ||
			strpos($title, 'odontologia') !== false
		) {
			$is_odontologia = true;
		}
	}
	?>

	<?php if ($is_odontologia): ?>
		<section class="tour-laboratorio-odontologia" style="margin: 40px 0;">
			<div class="center" style="">
				<h2 class="comBarra">Tour no Laboratório</h2>
				<div style="max-width: 650px;">
					<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">
						<iframe src="https://www.youtube.com/embed/Veg9iB0oHHk?si=nM2nJjJFgIJuZWTA"
							title="Tour Laboratório Odontologia"
							frameborder="0"
							allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
							allowfullscreen
							style="position:absolute;top:0;left:0;width:100%;height:100%;border-radius:10px;">
						</iframe>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?><br>

	<div class="clear"></div>

	<?php include 'quemFazRecomenda.php' ?>

	<div class="clear"></div>


	<?php include 'porqueFazer.php' ?>

    <div class="clear"></div>


	<?php include 'parceiros.php' ?>

</div>


<!-- Swiper JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
	var swiper = new Swiper(".parceiros", {
		slidesPerView: 1,
		spaceBetween: 30,
		loop: true,
		breakpoints: {
			460: {
				slidesPerView: 2,
				spaceBetween: 30
			},
			// when window width is >= 640px
			640: {
				slidesPerView: 4,
				spaceBetween: 40
			}
		},
		autoplay: {
			delay: 2500,
			disableOnInteraction: false,
		},
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},
		navigation: {
			nextEl: ".btnNext",
			prevEl: ".btnPrev",
		},
	});
</script>
<style>
	.blockPara {
	    text-align: left;
	    left: 5%;
	    position: relative;
	    right: 5%;
	}
	.innerIcon {
		position: relative;
		display: inline-block;
		width: 20%;
		vertical-align: middle;
	}
	.innerIcon img {
		position: relative;
		width: 100%;
		float: left;
	}	
	.innerFormas {
		position: relative;
		display: inline-block;
		width: 75%;
		vertical-align: middle;
	}
	.forma-ingresso p {
		width: 100%;
	}


	.swiper {
      width: 100%;
      height: 100%;
	  padding-bottom: 60px;
    }
	.btnNext, .btnPrev {
		position: absolute;
		display: inline-block;
		margin: 0 auto;
		text-align: center;
		width: 100%;
		cursor: pointer;
		bottom: 5px;
		z-index: 99999;
		color: #EF7D00;
	}
	.btnPrev {
		left: -20%
	}
	.btnNext {
		right: -20%
	}
	:root {
		--swiper-theme-color: #EF7D00;
	}
    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
	.barra-rosa:before {
		background-color: #E5457A !important;
	}
	.barra-roxa:before {
		background-color: #7D378D !important;
	}
</style>


<!-- diferencas do Digital -->
<script>
	window.addEventListener('load', function() {
		var modalidadeSlug = "<?php echo esc_js($page_modalidade_slug); ?>";
		var primaryColor = "<?php echo esc_js($page_primary_color); ?>";
		$("#innerMod").css("color", primaryColor);
		$(".box.azul").css("border-top", "8px solid " + primaryColor);
		$(".wrapSides h3").css("color", primaryColor);
		$(".wrapDocentes .left").css("color", primaryColor);
		if(modalidadeSlug === 'digital') {
			$(".comBarra").addClass("barra-rosa");
			$(".certificacoes-intermediarias").remove();
		} else if(modalidadeSlug === 'semipresencial') {
			$(".comBarra").addClass("barra-roxa");
		}
	});
</script>



<script>
// Variáveis globais para armazenar valores calculados
var valoresCalculadosEnem = {};
var valoresCalculadosSegunda = {};
var valoresCalculadosTransf = {};

document.addEventListener('DOMContentLoaded', function() {
  // Gera o array cidadesUnidades no PHP
  var cidadesUnidades = <?php
    $cidadesUnidades = [];
    foreach ($data['investimentos'] as $item) {
      if (!empty($item['cidade']) && !empty($item['unidade'])) {
        $cidadesUnidades[$item['cidade']][] = $item['unidade'];
      }
    }
    // Remove duplicados
    foreach ($cidadesUnidades as $cidade => $unidades) {
      $cidadesUnidades[$cidade] = array_values(array_unique($unidades));
    }
    echo json_encode($cidadesUnidades);
  ?>;

  var cidadeSelect = document.getElementById('cidade');
  var unidadeSelect = document.getElementById('unidade');

  if (cidadeSelect && unidadeSelect) {
    cidadeSelect.addEventListener('change', function() {
      var cidadeSelecionada = this.value;
      unidadeSelect.innerHTML = '<option value="">Escolha a sua unidade</option>';
      if (cidadesUnidades[cidadeSelecionada]) {
        cidadesUnidades[cidadeSelecionada].forEach(function(unidade) {
          var opt = document.createElement('option');
          opt.value = unidade;
          opt.textContent = unidade;
          unidadeSelect.appendChild(opt);
        });
      }
    });
  }
});
</script>

<script>
// Funções de cálculo para os modais
document.addEventListener('DOMContentLoaded', function() {
    // Função de cálculo do ENEM
    document.getElementById('btnCalcule').addEventListener('click', function() {
        const media = parseFloat(document.getElementById('media').value);
		const isDigital = <?php echo ($page_modalidade_slug === 'digital') ? 'true' : 'false'; ?>;
        
        let localizacao = '';
        let segundoParametro = '';
        
        if (isDigital) {
            localizacao = document.getElementById('cidadeEnem').value;
            segundoParametro = document.getElementById('unidadeEnem').value;
        } else {
            localizacao = document.getElementById('unidadeEnem').value;
            segundoParametro = document.getElementById('horarioEnem').value;
        }
        
        if (!media || media <= 0 || media > 1000) {
            alert('Por favor, insira uma nota válida do ENEM (entre 1 e 1000).');
            return;
        }
        
        if (!localizacao || !segundoParametro) {
            if (isDigital) {
                alert('Por favor, selecione a cidade e a unidade.');
            } else {
                alert('Por favor, selecione a unidade e o turno.');
            }
            return;
        }
        
        // Lógica de cálculo do desconto baseado na nota do ENEM
        let percentual = 0;
        if (media >= 800) percentual = 70;
        else if (media >= 700) percentual = 40;
        else if (media >= 600) percentual = 20;
        else if (media >= 400) percentual = 10;
        
        // Pega o valor base do elemento #valorCDesconto
        const valorCDescontoElement = document.getElementById('valorCDesconto');
        if (!valorCDescontoElement) {
            alert('Erro: valor de referência não encontrado.');
            return;
        }
        
        const valorBase = parseFloat(valorCDescontoElement.textContent.replace(/\./g, '').replace(',', '.'));
        const valorComDesconto = valorBase * (100 - percentual) / 100
        
        // Armazena os valores calculados
        valoresCalculadosEnem = {
            percentual: percentual,
            valorBase: valorBase,
            valorComDesconto: valorComDesconto,
            valorComDescontoFormatado: Math.ceil(valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})
        };
        
        // Atualiza os elementos do resultado imediatamente
        // Define o percentual para exibição conforme as regras do ENEM
        let percentualExibicao = percentual;
        if (percentual === 70) percentualExibicao = 85;
        else if (percentual === 40) percentualExibicao = 70;
        else if (percentual === 20) percentualExibicao = 60;
        else if (percentual === 10) percentualExibicao = 55;
        
        document.getElementById('percentual').textContent = percentualExibicao;
        document.getElementById('valorSDescontoEnem').textContent = Math.ceil(valorBase).toLocaleString('pt-BR');
        document.getElementById('valorCDescontoEnem').textContent = Math.ceil(valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        
        // Atualizar valorSDesconto para ser o dobro do valorSaga
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
            const valorSDescontoNumero = valorSagaNumero * 2;
            valorSDesconto.textContent = valorSDescontoNumero.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // Aplica a classe active nos elementos de resultado
        const resultadoCalcule = document.querySelector('.resultadoCalcule');
        const wrapCalcule = document.querySelector('.wrapCalcule');
        if (resultadoCalcule) resultadoCalcule.classList.add('active');
        if (wrapCalcule) wrapCalcule.classList.add('active');
        
        // Atualiza todos os elementos valorCDesconto da página principal
        const todosValorCDesconto = document.querySelectorAll('#valorCDesconto');
        todosValorCDesconto.forEach((elemento) => {
            elemento.textContent = valoresCalculadosEnem.valorComDescontoFormatado;
        });
        
        // Esconde a calculadora e mostra o formulário HubSpot
        document.getElementById('modalCalculatorEnem').style.display = 'none';
        document.getElementById('hubspotFormEnem').style.display = 'block';
        
        // Define qual modal está ativo para o sistema de detecção POST
        if (window.hubspotPostDetector) {
            hubspotPostDetector.currentModal = 'enem';
        }
    });
    
    // Função de cálculo da Segunda Graduação
    document.getElementById('btnCalculeT').addEventListener('click', function() {
        const mediaT = parseFloat(document.getElementById('mediaT').value);
		const isDigital = <?php echo ($page_modalidade_slug === 'digital') ? 'true' : 'false'; ?>;
        
        let localizacao = '';
        let segundoParametro = '';
        
        if (isDigital) {
            localizacao = document.getElementById('cidadeSegunda').value;
            segundoParametro = document.getElementById('unidadeSegunda').value;
        } else {
            localizacao = document.getElementById('unidadeSegunda').value;
            segundoParametro = document.getElementById('horarioSegunda').value;
        }
        
        if (!mediaT || mediaT <= 0 || mediaT > 10) {
            alert('Por favor, insira um CR válido entre 0.1 e 10.0.');
            return;
        }
        
        if (!localizacao || !segundoParametro) {
            if (isDigital) {
                alert('Por favor, selecione a cidade e a unidade.');
            } else {
                alert('Por favor, selecione a unidade e o turno.');
            }
            return;
        }
        
        // Lógica de cálculo do desconto baseado no CR
        let percentualT = 0;
        if (mediaT >= 9.1) percentualT = 30;
        else if (mediaT >= 7.1) percentualT = 20;
        else if (mediaT <= 7.0) percentualT = 10;
    
        
        // Pega o valor base do elemento #valorCDesconto
        const valorCDescontoElement = document.getElementById('valorCDesconto');
        if (!valorCDescontoElement) {
            alert('Erro: valor de referência não encontrado.');
            return;
        }
        
        const valorBase = parseFloat(valorCDescontoElement.textContent.replace(/\./g, '').replace(',', '.'));
        const valorComDesconto = valorBase * (100 - percentualT) / 100;
        
        // Armazena os valores calculados
        valoresCalculadosSegunda = {
            percentual: percentualT,
            valorBase: valorBase,
            valorComDesconto: valorComDesconto,
            valorComDescontoFormatado: Math.ceil(valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})
        };
        
        
        // Atualiza os elementos do resultado imediatamente
        // Define o percentual para exibição conforme as regras da Segunda Graduação
        let percentualExibicao = percentualT;
        if (percentualT === 30) percentualExibicao = 65;
        else if (percentualT === 20) percentualExibicao = 60;
        else if (percentualT === 10) percentualExibicao = 55;
        
        document.getElementById('percentualT').textContent = percentualExibicao;
        document.getElementById('valorSDescontoT').textContent = Math.ceil(valorBase).toLocaleString('pt-BR');
        document.getElementById('valorCDescontoT').textContent = Math.ceil(valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        
        // Atualizar valorSDesconto para ser o dobro do valorSaga
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
            const valorSDescontoNumero = valorSagaNumero * 2;
            valorSDesconto.textContent = valorSDescontoNumero.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // Aplica a classe active nos elementos de resultado
        const resultadoCalculeT = document.querySelector('.resultadoCalculeT');
        const wrapCalculeT = document.querySelector('.wrapCalculeT');
        if (resultadoCalculeT) resultadoCalculeT.classList.add('active');
        if (wrapCalculeT) wrapCalculeT.classList.add('active');
        
        // Atualiza todos os elementos valorCDesconto da página principal
        const todosValorCDesconto = document.querySelectorAll('#valorCDesconto');
        todosValorCDesconto.forEach((elemento) => {
            elemento.textContent = valoresCalculadosSegunda.valorComDescontoFormatado;
        });
        
        // Esconde a calculadora e mostra o formulário HubSpot
        document.getElementById('modalCalculatorSegunda').style.display = 'none';
        document.getElementById('hubspotFormSegunda').style.display = 'block';
        
        // Define qual modal está ativo para o sistema de detecção POST
        if (window.hubspotPostDetector) {
            hubspotPostDetector.currentModal = 'segunda';
        }
    });
    
    // Função para o modal de Transferência (igual ao Segunda Graduação)
    document.getElementById('btnCalculeTransf').addEventListener('click', function() {
        const mediaTR = parseFloat(document.getElementById('mediaTR').value);
		const isDigital = <?php echo ($page_modalidade_slug === 'digital') ? 'true' : 'false'; ?>;
        
        let localizacao = '';
        let segundoParametro = '';
        
        if (isDigital) {
            localizacao = document.getElementById('cidadeTransf').value;
            segundoParametro = document.getElementById('unidadeTransf').value;
        } else {
            localizacao = document.getElementById('unidadeTransf').value;
            segundoParametro = document.getElementById('horarioTransf').value;
        }
        
        if (!mediaTR || mediaTR <= 0 || mediaTR > 10) {
            alert('Por favor, insira um CR válido entre 0.1 e 10.0.');
            return;
        }
        
        if (!localizacao || !segundoParametro) {
            if (isDigital) {
                alert('Por favor, selecione a cidade e a unidade.');
            } else {
                alert('Por favor, selecione a unidade e o turno.');
            }
            return;
        }
        
        // Lógica de cálculo do desconto baseado no CR (igual ao Segunda Graduação)
         let percentualT = 0;
        if (mediaTR >= 9.1) percentualT = 30;
        else if (mediaTR >= 7.1) percentualT = 20;
        else if (mediaTR <= 7.0) percentualT = 10;
        
        // Pega o valor base do elemento #valorCDesconto
        const valorCDescontoElement = document.getElementById('valorCDesconto');
        if (!valorCDescontoElement) {
            alert('Erro: valor de referência não encontrado.');
            return;
        }
        
        const valorBase = parseFloat(valorCDescontoElement.textContent.replace(/\./g, '').replace(',', '.'));
        const valorComDesconto = valorBase * (100 - percentualT) / 100;
        
        // Armazena os valores calculados
        valoresCalculadosTransf = {
            percentual: percentualT,
            valorBase: valorBase,
            valorComDesconto: valorComDesconto,
            valorComDescontoFormatado: Math.ceil(valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})
        };
        
        // Atualiza os elementos do resultado imediatamente
        // Define o percentual para exibição conforme as regras da Transferência
        let percentualExibicao = percentualT;
        if (percentualT === 30) percentualExibicao = 65;
        else if (percentualT === 20) percentualExibicao = 60;
        else if (percentualT === 10) percentualExibicao = 55;
        
        document.getElementById('percentualTR').textContent = percentualExibicao;
        document.getElementById('valorSDescontoTR').textContent = Math.ceil(valorBase).toLocaleString('pt-BR');
        document.getElementById('valorCDescontoTR').textContent = Math.ceil(valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        
        // Atualizar valorSDesconto para ser o dobro do valorSaga
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
            const valorSDescontoNumero = valorSagaNumero * 2;
            valorSDesconto.textContent = valorSDescontoNumero.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // Aplica a classe active nos elementos de resultado
        const resultadoCalculeTransf = document.querySelector('.resultadoCalculeT');
        const wrapCalculeTransf = document.querySelector('.wrapCalculeT');
        if (resultadoCalculeTransf) resultadoCalculeTransf.classList.add('active');
        if (wrapCalculeTransf) wrapCalculeTransf.classList.add('active');
        
        // Atualiza todos os elementos valorCDesconto da página principal
        const todosValorCDesconto = document.querySelectorAll('#valorCDesconto');
        todosValorCDesconto.forEach((elemento) => {
            elemento.textContent = valoresCalculadosTransf.valorComDescontoFormatado;
        });
        
        // Esconde a calculadora e mostra o formulário HubSpot
        document.getElementById('modalCalculatorTransf').style.display = 'none';
        document.getElementById('hubspotFormTransf').style.display = 'block';
        
        // Define qual modal está ativo para o sistema de detecção POST
        if (window.hubspotPostDetector) {
            hubspotPostDetector.currentModal = 'transferencia';
        }
    });
    
	// Event listeners para os botões "MATRICULE-SE JÁ!" dos modais
	let hubspotBtnComprarInFlight = null;

	function coletarDadosContatoBtnComprar() {
		const offerFallback = document.getElementById('idCurso') ? document.getElementById('idCurso').textContent.trim() : '';
		const getInputValue = (id) => {
			const element = document.getElementById(id);
			if (!element) {
				return '';
			}
			return typeof element.value === 'string' ? element.value : '';
		};
		const resolveValue = (source) => {
			try {
				if (typeof source === 'function') {
					return source() || '';
				}
			} catch (err) {
				return '';
			}
			return source || '';
		};
		const formConfigs = [
			{
				firstname: 'firstname-vestibular',
				lastname: 'lastname-vestibular',
				email: 'email-vestibular',
				phone: 'phone-vestibular',
				offerId: () => getInputValue('offer-id-vestibular') || offerFallback,
				nota: () => getInputValue('nota-score-vestibular') || getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-vestibular') || 'VESTIBULAR'
			},
			{
				firstname: 'firstname-enem',
				lastname: 'lastname-enem',
				email: 'email-enem',
				phone: 'phone-enem',
				offerId: () => getInputValue('offer-id') || offerFallback,
				nota: () => getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-enem') || 'ENEM'
			},
			{
				firstname: 'firstname-segunda',
				lastname: 'lastname-segunda',
				email: 'email-segunda',
				phone: 'phone-segunda',
				offerId: () => getInputValue('offer-id') || offerFallback,
				nota: () => getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-segunda') || 'SEGUNDA GRADUAÇÃO'
			},
			{
				firstname: 'firstname-transf',
				lastname: 'lastname-transf',
				email: 'email-transf',
				phone: 'phone-transf',
				offerId: () => getInputValue('offer-i') || getInputValue('offer-id') || offerFallback,
				nota: () => getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-transf') || 'TRANSFERÊNCIA'
			}
		];

		for (const config of formConfigs) {
			const firstEl = document.getElementById(config.firstname);
			const lastEl = document.getElementById(config.lastname);
			const emailEl = document.getElementById(config.email);
			const phoneEl = document.getElementById(config.phone);
			if (!firstEl || !lastEl || !emailEl || !phoneEl) {
				continue;
			}

			const firstname = firstEl.value.trim();
			const lastname = lastEl.value.trim();
			const email = emailEl.value.trim();
			const phone = phoneEl.value.trim();
			if (!firstname || !lastname || !email || !phone) {
				continue;
			}

			return {
				firstname,
				lastname,
				email,
				phone,
				oferta: resolveValue(config.offerId) || offerFallback,
				nota: resolveValue(config.nota),
				formaIngresso: resolveValue(config.forma)
			};
		}

		return null;
	}

	function obterDadosContatoSaga() {
		if (window.vestibularExtraData) {
			const dados = Object.assign({}, window.vestibularExtraData);
			delete window.vestibularExtraData;
			return dados;
		}

		const contato = coletarDadosContatoBtnComprar();
		if (!contato) {
			return null;
		}

		const nome = `${contato.firstname || ''} ${contato.lastname || ''}`.trim();
		const email = (contato.email || '').trim();
		const telefone = (contato.phone || '').replace(/\D/g, '');
		const forma = contato.formaIngresso || '';

		if (!nome && !email && !telefone) {
			return null;
		}

		return { nome, email, telefone, forma };
	}

	let sagaRequestInFlight = null;

	function enviarDadosParaSaga(requestData) {
		if (!requestData) {
			return Promise.reject(new Error('Payload inválido para o SAGA.'));
		}

		if (sagaRequestInFlight) {
			return sagaRequestInFlight;
		}

		

		sagaRequestInFlight = fetch('https://cursos.unisuam.edu.br/wp-content/themes/twentyseventeen/sendAPI_interna.php', {   
			method: "POST",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(requestData)
		})
		.then((response) => {
			if (!response.ok) {
				throw new Error('Falha no envio para o SAGA');
			}
			return response.json();
		})
		.finally(() => {
			sagaRequestInFlight = null;
		});

		return sagaRequestInFlight;
	}

	function gerarCamposUtm() {
		const utmKeys = [
			'utm_source',
			'utm_medium',
			'utm_campaign',
			'utm_content',
			'utm_term',
			'utm_id',
			'utm_source_platform',
			'utm_campaign_id',
			'utm_creative_format',
			'utm_marketing_tactic'
		];
		const params = new URLSearchParams(window.location.search || '');
		const campos = utmKeys.map((key) => ({
			name: key,
			value: params.get(key) || ''
		}));
		// Adiciona o campo especial "origem261" com o valor de origemmkt
		campos.push({
			name: 'origem261',
			value: params.get('origemmkt') || ''
		});
		return campos;
	}

	async function enviarHubspotBtnComprar() {
		if (window.btnComprarHubspotSent) {
			return;
		}
		if (hubspotBtnComprarInFlight) {
			return hubspotBtnComprarInFlight;
		}

		const contato = coletarDadosContatoBtnComprar();
		if (!contato) {
			return;
		}

		const phoneDigits = contato.phone.replace(/\D/g, '');
		const payload = {
			fields: [
				{ name: 'firstname', value: contato.firstname },
				{ name: 'lastname', value: contato.lastname },
				{ name: 'email', value: contato.email },
				{ name: 'mobilephone', value: phoneDigits },
				{ name: 'id_da_oferta', value: contato.oferta },
				{ name: 'nota_calculadora_fdi', value: contato.nota || '' },
				{ name: 'forma_de_ingresso', value: (() => {
					const forma = (contato.formaIngresso || '').toLowerCase();
					if (forma === 'enem') return 'ENEM';
					if (forma === 'segunda graduação' || forma === 'segunda graduacao' || forma === '2ª graduação' || forma === '2a graduação') return 'Segunda Graduação';
					if (forma === 'transferência' || forma === 'transferencia') return 'Transferência';
					if (forma === 'vestibular') return 'Vestibular';
					return contato.formaIngresso || '';
				})() }
			].concat(gerarCamposUtm()),
			context: {
				pageUri: window.location.href,
				pageName: document.title
			}
		};

		hubspotBtnComprarInFlight = fetch('https://api.hsforms.com/submissions/v3/integration/submit/3462868/a1b9edd0-f4ef-41e9-9cfd-9ea30175d051', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json'
			},
			body: JSON.stringify(payload)
		})
		.then(async (response) => {
			if (!response.ok) {
				const errorText = await response.text();
				throw new Error(errorText || 'HubSpot submission failed');
			}
			window.btnComprarHubspotSent = true;
			if (!window.vestibularExtraData) {
				window.vestibularExtraData = {
					nome: `${contato.firstname} ${contato.lastname}`.trim(),
					email: contato.email,
					telefone: phoneDigits
				};
			}
		})
		.catch((error) => {
			console.error('Falha ao enviar dados para o HubSpot via btnComprar:', error);
		})
		.finally(() => {
			hubspotBtnComprarInFlight = null;
		});

		return hubspotBtnComprarInFlight;
	}

	document.querySelectorAll('#btnComprar').forEach(function(btn) {
		btn.addEventListener('click', async function(e) {
			e.preventDefault();

			await enviarHubspotBtnComprar();
            
			const oferta = document.getElementById('idCurso') ? document.getElementById('idCurso').textContent.trim() : '';
			const descricao_curso = [...new Set(Array.from(document.querySelectorAll('.entry-title'))
				.map(function(el) { return el.textContent.trim(); }))].join(' ');
            
			// Preparar dados base para sendAPI_interna.php
			const contatoSaga = obterDadosContatoSaga() || {};

			const requestData = {
				oferta: oferta,
				descricao_curso: descricao_curso,
				nome: contatoSaga.nome || '',
				email: contatoSaga.email || '',
				telefone: contatoSaga.telefone || '',
				forma_ingresso: contatoSaga.forma || contatoSaga.forma_ingresso || ''
			};

			enviarDadosParaSaga(requestData)
			.then(data => {
				if(data.data && data.data.redirect_url) {
					window.location.href = data.data.redirect_url;
					// console.log('Dados enviados para a inscrição:', requestData);
				}
			})
			.catch(error => {
				document.querySelectorAll('.selecioneError').forEach(el => el.remove());
				btn.insertAdjacentHTML('afterend', "<p class='selecioneError'>Selecione as opções acima.</p>");
			});
		});
	});

//    DISPARO DIRETO DOS MODALS AtÉ O SAGA 
	document.querySelectorAll('.btnComprar-modal').forEach(function(btn) {
		btn.addEventListener('click', async function(e) {
			e.preventDefault();
			await enviarHubspotBtnComprar();
            
            const oferta = document.getElementById('idCurso') ? document.getElementById('idCurso').textContent.trim() : '';
            const descricao_curso = [...new Set(Array.from(document.querySelectorAll('.entry-title'))
                .map(function(el) { return el.textContent.trim(); }))].join(' ');
            
            // Preparar dados para sendAPI_interna.php
            const requestData = {
                oferta: oferta,
                descricao_curso: descricao_curso
            };

			const contatoSaga = obterDadosContatoSaga();
			if (contatoSaga) {
				if (contatoSaga.nome) requestData.nome = contatoSaga.nome;
				if (contatoSaga.email) requestData.email = contatoSaga.email;
				if (contatoSaga.telefone) requestData.telefone = contatoSaga.telefone;
				if (contatoSaga.forma) requestData.forma_ingresso = contatoSaga.forma;
			}
            
			// Enviar para API do SAGA
			enviarDadosParaSaga(requestData)
			.then(data => {
                if(data.data && data.data.redirect_url) {
                    window.location.href = data.data.redirect_url;
                } else {
                    // Se não houver URL de redirecionamento, mostrar erro
                    document.querySelectorAll('.selecioneError').forEach(el => el.remove());
                    btn.insertAdjacentHTML('afterend', "<p class='selecioneError'>Erro ao processar. Tente novamente.</p>");
                }
            })
            .catch(error => {
                document.querySelectorAll('.selecioneError').forEach(el => el.remove());
                btn.insertAdjacentHTML('afterend', "<p class='selecioneError'>Selecione as opções acima.</p>");
            });
        });
    });
});
</script>

<script>
 $(window).on('scroll', function() {
        const $box = $('#box');
        const $footer = $('#footer');

        const footerRect = $footer[0].getBoundingClientRect();
        const boxRect = $box[0].getBoundingClientRect();

        // Verifica se o box está tocando o footer
        if (footerRect.top <= boxRect.bottom) {
            $box.css('right', '-200%');
        } else {
            $box.css('right', '10%');
        }
    });
</script>

<script>
// Script global para forçar transição após envio do formulário HubSpot
document.addEventListener('DOMContentLoaded', function() {
    // Função para verificar e fazer a transição para a área de resultado
    function verificarEForcarTransicao() {
        // Verifica ENEM
        const hubspotEnem = document.getElementById('hubspotFormEnem');
        if (hubspotEnem && hubspotEnem.style.display !== 'none') {
            const textoEnem = hubspotEnem.textContent || hubspotEnem.innerText;
            if (textoEnem.includes('Formulário enviado') || textoEnem.includes('Obrigado') || textoEnem.includes('sucesso')) {
                setTimeout(function() {
                    hubspotEnem.style.display = 'none';
                    const resultadoEnem = document.getElementById('resultadoCalculoEnem');
                    if (resultadoEnem) {
                        resultadoEnem.style.display = 'block';
                        
                        // Aplica valores se existirem
                        if (typeof valoresCalculadosEnem !== 'undefined' && valoresCalculadosEnem.percentual !== undefined) {
                            document.getElementById('percentual').textContent = valoresCalculadosEnem.percentual;
                            document.getElementById('valorSDescontoEnem').textContent = Math.ceil(valoresCalculadosEnem.valorBase).toLocaleString('pt-BR');
                            document.getElementById('valorCDescontoEnem').textContent = Math.ceil(valoresCalculadosEnem.valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                        }
                        
                        const elementos = resultadoEnem.querySelectorAll('.resultadoCalcule, .wrapCalcule');
                        elementos.forEach(el => {
                            el.style.display = 'block';
                            el.classList.add('active');
                        });
                    }
                }, 17000);
            }
        }
        
        // Verifica Segunda Graduação
        const hubspotSegunda = document.getElementById('hubspotFormSegunda');
        if (hubspotSegunda && hubspotSegunda.style.display !== 'none') {
            const textoSegunda = hubspotSegunda.textContent || hubspotSegunda.innerText;
            if (textoSegunda.includes('Formulário enviado') || textoSegunda.includes('Obrigado') || textoSegunda.includes('sucesso')) {
                setTimeout(function() {
                    hubspotSegunda.style.display = 'none';
                    const resultadoSegunda = document.getElementById('resultadoCalculoSegunda');
                    if (resultadoSegunda) {
                        resultadoSegunda.style.display = 'block';
                        
                        // Aplica valores se existirem
                        if (typeof valoresCalculadosSegunda !== 'undefined' && valoresCalculadosSegunda.percentual !== undefined) {
                            document.getElementById('percentualT').textContent = valoresCalculadosSegunda.percentual;
                            document.getElementById('valorSDescontoT').textContent = Math.ceil(valoresCalculadosSegunda.valorBase).toLocaleString('pt-BR');
                            document.getElementById('valorCDescontoT').textContent = Math.ceil(valoresCalculadosSegunda.valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                        }
                        
                        const elementos = resultadoSegunda.querySelectorAll('.resultadoCalculeT, .wrapCalculeT');
                        elementos.forEach(el => {
                            el.style.display = 'block';
                            el.classList.add('active');
                        });
                    }
                }, 17000);
            }
        }
        
        // Verifica Transferência
        const hubspotTransf = document.getElementById('hubspotFormTransf');
        if (hubspotTransf && hubspotTransf.style.display !== 'none') {
            const textoTransf = hubspotTransf.textContent || hubspotTransf.innerText;
            if (textoTransf.includes('Formulário enviado') || textoTransf.includes('Obrigado') || textoTransf.includes('sucesso')) {
                setTimeout(function() {
                    hubspotTransf.style.display = 'none';
                    const resultadoTransf = document.getElementById('resultadoCalculoTransf');
                    if (resultadoTransf) {
                        resultadoTransf.style.display = 'block';
                        
                        // Aplica valores se existirem
                        if (typeof valoresCalculadosTransf !== 'undefined' && valoresCalculadosTransf.percentual !== undefined) {
                            document.getElementById('percentualTR').textContent = valoresCalculadosTransf.percentual;
                            document.getElementById('valorSDescontoTR').textContent = Math.ceil(valoresCalculadosTransf.valorBase).toLocaleString('pt-BR');
                            document.getElementById('valorCDescontoTR').textContent = Math.ceil(valoresCalculadosTransf.valorComDesconto).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                        }
                        
                        const elementos = resultadoTransf.querySelectorAll('.resultadoCalculeT, .wrapCalculeT');
                        elementos.forEach(el => {
                            el.style.display = 'block';
                            el.classList.add('active');
                        });
                    }
                }, 17000);
            }
        }
    }
    
    // Verifica a cada 2 segundos se algum formulário foi enviado
    setInterval(verificarEForcarTransicao, 2000);
    
    // Também verifica imediatamente após carregar
    setTimeout(verificarEForcarTransicao, 1000);
});
</script>

<script>
// Script para esconder elementos com classe "esconde169" se for digital (valor = 169) ou odontologia
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se é página digital pela URL
    const isDigital = window.location.href.includes('-digital');
    
    // Verifica se é página de odontologia pela URL
    const isOdontologia = window.location.href.includes('odontologia');
    
    // Pega o valor do curso do elemento #valorCDesconto
    const valorCDescontoElement = document.getElementById('valorCDesconto');
    let valorCurso = 0;
    
    if (valorCDescontoElement) {
        // Remove formatação e converte para número
        const valorTexto = valorCDescontoElement.textContent.replace(/\./g, '').replace(',', '.');
        valorCurso = parseFloat(valorTexto);
    }
    
    // Se for odontologia OU (digital E valor = 169), esconde elementos com classe "esconde169"
    if (isOdontologia || (isDigital && valorCurso === 169)) {
        const elementosEsconde169 = document.querySelectorAll('.esconde169');
        elementosEsconde169.forEach(function(elemento) {
            elemento.style.display = 'none';
        });
        
        // Também esconde elementos com atributo div="esconde169"
        const elementosDivEsconde169 = document.querySelectorAll('[div="esconde169"]');
        elementosDivEsconde169.forEach(function(elemento) {
            elemento.style.display = 'none';
        });
        
    }
    
    // Garantir que valorSDesconto seja sempre o dobro do valorSaga ao carregar a página
    function atualizarValorSDescontoInicial() {
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
            const valorSDescontoNumero = valorSagaNumero * 2;
            valorSDesconto.textContent = valorSDescontoNumero.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }
    
    // Executa a função imediatamente após carregar
    atualizarValorSDescontoInicial();
    
    // Ativar modalidade automaticamente baseada nos parâmetros da URL
    function ativarModalidadePorURL() {
        const url = window.location.href;
        const urlParams = new URLSearchParams(window.location.search);
        
        let botaoParaClicar = null;
        
        // Verificar se contém os termos na URL
        if (url.includes('?enem') || url.includes('&enem') || urlParams.has('enem')) {
            botaoParaClicar = document.querySelector('.btnModalidade.btnEnem');
        } else if (url.includes('?segunda_graduacao') || url.includes('&segunda_graduacao') || urlParams.has('segunda_graduacao')) {
            botaoParaClicar = document.querySelector('.btnModalidade.btnSegundaGrad');
        } else if (url.includes('?transferencia') || url.includes('&transferencia') || urlParams.has('transferencia')) {
            botaoParaClicar = document.querySelector('.btnModalidade.btnTransferencia');
        }
        
        if (botaoParaClicar) {
            // Simular clique no botão da modalidade
            botaoParaClicar.click();
        }
    }
    
    // Executar após um pequeno delay para garantir que todos os elementos estejam carregados
    setTimeout(ativarModalidadePorURL, 800);
});
</script>

<style>
	.sidesTwo .custom-hubspot-form input {
		height: 30px !important;
	}
	@media(max-width:768px) {
		.form-group-sobrenome {
			margin-top: 15px;
		}
	}
</style>


<script>
	// Modificar o script existente na linha 2865

if (btnIniciarMatricula && sidesOne && sidesTwo) {
    btnIniciarMatricula.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Função para capturar dados dos selects de forma dinâmica
        function capturarDadosFormulario() {
            // Detecta se é digital pela URL
            const isDigital = window.location.href.includes('-digital');
            
            // Captura modalidade
            let modalidade = 'presencial'; // padrão
            const modalidadeElement = document.getElementById('modalidadeBox');
            if (modalidadeElement) {
                const modalidadeTexto = modalidadeElement.textContent.trim();
				const modalidadeTextoNormalizado = modalidadeTexto.toLowerCase();
				modalidade = (modalidadeTextoNormalizado.includes('digital') && !modalidadeTextoNormalizado.includes('ao vivo') && !modalidadeTextoNormalizado.includes('semipres')) ? 'ead' : 'presencial';
            } else if (isDigital) {
                modalidade = 'ead';
            }
            
            // Captura cidade (para cursos digitais)
            let cidade = '';
            const cidadeSelect = document.getElementById('cidade');
            if (cidadeSelect && cidadeSelect.value) {
                cidade = cidadeSelect.options[cidadeSelect.selectedIndex].text;
            }
            
            // Captura unidade
            let unidade = '';
            const unidadeSelect = document.getElementById('unidade');
            if (unidadeSelect && unidadeSelect.value) {
                unidade = unidadeSelect.options[unidadeSelect.selectedIndex].text;
            }
            
            // Captura turno/horário
            let turno = '';
            const horarioSelect = document.getElementById('horario');
            if (horarioSelect && horarioSelect.value) {
                turno = horarioSelect.options[horarioSelect.selectedIndex].text;
            }
            
            return {
                modalidade: modalidade,
                cidade: cidade || unidade, // Para cursos digitais usa cidade, presenciais usa unidade como cidade
                unidade: unidade,
                turno: turno
            };
        }
        
        // Captura dados dinamicamente
        const dados = capturarDadosFormulario();
        
        // Dispara PRIMEIRO evento GTM
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                event: "graduacao_iniciar_matricula",
                forma_ingresso: "vestibular",
                modalidade: dados.modalidade,
                cidade: dados.cidade,
                unidade: dados.unidade,
                turno: dados.turno
            });
        }
        
        // Esconde a primeira seção
        sidesOne.classList.add('hidden');
        
        // Mostra a segunda seção
        sidesTwo.classList.add('visible');
    });
}




// Modificar o script do formulário vestibular, substituindo a seção após response.ok

if (response.ok) {
    // Função para capturar dados dos selects de forma dinâmica (repetir aqui)
    function capturarDadosFormulario() {
        // Detecta se é digital pela URL
        const isDigital = window.location.href.includes('-digital');
        
        // Captura modalidade
        let modalidade = 'presencial'; // padrão
        const modalidadeElement = document.getElementById('modalidadeBox');
        if (modalidadeElement) {
            const modalidadeTexto = modalidadeElement.textContent.trim();
			const modalidadeTextoNormalizado = modalidadeTexto.toLowerCase();
			modalidade = (modalidadeTextoNormalizado.includes('digital') && !modalidadeTextoNormalizado.includes('ao vivo') && !modalidadeTextoNormalizado.includes('semipres')) ? 'ead' : 'presencial';
        } else if (isDigital) {
            modalidade = 'ead';
        }
        
        // Captura cidade (para cursos digitais)
        let cidade = '';
        const cidadeSelect = document.getElementById('cidade');
        if (cidadeSelect && cidadeSelect.value) {
            cidade = cidadeSelect.options[cidadeSelect.selectedIndex].text;
        }
        
        // Captura unidade
        let unidade = '';
        const unidadeSelect = document.getElementById('unidade');
        if (unidadeSelect && unidadeSelect.value) {
            unidade = unidadeSelect.options[unidadeSelect.selectedIndex].text;
        }
        
        // Captura turno/horário
        let turno = '';
        const horarioSelect = document.getElementById('horario');
        if (horarioSelect && horarioSelect.value) {
            turno = horarioSelect.options[horarioSelect.selectedIndex].text;
        }
        
        return {
            modalidade: modalidade,
            cidade: cidade || unidade, // Para cursos digitais usa cidade, presenciais usa unidade como cidade
            unidade: unidade,
            turno: turno
        };
    }
    
    // Capturar dados para o SEGUNDO EVENTO GTM
    const dados = capturarDadosFormulario();
    
    // Dispara SEGUNDO evento GTM antes do redirecionamento
    if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
            event: "graduacao_enviar_dados",
            forma_ingresso: "vestibular",
            modalidade: dados.modalidade,
            cidade: dados.cidade,
            unidade: dados.unidade,
            turno: dados.turno
        });
    }
    
    // Preparar dados extras do formulário vestibular para sendAPI_interna.php
    const firstnameVestibular = document.getElementById("firstname-vestibular").value;
    const lastnameVestibular = document.getElementById("lastname-vestibular").value;
    const emailVestibular = document.getElementById("email-vestibular").value;
    const phoneVestibular = document.getElementById("phone-vestibular").value;
    
    // Armazenar dados extras em variáveis globais para serem usados na sendAPI_interna.php
    window.vestibularExtraData = {
        nome: (firstnameVestibular + ' ' + lastnameVestibular).trim(),
        email: emailVestibular,
        telefone: phoneVestibular.replace(/\D/g, '') // Remove formatação
    };
    
    // Após sucesso no HubSpot, automaticamente clicar no #btnComprar
    const btnComprar = document.getElementById('btnComprar');
    if (btnComprar) {
        btnComprar.click();
    }
    
} else {
    // Reset em caso de erro para permitir nova tentativa
    hasSubmittedOnce = false;
}



</script>




<script>
document.addEventListener('DOMContentLoaded', function () {
  const btnIniciarMatricula = document.getElementById('btnIniciarMatricula');
  const btnVoltar = document.getElementById('btnVoltar');
  const sidesOne = document.getElementById('sidesOne');
  const sidesTwo = document.getElementById('sidesTwo');

  // utilidades
  function getSelectedText(id) {
    const el = document.getElementById(id);
    if (!el || el.selectedIndex < 0) return '';
    const opt = el.options[el.selectedIndex];
    return (opt && (opt.text || opt.label) ? (opt.text || opt.label).trim() : '').trim();
  }
  function getModalidade() {
    const box = document.getElementById('modalidadeBox');
    const t = (box ? box.textContent.trim().toLowerCase() : '');
		if ((t.includes('ao vivo') || t.includes('semipresencial')) && !t.includes('ead')) return 'presencial';
		if (t.includes('digital')) return 'ead';
    if (t.includes('presencial')) return 'presencial';
    return window.location.href.indexOf('-digital') > -1 ? 'ead' : 'presencial';
  }
  function getFormaIngresso() {
    const ativo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
    // Exigido: usar o textContent do botão ativo
    return ativo ? ativo.textContent.trim() : '';
  }

  if (btnIniciarMatricula && sidesOne && sidesTwo) {
    btnIniciarMatricula.addEventListener('click', function (e) {
      e.preventDefault();

      // Disparo dinâmico no GTM
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        event: 'graduacao_iniciar_matricula',
        forma_ingresso: getFormaIngresso(),         // textContent do .btnModalidade.active
        modalidade: getModalidade(),                // 'ead' ou 'presencial'
        cidade: getSelectedText('cidade'),          // se existir
        unidade: getSelectedText('unidade'),
        turno: getSelectedText('horario')
      });

      // Fluxo original: troca das seções
      sidesOne.classList.add('hidden');
      sidesTwo.classList.add('visible');
    });
  }

  if (btnVoltar && sidesOne && sidesTwo) {
    btnVoltar.addEventListener('click', function (e) {
      e.preventDefault();
      sidesOne.classList.remove('hidden');
      sidesTwo.classList.remove('visible');
    });
  }
});
</script>


<script>
/*
Como testar rapidamente:
1) Abra a página do curso, abra o DevTools (F12) e clique na aba Console.
2) Clique em "INICIAR MATRÍCULA" e rode:
	- dataLayer.filter(d => d.event === 'graduacao_iniciar_matricula').pop()
	- window.__lastGraduacaoPayload
3) Troque a forma de ingresso (ENEM, 2ª Graduação, Transferência), selecione Cidade/Unidade/Turno e clique de novo.
	Confira os campos forma_ingresso/modalidade/cidade/unidade/turno.
4) Teste o fallback por URL:
	- Adicione ?enem, ?segunda_graduacao ou ?transferencia na URL e recarregue a página.
	- Sem clicar nos botões de forma de ingresso, clique "INICIAR MATRÍCULA" e verifique o campo forma_ingresso.
*/

document.addEventListener('DOMContentLoaded', function() {
	// Mantém a mesma detecção da camada PHP (URL/sessão)
	var formaIngressoDetectada = <?php echo json_encode($forma_ingresso_detectada); ?>; // 'enem' | 'segunda_graduacao' | 'transferencia' | null

	function getSelectedText(id) {
		var el = document.getElementById(id);
		if (!el || typeof el.selectedIndex === 'undefined' || el.selectedIndex < 0) return '';
		var opt = el.options[el.selectedIndex];
		return (opt && (opt.text || opt.label)) ? (opt.text || opt.label).trim() : '';
	}
	function getFirstNonEmptySelectedText(ids) {
		for (var i = 0; i < ids.length; i++) {
			var v = getSelectedText(ids[i]);
			if (v) return v;
		}
		return '';
	}
	function getModalidade() {
		var box = document.getElementById('modalidadeBox');
		var t = box ? box.textContent.trim().toLowerCase() : '';
		if ((t.indexOf('ao vivo') > -1 || t.indexOf('semipresencial') > -1) && t.indexOf('ead') === -1) return 'presencial';
		if (t.indexOf('digital') > -1) return 'ead';
		if (t.indexOf('presencial') > -1) return 'presencial';
		return window.location.href.indexOf('-digital') > -1 ? 'ead' : 'presencial';
	}
	function getFormaIngressoFromActiveButton() {
		var ativo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
		return ativo ? ativo.textContent.trim() : '';
	}
	function labelFromSlug(slug) {
		if (!slug) return '';
		var map = {
			'enem': 'ENEM',
			'segunda_graduacao': 'SEGUNDA GRADUAÇÃO',
			'transferencia': 'TRANSFERÊNCIA'
		};
		return map[slug] || '';
	}
	function resolveFormaIngressoText() {
		// 1) Preferir o texto do botão ativo (fica igual ao que o usuário vê)
		var fromBtn = getFormaIngressoFromActiveButton();
		if (fromBtn) return fromBtn;

		// 2) Senão, usar o detectado por URL/sessão (mantém a lógica do PHP)
		var fromSlug = labelFromSlug(formaIngressoDetectada);
		if (fromSlug) return fromSlug;

		// 3) Padrão: Vestibular
		return 'VESTIBULAR';
	}
	function normalizeSlug(text) {
		return (text || '')
			.toLowerCase()
			.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
			.replace(/\s+/g, '_');
	}
	function getCursoTitle() {
		var el = document.querySelector('.entry-title');
		return el ? el.textContent.trim() : '';
	}
	function getOfferId() {
		// Tenta #idCurso (preferido), depois inputs hidden
		var el = document.getElementById('idCurso');
		var v = el ? (el.textContent || '').trim() : '';
		if (v) return v;

		var byId = document.getElementById('offer-id'); // pode existir repetido, o primeiro serve
		if (byId && byId.value) return byId.value.trim();

		var vesti = document.getElementById('offer-id-vestibular');
		if (vesti && vesti.value) return vesti.value.trim();

		return '';
	}
	function safePushToDataLayer(obj) {
		try {
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push(obj);
			window.__lastGraduacaoPayload = obj; // atalho para testes no console
			if (window.console && console.log) console.log('GTM push:', obj);
		} catch (err) {
			if (window.console && console.error) console.error('Erro ao disparar evento GTM:', err);
		}
	}
	function buildPayload() {
		var formaText = resolveFormaIngressoText();
		var formaSlug = normalizeSlug(formaText);

		// Cidade/Unidade/Turno: considera selects principais e dos modais
		var cidade = getFirstNonEmptySelectedText(['cidade','cidadeEnem','cidadeSegunda','cidadeTransf']);
		var unidade = getFirstNonEmptySelectedText(['unidade','unidadeEnem','unidadeSegunda','unidadeTransf']);
		var turno = getFirstNonEmptySelectedText(['horario','horarioEnem','horarioSegunda','horarioTransf']);

		return {
			event: 'graduacao_iniciar_matricula',
			forma_ingresso: formaText,                 // Ex.: ENEM | VESTIBULAR | TRANSFERÊNCIA | SEGUNDA GRADUAÇÃO
			forma_ingresso_slug: formaSlug,            // Ex.: enem | vestibular | transferencia | segunda_graduacao
			modalidade: getModalidade(),               // 'ead' | 'presencial'
			cidade: cidade,
			unidade: unidade,
			turno: turno,
			curso: getCursoTitle(),
			offer_id: getOfferId(),
			mneumonico: '<?php echo esc_js($mneumonico); ?>',
			page_path: location.pathname + location.search,
			ts: Date.now()
		};
	}

	// Evita duplicar nosso próprio disparo em cliques rápidos
	var lastPushAt = 0;

	// Delegado: garante que funciona mesmo que o botão seja recriado no DOM
	document.addEventListener('click', function(e) {
		var target = e.target && e.target.closest ? e.target.closest('#btnIniciarMatricula') : null;
		if (!target) return;

		var now = Date.now();
		if (now - lastPushAt < 400) return; // debouncing simples
		lastPushAt = now;

		safePushToDataLayer(buildPayload());
	}, true);

	// Acessibilidade: Enter/Espaço no botão também disparam
	document.addEventListener('keydown', function(e) {
		if ((e.key === 'Enter' || e.key === ' ') && e.target && e.target.id === 'btnIniciarMatricula') {
			safePushToDataLayer(buildPayload());
		}
	}, true);
});

(function () {
	var pushedOnce = false;

	function cloneAndSetEvent(base) {
		var copy = {};
		for (var k in base) {
			if (Object.prototype.hasOwnProperty.call(base, k)) copy[k] = base[k];
		}
		copy.event = 'graduacao_enviar_dados';
		copy.ts_enviar = Date.now();
		return copy;
	}

	function getSelectedText(id) {
		var el = document.getElementById(id);
		if (!el || el.selectedIndex < 0) return '';
		var opt = el.options[el.selectedIndex];
		return (opt && (opt.text || opt.label) ? (opt.text || opt.label) : '').trim();
	}

	function getFirstNonEmpty(ids) {
		for (var i = 0; i < ids.length; i++) {
			var v = getSelectedText(ids[i]);
			if (v) return v;
		}
		return '';
	}

	function getModalidade() {
		var box = document.getElementById('modalidadeBox');
		var t = box ? box.textContent.trim().toLowerCase() : '';
		if ((t.indexOf('ao vivo') > -1 || t.indexOf('semipresencial') > -1) && t.indexOf('ead') === -1) return 'presencial';
		if (t.indexOf('digital') > -1) return 'ead';
		if (t.indexOf('presencial') > -1) return 'presencial';
		return window.location.href.indexOf('-digital') > -1 ? 'ead' : 'presencial';
	}

	function getFormaIngressoFromActiveButton() {
		var ativo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
		return ativo ? ativo.textContent.trim() : 'VESTIBULAR';
	}

	function normalizeSlug(text) {
		return (text || '')
			.toLowerCase()
			.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
			.replace(/\s+/g, '_');
	}

	function getCursoTitle() {
		var el = document.querySelector('.entry-title');
		return el ? el.textContent.trim() : '';
	}

	function getOfferId() {
		var el = document.getElementById('idCurso');
		var v = el ? (el.textContent || '').trim() : '';
		if (v) return v;
		var byId = document.getElementById('offer-id');
		if (byId && byId.value) return byId.value.trim();
		var vesti = document.getElementById('offer-id-vestibular');
		if (vesti && vesti.value) return vesti.value.trim();
		return '';
	}

	function buildFallbackPayload() {
		var formaText = getFormaIngressoFromActiveButton();
		return {
			event: 'graduacao_enviar_dados',
			forma_ingresso: formaText,
			forma_ingresso_slug: normalizeSlug(formaText),
			modalidade: getModalidade(),
			cidade: getFirstNonEmpty(['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf']),
			unidade: getFirstNonEmpty(['unidade', 'unidadeEnem', 'unidadeSegunda', 'unidadeTransf']),
			turno: getFirstNonEmpty(['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf']),
			curso: getCursoTitle(),
			offer_id: getOfferId(),
			mneumonico: '<?php echo esc_js($mneumonico); ?>',
			page_path: location.pathname + location.search,
			ts: Date.now()
		};
	}

	function pushEnviarDados() {
		if (pushedOnce) return;
		pushedOnce = true;

		var base = (window.__lastGraduacaoPayload && typeof window.__lastGraduacaoPayload === 'object')
			? window.__lastGraduacaoPayload
			: null;

		var payload = base ? cloneAndSetEvent(base) : buildFallbackPayload();

		try {
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push(payload);
			console.log('[GTM] dataLayer.push graduacao_enviar_dados:', payload);
		} catch (e) {
			try { console.error(e); } catch (_) {}
		}
	}

	// Intercepta o fetch do SAGA e dispara o evento antes do redirecionamento
	var originalFetch = window.fetch;
	if (typeof originalFetch === 'function') {
		window.fetch = function () {
			var input = arguments[0];
			var url = (typeof input === 'string') ? input : (input && input.url) ? input.url : '';
			var promise = originalFetch.apply(this, arguments);

			try {
				if (url && url.indexOf('/sendAPI_interna.php') !== -1) {
					promise.then(function (resp) {
						// Tenta ler o JSON para sincronizar o timing; independente do parse, disparamos
						resp.clone().json().then(function () {
							pushEnviarDados();
						}).catch(function () {
							pushEnviarDados();
						});
					});
				}
			} catch (e) {
				// silencioso
			}
			return promise;
		};
	}

	// Rede de segurança: também dispara no clique do #btnComprar (antes do fetch)
	var btnComprar = document.getElementById('btnComprar');
	if (btnComprar) {
		btnComprar.addEventListener('click', function () {
			// dispara já no clique; o fetch intercept acima garantirá o disparo "antes do redirect" também
			if (!pushedOnce) {
				var base = window.__lastGraduacaoPayload;
				var payload = base ? cloneAndSetEvent(base) : buildFallbackPayload();
				try {
					window.dataLayer = window.dataLayer || [];
					window.dataLayer.push(payload);
					console.log('[GTM] dataLayer.push (click) graduacao_enviar_dados:', payload);
				} catch (e) {}
			}
		}, { capture: true });
	}
})();

</script>


<style>
	section.ConteudoCurso.wrap-metodologias {
    	z-index: 0 !important;
	}
	input.nota {
		font-size: 20px;
	}
</style>

<style>
	#btnVoltar {
		height: 45px;
		top: auto;
		position: relative;
	}
	.apenasEnem,
	.apenasSegunda,
	.apenasTransferencia {
		display: none !important;
	}
	.enem-note-field,
	.blocoNotaEnem {
		display: none !important;
	}
	.wrapSides input {
		height: 28px;
		margin-bottom: 9px;
	}
	.halfside {
		position: relative;
		display: inline-block;
		width: 48%;
		/* margin-right: 10px; */
	}
	.halfside:last-child {
		float: right
	}

</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
	const btnEnem = document.querySelector('.btnEnem');
	const btnVestibular = document.querySelector('.btnVestibular');
	const btnSegunda = document.querySelector('.btnSegundaGrad');
	const btnTransferencia = document.querySelector('.btnTransferencia');
	const modalidadeBox = document.getElementById('modalidadeBox');

	const modalidadeSlug = (function () {
		if (!modalidadeBox) return '';
		const datasetSlug = modalidadeBox.dataset.modalidadeNormalizada || '';
		if (datasetSlug) return datasetSlug.toLowerCase();
		return (modalidadeBox.textContent || '').toLowerCase();
	})();
	const isSemipresencial = modalidadeSlug.indexOf('semipresencial') !== -1;
	const isPresencialOuDigital = !isSemipresencial; // aplica à presencial e digital

	const VALOR_SEMIP = '399,00';
	const ateFinalElements = document.querySelectorAll('.ateFinal');
	const TEXTO_CUPOM = 'até o fim do curso mediante aplicação do cupom: <i><b>PRIMEIROS</b></i>';
	const TEXTO_PROVA = 'até o Final do curso<br>O valor varia conforme resultado na prova';
	const TEXTO_ENEM = 'até o Final do curso<br>O valor varia conforme resultado ENEM.';
	const TEXTO_CR = 'até o Final do curso<br>O valor varia conforme a média do seu CR.';

	function travarValorVestibularSemip() {
		if (!isSemipresencial) return;
		const alvo = document.getElementById('valorCDesconto');
		if (alvo) alvo.textContent = VALOR_SEMIP;
	}

	function atualizarLegendaSemip(texto) {
		if (!isSemipresencial) return;
		const legenda = document.querySelector('.dePorVersaoSemi');
		if (legenda) legenda.textContent = texto;
	}

	function textoPorModo(modo) {
		if (modo === 'cupom') return isSemipresencial ? TEXTO_CUPOM : TEXTO_PROVA;
		if (modo === 'enem') return TEXTO_ENEM;
		if (modo === 'cr') return TEXTO_CR;
		return null;
	}

	function atualizarTextoAteFinal(modo) {
		const proposito = modo || 'original';
		ateFinalElements.forEach(function(item) {
			if (!item.dataset.textoOriginal) {
				item.dataset.textoOriginal = (item.innerHTML || '').trim();
			}
			const novoTexto = textoPorModo(proposito);
			if (novoTexto) {
				item.innerHTML = novoTexto;
			} else if (item.dataset.textoOriginal) {
				item.innerHTML = item.dataset.textoOriginal;
			}
		});
	}

	function esconderClasses(classes) {
		classes.forEach(function(nomeClasse) {
			document.querySelectorAll(nomeClasse).forEach(function(el) {
				el.style.display = 'none';
			});
		});
	}

	function mostrarClasse(nomeClasse) {
		document.querySelectorAll(nomeClasse).forEach(function(el) {
			el.style.display = 'block';
		});
	}

	function resetBotaoVestibular() {
		$("#btnIniciarMatricula").text("INICIAR MATRÍCULA");
		$("#disparaHub").text("ENVIAR");
	}

	function acionarVestibular() {
		esconderClasses(['.apenasEnem', '.apenasSegunda', '.apenasTransferencia']);
		resetBotaoVestibular();
		mostrarClasse('.apenasVestibular');
		if (typeof window.restaurarValorPadraoCDesconto === 'function') {
			window.restaurarValorPadraoCDesconto();
		}
		travarValorVestibularSemip();
		atualizarLegendaSemip('De:');
		atualizarTextoAteFinal('cupom');
	}

	if (btnEnem) {
		btnEnem.addEventListener('click', function() {
			esconderClasses(['.apenasVestibular', '.apenasSegunda', '.apenasTransferencia']);
			resetBotaoVestibular();
			mostrarClasse('.apenasEnem');
			if (typeof window.aplicarMenorValorEnem === 'function') {
				window.aplicarMenorValorEnem();
			}
			atualizarLegendaSemip('A partir de:');
			atualizarTextoAteFinal('enem');
		});
	}

	if (btnSegunda) {
		btnSegunda.addEventListener('click', function() {
			esconderClasses(['.apenasVestibular', '.apenasEnem', '.apenasTransferencia']);
			resetBotaoVestibular();
			mostrarClasse('.apenasSegunda');
			if (typeof window.aplicarMenorValorSegunda === 'function') {
				window.aplicarMenorValorSegunda();
			}
			atualizarLegendaSemip('A partir de:');
			atualizarTextoAteFinal('cr');
		});
	}

	if (btnTransferencia) {
		btnTransferencia.addEventListener('click', function() {
			esconderClasses(['.apenasVestibular', '.apenasEnem', '.apenasSegunda']);
			resetBotaoVestibular();
			mostrarClasse('.apenasTransferencia');
			if (typeof window.aplicarMenorValorTransferencia === 'function') {
				window.aplicarMenorValorTransferencia();
			}
			atualizarLegendaSemip('A partir de:');
			atualizarTextoAteFinal('cr');
		});
	}

	if (btnVestibular) {
		btnVestibular.addEventListener('click', acionarVestibular);
		$(btnVestibular).trigger("click");
	} else {
		atualizarTextoAteFinal('cupom');
	}
});
</script>



<!-- METRICA BOSS GTM CARD -->
 <script>
	document.addEventListener('DOMContentLoaded', function () {

		window.dataLayer = window.dataLayer || [];
		const payload = {
			event: 'forma_ingresso_selecionada',
			modalidade: getModalidadeAtual(),
			forma_ingresso: 'VESTIBULAR'
		};
		dataLayer.push(payload);
		// console.log('forma_ingresso_selecionada', payload);

		const buttons = document.querySelectorAll('.btnVestibular, .btnEnem, .btnSegundaGrad, .btnTransferencia');
		const btnIniciar = document.getElementById('btnIniciarMatricula');
		const btnVestibularApenas = document.querySelectorAll('.apenasVestibular');
		const btnCalcularEnem = document.getElementById('btnCalcularEnem');
		const btnCalcularSegunda = document.getElementById('btnCalcularSegunda');
		const btnCalcularTransferencia = document.getElementById('btnCalcularTransferencia');
		const btnComprarElements = document.querySelectorAll('.btnComprar');
		const hiddenBtnComprar = document.getElementById('btnComprar');

		// function logEvent(label, payload) {
		// 	console.log('[metrics-boss]', label, payload);
		// }

		function pushDataLayer(label, payload) {
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push(payload);
			// logEvent(label, payload);
		}

		function getInputValue(id) {
			const el = document.getElementById(id);
			return el && el.value ? el.value.trim() : '';
		}

		function enrichWithScores(payload, formaReferencia) {
			const formaContexto = formaReferencia || getFormaAtual();
			const notaEnem = getInputValue('mediaEnem');
			const mediaSegunda = getInputValue('mediaSegunda');
			const mediaTransferencia = getInputValue('mediaTransferencia');

			if (formaContexto === 'ENEM' && notaEnem) {
				payload.nota_enem = notaEnem;
			}

			if (formaContexto === '2ª GRADUAÇÃO' && mediaSegunda) {
				payload.cr = mediaSegunda;
			}

			if (formaContexto === 'TRANSFERÊNCIA' && mediaTransferencia) {
				payload.cr = mediaTransferencia;
			}

			return payload;
		}

		function getModalidadeAtual() {
			const box = document.getElementById('modalidadeBox');
			if (!box) return '';
			const texto = box.textContent.trim().toLowerCase();
			if (texto.includes('digital')) return 'Digital';
			if (texto.includes('presencial')) return 'Presencial';
			return 'Presencial';
		}

		function getFormaIngresso(btn) {
			if (btn.classList && btn.classList.contains('btnVestibular')) return 'VESTIBULAR';
			if (btn.classList && btn.classList.contains('btnEnem')) return 'ENEM';
			if (btn.classList && btn.classList.contains('btnSegundaGrad')) return '2ª GRADUAÇÃO';
			if (btn.classList && btn.classList.contains('btnTransferencia')) return 'TRANSFERÊNCIA';
			return (btn.textContent || '').trim().toUpperCase();
		}

		function getFormaAtual() {
			const ativo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
			if (!ativo) return '';
			return getFormaIngresso(ativo);
		}

		function getCursoNome() {
			const el = document.querySelector('.entry-title');
			return el ? el.textContent.trim() : '';
		}

		function getSelectText(id) {
			const select = document.getElementById(id);
			if (!select || select.selectedIndex < 0) return '';
			const option = select.options[select.selectedIndex];
			return option ? option.text.trim() : '';
		}

		function getFirstSelectText(ids) {
			for (const id of ids) {
				const value = getSelectText(id);
				if (value) return value;
			}
			return '';
		}

		function buildCommonPayload(forma) {
			return {
				curso_nome: getCursoNome(),
				modalidade: getModalidadeAtual(),
				forma_ingresso: forma,
				cidade: getFirstSelectText(['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf']),
				unidade: getFirstSelectText(['unidade', 'unidadeEnem', 'unidadeSegunda', 'unidadeTransf']),
				turno: getFirstSelectText(['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf'])
			};
		}

		buttons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				const forma = getFormaIngresso(btn);
				const eventoAtual = enrichWithScores({
					event: 'forma_ingresso_selecionada',
					modalidade: getModalidadeAtual(),
					forma_ingresso: forma
				}, forma);
				pushDataLayer('evento atual (forma_ingresso_selecionada)', eventoAtual);
			});
		});

		if (btnIniciar) {
			btnIniciar.addEventListener('click', function () {
				const forma = getFormaAtual();
				if (forma === 'VESTIBULAR') {
					const payload = enrichWithScores(Object.assign({
						event: 'cursos_vestibular_iniciar_matricula'
					}, buildCommonPayload(forma)), forma);
					pushDataLayer('evento atual (cursos_vestibular_iniciar_matricula)', payload);
				} else if (forma === 'ENEM') {
					const payload = enrichWithScores(Object.assign({
						event: 'cursos_enem_avancar'
					}, buildCommonPayload(forma)), forma);
					pushDataLayer('evento atual (cursos_enem_avancar)', payload);
				} else if (forma === '2ª GRADUAÇÃO') {
					const payload = enrichWithScores(Object.assign({
						event: 'cursos_segunda_grad_avancar'
					}, buildCommonPayload(forma)), forma);
					pushDataLayer('evento atual (cursos_segunda_grad_avancar)', payload);
				} else if (forma === 'TRANSFERÊNCIA') {
					const payload = enrichWithScores(Object.assign({
						event: 'cursos_transferencia_avancar'
					}, buildCommonPayload(forma)), forma);
					pushDataLayer('evento atual (cursos_transferencia_avancar)', payload);
				}
			});
		}

		if (btnVestibularApenas.length) {
			btnVestibularApenas.forEach(function (btn) {
				btn.addEventListener('click', function () {
					if (getFormaAtual() !== 'VESTIBULAR') return;
					const payload = enrichWithScores(Object.assign({
						event: 'cursos_vestibular_enviar'
					}, buildCommonPayload('VESTIBULAR')), 'VESTIBULAR');
					pushDataLayer('evento atual (cursos_vestibular_enviar)', payload);
				});
			});
		}

		if (btnCalcularEnem) {
			btnCalcularEnem.addEventListener('click', function () {
				if (getFormaAtual() !== 'ENEM') return;
				const payload = enrichWithScores(Object.assign({
					event: 'cursos_enem_calcular'
				}, buildCommonPayload('ENEM')), 'ENEM');
				pushDataLayer('evento atual (cursos_enem_calcular)', payload);
			});
		}

		if (btnCalcularSegunda) {
			btnCalcularSegunda.addEventListener('click', function () {
				if (getFormaAtual() !== '2ª GRADUAÇÃO') return;
				const payload = enrichWithScores(Object.assign({
					event: 'cursos_segunda_grad_calcular'
				}, buildCommonPayload('2ª GRADUAÇÃO')), '2ª GRADUAÇÃO');
				pushDataLayer('evento atual (cursos_segunda_grad_calcular)', payload);
			});
		}

		if (btnCalcularTransferencia) {
			btnCalcularTransferencia.addEventListener('click', function () {
				if (getFormaAtual() !== 'TRANSFERÊNCIA') return;
				const payload = enrichWithScores(Object.assign({
					event: 'cursos_transferencia_calcular'
				}, buildCommonPayload('TRANSFERÊNCIA')), 'TRANSFERÊNCIA');
				pushDataLayer('evento atual (cursos_transferencia_calcular)', payload);
			});
		}

		function handleBtnComprarClick(event) {
			// Ignora disparos sintéticos (ex.: clique automático no #btnComprar após o HubSpot)
			if (event && event.isTrusted === false) {
				return;
			}
			const forma = getFormaAtual();
			if (forma === 'ENEM') {
				const payload = enrichWithScores(Object.assign({
					event: 'cursos_enem_matricule_se'
				}, buildCommonPayload('ENEM')), 'ENEM');
				pushDataLayer('evento atual (cursos_enem_matricule_se)', payload);
			} else if (forma === '2ª GRADUAÇÃO') {
				const payload = enrichWithScores(Object.assign({
					event: 'cursos_segunda_grad_matricule_se'
				}, buildCommonPayload('2ª GRADUAÇÃO')), '2ª GRADUAÇÃO');
				pushDataLayer('evento atual (cursos_segunda_grad_matricule_se)', payload);
			} else if (forma === 'TRANSFERÊNCIA') {
				const payload = enrichWithScores(Object.assign({
					event: 'cursos_transferencia_matricule_se'
				}, buildCommonPayload('TRANSFERÊNCIA')), 'TRANSFERÊNCIA');
				pushDataLayer('evento atual (cursos_transferencia_matricule_se)', payload);
			}
		}

		if (btnComprarElements.length) {
			btnComprarElements.forEach(function (btn) {
				btn.addEventListener('click', handleBtnComprarClick);
			});
		}

		if (hiddenBtnComprar) {
			hiddenBtnComprar.addEventListener('click', handleBtnComprarClick, true);
		}
	});
 </script>
<!-- METRICA BOSS GTM CARD -->



<script>
// Exibe o .box com fade-in suave após 2 segundos
// document.addEventListener('DOMContentLoaded', function() {
// 	var box = document.querySelector('.box');
// 	if (!box) return;

// 	box.style.opacity = '0';
// 	box.style.visibility = 'visible';
// 	box.style.transition = 'opacity 0.7s cubic-bezier(.4,0,.2,1)';

// 	setTimeout(function() {
// 		box.style.opacity = '1';
// 	}, 2000);
// });
</script>

<style>
	.site-content-contain {
    	padding-top: 40px !important;
	}
	.wrapParaAll {
		padding-top: 50px !important;
	}
</style>

<?php

// var_dump($data);
get_footer();

?>

<!-- INICIO BLOCO TEMPORARIO: CARD FIXO LATERAL DIREITA (ROLLBACK FACIL) -->
<!--
BLOCO TEMPORARIO UNIFICADO (PHP + HTML + CSS + JS)

Objetivo:
- Exibir card fixo lateral com a regra visual de oferta.
- Resolver o curso atual por nome/mneumonico e consultar dadosHome.json.
- Aplicar a mesma regra de oferta usada na home.

Rollback simples:
- Remover TODO o conteudo entre os marcadores:
  INICIO BLOCO TEMPORARIO: CARD FIXO LATERAL DIREITA (ROLLBACK FACIL)
  FIM BLOCO TEMPORARIO: CARD FIXO LATERAL DIREITA (ROLLBACK FACIL)
-->

<?php
	/*
	 * PREPARACAO SERVER-SIDE TEMPORARIA
	 * - Le os dados da pagina atual (titulo/mneumonico/modalidade).
	 * - Faz match no dadosHome.json.
	 * - Exporta os campos minimos para o JS aplicar a regra visual.
	 */

$oferta_json_preco = '';
$oferta_json_modalidade = '';
$oferta_json_mnemonico = '';
$oferta_json_encontrado = false;

$post_id_oferta = !empty($current_post_id) ? (int) $current_post_id : (int) get_the_ID();
$titulo_pagina_oferta = $post_id_oferta > 0 ? get_the_title($post_id_oferta) : get_the_title();
$mneumonico_pagina_oferta = is_string($mneumonico) ? trim($mneumonico) : '';
if ($mneumonico_pagina_oferta === '') {
	$mneumonico_pagina_oferta = trim((string) get_post_meta($post_id_oferta, 'mneumonico', true));
}
if ($mneumonico_pagina_oferta === '') {
	$mneumonico_pagina_oferta = trim((string) get_post_meta($post_id_oferta, 'mnemonico', true));
}

$normalizar_texto_oferta = function($valor) {
	$valor = (string) $valor;
	if (function_exists('remove_accents')) {
		$valor = remove_accents($valor);
	}
	$valor = strtolower($valor);
	$valor = preg_replace('/\s+/', ' ', trim($valor));
	return $valor;
};

$normalizar_modalidade_oferta = function($valor) use ($normalizar_texto_oferta) {
	$base = $normalizar_texto_oferta($valor);
	if ($base === '') return 'presencial';
	if (strpos($base, 'semipresencial') !== false || strpos($base, 'semi presencial') !== false) return 'digitalaovivo';
	if (strpos($base, 'webconferencia') !== false || strpos($base, 'web conferencia') !== false || strpos($base, 'digital ao vivo') !== false || strpos($base, 'ao vivo') !== false) return 'digitalaovivo';
	if (strpos($base, 'ead') !== false || strpos($base, 'digital') !== false) return 'digital';
	return 'presencial';
};

$variantes_titulo_oferta = function($titulo) use ($normalizar_texto_oferta) {
	$base = $normalizar_texto_oferta($titulo);
	if ($base === '') return array();
	$variantes = array($base);
	$sem_prefixo = preg_replace('/^pos[\-\s]*graduacao\s*/', '', $base);
	$sem_prefixo = preg_replace('/^(presencial|digital\s*\(ead\)|digital\s*ao\s*vivo|ead|webconferencia)\s*/', '', $sem_prefixo);
	$sem_prefixo = preg_replace('/^em\s+/', '', $sem_prefixo);
	$sem_prefixo = trim((string) $sem_prefixo);
	if ($sem_prefixo !== '' && !in_array($sem_prefixo, $variantes, true)) {
		$variantes[] = $sem_prefixo;
	}
	if ($sem_prefixo !== '' && strpos($sem_prefixo, ' em ') !== false) {
		$partes = explode(' em ', $sem_prefixo);
		$apos_em = trim((string) end($partes));
		if ($apos_em !== '' && !in_array($apos_em, $variantes, true)) {
			$variantes[] = $apos_em;
		}
	}
	return $variantes;
};

$json_path_oferta = __DIR__ . '/dadosHome.json';
if (file_exists($json_path_oferta)) {
	$conteudo_json_oferta = file_get_contents($json_path_oferta);
	if (is_string($conteudo_json_oferta) && trim($conteudo_json_oferta) !== '') {
		$conteudo_json_oferta = preg_replace('/\n?\/\*.*?\*\/\s*$/s', '', $conteudo_json_oferta);
		$payload_oferta = json_decode($conteudo_json_oferta, true);
		$lista_oferta = (is_array($payload_oferta) && !empty($payload_oferta['posgraduacao']) && is_array($payload_oferta['posgraduacao']))
			? $payload_oferta['posgraduacao']
			: array();

		if (!empty($lista_oferta)) {
			$melhor_item = null;
			$melhor_score = -1;
			$titulos_possiveis = $variantes_titulo_oferta($titulo_pagina_oferta);
			$mnemo_norm_pagina = $normalizar_texto_oferta($mneumonico_pagina_oferta);
			$modalidade_norm_pagina = $normalizar_modalidade_oferta($page_modalidade_slug ?? '');

			foreach ($lista_oferta as $item_oferta) {
				if (!is_array($item_oferta)) continue;

				$item_titulo = $normalizar_texto_oferta($item_oferta['curso'] ?? ($item_oferta['nome'] ?? ''));
				$item_modalidade = $normalizar_modalidade_oferta($item_oferta['modalidade'] ?? '');
				$item_mnemo = $normalizar_texto_oferta($item_oferta['mnemonico'] ?? ($item_oferta['mneumonico'] ?? ''));

				$score = 0;
				if ($mnemo_norm_pagina !== '' && $item_mnemo !== '' && $mnemo_norm_pagina === $item_mnemo) {
					$score += 100;
				}

				if (!empty($titulos_possiveis) && $item_titulo !== '') {
					if (in_array($item_titulo, $titulos_possiveis, true)) {
						$score += 50;
					} else {
						foreach ($titulos_possiveis as $titulo_possivel) {
							if ($titulo_possivel === '') continue;
							if (strpos($item_titulo, $titulo_possivel) !== false || strpos($titulo_possivel, $item_titulo) !== false) {
								$score += 30;
								break;
							}
						}
					}
				}

				if ($modalidade_norm_pagina !== '' && $item_modalidade === $modalidade_norm_pagina) {
					$score += 20;
				}

				if ($score > $melhor_score) {
					$melhor_item = $item_oferta;
					$melhor_score = $score;
				}
			}

			if (is_array($melhor_item) && $melhor_score > 0) {
				$oferta_json_preco = (string) ($melhor_item['precos'] ?? '');
				$oferta_json_modalidade = (string) ($melhor_item['modalidade'] ?? '');
				$oferta_json_mnemonico = (string) ($melhor_item['mnemonico'] ?? ($melhor_item['mneumonico'] ?? ''));
				$oferta_json_encontrado = ($oferta_json_preco !== '');
			}
		}
	}
}

?>

<!-- HTML TEMPORARIO: estrutura do card fixo lateral direita -->

<aside id="floatingOfferCard" aria-hidden="true">
	<div class="floatingOfferHeader">
		<h3 id="floatingOfferTitle">Pos-Graduacao Presencial</h3>
	</div>

	<div class="floatingOfferPrice">
		<p class="floatingOfferFrom">A partir de:</p>
		<p class="floatingOfferDe" id="floatingOfferDe">18x de R$000,00</p>
		<p class="floatingOfferParcela" id="floatingOfferParcela">18x de R$000,00</p>
		<p class="floatingOfferCupom" id="floatingOfferCupom">com o cupom 300NAPOS</p>
	</div>

	<div class="floatingOfferGrid">
		<div>
			<h4>Modalidade:</h4>
			<p id="floatingOfferModalidade">Presencial</p>
		</div>
	</div>

	<a id="floatingOfferCta" class="btnInscreva btnBoxTopo" href="#" target="_blank" rel="noopener">INSCREVA-SE JÁ!</a>

	<!-- ===== INICIO BLOCO TEMPORARIO: FORMULARIO HUBSPOT NO CARD (ROLLBACK FACIL) ===== -->
	<div class="sagaFormShell formEscondido" aria-hidden="true">
		<div class="sagaFormHeader">
			<span class="sagaFormTitle">Quase lá! Complete seus dados.</span>
			<button type="button" class="sagaFormClose" aria-label="Fechar formulario">&times;</button>
		</div>
		<form class="sagaHubForm" novalidate>
			<div class="sagaRow">
				<label class="sagaField">
					Nome*
					<input type="text" name="firstname" autocomplete="given-name" required>
					<span class="sagaFieldError" data-error-for="firstname"></span>
				</label>
				<label class="sagaField">
					Sobrenome*
					<input type="text" name="lastname" autocomplete="family-name" required>
					<span class="sagaFieldError" data-error-for="lastname"></span>
				</label>
			</div>
			<div class="sagaRow">
				<label class="sagaField">
					E-mail*
					<input type="email" name="email" autocomplete="email" required>
					<span class="sagaFieldError" data-error-for="email"></span>
				</label>
				<label class="sagaField">
					Telefone*
					<input type="tel" name="mobilephone" placeholder="(21) 99999-9999" autocomplete="tel" required>
					<span class="sagaFieldError" data-error-for="mobilephone"></span>
				</label>
			</div>
			<label class="sagaOptIn">
				<input type="checkbox" name="lgpd_opt_in" required>
				<span class="autorizo">Autorizo o uso dos meus dados pessoais para contato e matricula, em conformidade com a LGPD.</span>
			</label>
			<p class="sagaFieldError" data-error-for="lgpd_opt_in"></p>
			<input type="hidden" name="redirectHref" value="">
			<input type="hidden" name="redirectTarget" value="">
			<button type="submit" class="sagaSubmit">ENVIAR</button>
		</form>
		<div class="sagaFormStatus" role="status" aria-live="polite"></div>
	</div>
	<!-- ===== FIM BLOCO TEMPORARIO: FORMULARIO HUBSPOT NO CARD (ROLLBACK FACIL) ===== -->
	<!-- <p class="floatingOfferFoot">DESCONTO ESPECIAL PARA EX-ALUNOS DA UNISUAM</p> -->
</aside>

<!-- CSS TEMPORARIO: visual, tipografia e posicionamento fixo -->
<style>
	/* Escurecimento leve do header destacado para melhorar contraste do texto. */
	@media(min-width: 1024px) {
		.single-featured-image-header {
			background-color: rgba(0, 0, 0, .3) !important;
			background-blend-mode: multiply;
		}
	}

	#floatingOfferCard {
		--offer-accent: #15b8cc;
		--offer-accent-strong: #018ead;
		--offer-accent-soft: #c9e8ed;
		position: fixed;
		right: 8%;
		top: 50%;
		transform: translateY(-50%);
		width: min(420px, calc(100vw - 24px));
		background: #f1f3f6;
		border-radius: 16px;
		border-top: 6px solid var(--offer-accent);
		box-shadow: 0 18px 40px rgba(20, 35, 55, 0.25);
		padding: 20px 18px 14px 18px;
		z-index: 99997;
		opacity: 1;
		pointer-events: auto;
		visibility: visible;
		transition: opacity .28s ease, transform .28s ease, visibility .28s ease;
		font-family: 'Poppins', 'Montserrat', sans-serif;
	}

	#floatingOfferCard.is-hidden-right {
		opacity: 0;
		pointer-events: none;
		transform: translate(calc(100% + 48px), -50%);
	}

	#floatingOfferCard .floatingOfferHeader h3 {
		margin: 0 0 16px 0;
		font-size: 16px;
		line-height: 1.14;
		color: #4f596b;
		font-weight: 700;
		letter-spacing: -.2px;
	}

	#floatingOfferCard .floatingOfferPrice {
		margin-bottom: 14px;
		padding-bottom: 10px;
		border-bottom: 1px solid #dfe4eb;
	}

	#floatingOfferCard .floatingOfferFrom {
		margin: 0;
		font-size: 13px;
		color: #9aa4b5;
		font-weight: 700;
		line-height: 1;
	}

	#floatingOfferCard .floatingOfferDe {
		margin: 1px 0 0 0;
		font-size: 22px;
		line-height: 1.1;
		text-decoration: line-through;
		color: #6f7b8c;
		font-weight: 500;
	}

	#floatingOfferCard .floatingOfferParcela {
		margin: 2px 0 0 0;
		font-size: 40px;
		line-height: .96;
		font-weight: 800;
		color: #2f3b4f;
		letter-spacing: -.6px;
	}

	#floatingOfferCard .floatingOfferCupom {
		margin: 6px 0 0 0;
		font-size: 15px;
		line-height: 1.2;
		font-weight: 600;
		color: #606c7a;
	}

	#floatingOfferCard .floatingOfferGrid {
		display: grid;
		grid-template-columns: 1fr;
		gap: 14px 18px;
		margin-top: 2px;
	}

	#floatingOfferCard .floatingOfferGrid h4 {
		margin: 0;
		font-size: 13px;
		line-height: 1.15;
		color: var(--offer-accent-strong);
		font-weight: 700;
	}

	#floatingOfferCard .floatingOfferGrid p {
		margin: 3px 0 0 0;
		font-size: 14px;
		line-height: 1.22;
		color: #586476;
	}

	#floatingOfferCta {
		display: block;
		text-align: center;
		text-decoration: none;
		background: #ef7d00;
		color: #fff;
		font-weight: 800;
		font-size: 22px;
		line-height: 1;
		padding: 18px 12px;
		border-radius: 6px;
		margin-top: 14px;
	}

	#floatingOfferCard.form-open #floatingOfferCta {
		display: none;
	}

	#floatingOfferCard .sagaFormShell {
		display: none;
		margin-top: 12px;
		padding-top: 12px;
		border-top: 1px solid #dfe4eb;
	}

	#floatingOfferCard .sagaFormShell.is-visible {
		display: block;
	}

	#floatingOfferCard .sagaFormHeader {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 10px;
	}

	#floatingOfferCard .sagaFormTitle {
		font-size: 13px;
		font-weight: 700;
		color: var(--offer-accent-strong);
	}

	#floatingOfferCard .sagaFormClose {
		border: none;
		background: transparent;
		font-size: 22px;
		line-height: 1;
		cursor: pointer;
		color: #5b6778;
	}

	#floatingOfferCard .sagaHubForm {
		display: grid;
		gap: 8px;
	}

	#floatingOfferCard .sagaRow {
		display: grid;
		gap: 8px;
	}

	#floatingOfferCard .sagaField {
		display: flex;
		flex-direction: column;
		font-size: 12px;
		font-weight: 600;
		color: #586476;
	}

	#floatingOfferCard .sagaField input {
		margin-top: 4px;
		height: 36px;
		padding: 0 10px;
		border: 1px solid #cfd6de;
		border-radius: 4px;
		font-size: 13px;
		color: #2f3b4f;
	}

	#floatingOfferCard .sagaField input:focus {
		outline: none;
		border-color: var(--offer-accent-strong);
		box-shadow: 0 0 0 2px rgba(15, 150, 174, 0.12);
	}

	#floatingOfferCard .sagaFieldError {
		display: none;
		font-size: 11px;
		line-height: 1.2;
		color: #c0392b;
		margin-top: 4px;
	}

	#floatingOfferCard .sagaFieldError.is-visible {
		display: block;
	}

	#floatingOfferCard .sagaOptIn {
		display: grid;
		grid-template-columns: 16px 1fr;
		gap: 8px;
		align-items: start;
		margin-top: 2px;
	}

	#floatingOfferCard .sagaOptIn input[type="checkbox"] {
		margin-top: 2px;
	}

	#floatingOfferCard .autorizo {
		font-size: 11px;
		line-height: 1.25;
		color: #586476;
	}

	#floatingOfferCard .sagaSubmit {
		width: 100%;
		height: 40px;
		border: 0;
		border-radius: 6px;
		font-size: 14px;
		font-weight: 700;
		color: #fff;
		cursor: pointer;
		background: #ef7d00;
	}

	#floatingOfferCard .sagaSubmit[disabled] {
		opacity: .65;
		cursor: not-allowed;
	}

	#floatingOfferCard .sagaFormStatus {
		margin-top: 6px;
		font-size: 12px;
		line-height: 1.3;
		color: #3a475a;
	}

	#floatingOfferCard .floatingOfferFoot {
		margin: 14px -18px -14px -18px;
		padding: 12px 14px;
		border-radius: 0 0 16px 16px;
		background: var(--offer-accent-soft);
		color: var(--offer-accent-strong);
		font-size: 12px;
		font-weight: 700;
		line-height: 1.2;
		text-align: center;
		letter-spacing: .2px;
	}

	/* Tema por modalidade: -digital usa rosa do projeto. */
	#floatingOfferCard.tema-digital {
		--offer-accent: #E5457A;
		--offer-accent-strong: #E5457A;
		--offer-accent-soft: #f7d6e3;
	}

	/* Tema por modalidade: -aovivo usa lilas do projeto. */
	#floatingOfferCard.tema-aovivo {
		--offer-accent: #7D378D;
		--offer-accent-strong: #7D378D;
		--offer-accent-soft: #e6d8ec;
	}

	@media (max-width: 991px) {
		#floatingOfferCard {
			position: relative;
			right: auto;
			top: auto;
			transform: none;
			width: calc(100% - 24px);
			max-width: 560px;
			margin: 14px auto 0 auto;
			z-index: 1;
		}

		#floatingOfferCard.is-hidden-right {
			opacity: 1;
			pointer-events: auto;
			transform: none;
		}
		#floatingOfferCard .floatingOfferParcela {
			font-size: 30px !important;
		}

		#floatingOfferCard .floatingOfferHeader h3 {
			font-size: 16px;
		}

		#floatingOfferCard .floatingOfferFrom {
			font-size: 12px;
		}

		#floatingOfferCard .floatingOfferDe {
			font-size: 26px;
		}

		#floatingOfferCard .floatingOfferParcela {
			font-size: 40px;
		}

		#floatingOfferCard .floatingOfferCupom {
			font-size: 13px;
		}

		#floatingOfferCard .floatingOfferGrid h4 {
			font-size: 15px;
		}

		#floatingOfferCard .floatingOfferGrid p {
			font-size: 13px;
		}

		#floatingOfferCta {
			font-size: 20px;
		}

		#floatingOfferCard .sagaRow {
			grid-template-columns: 1fr;
		}

		#floatingOfferCard .floatingOfferFoot {
			font-size: 13px;
		}
	}

	@media (min-width: 992px) {
		#floatingOfferCard .sagaRow {
			grid-template-columns: 1fr 1fr;
		}
	}
</style>

<!-- SCRIPT TEMPORARIO: aplica regra de oferta no card usando dados preparados no PHP -->
<script>
(function() {
	var OFERTAS_REGRA_HOME = {
		'399': { de: 'R$ 6.463,80', parcela: 'R$ 513,65', vezes: '12x de ' },
		'299': { de: 'R$ 4.843,80', parcela: 'R$ 378,58', vezes: '12x de ' },
		'599': { de: 'R$ 9.703,80', parcela: 'R$ 783,65', vezes: '12x de ' },
		'199': { de: 'R$ 3.223,80', parcela: 'R$ 243,65', vezes: '12x de ' }
	};

	var OFERTA_DIGITAL_FIXA_INTERNA = {
		de: 'R$ 2.400,00',
		parcela: '12x de R$ 99,00'
	};

	var OFERTA_JSON_PRECO = <?php echo wp_json_encode($oferta_json_preco); ?>;
	var OFERTA_JSON_MODALIDADE = <?php echo wp_json_encode($oferta_json_modalidade); ?>;
	var OFERTA_JSON_MNEMONICO = <?php echo wp_json_encode($oferta_json_mnemonico); ?>;
	var OFERTA_JSON_ENCONTRADO = <?php echo $oferta_json_encontrado ? 'true' : 'false'; ?>;
	var MNEUMONICO_PAGINA = <?php echo wp_json_encode($mneumonico); ?>;
	var TITULO_PAGINA_PHP = <?php echo wp_json_encode(get_the_title()); ?>;
	var MODALIDADE_SLUG_PHP = <?php echo wp_json_encode($page_modalidade_slug); ?>;
	var MODALIDADE_LABEL_PHP = <?php echo wp_json_encode($modalidade_box_value); ?>;
	var HUB_ENDPOINT = 'https://api.hsforms.com/submissions/v3/integration/submit/3462868/709c003b-fb18-4cff-beac-fbd2af676bcb';
	var HUB_SUCCESS_REDIRECT_URL = 'https://inscricao.unisuam.edu.br/pos';
	var sagaFormState = {
		lastRedirectUrl: null,
		lastRedirectTarget: null,
		submitting: false
	};

	function textOf(selector) {
		var el = document.querySelector(selector);
		if (!el) return '';
		return (el.textContent || '').replace(/\s+/g, ' ').trim();
	}

	function valorMoedaDeTexto(texto) {
		var m = (texto || '').match(/R\$\s*[\d\.]+(?:,[\d]{2})?/);
		return m ? m[0].replace(/\s+/g, ' ') : '';
	}

	function normalizarModalidade(valor) {
		var base = (valor || '')
			.toString()
			.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
			.toLowerCase()
			.trim();

		// Mantem a mesma prioridade da regra da home: ao vivo/semipresencial antes de digital.
		if (base.indexOf('semipresencial') !== -1 || base.indexOf('semi presencial') !== -1) {
			return 'digitalaovivo';
		}
		if (base.indexOf('webconferencia') !== -1 || base.indexOf('web conferencia') !== -1 || base.indexOf('digital ao vivo') !== -1 || base.indexOf('ao vivo') !== -1) {
			return 'digitalaovivo';
		}
		if (base.indexOf('ead') !== -1 || base.indexOf('digital') !== -1) {
			return 'digital';
		}
		return 'presencial';
	}

	function normalizarTexto(valor) {
		return (valor || '')
			.toString()
			.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
			.toLowerCase()
			.replace(/\s+/g, ' ')
			.trim();
	}

	function variantesTituloCurso(tituloOriginal) {
		var base = normalizarTexto(tituloOriginal);
		if (!base) return [];

		var variantes = [base];
		var semPrefixo = base
			.replace(/^pos[\-\s]*graduacao\s*/i, '')
			.replace(/^(presencial|digital\s*\(ead\)|digital\s*ao\s*vivo|ead|webconferencia)\s*/i, '')
			.replace(/^em\s+/i, '')
			.trim();

		if (semPrefixo && variantes.indexOf(semPrefixo) === -1) {
			variantes.push(semPrefixo);
		}

		if (semPrefixo.indexOf(' em ') !== -1) {
			var aposEm = semPrefixo.split(' em ').pop().trim();
			if (aposEm && variantes.indexOf(aposEm) === -1) {
				variantes.push(aposEm);
			}
		}

		return variantes;
	}

	function chaveOfertaPorTexto(textoValor) {
		if (!textoValor) return '';
		var numero = String(textoValor)
			.replace(/\s+/g, '')
			.replace('R$', '')
			.replace(/[^\d,\.\-]/g, '');

		if (numero.indexOf(',') !== -1) {
			// Formato BR: 1.234,56
			numero = numero.replace(/\./g, '').replace(',', '.');
		} else if ((numero.match(/\./g) || []).length > 1) {
			// Formatos com mais de um ponto: mantem apenas o ultimo como decimal.
			var partes = numero.split('.');
			var decimal = partes.pop();
			numero = partes.join('') + '.' + decimal;
		}

		var parsed = parseFloat(numero);
		if (!isFinite(parsed)) return '';
		return String(Math.round(parsed));
	}

	function numeroPorTextoMoeda(textoValor) {
		if (!textoValor) return NaN;
		var numero = String(textoValor)
			.replace(/\s+/g, '')
			.replace('R$', '')
			.replace(/[^\d,\.\-]/g, '');

		if (numero.indexOf(',') !== -1) {
			numero = numero.replace(/\./g, '').replace(',', '.');
		} else if ((numero.match(/\./g) || []).length > 1) {
			var partes = numero.split('.');
			var decimal = partes.pop();
			numero = partes.join('') + '.' + decimal;
		}

		return parseFloat(numero);
	}

	function formatarMoedaBR(valor) {
		if (!isFinite(valor)) return '';
		return 'R$ ' + Number(valor).toLocaleString('pt-BR', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	}

	function modalidadePorSlug(slug) {
		var base = normalizarTexto(slug);
		if (base === 'digital') return 'digital';
		if (base === 'semipresencial' || base === 'digitalaovivo') return 'digitalaovivo';
		return 'presencial';
	}

	function rotuloModalidadePorSlug(slugModalidade) {
		if (slugModalidade === 'digital') return 'Digital (EaD)';
		if (slugModalidade === 'digitalaovivo') return 'Digital ao Vivo';
		return 'Presencial';
	}

	function corModalidadePorSlug(slugModalidade) {
		if (slugModalidade === 'digital') return '#E5457A';
		if (slugModalidade === 'digitalaovivo') return '#7D378D';
		return '#0F96AE';
	}

	function getFormRefs(card) {
		return {
			shell: card.querySelector('.sagaFormShell'),
			form: card.querySelector('.sagaHubForm'),
			status: card.querySelector('.sagaFormStatus')
		};
	}

	function toggleForm(card, shouldShow) {
		var refs = getFormRefs(card);
		var ctaBtn = card.querySelector('#floatingOfferCta');
		if (!refs.shell) return;

		if (shouldShow) {
			card.classList.add('form-open');
			if (ctaBtn) ctaBtn.style.display = 'none';
			refs.shell.classList.add('is-visible');
			refs.shell.classList.remove('formEscondido');
			refs.shell.setAttribute('aria-hidden', 'false');
			return;
		}

		card.classList.remove('form-open');
		if (ctaBtn) ctaBtn.style.display = '';
		refs.shell.classList.remove('is-visible');
		refs.shell.classList.add('formEscondido');
		refs.shell.setAttribute('aria-hidden', 'true');
		if (refs.status) refs.status.textContent = '';
		if (refs.form) refs.form.reset();
		if (refs.form) {
			Array.prototype.forEach.call(refs.form.querySelectorAll('.sagaFieldError'), function(el) {
				el.classList.remove('is-visible');
				el.textContent = '';
			});
		}
	}

	function formatPhone(raw) {
		var digits = String(raw || '').replace(/\D/g, '');
		if (!digits) return '';
		if (digits.length <= 10) {
			return digits.replace(/(\d{0,2})(\d{0,4})(\d{0,4})/, function(_, a, b, c) {
				var out = '';
				if (a) out += '(' + a;
				if (a && a.length === 2) out += ') ';
				if (b) out += b;
				if (c) out += '-' + c;
				return out;
			});
		}
		return digits.replace(/(\d{0,2})(\d{0,5})(\d{0,4})/, function(_, a, b, c) {
			var out = '';
			if (a) out += '(' + a;
			if (a && a.length === 2) out += ') ';
			if (b) out += b;
			if (c) out += '-' + c;
			return out;
		});
	}

	function isValidPhone(raw) {
		var digits = String(raw || '').replace(/\D/g, '');
		if (digits.length !== 10 && digits.length !== 11) return false;
		if (/^(\d)\1+$/.test(digits)) return false;
		return true;
	}

	function isValidEmail(raw) {
		var value = String(raw || '').trim();
		if (!value) return false;
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
	}

	function collectUTMFields() {
		var params = new URLSearchParams(window.location.search || '');
		var keys = [
			'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term',
			'utm_id', 'utm_source_platform', 'utm_campaign_id', 'utm_creative_format', 'utm_marketing_tactic'
		];
		var fields = keys.map(function(key) {
			return { name: key, value: params.get(key) || '' };
		});
		fields.push({ name: 'origem261', value: params.get('origemmkt') || '' });
		return fields;
	}

	function hydrateRedirect(form, url, target) {
		if (!form) return;
		var urlInput = form.querySelector('input[name="redirectHref"]');
		var targetInput = form.querySelector('input[name="redirectTarget"]');
		if (urlInput) urlInput.value = url || '';
		if (targetInput) targetInput.value = target || '';
		sagaFormState.lastRedirectUrl = url || '';
		sagaFormState.lastRedirectTarget = target || '';
	}

	function handleSubmitSuccess(card) {
		var redirectUrl = HUB_SUCCESS_REDIRECT_URL;
		var redirectTarget = '_blank';
		if (!redirectUrl) return;
		setTimeout(function() {
			if (redirectTarget === '_blank') {
				window.open(redirectUrl, '_blank');
			} else {
				window.location.href = redirectUrl;
			}
			toggleForm(card, false);
		}, 900);
	}

	function iniciarCardLateralTemporario() {
		var card = document.getElementById('floatingOfferCard');
		if (!card) return;
		var parentOriginal = card.parentNode;
		var proximoOriginal = card.nextSibling;

		var titulo = textOf('.entry-title') || textOf('h1.entry-title') || textOf('h2.entry-title') || TITULO_PAGINA_PHP || '';
		var modalidade = textOf('#modalidadeBox') || textOf('#innerMod') || MODALIDADE_LABEL_PHP || 'Presencial';

		var valorDe = valorMoedaDeTexto(textOf('.dePor')) || valorMoedaDeTexto(textOf('#valorSDesconto'));
		var valorParcela = valorMoedaDeTexto(textOf('.valorParcela')) || valorMoedaDeTexto(textOf('#valorCDesconto'));
		var modalidadeNormalizada = modalidadePorSlug(MODALIDADE_SLUG_PHP) || normalizarModalidade(modalidade);

		if (OFERTA_JSON_MODALIDADE) {
			modalidadeNormalizada = normalizarModalidade(OFERTA_JSON_MODALIDADE);
		}

		card.classList.remove('tema-digital', 'tema-aovivo');
		if (modalidadeNormalizada === 'digital') {
			card.classList.add('tema-digital');
		} else if (modalidadeNormalizada === 'digitalaovivo') {
			card.classList.add('tema-aovivo');
		}

		var chaveOfertaDadosHome = OFERTA_JSON_ENCONTRADO ? chaveOfertaPorTexto(OFERTA_JSON_PRECO || '') : '';
		var valorBaseEl = document.getElementById('valorCDesconto');
		var chaveOfertaValorBase = '';
		if (valorBaseEl && valorBaseEl.getAttribute('data-valor-base')) {
			chaveOfertaValorBase = chaveOfertaPorTexto(valorBaseEl.getAttribute('data-valor-base'));
		}
		var chaveOfertaPagina = chaveOfertaPorTexto(textOf('#valorCDesconto')) || chaveOfertaPorTexto(valorParcela);
		var chaveOferta = chaveOfertaDadosHome || chaveOfertaValorBase || chaveOfertaPagina;
		var ofertaAplicavel = OFERTAS_REGRA_HOME[chaveOferta] || null;

		if (titulo) {
			document.getElementById('floatingOfferTitle').textContent = titulo;
		}
		var modalidadeLabelFinal = rotuloModalidadePorSlug(modalidadeNormalizada);
		document.getElementById('floatingOfferModalidade').textContent = modalidadeLabelFinal;

		// Sincroniza modalidade exibida no topo (#innerMod) com nome e cor da modalidade atual.
		var innerModEl = document.getElementById('innerMod');
		if (innerModEl) {
			innerModEl.textContent = modalidadeLabelFinal;
			innerModEl.style.color = corModalidadePorSlug(modalidadeNormalizada);
		}

		var precoJsonNumero = OFERTA_JSON_ENCONTRADO ? numeroPorTextoMoeda(OFERTA_JSON_PRECO) : NaN;
		if (modalidadeNormalizada === 'digital') {
			document.getElementById('floatingOfferDe').textContent = OFERTA_DIGITAL_FIXA_INTERNA.de;
			document.getElementById('floatingOfferParcela').textContent = OFERTA_DIGITAL_FIXA_INTERNA.parcela;
			document.getElementById('floatingOfferCupom').style.display = 'none';
		} else if (isFinite(precoJsonNumero) && precoJsonNumero > 0) {
			document.getElementById('floatingOfferDe').textContent = formatarMoedaBR(precoJsonNumero * 2);
			document.getElementById('floatingOfferParcela').textContent = formatarMoedaBR(precoJsonNumero);
			document.getElementById('floatingOfferCupom').style.display = 'none';
		} else if (ofertaAplicavel) {
			document.getElementById('floatingOfferDe').textContent = ofertaAplicavel.de;
			document.getElementById('floatingOfferParcela').textContent = ofertaAplicavel.vezes + ofertaAplicavel.parcela;
			document.getElementById('floatingOfferCupom').style.display = 'block';
			document.getElementById('floatingOfferCupom').textContent = 'com o cupom 300NAPOS';
		} else {
			if (valorDe) {
				document.getElementById('floatingOfferDe').textContent = valorDe;
			}
			if (valorParcela) {
				document.getElementById('floatingOfferParcela').textContent = '18x de ' + valorParcela;
			}
			document.getElementById('floatingOfferCupom').style.display = 'none';
		}

		var cta = document.getElementById('floatingOfferCta');
		var hrefPreferencial = '#';
		var targetPreferencial = '_blank';
		var btnComprar = document.getElementById('btnComprar');
		if (btnComprar && btnComprar.getAttribute('href')) {
			hrefPreferencial = btnComprar.getAttribute('href');
			targetPreferencial = btnComprar.getAttribute('target') || targetPreferencial;
		}
		if (hrefPreferencial === '#') {
			var linkAlt = document.querySelector('.btnComprar a, a.btnComprar, .cursoPage');
			if (linkAlt && linkAlt.getAttribute('href')) {
				hrefPreferencial = linkAlt.getAttribute('href');
				targetPreferencial = linkAlt.getAttribute('target') || targetPreferencial;
			}
		}
		cta.setAttribute('href', hrefPreferencial || '#');
		cta.setAttribute('target', targetPreferencial || '_blank');
		cta.setAttribute('rel', 'noopener');

		// Espelha a logica do formulario HubSpot da referencia .DadosCurso.
		var refs = getFormRefs(card);
		if (refs.form && refs.shell) {
			cta.addEventListener('click', function(event) {
				event.preventDefault();
				var href = (cta.getAttribute('href') || '').trim();
				var target = (cta.getAttribute('target') || '').trim();

				// O clique no CTA sempre deve abrir o formulario.
				toggleForm(card, true);
				hydrateRedirect(refs.form, href, target);

				var firstNameInput = refs.form.querySelector('input[name="firstname"]');
				if (firstNameInput) firstNameInput.focus();
			});

			var closeBtn = refs.shell.querySelector('.sagaFormClose');
			if (closeBtn) {
				closeBtn.addEventListener('click', function() {
					toggleForm(card, false);
				});
			}

			refs.form.addEventListener('input', function(event) {
				if (event.target && event.target.name === 'mobilephone') {
					event.target.value = formatPhone(event.target.value);
				}
			});

			refs.form.addEventListener('submit', function(event) {
				event.preventDefault();
				if (sagaFormState.submitting) return;

				var firstNameField = refs.form.querySelector('input[name="firstname"]');
				var lastNameField = refs.form.querySelector('input[name="lastname"]');
				var emailField = refs.form.querySelector('input[name="email"]');
				var phoneField = refs.form.querySelector('input[name="mobilephone"]');
				var optInField = refs.form.querySelector('input[name="lgpd_opt_in"]');
				var submitBtn = refs.form.querySelector('.sagaSubmit');

				var firstNameError = refs.form.querySelector('[data-error-for="firstname"]');
				var lastNameError = refs.form.querySelector('[data-error-for="lastname"]');
				var emailError = refs.form.querySelector('[data-error-for="email"]');
				var phoneError = refs.form.querySelector('[data-error-for="mobilephone"]');
				var optInError = refs.form.querySelector('[data-error-for="lgpd_opt_in"]');

				[firstNameError, lastNameError, emailError, phoneError, optInError].forEach(function(errorEl) {
					if (!errorEl) return;
					errorEl.classList.remove('is-visible');
					errorEl.textContent = '';
				});

				var valid = true;
				var firstInvalid = null;

				if (!firstNameField || !firstNameField.value.trim()) {
					valid = false;
					if (firstNameError) {
						firstNameError.textContent = 'Informe seu nome.';
						firstNameError.classList.add('is-visible');
					}
					firstInvalid = firstInvalid || firstNameField;
				}

				if (!lastNameField || !lastNameField.value.trim()) {
					valid = false;
					if (lastNameError) {
						lastNameError.textContent = 'Informe seu sobrenome.';
						lastNameError.classList.add('is-visible');
					}
					firstInvalid = firstInvalid || lastNameField;
				}

				if (!phoneField || !isValidPhone(phoneField.value)) {
					valid = false;
					if (phoneError) {
						phoneError.textContent = 'Informe um telefone valido.';
						phoneError.classList.add('is-visible');
					}
					firstInvalid = firstInvalid || phoneField;
				}

				if (!emailField || !isValidEmail(emailField.value)) {
					valid = false;
					if (emailError) {
						emailError.textContent = 'Informe um e-mail valido.';
						emailError.classList.add('is-visible');
					}
					firstInvalid = firstInvalid || emailField;
				}

				if (optInField && !optInField.checked) {
					valid = false;
					if (optInError) {
						optInError.textContent = 'E necessario autorizar o uso dos dados para continuar.';
						optInError.classList.add('is-visible');
					}
					firstInvalid = firstInvalid || optInField;
				}

				if (!valid) {
					if (firstInvalid && typeof firstInvalid.focus === 'function') firstInvalid.focus();
					return;
				}

				sagaFormState.submitting = true;
				if (submitBtn) {
					submitBtn.setAttribute('disabled', 'disabled');
					submitBtn.textContent = 'Enviando...';
				}
				if (refs.status) refs.status.textContent = 'Enviando seus dados, por favor aguarde...';

				var phoneDigits = phoneField.value.replace(/\D/g, '');
				var payload = {
					fields: [
						{ name: 'firstname', value: firstNameField.value.trim() },
						{ name: 'lastname', value: lastNameField.value.trim() },
						{ name: 'email', value: emailField.value.trim() },
						{ name: 'mobilephone', value: phoneDigits }
					].concat(collectUTMFields()),
					context: {
						pageUri: window.location.href,
						pageName: document.title
					}
				};

				if (optInField) {
					payload.fields.push({ name: 'lgpd_opt_in', value: 'true' });
				}

				window.fetch(HUB_ENDPOINT, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					body: JSON.stringify(payload)
				}).then(function(response) {
					sagaFormState.submitting = false;
					if (submitBtn) {
						submitBtn.removeAttribute('disabled');
						submitBtn.textContent = 'ENVIAR';
					}
					if (!response.ok) {
						if (refs.status) refs.status.textContent = 'Nao foi possivel enviar agora. Tente novamente em instantes.';
						return;
					}
					if (refs.status) refs.status.textContent = 'Dados enviados! Vamos te direcionar para a inscricao.';
					handleSubmitSuccess(card);
				}).catch(function() {
					sagaFormState.submitting = false;
					if (submitBtn) {
						submitBtn.removeAttribute('disabled');
						submitBtn.textContent = 'ENVIAR';
					}
					if (refs.status) refs.status.textContent = 'Nao foi possivel enviar agora. Tente novamente em instantes.';
				});
			});

			document.addEventListener('click', function(event) {
				var shellVisible = refs.shell.classList.contains('is-visible');
				if (!shellVisible) return;
				if (card.contains(event.target)) return;
				toggleForm(card, false);
			});
		}

		// No mobile, posiciona o card imediatamente abaixo do titulo da pagina.
		function posicionarCardMobileAbaixoDoTitulo() {
			var isMobile = window.matchMedia('(max-width: 991px)').matches;
			var tituloEl = document.querySelector('.headerCurso .entry-title') || document.querySelector('.entry-title');

			if (isMobile && tituloEl && tituloEl.parentNode) {
				if (tituloEl.nextSibling !== card) {
					tituloEl.parentNode.insertBefore(card, tituloEl.nextSibling);
				}
				return;
			}

			if (!isMobile && parentOriginal) {
				if (proximoOriginal && proximoOriginal.parentNode === parentOriginal) {
					parentOriginal.insertBefore(card, proximoOriginal);
				} else {
					parentOriginal.appendChild(card);
				}
			}
		}

		posicionarCardMobileAbaixoDoTitulo();
		window.addEventListener('resize', posicionarCardMobileAbaixoDoTitulo);

		function alternarCardSobreFooter() {
			var isMobile = window.matchMedia('(max-width: 991px)').matches;
			if (isMobile) {
				card.classList.remove('is-hidden-right');
				return;
			}

			var footer = document.querySelector('#footer, footer.footer, .site-footer');
			if (!footer) {
				card.classList.remove('is-hidden-right');
				return;
			}

			var cardRect = card.getBoundingClientRect();
			var footerRect = footer.getBoundingClientRect();

			// Considera sobreposicao quando o rodape toca a area do card fixo.
			var encostouNoFooter = footerRect.top <= (cardRect.bottom - 8);
			card.classList.toggle('is-hidden-right', encostouNoFooter);
		}

		alternarCardSobreFooter();
		window.addEventListener('scroll', alternarCardSobreFooter, { passive: true });
		window.addEventListener('resize', alternarCardSobreFooter);

		card.setAttribute('aria-hidden', 'false');
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', iniciarCardLateralTemporario);
	} else {
		iniciarCardLateralTemporario();
	}
})();
</script>

<!-- FIM BLOCO TEMPORARIO: CARD FIXO LATERAL DIREITA (ROLLBACK FACIL) -->

