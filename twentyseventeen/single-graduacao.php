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

	// Endpoint local do tema: adapta automaticamente ao host (local, homolog, producao).
	$graduacao_send_api_url = esc_url(get_template_directory_uri() . '/sendAPI_interna.php');
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
	<?php
	if (!isset($_GET['tipo']) || trim((string) $_GET['tipo']) === '') {
		$_GET['tipo'] = 'graduacao';
	}
	include 'getAPI_interna.php';

	if (!isset($data) || !is_array($data)) {
		$data = array();
	}

	$is_list_array_api = function ($valor) {
		if (!is_array($valor)) {
			return false;
		}
		if ($valor === array()) {
			return true;
		}
		return array_keys($valor) === range(0, count($valor) - 1);
	};

	$obter_por_caminho_api = function ($array, $caminho) {
		$cursor = $array;
		foreach ($caminho as $parte) {
			if (!is_array($cursor) || !array_key_exists($parte, $cursor)) {
				return null;
			}
			$cursor = $cursor[$parte];
		}
		return $cursor;
	};

	$normalizar_grupos_api = function ($grupos_raw) use ($is_list_array_api) {
		if (!is_array($grupos_raw)) {
			return array();
		}

		if (!$is_list_array_api($grupos_raw)) {
			$grupos_raw = array($grupos_raw);
		}

		$resultado = array();
		$indice_modulo = 1;

		foreach ($grupos_raw as $grupo_item) {
			$descricao = '';
			$disciplinas_raw = array();

			if (is_string($grupo_item)) {
				$descricao = trim($grupo_item);
			} elseif (is_array($grupo_item)) {
				foreach (array('descricao', 'descricao_grupo', 'nome', 'titulo', 'modulo', 'grupo', 'periodo', 'label') as $chave_descricao) {
					if (!empty($grupo_item[$chave_descricao]) && is_string($grupo_item[$chave_descricao])) {
						$descricao = trim($grupo_item[$chave_descricao]);
						break;
					}
				}

				foreach (array('disciplinas', 'itens', 'conteudos', 'materias', 'componentes', 'unidades') as $chave_disciplinas) {
					if (!empty($grupo_item[$chave_disciplinas]) && is_array($grupo_item[$chave_disciplinas])) {
						$disciplinas_raw = $grupo_item[$chave_disciplinas];
						break;
					}
				}
			}

			if (is_array($disciplinas_raw) && !$is_list_array_api($disciplinas_raw)) {
				$disciplinas_raw = array($disciplinas_raw);
			}

			$disciplinas = array();
			if (is_array($disciplinas_raw)) {
				foreach ($disciplinas_raw as $disc_item) {
					$nome_disciplina = '';
					if (is_string($disc_item)) {
						$nome_disciplina = trim($disc_item);
					} elseif (is_array($disc_item)) {
						foreach (array('disciplina', 'nome', 'descricao', 'titulo', 'name', 'componente') as $chave_disciplina) {
							if (!empty($disc_item[$chave_disciplina]) && is_string($disc_item[$chave_disciplina])) {
								$nome_disciplina = trim($disc_item[$chave_disciplina]);
								break;
							}
						}
					}

					if ($nome_disciplina !== '') {
						$disciplinas[] = array('disciplina' => $nome_disciplina);
					}
				}
			}

			if ($descricao === '' && empty($disciplinas)) {
				continue;
			}

			if ($descricao === '') {
				$descricao = 'Módulo ' . $indice_modulo;
			}

			$resultado[] = array(
				'descricao' => $descricao,
				'disciplinas' => $disciplinas,
			);

			$indice_modulo++;
		}

		return $resultado;
	};

	$aplicar_estrutura_modulos_api = function (&$dados_api) use ($obter_por_caminho_api, $normalizar_grupos_api) {
		if (!is_array($dados_api)) {
			return;
		}

		$caminhos_possiveis = array(
			array('estrutura', 'grupos'),
			array('curso', 'estrutura', 'grupos'),
			array('curso', 'grupos'),
			array('grupos'),
			array('modulos'),
			array('modulos_curriculares'),
			array('matriz', 'grupos'),
			array('matriz', 'modulos'),
			array('estrutura_curricular', 'grupos'),
			array('estrutura_curricular', 'modulos'),
			array('data', 'estrutura', 'grupos'),
			array('data', 'modulos'),
		);

		foreach ($caminhos_possiveis as $caminho) {
			$valor_bruto = $obter_por_caminho_api($dados_api, $caminho);
			$grupos_normalizados = $normalizar_grupos_api($valor_bruto);
			if (!empty($grupos_normalizados)) {
				if (empty($dados_api['estrutura']) || !is_array($dados_api['estrutura'])) {
					$dados_api['estrutura'] = array();
				}
				$dados_api['estrutura']['grupos'] = $grupos_normalizados;
				return;
			}
		}
	};

	$aplicar_estrutura_modulos_api($data);

	$dados_essenciais_faltando = empty($data['investimentos']) || !is_array($data['investimentos']) || empty($data['estrutura']['grupos']) || !is_array($data['estrutura']['grupos']);

	if ($dados_essenciais_faltando) {
		// Regras de acesso baseadas no index.php: token -> lista de cursos -> curso individual.
		$api_base_url = 'https://apisite.unisuam.edu.br';
		$api_login = 'frog';
		$api_password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

		$normalizar_texto_api = function ($valor) {
			$valor = is_string($valor) ? trim($valor) : '';
			if ($valor === '') {
				return '';
			}
			if (function_exists('remove_accents')) {
				$valor = remove_accents($valor);
			}
			$valor = strtolower($valor);
			$valor = preg_replace('/[^a-z0-9]+/i', ' ', $valor);
			$valor = preg_replace('/\s+/', ' ', trim($valor));
			return $valor;
		};

		$coletar_codigos_candidatos = function () use ($mneumonico, $current_post_id) {
			$candidatos = array();
			$meta_keys = array(
				'mneumonico',
				'mnemonico',
				'codigo',
				'codigo_curso',
				'cod_curso',
				'id_curso',
				'id_da_oferta',
				'offer_id',
				'offer-id',
				'course_code',
			);

			if (is_string($mneumonico) && trim($mneumonico) !== '') {
				$candidatos[] = trim($mneumonico);
			}

			foreach ($meta_keys as $meta_key) {
				$valor_meta = get_post_meta($current_post_id, $meta_key, true);
				if (is_string($valor_meta) && trim($valor_meta) !== '') {
					$candidatos[] = trim($valor_meta);
				}
			}

			$candidatos = array_values(array_unique(array_filter($candidatos, function ($valor) {
				return is_string($valor) && trim($valor) !== '';
			})));

			return $candidatos;
		};

		$tipo_slug_por_nome = array(
			'Graduação' => 'graduacao',
			'Pós-Graduação' => 'posgraduacao',
			'Doutorado' => 'doutorado',
			'Mestrado' => 'mestrado',
			'Pós-Doutorado' => 'posdoutorado',
		);

		$auth_header = '';
		$token_response = wp_remote_post(
			$api_base_url . '/token',
			array(
				'timeout' => 8,
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'body' => wp_json_encode(
					array(
						'login' => $api_login,
						'password' => $api_password,
					)
				),
			)
		);

		if (!is_wp_error($token_response) && (int) wp_remote_retrieve_response_code($token_response) >= 200 && (int) wp_remote_retrieve_response_code($token_response) < 300) {
			$token_body = json_decode((string) wp_remote_retrieve_body($token_response), true);
			$resource = is_array($token_body) && !empty($token_body['resource']) ? trim((string) $token_body['resource']) : '';
			$token = is_array($token_body) && !empty($token_body['token']) ? trim((string) $token_body['token']) : '';
			if ($resource !== '' && $token !== '') {
				$auth_header = $resource . ' ' . $token;
			}
		}

		if ($auth_header !== '') {
			$codigo_escolhido = '';
			$tipo_escolhido = 'graduacao';

			$codigos_candidatos = $coletar_codigos_candidatos();
			$titulo_atual_norm = $normalizar_texto_api(get_the_title($current_post_id));
			$slug_atual_norm = $normalizar_texto_api(get_post_field('post_name', $current_post_id));

			$cursos_response = wp_remote_get(
				$api_base_url . '/api/cursos',
				array(
					'timeout' => 10,
					'headers' => array(
						'Content-Type' => 'application/json',
						'Authorization' => $auth_header,
					),
				)
			);

			if (!is_wp_error($cursos_response) && (int) wp_remote_retrieve_response_code($cursos_response) >= 200 && (int) wp_remote_retrieve_response_code($cursos_response) < 300) {
				$cursos_body = json_decode((string) wp_remote_retrieve_body($cursos_response), true);
				$cursos_lista = (is_array($cursos_body) && !empty($cursos_body['data']['cursos']) && is_array($cursos_body['data']['cursos'])) ? $cursos_body['data']['cursos'] : array();

				$melhor_curso = null;
				$melhor_score = -1;

				foreach ($cursos_lista as $curso_item) {
					if (!is_array($curso_item)) {
						continue;
					}

					$codigo_item = isset($curso_item['codigo']) ? trim((string) $curso_item['codigo']) : '';
					$tipo_item_nome = isset($curso_item['tipo']) ? trim((string) $curso_item['tipo']) : '';
					$tipo_item_slug = isset($tipo_slug_por_nome[$tipo_item_nome]) ? $tipo_slug_por_nome[$tipo_item_nome] : '';
					$curso_nome_item = isset($curso_item['curso']) ? (string) $curso_item['curso'] : '';
					$curso_nome_item_norm = $normalizar_texto_api($curso_nome_item);

					if ($codigo_item === '' || $tipo_item_slug === '') {
						continue;
					}

					$score = 0;
					if (!empty($codigos_candidatos) && in_array($codigo_item, $codigos_candidatos, true)) {
						$score += 100;
					}

					if ($curso_nome_item_norm !== '' && $titulo_atual_norm !== '') {
						if ($curso_nome_item_norm === $titulo_atual_norm) {
							$score += 60;
						} elseif (strpos($curso_nome_item_norm, $titulo_atual_norm) !== false || strpos($titulo_atual_norm, $curso_nome_item_norm) !== false) {
							$score += 40;
						}
					}

					if ($curso_nome_item_norm !== '' && $slug_atual_norm !== '' && (strpos($curso_nome_item_norm, $slug_atual_norm) !== false || strpos($slug_atual_norm, $curso_nome_item_norm) !== false)) {
						$score += 20;
					}

					if ($tipo_item_slug === 'graduacao') {
						$score += 10;
					}

					if ($score > $melhor_score) {
						$melhor_score = $score;
						$melhor_curso = $curso_item;
					}
				}

				if (is_array($melhor_curso) && !empty($melhor_curso['codigo']) && !empty($melhor_curso['tipo'])) {
					$codigo_escolhido = trim((string) $melhor_curso['codigo']);
					$tipo_nome = trim((string) $melhor_curso['tipo']);
					if (!empty($tipo_slug_por_nome[$tipo_nome])) {
						$tipo_escolhido = $tipo_slug_por_nome[$tipo_nome];
					}
				}
			}

			if ($codigo_escolhido === '' && !empty($codigos_candidatos)) {
				$codigo_escolhido = $codigos_candidatos[0];
			}

			if ($codigo_escolhido !== '') {
				$tipos_tentativa = array_values(array_unique(array_merge(array($tipo_escolhido), array_values($tipo_slug_por_nome))));
				foreach ($tipos_tentativa as $tipo_tentativa) {
					$curso_response = wp_remote_get(
						$api_base_url . '/api/curso/' . rawurlencode($tipo_tentativa) . '/' . rawurlencode($codigo_escolhido),
						array(
							'timeout' => 12,
							'headers' => array(
								'Content-Type' => 'application/json',
								'Authorization' => $auth_header,
							),
						)
					);

					if (is_wp_error($curso_response) || (int) wp_remote_retrieve_response_code($curso_response) < 200 || (int) wp_remote_retrieve_response_code($curso_response) >= 300) {
						continue;
					}

					$curso_body = json_decode((string) wp_remote_retrieve_body($curso_response), true);
					$curso_data = (is_array($curso_body) && !empty($curso_body['data']) && is_array($curso_body['data'])) ? $curso_body['data'] : array();
					if (!empty($curso_data)) {
						$aplicar_estrutura_modulos_api($curso_data);
						// Prioriza o que já existe e usa API para preencher o que estiver faltando.
						$data = array_replace_recursive($curso_data, $data);
						break;
					}
				}
			}
		}
	}

	$aplicar_estrutura_modulos_api($data);

	if (empty($data['investimentos']) || !is_array($data['investimentos'])) {
		$data['investimentos'] = array();
	}
	if (empty($data['estrutura']) || !is_array($data['estrutura'])) {
		$data['estrutura'] = array();
	}
	if (empty($data['estrutura']['grupos']) || !is_array($data['estrutura']['grupos'])) {
		$data['estrutura']['grupos'] = array();
	}
	if (empty($data['modalidades']) || !is_array($data['modalidades'])) {
		$data['modalidades'] = array();
	}
	if (empty($data['resumo']) || !is_array($data['resumo'])) {
		$data['resumo'] = array();
	}
	if (empty($data['curso']) || !is_array($data['curso'])) {
		$data['curso'] = array();
	}

	/*
	 * Dados da .box devem vir apenas da API.
	 * Fallback local via dadosHome.json foi removido.
	 */
	$fallback_box_dadoshome_ativo = false;
	?>

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

	/*
	 * REGRA: Nutricao Esportiva (Digital ao Vivo) usa disciplinas da API posgraduacao.
	 * A pagina continua como graduacao para investimentos/demais dados; apenas
	 * $data['estrutura']['grupos'] e substituido pelo retorno de tipo=posgraduacao.
	 */
	$eh_nutricao_esportiva_aovivo = false;
	if ($page_modalidade_slug === 'semipresencial') {
		$normalizar_nome_curso_nutricao_aovivo = function ($valor) {
			$valor = is_scalar($valor) ? (string) $valor : '';
			if ($valor === '') {
				return '';
			}
			if (function_exists('remove_accents')) {
				$valor = remove_accents($valor);
			}
			$valor = function_exists('mb_strtolower') ? mb_strtolower($valor, 'UTF-8') : strtolower($valor);
			$valor = preg_replace('/[^a-z0-9\s]/u', ' ', $valor);
			return preg_replace('/\s+/u', ' ', trim($valor));
		};

		$titulo_curso_nutricao_aovivo = get_the_title($current_post_id);
		$slug_curso_nutricao_aovivo = (string) get_post_field('post_name', $current_post_id);
		$titulo_curso_nutricao_norm = $normalizar_nome_curso_nutricao_aovivo($titulo_curso_nutricao_aovivo);

		if (
			$titulo_curso_nutricao_norm === 'nutricao esportiva estetica e emagrecimento'
			|| strpos($slug_curso_nutricao_aovivo, 'nutricao-esportiva-estetica-e-emagrecimento') !== false
		) {
			$eh_nutricao_esportiva_aovivo = true;
		}
	}

	if ($eh_nutricao_esportiva_aovivo) {
		$codigo_curso_pos_nutricao = is_string($mneumonico) ? trim($mneumonico) : '';
		if ($codigo_curso_pos_nutricao !== '' && function_exists('getToken') && function_exists('getCursoPorCodigo')) {
			$api_matricula_nutricao_url = 'https://apimatricula.unisuam.edu.br';
			$api_matricula_nutricao_login = 'frog';
			$api_matricula_nutricao_senha = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';
			$token_pos_nutricao = getToken($api_matricula_nutricao_url, $api_matricula_nutricao_login, $api_matricula_nutricao_senha);

			if ($token_pos_nutricao) {
				$resposta_pos_nutricao = getCursoPorCodigo(
					$api_matricula_nutricao_url,
					$token_pos_nutricao,
					'posgraduacao',
					$codigo_curso_pos_nutricao
				);

				$data_pos_nutricao = array();
				if (is_array($resposta_pos_nutricao) && !empty($resposta_pos_nutricao['data']) && is_array($resposta_pos_nutricao['data'])) {
					$data_pos_nutricao = $resposta_pos_nutricao['data'];
				}

				if (!empty($data_pos_nutricao)) {
					$aplicar_estrutura_modulos_api($data_pos_nutricao);
					if (!empty($data_pos_nutricao['estrutura']['grupos']) && is_array($data_pos_nutricao['estrutura']['grupos'])) {
						if (empty($data['estrutura']) || !is_array($data['estrutura'])) {
							$data['estrutura'] = array();
						}
						$data['estrutura']['grupos'] = $data_pos_nutricao['estrutura']['grupos'];
					}
				}
			}
		}
	}

	/*
	 * INICIO BLOCO TEMPORARIO: REGRA DE VALOR POR CURSO (ROLLBACK FACIL) FAKE
	 *
	 * Objetivo:
	 * - Forcar os valores dos investimentos para cursos especificos,
	 *   garantindo exibicao consistente nas paginas individuais.
	 *
	 * Cursos e valores:
	 * - engenharia-estrutural-com-enfase-em-estruturas-de-concreto => 299.00
	 * - engenharia-estrutural-com-enfase-em-estruturas-metalicas => 299.00
	 * - gestao-de-obras-civis => 299.00
	 * - gestao-e-projetos-de-edificacoes-em-bim => 299.00
	 * - psicologia-organizacional-e-do-trabalho => 199.00
	 *
	 * Rollback:
	 * - Remover todo o conteudo entre:
	 *   INICIO BLOCO TEMPORARIO: REGRA DE VALOR POR CURSO (ROLLBACK FACIL)
	 *   FIM BLOCO TEMPORARIO: REGRA DE VALOR POR CURSO (ROLLBACK FACIL)
	 */
	$regras_valor_por_slug = array(
		'engenharia-estrutural-com-enfase-em-estruturas-de-concreto' => 299.00,
		'engenharia-estrutural-com-enfase-em-estruturas-metalicas' => 299.00,
		'gestao-de-obras-civis' => 299.00,
		'gestao-e-projetos-de-edificacoes-em-bim' => 299.00,
		'psicologia-organizacional-e-do-trabalho' => 199.00,
	);

	$normalizar_slug_curso_temporario = function($slug) {
		$slug = strtolower(trim((string) $slug));
		if ($slug === '') {
			return '';
		}
		$slug = preg_replace('/-(digital|aovivo)$/', '', $slug);
		return trim((string) $slug);
	};

	$slug_post_atual = $normalizar_slug_curso_temporario(get_post_field('post_name', (int) $current_post_id));
	$uri_atual = $_SERVER['REQUEST_URI'] ?? '';
	$path_atual = is_string($uri_atual) ? (string) wp_parse_url($uri_atual, PHP_URL_PATH) : '';
	$slug_url_atual = $normalizar_slug_curso_temporario(basename(untrailingslashit($path_atual)));

	$slugs_candidatos = array_values(array_unique(array_filter(array($slug_post_atual, $slug_url_atual), function($valor) {
		return is_string($valor) && trim($valor) !== '';
	})));

	$valor_forcado_curso = null;
	foreach ($slugs_candidatos as $slug_candidato) {
		if (array_key_exists($slug_candidato, $regras_valor_por_slug)) {
			$valor_forcado_curso = (float) $regras_valor_por_slug[$slug_candidato];
			break;
		}
	}

	if ($valor_forcado_curso !== null && $valor_forcado_curso > 0) {
		if (empty($data['investimentos']) || !is_array($data['investimentos'])) {
			$data['investimentos'] = array(
				array(
					'valor' => $valor_forcado_curso,
					'preco' => $valor_forcado_curso,
					'mensalidade' => $valor_forcado_curso,
					'parcelas' => 18,
					'modalidade' => $modalidade_box_value,
				),
			);
		} else {
			foreach ($data['investimentos'] as &$investimento_tmp) {
				if (!is_array($investimento_tmp)) {
					$investimento_tmp = array();
				}
				$investimento_tmp['valor'] = $valor_forcado_curso;
				$investimento_tmp['preco'] = $valor_forcado_curso;
				$investimento_tmp['mensalidade'] = $valor_forcado_curso;
				if (empty($investimento_tmp['parcelas'])) {
					$investimento_tmp['parcelas'] = 18;
				}
				if (empty($investimento_tmp['modalidade'])) {
					$investimento_tmp['modalidade'] = $modalidade_box_value;
				}
			}
			unset($investimento_tmp);
		}
	}
	/* FIM BLOCO TEMPORARIO: REGRA DE VALOR POR CURSO (ROLLBACK FACIL) FAKE */

	?>

	<?php
	$normalizar_valor_investimento_render = static function($valor) {
		if (is_numeric($valor)) {
			return (float) $valor;
		}
		$valor_limpo = preg_replace('/[^0-9,.\-]/', '', (string) $valor);
		if ($valor_limpo === '') {
			return 0.0;
		}
		if (strpos($valor_limpo, ',') !== false) {
			$valor_limpo = str_replace('.', '', $valor_limpo);
			$valor_limpo = str_replace(',', '.', $valor_limpo);
		}
		return (float) $valor_limpo;
	};
	?>

	<!-- Dados já puxados da API -->
	
		<?php foreach ($data['investimentos'] as $investimento) {
		$valor_investimento_atual = $normalizar_valor_investimento_render(
			$investimento['valor']
				?? ($investimento['preco']
					?? ($investimento['mensalidade']
						?? ($investimento['parcela']
							?? ($investimento['valor_parcela'] ?? '')
						)
					)
				)
		);
		if ($valor_investimento_atual <= 0) {
			continue;
		}
		$investimento['valor'] = $valor_investimento_atual;
		// $modalidade2 = get_post_meta(get_the_ID(), 'modalidade_type', true);
		$modalidade = "graduacao";
		if ($modalidade) {
			$valorSemDesconto = round($valor_investimento_atual / 0.4, 2);
			
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
			<script>
			document.addEventListener('DOMContentLoaded', function () {
				var tentativa = 0;
				var maxTentativas = 25;
				var timer = null;
				var observer = null;

				function textoModalidadeCard() {
					var floatModal = document.getElementById('floatingOfferModalidade');
					if (!floatModal) {
						return '';
					}
					return (floatModal.textContent || floatModal.innerText || '').replace(/\s+/g, ' ').trim();
				}

				function jaAgrupado() {
					var modulos = document.querySelectorAll('.cursar .wrapModulo');
					return !!document.querySelector('.cursar .wrapModuloUnified') || modulos.length === 1;
				}

				function agruparSeDigital() {
					if (jaAgrupado()) {
						return true;
					}

					if (textoModalidadeCard() !== 'Digital (EaD)') {
						return false;
					}

					var modulos = Array.from(document.querySelectorAll('.cursar .wrapModulo'));
					if (modulos.length <= 1) {
						return false;
					}

					var parent = modulos[0].parentNode;
					if (!parent) {
						return false;
					}

					var wrapper = document.createElement('div');
					wrapper.className = 'wrapModuloUnified';
					parent.insertBefore(wrapper, modulos[0]);
					modulos.forEach(function (modulo) {
						wrapper.appendChild(modulo);
					});

					return true;
				}

				function finalizarMonitoramento() {
					if (timer) {
						clearInterval(timer);
						timer = null;
					}
					if (observer) {
						observer.disconnect();
						observer = null;
					}
				}

				function tentarAgrupamento() {
					tentativa++;
					if (agruparSeDigital() || tentativa >= maxTentativas) {
						finalizarMonitoramento();
					}
				}

				tentarAgrupamento();
				if (!jaAgrupado()) {
					timer = setInterval(tentarAgrupamento, 250);
				}

				var floatModalObserver = document.getElementById('floatingOfferModalidade');
				if (floatModalObserver && typeof MutationObserver !== 'undefined') {
					observer = new MutationObserver(function () {
						if (agruparSeDigital()) {
							finalizarMonitoramento();
						}
					});
					observer.observe(floatModalObserver, {
						childList: true,
						subtree: true,
						characterData: true
					});
				}
			});
			</script>
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
					<p class="dePor valorVersaoSemi"><span>R$</span> <span id="valorSDesconto"><?php echo number_format($valorSemDesconto, 2, ',', '.'); ?></span></p>
					<p class="dePor dePorVersaoSemi" style="margin-bottom: -25px;">A partir de:</p>
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
							var modalidadeNormalizada = modalidadeSlug
								.toString()
								.normalize('NFD')
								.replace(/[\u0300-\u036f]/g, '')
								.replace(/\s+/g, ' ')
								.trim();
							var isDigitalAoVivo = (
								modalidadeNormalizada.indexOf('semipresencial') !== -1 ||
								modalidadeNormalizada.indexOf('digitalaovivo') !== -1 ||
								modalidadeNormalizada.indexOf('digital ao vivo') !== -1 ||
								modalidadeNormalizada.indexOf('ao vivo') !== -1
							);

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

							if (!isDigitalAoVivo) {
							return;
						}

							var semipresencialLabel = 'Digital ao Vivo';
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

							var mapaParcelaDigitalAoVivo18x = {
								'298.50': '199,00',
								'448.50': '299,00',
								'598.50': '399,00'
							};

							var alvo = document.getElementById('valorCDesconto');
							if (alvo) {
								var valorBase = parseFloat(String(alvo.getAttribute('data-valor-base') || '').replace(',', '.'));
								if (isFinite(valorBase) && valorBase > 0) {
									var parcelaBase12x = valorBase;
									var chaveParcela12x = Number(parcelaBase12x).toFixed(2);
									var prefixoParcela = document.querySelector('.versaoGlobal .valorParcela .dePorNovo');

									if (Object.prototype.hasOwnProperty.call(mapaParcelaDigitalAoVivo18x, chaveParcela12x)) {
										alvo.textContent = mapaParcelaDigitalAoVivo18x[chaveParcela12x];
										if (prefixoParcela) {
											prefixoParcela.textContent = '18x de ';
										}
									} else {
										alvo.textContent = Number(parcelaBase12x).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
										if (prefixoParcela) {
											prefixoParcela.textContent = '18x de ';
										}
									}
								}
							}

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
			</div>


		</section>

		<?php 
			} elseif ($investimento['parcelas'] == 12) {
				$valorSemDesconto = round($investimento['valor'] / 0.4, 2);
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
// if (!is_string($conteudo_sobre) || trim(wp_strip_all_tags($conteudo_sobre)) === '') {
// 	$conteudo_sobre = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
// }

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

<div class="clear"></div>

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


						<!-- WRAP EBOOK  -->
						<?php if ($page_modalidade_slug === 'digital') : ?>
						<?php
						$banner_ebook_pos_link = '#';
						$titulo_curso_ebook = get_the_title($current_post_id);
						$upload_dir_ebook = wp_upload_dir();
						$pdfs_ead_dir = trailingslashit($upload_dir_ebook['basedir']) . 'pdfsEad';

						if (is_dir($pdfs_ead_dir)) {
							$normalizar_nome_pdf_ead = function ($valor) {
								$valor = is_string($valor) ? trim($valor) : '';
								if ($valor === '') {
									return '';
								}
								if (function_exists('remove_accents')) {
									$valor = remove_accents($valor);
								}
								$valor = function_exists('mb_strtolower') ? mb_strtolower($valor, 'UTF-8') : strtolower($valor);
								$valor = preg_replace('/[^a-z0-9]+/i', ' ', $valor);
								return preg_replace('/\s+/', ' ', trim($valor));
							};

							$titulo_curso_ebook = get_the_title($current_post_id);
							$titulo_curso_ebook_norm = $normalizar_nome_pdf_ead($titulo_curso_ebook);
							$pdf_ead_encontrado = '';

							$candidato_exato = trailingslashit($pdfs_ead_dir) . $titulo_curso_ebook . '.pdf';
							if (is_file($candidato_exato)) {
								$pdf_ead_encontrado = $candidato_exato;
							} else {
								$arquivos_pdf_ead = glob(trailingslashit($pdfs_ead_dir) . '*.pdf');
								$melhor_score_pdf_ead = -1;

								if (is_array($arquivos_pdf_ead)) {
									foreach ($arquivos_pdf_ead as $arquivo_pdf_ead) {
										if (!is_file($arquivo_pdf_ead)) {
											continue;
										}

										$nome_pdf_ead = pathinfo($arquivo_pdf_ead, PATHINFO_FILENAME);
										$nome_pdf_ead_norm = $normalizar_nome_pdf_ead($nome_pdf_ead);

										if ($nome_pdf_ead_norm === '' || $titulo_curso_ebook_norm === '') {
											continue;
										}

										$score_pdf_ead = 0;
										if ($nome_pdf_ead_norm === $titulo_curso_ebook_norm) {
											$score_pdf_ead = 100;
										} elseif (strpos($titulo_curso_ebook_norm, $nome_pdf_ead_norm) !== false || strpos($nome_pdf_ead_norm, $titulo_curso_ebook_norm) !== false) {
											$score_pdf_ead = 60;
										}

										if ($score_pdf_ead > $melhor_score_pdf_ead) {
											$melhor_score_pdf_ead = $score_pdf_ead;
											$pdf_ead_encontrado = $arquivo_pdf_ead;
										}
									}
								}
							}

							if ($pdf_ead_encontrado !== '') {
								$banner_ebook_pos_link = trailingslashit($upload_dir_ebook['baseurl']) . 'pdfsEad/' . rawurlencode(basename($pdf_ead_encontrado));
							}
						}

						$has_ebook_pdf = ($banner_ebook_pos_link !== '#');
						?>
						<div class="ebookEAD">
							<a
								class="ebookEAD__link<?php echo $has_ebook_pdf ? ' ebookEAD__link--gated' : ''; ?>"
								href="<?php echo esc_url($has_ebook_pdf ? '#' : $banner_ebook_pos_link); ?>"
								<?php if ($has_ebook_pdf) : ?>
									data-pdf-url="<?php echo esc_url($banner_ebook_pos_link); ?>"
									aria-haspopup="dialog"
								<?php else : ?>
									target="_blank"
									rel="noopener noreferrer"
								<?php endif; ?>
							>
								<img
									class="ebookEAD__img ebookEAD__img--desktop"
									src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/06/banner-e-book-pos-desktop.png"
									alt="Baixe o e-book da pós-graduação digital"
									loading="lazy"
									decoding="async"
								>
								<img
									class="ebookEAD__img ebookEAD__img--mobile"
									src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/06/banner-e-book-pos-mobile.png"
									alt="Baixe o e-book da pós-graduação digital"
									loading="lazy"
									decoding="async"
								>
							</a>
						</div>

						<?php if ($has_ebook_pdf) : ?>
						<div id="ebookEadModal" class="ebookEadModal" aria-hidden="true" role="dialog" aria-labelledby="ebookEadModalTitle" aria-modal="true">
							<div class="ebookEadModal__overlay" data-ebook-ead-close></div>
							<div class="ebookEadModal__dialog" role="document">
								<button type="button" class="ebookEadModal__close" aria-label="Fechar" data-ebook-ead-close>&times;</button>
								<div class="ebookEadModal__header">
									<h2 id="ebookEadModalTitle">Baixe o e-book da pós-graduação digital</h2>
								</div>
								<div class="ebookEadModal__body">
									<form id="ebookEadForm" class="ebookEadForm" novalidate>
										<div class="ebookEadForm__row">
											<label class="ebookEadForm__field">
												Nome*
												<input type="text" name="firstname" autocomplete="given-name" required>
												<span class="ebookEadForm__error" data-error-for="firstname"></span>
											</label>
											<label class="ebookEadForm__field">
												Sobrenome*
												<input type="text" name="lastname" autocomplete="family-name" required>
												<span class="ebookEadForm__error" data-error-for="lastname"></span>
											</label>
										</div>
										<div class="ebookEadForm__row">
											<label class="ebookEadForm__field">
												E-mail*
												<input type="email" name="email" autocomplete="email" required>
												<span class="ebookEadForm__error" data-error-for="email"></span>
											</label>
											<label class="ebookEadForm__field">
												Número de telefone*
												<input type="tel" name="phone" placeholder="(21) 99999-9999" autocomplete="tel" required>
												<span class="ebookEadForm__error" data-error-for="phone"></span>
											</label>
										</div>
										<button type="submit" class="ebookEadForm__submit">Enviar</button>
										<div class="ebookEadForm__status" role="status" aria-live="polite"></div>
									</form>
								</div>
							</div>
						</div>
						<script>
						(function () {
							var HUBSPOT_EBOOK_ENDPOINT = 'https://api.hsforms.com/submissions/v3/integration/submit/3462868/e77f19cb-61f0-4e6b-b499-0acc56618210';
							var CURSO_EBOOK = <?php echo wp_json_encode($titulo_curso_ebook); ?>;
							var modal = document.getElementById('ebookEadModal');
							var trigger = document.querySelector('.ebookEAD__link--gated');
							var form = document.getElementById('ebookEadForm');
							var statusEl = form ? form.querySelector('.ebookEadForm__status') : null;
							var submitBtn = form ? form.querySelector('.ebookEadForm__submit') : null;
							var lastFocused = null;
							var pendingPdfUrl = '';
							var submitting = false;

							if (!modal || !trigger || !form) {
								return;
							}

							function closeModal() {
								modal.setAttribute('aria-hidden', 'true');
								if (lastFocused && typeof lastFocused.focus === 'function') {
									lastFocused.focus();
								}
							}

							function openPdfAfterSubmit() {
								if (!pendingPdfUrl) {
									return;
								}
								window.open(pendingPdfUrl, '_blank', 'noopener');
								closeModal();
							}

							function setFieldError(name, message) {
								var errorEl = form.querySelector('[data-error-for="' + name + '"]');
								if (!errorEl) {
									return;
								}
								if (message) {
									errorEl.textContent = message;
									errorEl.classList.add('is-visible');
								} else {
									errorEl.textContent = '';
									errorEl.classList.remove('is-visible');
								}
							}

							function clearErrors() {
								form.querySelectorAll('.ebookEadForm__error').forEach(function (errorEl) {
									errorEl.textContent = '';
									errorEl.classList.remove('is-visible');
								});
							}

							function isValidEmail(value) {
								return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || '').trim());
							}

							function isValidPhone(value) {
								var digits = String(value || '').replace(/\D/g, '');
								return digits.length >= 10 && digits.length <= 11;
							}

							function formatPhone(value) {
								var digits = String(value || '').replace(/\D/g, '').slice(0, 11);
								if (digits.length <= 2) {
									return digits ? '(' + digits : '';
								}
								if (digits.length <= 6) {
									return '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
								}
								if (digits.length <= 10) {
									return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 6) + '-' + digits.slice(6);
								}
								return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7);
							}

							function collectUtmFields() {
								var params = new URLSearchParams(window.location.search || '');
								var keys = [
									'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term',
									'utm_id', 'utm_source_platform', 'utm_campaign_id', 'utm_creative_format', 'utm_marketing_tactic'
								];
								return keys.map(function (key) {
									return { name: key, value: params.get(key) || '' };
								}).concat([{ name: 'origem261', value: params.get('origemmkt') || '' }]);
							}

							function buildHubspotPayload() {
								var firstName = form.querySelector('[name="firstname"]').value.trim();
								var lastName = form.querySelector('[name="lastname"]').value.trim();
								var email = form.querySelector('[name="email"]').value.trim();
								var phone = form.querySelector('[name="phone"]').value.replace(/\D/g, '');
								var fields = [
									{ name: 'firstname', value: firstName },
									{ name: 'lastname', value: lastName },
									{ name: 'email', value: email },
									{ name: 'phone', value: phone },
									{ name: 'n_vel_do_curso_de_interesse', value: 'Pós-graduação' }
								];

								if (CURSO_EBOOK) {
									fields.push({ name: 'pos___curso_de_interesse___ead', value: CURSO_EBOOK });
									fields.push({ name: 'curso_de_interesse', value: CURSO_EBOOK });
								}

								return {
									fields: fields.concat(collectUtmFields()),
									context: {
										pageUri: window.location.href,
										pageName: document.title
									}
								};
							}

							function openModal(pdfUrl) {
								pendingPdfUrl = pdfUrl || '';
								lastFocused = document.activeElement;
								modal.setAttribute('aria-hidden', 'false');
								if (statusEl) {
									statusEl.textContent = '';
								}
								var closeBtn = modal.querySelector('.ebookEadModal__close');
								if (closeBtn) {
									closeBtn.focus();
								}
							}

							trigger.addEventListener('click', function (event) {
								var pdfUrl = trigger.getAttribute('data-pdf-url') || '';
								if (!pdfUrl || pdfUrl === '#') {
									return;
								}
								event.preventDefault();
								openModal(pdfUrl);
							});

							form.addEventListener('input', function (event) {
								if (event.target && event.target.name === 'phone') {
									event.target.value = formatPhone(event.target.value);
								}
							});

							form.addEventListener('submit', function (event) {
								event.preventDefault();
								if (submitting) {
									return;
								}

								clearErrors();

								var firstNameField = form.querySelector('[name="firstname"]');
								var lastNameField = form.querySelector('[name="lastname"]');
								var emailField = form.querySelector('[name="email"]');
								var phoneField = form.querySelector('[name="phone"]');
								var valid = true;
								var firstInvalid = null;

								if (!firstNameField.value.trim()) {
									valid = false;
									setFieldError('firstname', 'Informe seu nome.');
									firstInvalid = firstInvalid || firstNameField;
								}
								if (!lastNameField.value.trim()) {
									valid = false;
									setFieldError('lastname', 'Informe seu sobrenome.');
									firstInvalid = firstInvalid || lastNameField;
								}
								if (!isValidEmail(emailField.value)) {
									valid = false;
									setFieldError('email', 'Informe um e-mail válido.');
									firstInvalid = firstInvalid || emailField;
								}
								if (!isValidPhone(phoneField.value)) {
									valid = false;
									setFieldError('phone', 'Informe um telefone válido.');
									firstInvalid = firstInvalid || phoneField;
								}

								if (!valid) {
									if (firstInvalid && typeof firstInvalid.focus === 'function') {
										firstInvalid.focus();
									}
									return;
								}

								submitting = true;
								if (submitBtn) {
									submitBtn.setAttribute('disabled', 'disabled');
									submitBtn.textContent = 'Enviando...';
								}
								if (statusEl) {
									statusEl.textContent = 'Enviando seus dados, por favor aguarde...';
								}

								window.fetch(HUBSPOT_EBOOK_ENDPOINT, {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json',
										'Accept': 'application/json'
									},
									body: JSON.stringify(buildHubspotPayload())
								}).then(function (response) {
									submitting = false;
									if (submitBtn) {
										submitBtn.removeAttribute('disabled');
										submitBtn.textContent = 'Enviar';
									}
									if (!response.ok) {
										if (statusEl) {
											statusEl.textContent = 'Não foi possível enviar agora. Tente novamente em instantes.';
										}
										return;
									}
									if (statusEl) {
										statusEl.textContent = 'Dados enviados! Abrindo o e-book...';
									}
									setTimeout(openPdfAfterSubmit, 400);
								}).catch(function () {
									submitting = false;
									if (submitBtn) {
										submitBtn.removeAttribute('disabled');
										submitBtn.textContent = 'Enviar';
									}
									if (statusEl) {
										statusEl.textContent = 'Não foi possível enviar agora. Tente novamente em instantes.';
									}
								});
							});

							modal.querySelectorAll('[data-ebook-ead-close]').forEach(function (element) {
								element.addEventListener('click', closeModal);
							});

							document.addEventListener('keydown', function (event) {
								if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
									closeModal();
								}
							});
						})();
						</script>
						<?php endif; ?>
						<style>
							.ebookEAD {
								display: block;
								position: relative;
							}
							.ebookEAD__link {
								display: block;
								line-height: 0;
							}
							.ebookEAD__img {
								/* width: 100%; */
								height: auto;
								display: block;
							}
							.ebookEAD__img--mobile {
								display: none;
							}
							@media (max-width: 768px) {
								.ebookEAD__img--desktop {
									display: none;
								}
								.ebookEAD__img--mobile {
									display: block;
								}
							}
							.ebookEadModal {
								position: fixed;
								inset: 0;
								display: none;
								align-items: center;
								justify-content: center;
								z-index: 99999;
							}
							.ebookEadModal[aria-hidden="false"] {
								display: flex;
							}
							.ebookEadModal__overlay {
								position: absolute;
								inset: 0;
								background: rgba(0, 0, 0, 0.55);
							}
							.ebookEadModal__dialog {
								position: relative;
								z-index: 1;
								background: #fff;
								border-radius: 10px;
								width: min(94%, 760px);
								max-height: 90vh;
								overflow: auto;
								box-shadow: 0 14px 48px rgba(10, 10, 10, 0.36);
								padding: 20px;
							}
							.ebookEadModal__close {
								position: absolute;
								right: 12px;
								top: 8px;
								background: transparent !important;
								border: 0;
								font-size: 26px;
								line-height: 1;
								cursor: pointer;
								color: #444;
							}
							@media(max-width:600px) {
								.ebookEadModal__close {
									right: -37px;
									top: -22px;
								}
							}
							.ebookEadModal__header h2 {
								margin: 8px 32px 16px 0;
								font-size: 20px;
								text-align: center;
							}
							.ebookEadForm {
								display: grid;
								gap: 12px;
								max-width: 560px;
								margin: 0 auto;
							}
							.ebookEadForm__row {
								display: grid;
								grid-template-columns: repeat(2, minmax(0, 1fr));
								gap: 12px;
							}
							.ebookEadForm__field {
								display: flex;
								flex-direction: column;
								font-size: 13px;
								font-weight: 600;
								color: #586476;
							}
							.ebookEadForm__field input {
								margin-top: 4px;
								height: 40px;
								padding: 0 12px;
								border: 1px solid #cfd6de;
								border-radius: 4px;
								font-size: 14px;
								color: #2f3b4f;
								background: #fff;
							}
							.ebookEadForm__field input:focus {
								outline: none;
								border-color: #E5457A;
								box-shadow: 0 0 0 2px rgba(229, 69, 122, 0.12);
							}
							.ebookEadForm__error {
								display: none;
								font-size: 12px;
								line-height: 1.2;
								color: #c0392b;
								margin-top: 4px;
							}
							.ebookEadForm__error.is-visible {
								display: block;
							}
							.ebookEadForm__submit {
								justify-self: center;
								min-width: 160px;
								height: 44px;
								border: 0;
								border-radius: 4px;
								background: #ff9800;
								color: #fff;
								font-size: 16px;
								font-weight: 700;
								cursor: pointer;
								line-height: 14px;
							}
							.ebookEadForm__submit[disabled] {
								opacity: 0.7;
								cursor: not-allowed;
							}
							.ebookEadForm__status {
								min-height: 18px;
								font-size: 13px;
								text-align: center;
								color: #2f3b4f;
							}
							@media (max-width: 640px) {
								.ebookEadForm__row {
									grid-template-columns: 1fr;
								}
							}
						</style>
						<?php endif; ?>

						<!-- WRAP EBOOK  -->



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
				// Esconde o indicador .ruleModulo quando a listagem deve ficar aberta (sem acordeão)
				if (in_array($page_modalidade_slug, array('presencial', 'semipresencial', 'digital'), true)) : ?>
					<style>
						.cursar .wrapModulo .ruleModulo { display: none !important; }
						.cursar .wrapModulo { cursor: default !important; }
						<?php if ($page_modalidade_slug === 'digital') : ?>
						.cursar .wrapModulo { height: auto !important; }
						<?php endif; ?>
					</style>
				<?php endif; ?>
				<?php
				$is_regra_tcc_nutricao = !empty($eh_nutricao_esportiva_aovivo);

				$normalizar_texto_tcc = function ($valor) {
					$valor = is_scalar($valor) ? (string) $valor : '';
					if ($valor === '') {
						return '';
					}
					$valor = function_exists('remove_accents') ? remove_accents($valor) : $valor;
					$valor = function_exists('mb_strtolower') ? mb_strtolower($valor, 'UTF-8') : strtolower($valor);
					$valor = preg_replace('/\s+/u', ' ', trim($valor));
					return $valor;
				};

				$extrair_texto_disciplina = function ($item) {
					if (is_string($item)) {
						$txt = trim($item);
						return $txt !== '' ? $txt : '';
					}
					if (!is_array($item)) {
						return '';
					}
					foreach (array('disciplina', 'nome', 'titulo', 'descricao', 'componente', 'name', 'label') as $chave) {
						if (!empty($item[$chave]) && is_string($item[$chave])) {
							$txt = trim($item[$chave]);
							if ($txt !== '') {
								return $txt;
							}
						}
					}
					return '';
				};

				$modulo_contem_tcc = function ($modulo_item) use ($extrair_texto_disciplina, $normalizar_texto_tcc) {
					if (!is_array($modulo_item)) {
						return false;
					}

					$descricao = !empty($modulo_item['descricao']) ? $normalizar_texto_tcc($modulo_item['descricao']) : '';
					if ($descricao !== '' && strpos($descricao, 'tcc') !== false) {
						return true;
					}

					if (!empty($modulo_item['disciplinas']) && is_array($modulo_item['disciplinas'])) {
						foreach ($modulo_item['disciplinas'] as $disciplina_item) {
							$texto_disciplina = $extrair_texto_disciplina($disciplina_item);
							if ($texto_disciplina === '') {
								continue;
							}
							if (strpos($normalizar_texto_tcc($texto_disciplina), 'tcc') !== false) {
								return true;
							}
						}
					}

					return false;
				};

				// Copia os módulos para um novo array para ordenação
				$modulos = $data['estrutura']['grupos'];

				// Se for -digital, ordena pelo número extraído do 'descricao'
                // Verifica se há conteúdo útil nos módulos (descrição ou disciplinas)
                $hasContent = false;
                if (!empty($modulos) && is_array($modulos)) {
                    foreach ($modulos as $m) {
                        $descricaoOk = !empty($m['descricao']) && trim($m['descricao']) !== '';
                        $disciplinasOk = !empty($m['disciplinas']) && is_array($m['disciplinas']) && count(array_filter($m['disciplinas'], function($d){
							if (is_string($d)) {
								return trim($d) !== '';
							}
							if (!is_array($d)) {
								return false;
							}
							foreach (array('disciplina', 'nome', 'titulo', 'descricao', 'componente', 'name', 'label') as $chaveDisciplina) {
								if (!empty($d[$chaveDisciplina]) && trim((string) $d[$chaveDisciplina]) !== '') {
									return true;
								}
							}
							return false;
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

					if ($is_regra_tcc_nutricao) {
						$modulos_sem_tcc = array();
						$modulos_com_tcc = array();

						foreach ($modulos as $modulo_item) {
							if ($modulo_contem_tcc($modulo_item)) {
								$modulos_com_tcc[] = $modulo_item;
							} else {
								$modulos_sem_tcc[] = $modulo_item;
							}
						}

						$modulos = array_merge($modulos_sem_tcc, $modulos_com_tcc);
					}
                }

				$indice_disciplina_nutricao = 1;
				$is_pagina_digital = ($page_modalidade_slug === 'digital');

				// Digital: todo o conteúdo fica dentro de um único .wrapModulo.
				if ($is_pagina_digital) {
					echo '<div class="wrapModulo">';
					echo '<div class="ruleModulo">' . $ruleModulo . '</div>';
				}

				foreach ($modulos as $modulo) {
					if (!$is_pagina_digital) {
				?>
					<div class="wrapModulo">
						<div class="ruleModulo"><?php echo $ruleModulo ?></div>
					<?php } ?>
						<?php
						$descricao_modulo = !empty($modulo['descricao']) ? trim((string) $modulo['descricao']) : '';
						$descricao_modulo_limpa = preg_replace('/^(m[oó]dulo)\s*:?\s*/iu', '', $descricao_modulo);
						$presencial_disciplina_nome = '';
						$usar_regra_disciplina = in_array($page_modalidade_slug, array('presencial', 'semipresencial'), true);
						$usar_regra_modulo_digital = ($page_modalidade_slug === 'digital');

						if ($usar_regra_disciplina && $descricao_modulo !== '') {
							// Presencial/Semipresencial: manter formato "Disciplina X: Nome".
							if ($is_regra_tcc_nutricao) {
								$presencial_disciplina_nome = (string) $indice_disciplina_nutricao;
							} else {
								$presencial_disciplina_nome = preg_replace('/^(m[oó]dulo|disciplina)\s*:?\s*/iu', '', $descricao_modulo);
							}
						} else {
							if ($usar_regra_modulo_digital) {
								// Digital usa o mesmo padrão visual da regra de Disciplina:
								// sem cabeçalho separado, apenas linhas no formato aberto.
								$descricao_modulo_limpa = trim((string) $descricao_modulo_limpa);
							} else {
							// Demais modalidades: mantém cabeçalho padrão de módulo.
							// echo '<p class="titleModulo"><b>Módulo:</b> ' . esc_html($descricao_modulo_limpa) . '</p>';
							}
						}
						?>
						<?php
							$disciplinas_render = array();
							if (!empty($modulo['disciplinas']) && is_array($modulo['disciplinas'])) {
								$disciplinas_render = $modulo['disciplinas'];
							}
							foreach ($disciplinas_render as $disciplina) {
								$texto_disciplina = $extrair_texto_disciplina($disciplina);
								if ($texto_disciplina === '') {
									continue;
								}
								$texto_disciplina_exibicao = $texto_disciplina;
								if ($is_regra_tcc_nutricao) {
									$texto_disciplina_normalizado = $normalizar_texto_tcc($texto_disciplina_exibicao);
									if (strpos($texto_disciplina_normalizado, 'tcc') !== false && strpos($texto_disciplina_normalizado, 'disciplina opcional') === false) {
										$texto_disciplina_exibicao = preg_replace('/^\s*[:-]?\s*/u', '', $texto_disciplina_exibicao . ' (Disciplina opcional) ');
									}
								}
								if ($usar_regra_modulo_digital) {
									$texto_disciplina_exibicao = preg_replace('/^\s*m[oó]dulo\s*\d+\s*[:\-]?\s*/iu', '', $texto_disciplina_exibicao);
									$texto_disciplina_exibicao = trim((string) $texto_disciplina_exibicao);
									if ($texto_disciplina_exibicao === '') {
										continue;
									}
								}
								if ($usar_regra_disciplina && $presencial_disciplina_nome !== '') {
									// Para presencial e digital ao vivo, exibe já aberto: "Disciplina {n}: Nome da matéria"
									// echo '<p class="titleModulo"><b>Disciplina ' . esc_html($presencial_disciplina_nome) . ':</b> ' . esc_html($texto_disciplina_exibicao) . '</p>';
									echo esc_html($texto_disciplina_exibicao);
								} elseif ($usar_regra_modulo_digital) {
									// Digital: exibe apenas o nome da disciplina, sem o prefixo "Módulo X:".
									echo '<p class="titleModulo">' . esc_html($texto_disciplina_exibicao) . '</p>';
								} else {
									// Comportamento padrão: conteúdo oculto que abre ao clicar
									echo '<p class="contentModulo">' . esc_html($texto_disciplina_exibicao) . '</p>';
								}
							}
							if ($is_regra_tcc_nutricao && $usar_regra_disciplina) {
								$indice_disciplina_nutricao++;
							}
						?>
					<?php if (!$is_pagina_digital) { ?>
					</div>
					<?php } ?>
				<?php
				}
				if ($is_pagina_digital) {
					echo '</div>'; // .wrapModulo único da modalidade digital
				}
				?>
				<!-- loop de modulos -->
				</div>
			</section>

			<style>
				.cursar .wrapModulo {
					cursor: pointer;
					overflow: hidden;
				}
				.cursar .wrapModulo .contentModulo {
					display: none;
				}
				.cursar .wrapModulo.is-open {
					overflow: visible;
				}
				.cursar .wrapModulo.is-open .contentModulo {
					display: block;
				}
				.cursar .wrapModulo + .wrapModulo {
					margin-top: 10px;
				}
			</style>

			<script>
			document.addEventListener('DOMContentLoaded', function () {
				var modulos = document.querySelectorAll('.cursar .wrapModulo');
				if (!modulos.length) {
					return;
				}

				modulos.forEach(function (modulo) {
					var conteudos = modulo.querySelectorAll('.contentModulo');
					if (!conteudos.length) {
						return;
					}

					// Estado inicial fechado para todos os módulos.
					modulo.classList.remove('is-open');
					conteudos.forEach(function (item) {
						item.style.display = 'none';
					});

					modulo.addEventListener('click', function (event) {
						// Garante o controle aqui mesmo, sem depender de scripts externos.
						event.preventDefault();
						event.stopPropagation();
						if (typeof event.stopImmediatePropagation === 'function') {
							event.stopImmediatePropagation();
						}

						var abriu = !modulo.classList.contains('is-open');
						modulo.classList.toggle('is-open', abriu);

						conteudos.forEach(function (item) {
							item.style.display = abriu ? 'block' : 'none';
						});

						modulo.style.height = abriu ? 'auto' : '';
						modulo.style.overflow = abriu ? 'visible' : 'hidden';

						var indicador = modulo.querySelector('.ruleModulo');
						if (indicador) {
							indicador.classList.toggle('active', abriu);
						}
					}, true);
				});
			});
			</script>
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
  var investimentosBrutos = <?php echo wp_json_encode($data['investimentos'] ?? array()); ?>;
  window.investimentos = investimentosBrutos;

	function normalizarTextoBusca(valor) {
		var texto = String(valor || '').trim().toLowerCase();
		if (typeof texto.normalize === 'function') {
			texto = texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
		}
		return texto;
	}

	function valorParaTextoSimples(valor) {
		if (valor == null) {
			return '';
		}
		if (typeof valor === 'string' || typeof valor === 'number' || typeof valor === 'boolean') {
			return String(valor).trim();
		}
		if (Array.isArray(valor)) {
			for (var i = 0; i < valor.length; i++) {
				var textoLista = valorParaTextoSimples(valor[i]);
				if (textoLista) {
					return textoLista;
				}
			}
			return '';
		}
		if (typeof valor === 'object') {
			var chavesPreferidas = ['valor', 'value', 'texto', 'text', 'nome', 'name', 'label'];
			for (var j = 0; j < chavesPreferidas.length; j++) {
				if (Object.prototype.hasOwnProperty.call(valor, chavesPreferidas[j])) {
					var textoObj = valorParaTextoSimples(valor[chavesPreferidas[j]]);
					if (textoObj) {
						return textoObj;
					}
				}
			}
		}
		return '';
	}

	function obterValorPorChavesFlexiveis(obj, chaves) {
		if (!obj || typeof obj !== 'object') {
			return '';
		}

		for (var i = 0; i < chaves.length; i++) {
			var chaveDireta = chaves[i];
			if (Object.prototype.hasOwnProperty.call(obj, chaveDireta)) {
				var valorDireto = valorParaTextoSimples(obj[chaveDireta]);
				if (valorDireto) {
					return valorDireto;
				}
			}
		}

		var mapaNormalizado = Object.create(null);
		Object.keys(obj).forEach(function(chaveObj) {
			mapaNormalizado[normalizarTextoBusca(chaveObj).replace(/[^a-z0-9]/g, '')] = chaveObj;
		});

		for (var j = 0; j < chaves.length; j++) {
			var chaveNormalizada = normalizarTextoBusca(chaves[j]).replace(/[^a-z0-9]/g, '');
			if (Object.prototype.hasOwnProperty.call(mapaNormalizado, chaveNormalizada)) {
				var chaveOriginal = mapaNormalizado[chaveNormalizada];
				var valorFlex = valorParaTextoSimples(obj[chaveOriginal]);
				if (valorFlex) {
					return valorFlex;
				}
			}
		}

		return '';
	}

	function construirDadosUnidade() {
		var mapa = Object.create(null);
		var todas = [];

		function adicionar(cidade, unidade) {
			var cidadeTexto = String(cidade || '').trim();
			var unidadeTexto = String(unidade || '').trim();
			if (!unidadeTexto) {
				return;
			}
			if (todas.indexOf(unidadeTexto) === -1) {
				todas.push(unidadeTexto);
			}
			if (!cidadeTexto) {
				return;
			}
			if (!Array.isArray(mapa[cidadeTexto])) {
				mapa[cidadeTexto] = [];
			}
			if (mapa[cidadeTexto].indexOf(unidadeTexto) === -1) {
				mapa[cidadeTexto].push(unidadeTexto);
			}
		}

		(Array.isArray(investimentosBrutos) ? investimentosBrutos : []).forEach(function(item) {
			if (!item || typeof item !== 'object') {
				return;
			}
			var unidade = obterValorPorChavesFlexiveis(item, ['unidade', 'unidade_nome', 'unidadeNome', 'campus', 'local', 'polo']);
			var cidade = obterValorPorChavesFlexiveis(item, ['cidade', 'cidade_nome', 'cidadeNome', 'municipio', 'municipio_nome']);
			adicionar(cidade, unidade);
		});

		Object.keys(cidadesUnidades || {}).forEach(function(cidade) {
			var lista = Array.isArray(cidadesUnidades[cidade]) ? cidadesUnidades[cidade] : [];
			lista.forEach(function(unidade) {
				adicionar(cidade, unidade);
			});
		});

		return { mapa: mapa, todas: todas };
	}

	var dadosUnidade = construirDadosUnidade();
	cidadesUnidades = dadosUnidade.mapa;
	var unidadesTodasDaApi = dadosUnidade.todas;

	function detectarPresencial() {
		var modalidadeBox = document.getElementById('modalidadeBox');
		var texto = modalidadeBox ? String(modalidadeBox.dataset.modalidadeNormalizada || modalidadeBox.textContent || '') : '';
		var normalizado = normalizarTextoBusca(texto);
		var isSemipresencial =
			normalizado.indexOf('semipresencial') !== -1 ||
			normalizado.indexOf('digitalaovivo') !== -1 ||
			normalizado.indexOf('digital ao vivo') !== -1 ||
			normalizado.indexOf('ao vivo') !== -1;
		var isDigital = normalizado.indexOf('digital') !== -1 && !isSemipresencial;
		return !isSemipresencial && !isDigital;
	}

	function isSelectSecundario(idOuName) {
		var base = normalizarTextoBusca(idOuName);
		return base.indexOf('enem') !== -1 ||
			base.indexOf('segunda') !== -1 ||
			base.indexOf('transf') !== -1 ||
			base.indexOf('transfer') !== -1;
	}

	function obterPrimeiroSelectPorCandidatos(candidatos, filtro) {
		for (var i = 0; i < candidatos.length; i++) {
			var elementos = document.querySelectorAll(candidatos[i]);
			for (var j = 0; j < elementos.length; j++) {
				var el = elementos[j];
				if (!el || !el.tagName || el.tagName.toLowerCase() !== 'select') {
					continue;
				}
				if (typeof filtro === 'function' && !filtro(el)) {
					continue;
				}
				return el;
			}
		}
		return null;
	}

	function obterSelectUnidadePrincipal() {
		return obterPrimeiroSelectPorCandidatos([
			'#unidade',
			'#unidadeVestibular',
			'#unidade-vestibular',
			'select[name="unidade"]',
			'select[name="unidadeVestibular"]',
			'select[name="unidade_vestibular"]',
			'.apenasVestibular select[id*="unidade"]',
			'.apenasVestibular select[name*="unidade"]',
			'#sidesTwo select[id*="unidade"]',
			'#sidesTwo select[name*="unidade"]',
			'select[id*="unidade"]',
			'select[name*="unidade"]'
		], function(el) {
			return !isSelectSecundario(el.id || '') && !isSelectSecundario(el.name || '');
		});
	}

	function obterSelectCidadePrincipal() {
		return obterPrimeiroSelectPorCandidatos([
			'#cidade',
			'#cidadeVestibular',
			'#cidade-vestibular',
			'select[name="cidade"]',
			'select[name="cidadeVestibular"]',
			'select[name="cidade_vestibular"]',
			'.apenasVestibular select[id*="cidade"]',
			'.apenasVestibular select[name*="cidade"]',
			'#sidesTwo select[id*="cidade"]',
			'#sidesTwo select[name*="cidade"]',
			'select[id*="cidade"]',
			'select[name*="cidade"]'
		], function(el) {
			return !isSelectSecundario(el.id || '') && !isSelectSecundario(el.name || '');
		});
	}

	function listaUnicaUnidades(lista) {
		var unicas = [];
		(Array.isArray(lista) ? lista : []).forEach(function(unidade) {
			var valor = String(unidade || '').trim();
			if (valor && unicas.indexOf(valor) === -1) {
				unicas.push(valor);
			}
		});
		return unicas;
	}

	function obterTodasUnidades() {
		return listaUnicaUnidades(unidadesTodasDaApi);
	}

	function preencherSelectUnidade(unidadeSelect, unidades) {
		unidadeSelect.innerHTML = '<option value="">Escolha a sua unidade</option>';
		listaUnicaUnidades(unidades).forEach(function(unidade) {
			var opt = document.createElement('option');
			opt.value = unidade;
			opt.textContent = unidade;
			unidadeSelect.appendChild(opt);
		});
	}

	function obterUnidadesDaCidade(cidadeSelect) {
		if (!cidadeSelect) {
			return [];
		}

		var valorCidade = String(cidadeSelect.value || '').trim();
		var textoCidade = '';
		if (typeof cidadeSelect.selectedIndex === 'number' && cidadeSelect.selectedIndex >= 0) {
			var optionSelecionada = cidadeSelect.options[cidadeSelect.selectedIndex];
			if (optionSelecionada) {
				textoCidade = String(optionSelecionada.text || '').trim();
			}
		}

		var mapaNormalizado = Object.create(null);
		Object.keys(cidadesUnidades || {}).forEach(function(chaveCidade) {
			mapaNormalizado[normalizarTextoBusca(chaveCidade)] = cidadesUnidades[chaveCidade];
		});

		var candidatos = [valorCidade, textoCidade];
		for (var i = 0; i < candidatos.length; i++) {
			var chave = normalizarTextoBusca(candidatos[i]);
			if (!chave) {
				continue;
			}
			if (Object.prototype.hasOwnProperty.call(cidadesUnidades, candidatos[i])) {
				return cidadesUnidades[candidatos[i]];
			}
			if (Object.prototype.hasOwnProperty.call(mapaNormalizado, chave)) {
				return mapaNormalizado[chave];
			}
		}

		return [];
	}

	function restaurarSelecaoUnidade(unidadeSelect, valorAnterior) {
		var alvo = normalizarTextoBusca(valorAnterior);
		if (!alvo) {
			return;
		}
		for (var i = 0; i < unidadeSelect.options.length; i++) {
			var option = unidadeSelect.options[i];
			var comparavel = normalizarTextoBusca(option.value || option.text || '');
			if (comparavel && comparavel === alvo) {
				unidadeSelect.selectedIndex = i;
				return;
			}
		}
	}

	function inicializarSelecaoCidadeUnidade() {
		var cidadeSelect = obterSelectCidadePrincipal();
		var unidadeSelect = obterSelectUnidadePrincipal();

		if (!unidadeSelect) {
			return false;
		}

		var atualizarUnidades = function() {
			var valorSelecionadoAntes = String(unidadeSelect.value || '').trim();
			var unidadesDaCidade = obterUnidadesDaCidade(cidadeSelect);
			if (unidadesDaCidade.length) {
				preencherSelectUnidade(unidadeSelect, unidadesDaCidade);
				restaurarSelecaoUnidade(unidadeSelect, valorSelecionadoAntes);
				return;
			}

			if (detectarPresencial()) {
				preencherSelectUnidade(unidadeSelect, obterTodasUnidades());
				restaurarSelecaoUnidade(unidadeSelect, valorSelecionadoAntes);
				return;
			}

			preencherSelectUnidade(unidadeSelect, []);
			restaurarSelecaoUnidade(unidadeSelect, valorSelecionadoAntes);
		};

		if (cidadeSelect && cidadeSelect.dataset.unidadeCidadeBindSaga !== '1') {
			cidadeSelect.addEventListener('change', atualizarUnidades);
			cidadeSelect.dataset.unidadeCidadeBindSaga = '1';
		}

		atualizarUnidades();
		unidadeSelect.dataset.unidadeInitSaga = '1';
		return true;
	}

	function tentarInicializarComRetentativa(maxTentativas, intervalo) {
		if (inicializarSelecaoCidadeUnidade()) {
			return;
		}
		var tentativas = 0;
		var timerInitUnidade = setInterval(function() {
			tentativas += 1;
			if (inicializarSelecaoCidadeUnidade() || tentativas >= maxTentativas) {
				clearInterval(timerInitUnidade);
			}
		}, intervalo);
	}

	tentarInicializarComRetentativa(40, 300);

	document.addEventListener('click', function(e) {
		var alvo = e.target && e.target.closest
			? e.target.closest('#btnIniciarMatricula, .btnModalidade, .btnVestibular')
			: null;
		if (!alvo) {
			return;
		}
		setTimeout(function() {
			inicializarSelecaoCidadeUnidade();
		}, 80);
	});

	window.addEventListener('load', function() {
		inicializarSelecaoCidadeUnidade();
	});

	if (typeof MutationObserver !== 'undefined' && document.body) {
		var observerInitUnidade = new MutationObserver(function() {
			if (inicializarSelecaoCidadeUnidade()) {
				observerInitUnidade.disconnect();
			}
		});
		observerInitUnidade.observe(document.body, { childList: true, subtree: true });
		setTimeout(function() {
			observerInitUnidade.disconnect();
		}, 60000);
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
        
		// Atualizar valorSDesconto para refletir desconto de 60%.
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
			const valorSDescontoNumero = valorSagaNumero / 0.4;
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
        
		// Atualizar valorSDesconto para refletir desconto de 60%.
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
			const valorSDescontoNumero = valorSagaNumero / 0.4;
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
        
		// Atualizar valorSDesconto para refletir desconto de 60%.
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
			const valorSDescontoNumero = valorSagaNumero / 0.4;
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

	function obterTextoSelecionadoSelect(id) {
		const select = document.getElementById(id);
		if (!select || typeof select.selectedIndex !== 'number' || select.selectedIndex < 0) {
			return '';
		}
		const option = select.options[select.selectedIndex];
		if (!option) {
			return '';
		}
		if (typeof option.value === 'string' && option.value.trim() === '') {
			return '';
		}
		return typeof option.text === 'string' ? option.text.trim() : '';
	}

	function obterPrimeiroTextoSelecionado(ids) {
		for (let i = 0; i < ids.length; i++) {
			const valor = obterTextoSelecionadoSelect(ids[i]);
			if (valor) {
				return valor;
			}
		}
		return '';
	}

	function obterPrimeiroValorSelecionado(ids) {
		for (let i = 0; i < ids.length; i++) {
			const select = document.getElementById(ids[i]);
			if (!select) {
				continue;
			}
			const valor = typeof select.value === 'string' ? select.value.trim() : '';
			if (valor) {
				return valor;
			}
		}
		return '';
	}

	function normalizarTextoSaga(valor) {
		const texto = String(valor || '').trim().toLowerCase();
		if (typeof texto.normalize === 'function') {
			return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
		}
		return texto;
	}

	function normalizarChaveFormaIngressoSaga(valor) {
		const texto = normalizarTextoSaga(valor);
		if (!texto) {
			return 'vestibular';
		}
		if (texto.indexOf('enem') !== -1) {
			return 'enem';
		}
		if (texto.indexOf('segunda') !== -1 || texto.indexOf('2a') !== -1 || texto.indexOf('2ª') !== -1) {
			return 'segunda';
		}
		if (texto.indexOf('transfer') !== -1) {
			return 'transf';
		}
		return 'vestibular';
	}

	function obterLabelFormaSaga(chave) {
		if (chave === 'enem') {
			return 'ENEM';
		}
		if (chave === 'segunda') {
			return 'SEGUNDA GRADUAÇÃO';
		}
		if (chave === 'transf') {
			return 'TRANSFERÊNCIA';
		}
		return 'VESTIBULAR';
	}

	function obterChaveFormaAtivaSaga() {
		const botaoAtivo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
		const classes = botaoAtivo && botaoAtivo.classList ? botaoAtivo.classList : null;
		if (classes && classes.contains('btnEnem')) {
			return 'enem';
		}
		if (classes && classes.contains('btnSegundaGrad')) {
			return 'segunda';
		}
		if (classes && classes.contains('btnTransferencia')) {
			return 'transf';
		}
		return 'vestibular';
	}

	function obterFormaIngressoAtivaSaga() {
		const botaoAtivo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
		return botaoAtivo ? String(botaoAtivo.textContent || '').trim() : '';
	}

	function obterContextoSelecaoSaga(botao) {
		const contexto = {
			formaChave: obterChaveFormaAtivaSaga(),
			formaIngressoHint: obterFormaIngressoAtivaSaga() || '',
			cidadeIds: ['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf'],
			unidadeIds: ['unidade', 'unidadeEnem', 'unidadeSegunda', 'unidadeTransf'],
			turnoIds: ['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf']
		};

		let modalId = '';
		if (botao && typeof botao.closest === 'function') {
			const modalEl = botao.closest('#modalEnem, #modalSegunda, #modalSegundaGrad, #modalTransf, #modalTransferencia');
			modalId = modalEl ? modalEl.id : '';
		}

		if (modalId === 'modalEnem') {
			contexto.formaChave = 'enem';
			contexto.formaIngressoHint = 'ENEM';
			contexto.cidadeIds = ['cidadeEnem', 'cidade'];
			contexto.unidadeIds = ['unidadeEnem', 'unidade'];
			contexto.turnoIds = ['horarioEnem', 'horario'];
			return contexto;
		}

		if (modalId === 'modalSegunda' || modalId === 'modalSegundaGrad') {
			contexto.formaChave = 'segunda';
			contexto.formaIngressoHint = 'SEGUNDA GRADUAÇÃO';
			contexto.cidadeIds = ['cidadeSegunda', 'cidade'];
			contexto.unidadeIds = ['unidadeSegunda', 'unidade'];
			contexto.turnoIds = ['horarioSegunda', 'horario'];
			return contexto;
		}

		if (modalId === 'modalTransf' || modalId === 'modalTransferencia') {
			contexto.formaChave = 'transf';
			contexto.formaIngressoHint = 'TRANSFERÊNCIA';
			contexto.cidadeIds = ['cidadeTransf', 'cidade'];
			contexto.unidadeIds = ['unidadeTransf', 'unidade'];
			contexto.turnoIds = ['horarioTransf', 'horario'];
			return contexto;
		}

		if (contexto.formaChave === 'enem') {
			contexto.cidadeIds = ['cidadeEnem', 'cidade'];
			contexto.unidadeIds = ['unidadeEnem', 'unidade'];
			contexto.turnoIds = ['horarioEnem', 'horario'];
		}
		if (contexto.formaChave === 'segunda') {
			contexto.cidadeIds = ['cidadeSegunda', 'cidade'];
			contexto.unidadeIds = ['unidadeSegunda', 'unidade'];
			contexto.turnoIds = ['horarioSegunda', 'horario'];
		}
		if (contexto.formaChave === 'transf') {
			contexto.cidadeIds = ['cidadeTransf', 'cidade'];
			contexto.unidadeIds = ['unidadeTransf', 'unidade'];
			contexto.turnoIds = ['horarioTransf', 'horario'];
		}

		if (!contexto.formaIngressoHint) {
			contexto.formaIngressoHint = obterLabelFormaSaga(contexto.formaChave);
		}

		return contexto;
	}

	function obterSelecaoUnidadeSaga(contextoSelecao) {
		const unidadeIds = contextoSelecao && Array.isArray(contextoSelecao.unidadeIds) && contextoSelecao.unidadeIds.length
			? contextoSelecao.unidadeIds
			: ['unidade', 'unidadeEnem', 'unidadeSegunda', 'unidadeTransf'];

		const modalidade = obterSlugModalidadeSaga();
		if (modalidade === 'presencial' && unidadeIds.length) {
			const unidadePrincipalId = unidadeIds[0];
			return {
				ids: unidadeIds,
				texto: obterTextoSelecionadoSelect(unidadePrincipalId),
				valor: obterPrimeiroValorSelecionado([unidadePrincipalId])
			};
		}

		return {
			ids: unidadeIds,
			texto: obterPrimeiroTextoSelecionado(unidadeIds),
			valor: obterPrimeiroValorSelecionado(unidadeIds)
		};
	}

	function obterSlugModalidadeSaga() {
		const modalidadeBox = document.getElementById('modalidadeBox');
		const modalidadeData = modalidadeBox && modalidadeBox.dataset
			? String(modalidadeBox.dataset.modalidadeNormalizada || '').trim()
			: '';
		const texto = modalidadeBox ? String(modalidadeBox.textContent || '') : '';
		const textoNormalizado = normalizarTextoSaga(modalidadeData || texto);
		const urlNormalizada = normalizarTextoSaga(window.location.href || '');

		if (
			textoNormalizado.indexOf('semipresencial') !== -1 ||
			textoNormalizado.indexOf('digital ao vivo') !== -1 ||
			textoNormalizado.indexOf('digitalaovivo') !== -1 ||
			textoNormalizado.indexOf('ao vivo') !== -1 ||
			urlNormalizada.indexOf('-semipresencial') !== -1 ||
			urlNormalizada.indexOf('-aovivo') !== -1 ||
			urlNormalizada.indexOf('digital-ao-vivo') !== -1
		) {
			return 'digital_ao_vivo';
		}
		if (textoNormalizado.indexOf('digital') !== -1 || urlNormalizada.indexOf('-digital') !== -1) {
			return 'ead';
		}
		if (textoNormalizado.indexOf('presencial') !== -1) {
			return 'presencial';
		}
		return 'presencial';
	}

	function obterListaInvestimentosSaga() {
		if (Array.isArray(window.investimentos)) {
			return window.investimentos;
		}
		if (typeof investimentos !== 'undefined' && Array.isArray(investimentos)) {
			return investimentos;
		}
		if (Array.isArray(window.OFERTA_INFO_INVESTIMENTOS_API)) {
			return window.OFERTA_INFO_INVESTIMENTOS_API;
		}
		if (typeof OFERTA_INFO_INVESTIMENTOS_API !== 'undefined' && Array.isArray(OFERTA_INFO_INVESTIMENTOS_API)) {
			return OFERTA_INFO_INVESTIMENTOS_API;
		}
		return [];
	}

	function obterIdOfertaInvestimento(item) {
		if (!item || typeof item !== 'object') {
			return '';
		}

		const campos = ['id', 'Id', 'ID', 'idCombinacao', 'id_oferta', 'oferta_id', 'oferta'];
		for (let i = 0; i < campos.length; i++) {
			const bruto = item[campos[i]];
			if (bruto == null || bruto === '') {
				continue;
			}
			const texto = String(bruto).trim();
			if (texto) {
				return texto;
			}
		}

		return '';
	}

	function resolverIdOfertaPorInvestimentos(unidade, horario, cidade) {
		const listaInvestimentos = obterListaInvestimentosSaga();
		if (!listaInvestimentos.length) {
			return '';
		}

		const normalizar = normalizarTextoSaga;
		const unidadeN = normalizar(unidade);
		const horarioN = normalizar(horario);
		const cidadeN = normalizar(cidade);
		const modalidade = obterSlugModalidadeSaga();
		let encontrado = null;

		if (modalidade === 'ead') {
			if (cidadeN && unidadeN) {
				encontrado = listaInvestimentos.find(function(item) {
					return normalizar(item.cidade) === cidadeN && normalizar(item.unidade) === unidadeN;
				}) || null;
			}
			if (!encontrado && unidadeN) {
				encontrado = listaInvestimentos.find(function(item) {
					return normalizar(item.unidade) === unidadeN;
				}) || null;
			}
			if (!encontrado && cidadeN) {
				encontrado = listaInvestimentos.find(function(item) {
					return normalizar(item.cidade) === cidadeN;
				}) || null;
			}
		} else {
			if (unidadeN && horarioN) {
				encontrado = listaInvestimentos.find(function(item) {
					return normalizar(item.unidade) === unidadeN && normalizar(item.horario) === horarioN;
				}) || null;
			}
			if (!encontrado && unidadeN) {
				encontrado = listaInvestimentos.find(function(item) {
					return normalizar(item.unidade) === unidadeN;
				}) || null;
			}
		}

		return encontrado ? obterIdOfertaInvestimento(encontrado) : '';
	}

	function obterOfertaAtualSaga(contextoSelecao) {
		const idCursoEl = document.getElementById('idCurso');
		const ofertaDaTela = idCursoEl ? String(idCursoEl.textContent || '').trim() : '';
		const modalidade = obterSlugModalidadeSaga();

		const cidadeIds = contextoSelecao && Array.isArray(contextoSelecao.cidadeIds) && contextoSelecao.cidadeIds.length
			? contextoSelecao.cidadeIds
			: ['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf'];
		const turnoIds = contextoSelecao && Array.isArray(contextoSelecao.turnoIds) && contextoSelecao.turnoIds.length
			? contextoSelecao.turnoIds
			: ['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf'];
		const selecaoUnidade = obterSelecaoUnidadeSaga(contextoSelecao);

		const cidade = obterPrimeiroTextoSelecionado(cidadeIds);
		const unidade = selecaoUnidade.texto;
		const horario = obterPrimeiroTextoSelecionado(turnoIds);
		const cidadeValor = obterPrimeiroValorSelecionado(cidadeIds);
		const unidadeValor = selecaoUnidade.valor;
		const horarioValor = obterPrimeiroValorSelecionado(turnoIds);
		const normalizar = normalizarTextoSaga;

		const cidadeCandidatas = [normalizar(cidade), normalizar(cidadeValor)].filter(Boolean);
		const unidadeCandidatas = [normalizar(unidade), normalizar(unidadeValor)].filter(Boolean);
		const horarioCandidatos = [normalizar(horario), normalizar(horarioValor)].filter(Boolean);

		if (modalidade === 'presencial' && !unidadeCandidatas.length) {
			if (idCursoEl) {
				idCursoEl.textContent = '';
			}
			return '';
		}

		if (ofertaDaTela && modalidade !== 'presencial') {
			return ofertaDaTela;
		}

		const listaInvestimentos = obterListaInvestimentosSaga();
		if (!listaInvestimentos.length) {
			return '';
		}

		const buscarOferta = (matcher) => {
			const encontrado = listaInvestimentos.find(matcher);
			return encontrado ? obterIdOfertaInvestimento(encontrado) : '';
		};

		const normalizarTurnoSaga = (valor) => {
			const texto = normalizar(valor);
			if (!texto) {
				return '';
			}
			if (texto.indexOf('manha') !== -1 || texto.indexOf('matut') !== -1) {
				return 'manha';
			}
			if (texto.indexOf('tarde') !== -1 || texto.indexOf('vespert') !== -1) {
				return 'tarde';
			}
			if (texto.indexOf('noite') !== -1 || texto.indexOf('noturn') !== -1) {
				return 'noite';
			}
			if (texto.indexOf('integral') !== -1) {
				return 'integral';
			}
			return texto;
		};

		const emLista = (valor, candidatas) => {
			if (!valor || !Array.isArray(candidatas) || !candidatas.length) {
				return false;
			}
			return candidatas.indexOf(valor) !== -1;
		};

		const turnoCandidatos = Array.from(new Set(horarioCandidatos.map((valor) => normalizarTurnoSaga(valor)).filter(Boolean)));

		let ofertaEncontrada = '';
		if (modalidade === 'ead') {
			if (!unidadeCandidatas.length) {
				return '';
			}
			ofertaEncontrada = (
				buscarOferta((item) => {
					const cidadeItem = normalizar(item.cidade);
					const unidadeItem = normalizar(item.unidade);
					return emLista(unidadeItem, unidadeCandidatas) && emLista(cidadeItem, cidadeCandidatas);
				}) ||
				buscarOferta((item) => emLista(normalizar(item.unidade), unidadeCandidatas))
			);

			if (!ofertaEncontrada && unidadeCandidatas.length) {
				ofertaEncontrada = resolverIdOfertaPorInvestimentos(
					unidade || unidadeValor,
					horario || horarioValor,
					cidade || cidadeValor
				);
			}
		} else {
			if (!unidadeCandidatas.length) {
				return '';
			}

			if (horarioCandidatos.length) {
				ofertaEncontrada = buscarOferta((item) => {
					const unidadeItem = normalizar(item.unidade);
					const horarioItem = normalizar(item.horario);
					const turnoItem = normalizarTurnoSaga(item.horario);
					return emLista(unidadeItem, unidadeCandidatas) && (
						emLista(horarioItem, horarioCandidatos) ||
						emLista(turnoItem, turnoCandidatos)
					);
				});
			}

			if (!ofertaEncontrada && turnoCandidatos.length) {
				const ofertasDoTurno = listaInvestimentos
					.filter((item) => emLista(normalizar(item.unidade), unidadeCandidatas) && emLista(normalizarTurnoSaga(item.horario), turnoCandidatos))
					.map((item) => obterIdOfertaInvestimento(item))
					.filter(Boolean);
				const ofertasTurnoUnicas = Array.from(new Set(ofertasDoTurno));
				if (ofertasTurnoUnicas.length === 1) {
					ofertaEncontrada = ofertasTurnoUnicas[0];
				}
			}

			if (!ofertaEncontrada && unidadeCandidatas.length) {
				ofertaEncontrada = resolverIdOfertaPorInvestimentos(
					unidade || unidadeValor,
					horario || horarioValor,
					cidade || cidadeValor
				);
			}
		}

		if (ofertaEncontrada && idCursoEl) {
			idCursoEl.textContent = ofertaEncontrada;
		}

		if (ofertaEncontrada) {
			return ofertaEncontrada;
		}

		if (modalidade === 'presencial' && !unidadeCandidatas.length) {
			if (idCursoEl) {
				idCursoEl.textContent = '';
			}
			return '';
		}

		const chaveForma = contextoSelecao && contextoSelecao.formaChave
			? normalizarChaveFormaIngressoSaga(contextoSelecao.formaChave)
			: normalizarChaveFormaIngressoSaga(obterFormaIngressoAtivaSaga());

		let camposOferta = ['offer-id-vestibular', 'offer-id', 'offer-i'];
		if (chaveForma === 'enem') {
			camposOferta = ['offer-id', 'offer-id-vestibular', 'offer-i'];
		} else if (chaveForma === 'segunda') {
			camposOferta = ['offer-id', 'offer-id-vestibular', 'offer-i'];
		} else if (chaveForma === 'transf') {
			camposOferta = ['offer-i', 'offer-id', 'offer-id-vestibular'];
		}

		for (let i = 0; i < camposOferta.length; i++) {
			const inputOferta = document.getElementById(camposOferta[i]);
			const valorOferta = inputOferta && typeof inputOferta.value === 'string' ? inputOferta.value.trim() : '';
			if (valorOferta) {
				return valorOferta;
			}
		}

		return '';
	}

	function coletarDadosContatoBtnComprar(contextoSelecao) {
		const offerFallback = obterOfertaAtualSaga(contextoSelecao);
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
		const formConfigs = {
			vestibular: {
				firstname: 'firstname-vestibular',
				lastname: 'lastname-vestibular',
				email: 'email-vestibular',
				phone: 'phone-vestibular',
				offerId: () => getInputValue('offer-id-vestibular') || offerFallback,
				nota: () => getInputValue('nota-score-vestibular') || getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-vestibular') || 'VESTIBULAR'
			},
			enem: {
				firstname: 'firstname-enem',
				lastname: 'lastname-enem',
				email: 'email-enem',
				phone: 'phone-enem',
				offerId: () => getInputValue('offer-id') || offerFallback,
				nota: () => getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-enem') || 'ENEM'
			},
			segunda: {
				firstname: 'firstname-segunda',
				lastname: 'lastname-segunda',
				email: 'email-segunda',
				phone: 'phone-segunda',
				offerId: () => getInputValue('offer-id') || offerFallback,
				nota: () => getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-segunda') || 'SEGUNDA GRADUAÇÃO'
			},
			transf: {
				firstname: 'firstname-transf',
				lastname: 'lastname-transf',
				email: 'email-transf',
				phone: 'phone-transf',
				offerId: () => getInputValue('offer-i') || getInputValue('offer-id') || offerFallback,
				nota: () => getInputValue('nota-score'),
				forma: () => getInputValue('graduacao-forma-ingresso-transf') || 'TRANSFERÊNCIA'
			}
		};

		const ordemBusca = [];
		const chavePrioritaria = contextoSelecao && contextoSelecao.formaChave
			? normalizarChaveFormaIngressoSaga(contextoSelecao.formaChave)
			: normalizarChaveFormaIngressoSaga(obterFormaIngressoAtivaSaga());
		if (formConfigs[chavePrioritaria]) {
			ordemBusca.push(chavePrioritaria);
		}
		Object.keys(formConfigs).forEach((chave) => {
			if (ordemBusca.indexOf(chave) === -1) {
				ordemBusca.push(chave);
			}
		});

		for (let i = 0; i < ordemBusca.length; i++) {
			const config = formConfigs[ordemBusca[i]];
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

	function obterDadosContatoSaga(contextoSelecao) {
		const formaChave = contextoSelecao && contextoSelecao.formaChave
			? normalizarChaveFormaIngressoSaga(contextoSelecao.formaChave)
			: normalizarChaveFormaIngressoSaga(obterFormaIngressoAtivaSaga());

		if (window.vestibularExtraData && formaChave === 'vestibular') {
			const dados = Object.assign({}, window.vestibularExtraData);
			delete window.vestibularExtraData;
			return dados;
		}

		const contato = coletarDadosContatoBtnComprar(contextoSelecao);
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

		

		sagaRequestInFlight = fetch(<?php echo wp_json_encode($graduacao_send_api_url); ?>, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(requestData)
		})
			.then(async (response) => {
				let responseText = '';
				try {
					responseText = await response.text();
				} catch (err) {
					responseText = '';
				}

				let parsed = {};
				if (responseText) {
					try {
						parsed = JSON.parse(responseText);
					} catch (err) {
						parsed = { raw: responseText };
					}
				}

				if (!response.ok) {
					const mensagemErro = obterMensagemErroSaga(parsed) || `Falha no envio para o SAGA (${response.status}).`;
					const erro = new Error(mensagemErro);
					erro.status = response.status;
					erro.payload = parsed;
					throw erro;
				}

				if (!parsed || typeof parsed !== 'object') {
					return {};
				}

				return parsed;
			})
			.finally(() => {
				sagaRequestInFlight = null;
			});

		return sagaRequestInFlight;
	}

	function obterRedirectUrlSaga(payload) {
		return extrairRedirectUrlSaga(payload);
	}

	function obterMensagemErroSaga(payload) {
		if (!payload) {
			return '';
		}
		if (typeof payload === 'string') {
			return payload.trim();
		}
		if (typeof payload !== 'object') {
			return '';
		}
		const mensagem = payload.message || payload.error || (payload.data && (payload.data.message || payload.data.error));
		return typeof mensagem === 'string' ? mensagem.trim() : '';
	}

	function obterOfertasAlternativasSaga(contextoSelecao) {
		const listaInvestimentos = obterListaInvestimentosSaga();
		if (!listaInvestimentos.length) {
			return [];
		}

		const normalizar = normalizarTextoSaga;
		const normalizarTurno = (valor) => {
			const texto = normalizar(valor);
			if (!texto) {
				return '';
			}
			if (texto.indexOf('manha') !== -1 || texto.indexOf('matut') !== -1) {
				return 'manha';
			}
			if (texto.indexOf('tarde') !== -1 || texto.indexOf('vespert') !== -1) {
				return 'tarde';
			}
			if (texto.indexOf('noite') !== -1 || texto.indexOf('noturn') !== -1) {
				return 'noite';
			}
			if (texto.indexOf('integral') !== -1) {
				return 'integral';
			}
			return texto;
		};
		const emLista = (valor, candidatas) => {
			if (!valor || !Array.isArray(candidatas) || !candidatas.length) {
				return false;
			}
			return candidatas.indexOf(valor) !== -1;
		};

		const modalidade = obterSlugModalidadeSaga();
		const cidadeIds = contextoSelecao && Array.isArray(contextoSelecao.cidadeIds) && contextoSelecao.cidadeIds.length
			? contextoSelecao.cidadeIds
			: ['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf'];
		const turnoIds = contextoSelecao && Array.isArray(contextoSelecao.turnoIds) && contextoSelecao.turnoIds.length
			? contextoSelecao.turnoIds
			: ['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf'];
		const selecaoUnidade = obterSelecaoUnidadeSaga(contextoSelecao);

		const cidadeTexto = obterPrimeiroTextoSelecionado(cidadeIds);
		const cidadeValor = obterPrimeiroValorSelecionado(cidadeIds);
		const unidadeTexto = selecaoUnidade.texto;
		const unidadeValor = selecaoUnidade.valor;
		const horarioTexto = obterPrimeiroTextoSelecionado(turnoIds);
		const horarioValor = obterPrimeiroValorSelecionado(turnoIds);

		const cidadeCandidatas = [normalizar(cidadeTexto), normalizar(cidadeValor)].filter(Boolean);
		const unidadeCandidatas = [normalizar(unidadeTexto), normalizar(unidadeValor)].filter(Boolean);
		const horarioCandidatos = [normalizar(horarioTexto), normalizar(horarioValor)].filter(Boolean);
		const turnoCandidatos = Array.from(new Set(horarioCandidatos.map((valor) => normalizarTurno(valor)).filter(Boolean)));

		if (!unidadeCandidatas.length) {
			return [];
		}

		let candidatos = [];
		if (modalidade === 'ead') {
			candidatos = listaInvestimentos.filter((item) => {
				const unidadeItem = normalizar(item.unidade);
				const cidadeItem = normalizar(item.cidade);
				if (!emLista(unidadeItem, unidadeCandidatas)) {
					return false;
				}
				if (!cidadeCandidatas.length) {
					return true;
				}
				return emLista(cidadeItem, cidadeCandidatas);
			});
		} else {
			if (horarioCandidatos.length || turnoCandidatos.length) {
				candidatos = listaInvestimentos.filter((item) => {
					const unidadeItem = normalizar(item.unidade);
					const horarioItem = normalizar(item.horario);
					const turnoItem = normalizarTurno(item.horario);
					if (!emLista(unidadeItem, unidadeCandidatas)) {
						return false;
					}
					return emLista(horarioItem, horarioCandidatos) || emLista(turnoItem, turnoCandidatos);
				});
			}

			if (!candidatos.length) {
				candidatos = listaInvestimentos.filter((item) => emLista(normalizar(item.unidade), unidadeCandidatas));
			}
		}

		return Array.from(new Set(candidatos
			.map((item) => obterIdOfertaInvestimento(item))
			.filter(Boolean)));
	}

	function enviarDadosParaSagaComFallback(requestData, contextoSelecao) {
		if (!requestData || typeof requestData !== 'object') {
			return Promise.reject(new Error('Payload inválido para o SAGA.'));
		}

		const ofertaInicial = String(requestData.oferta || '').trim();
		const ofertasAlternativas = obterOfertasAlternativasSaga(contextoSelecao);
		const ofertasTentativa = Array.from(new Set([ofertaInicial].concat(ofertasAlternativas).filter(Boolean)));

		if (!ofertasTentativa.length) {
			return Promise.reject(new Error('Selecione as opções acima.'));
		}

		const tentarEnvio = (indice) => {
			const payloadAtual = Object.assign({}, requestData, { oferta: ofertasTentativa[indice] });
			return enviarDadosParaSaga(payloadAtual).then((data) => {
				const redirectUrl = obterRedirectUrlSaga(data);
				if (redirectUrl) {
					return { redirectUrl, data };
				}
				const mensagem = obterMensagemErroSaga(data) || 'URL de redirecionamento não retornada pelo SAGA.';
				const erro = new Error(mensagem);
				erro.payload = data;
				throw erro;
			}).catch((erro) => {
				if (indice < ofertasTentativa.length - 1) {
					return tentarEnvio(indice + 1);
				}
				throw erro;
			});
		};

		return tentarEnvio(0);
	}

	function exibirErroSagaNoBotao(botao, mensagem) {
		document.querySelectorAll('.selecioneError').forEach((el) => el.remove());
		const erroEl = document.createElement('p');
		erroEl.className = 'selecioneError';
		erroEl.textContent = mensagem || 'Selecione as opções acima.';
		botao.insertAdjacentElement('afterend', erroEl);
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


	
	// INICIO: Regra exclusiva para iPhone (Safari) em paginas digitais e presenciais.
	function extrairRedirectUrlSaga(responseData) {
		if (typeof responseData === 'string') {
			return responseData
				.trim()
				.replace(/^['"]+|['"]+$/g, '')
				.replace(/&amp;/gi, '&');
		}

		if (!responseData || typeof responseData !== 'object') {
			return '';
		}

		const candidatos = [
			responseData.redirect_url,
			responseData.redirectUrl,
			responseData.url,
			typeof responseData.data === 'string' ? responseData.data : '',
			responseData.data && responseData.data.redirect_url,
			responseData.data && responseData.data.redirectUrl,
			responseData.data && responseData.data.url,
			responseData.result && responseData.result.redirect_url,
			responseData.result && responseData.result.redirectUrl,
			responseData.result && responseData.result.url
		];

		for (const candidato of candidatos) {
			if (typeof candidato !== 'string') {
				continue;
			}

			const limpa = candidato
				.trim()
				.replace(/^['"]+|['"]+$/g, '')
				.replace(/&amp;/gi, '&');

			if (limpa) {
				return limpa;
			}
		}

		return '';
	}

	function redirecionarUrlApiSaga(redirectUrl) {
		const rawUrl = String(redirectUrl || '')
			.trim()
			.replace(/^['"]+|['"]+$/g, '')
			.replace(/&amp;/gi, '&');
		if (!rawUrl) {
			return;
		}

		const paginaDigital = window.location.href.indexOf('-digital') > -1;
		const paginaAoVivo = window.location.href.indexOf('-aovivo') > -1;
		const paginaPresencial = !paginaDigital && !paginaAoVivo;
		const ua = navigator.userAgent || '';
		const iphoneSafari = /iPhone/i.test(ua)
			&& /Safari/i.test(ua)
			&& !/CriOS|FxiOS|EdgiOS|OPiOS/i.test(ua);

		if (!((paginaDigital || paginaPresencial) && iphoneSafari)) {
			window.location.href = rawUrl;
			return;
		}

		let destino = rawUrl;
		if (destino.indexOf('//') === 0) {
			destino = `${window.location.protocol}${destino}`;
		} else if (!/^[a-z][a-z0-9+\-.]*:/i.test(destino)) {
			if (destino.charAt(0) === '/') {
				destino = `${window.location.origin}${destino}`;
			} else {
				destino = `https://${destino.replace(/^\/+/, '')}`;
			}
		}

		try {
			destino = encodeURI(destino);
		} catch (error) {
			// Mantem a URL original se ja vier codificada.
		}

		// Redireciona sempre na aba atual.
		window.location.href = destino;
	}
	// FIM: Regra exclusiva para iPhone (Safari) em paginas digitais e presenciais.



	async function enviarHubspotBtnComprar(contextoSelecao) {
		if (window.btnComprarHubspotSent) {
			return;
		}
		if (hubspotBtnComprarInFlight) {
			return hubspotBtnComprarInFlight;
		}

		const contato = coletarDadosContatoBtnComprar(contextoSelecao);
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
				{ name: 'phone', value: phoneDigits },
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
			return true;
		})
		.catch((error) => {
			console.error('Falha ao enviar dados para o HubSpot via btnComprar:', error);
			return false;
		})
		.finally(() => {
			hubspotBtnComprarInFlight = null;
		});

		return hubspotBtnComprarInFlight;
	}

	document.querySelectorAll('#btnComprar').forEach(function(btn) {
		btn.addEventListener('click', async function(e) {
			e.preventDefault();
			const contextoSelecao = obterContextoSelecaoSaga(btn);
				const selecaoUnidade = obterSelecaoUnidadeSaga(contextoSelecao);

			await enviarHubspotBtnComprar(contextoSelecao);

			const oferta = obterOfertaAtualSaga(contextoSelecao);
			if (!oferta) {
				exibirErroSagaNoBotao(btn, 'Selecione unidade e turno para continuar.');
				return;
			}
			const descricao_curso = [...new Set(Array.from(document.querySelectorAll('.entry-title'))
				.map(function(el) { return el.textContent.trim(); }))].join(' ');

			// Preparar dados base para sendAPI_interna.php
			const contatoSaga = obterDadosContatoSaga(contextoSelecao) || {};
			const formaIngressoAtiva = contextoSelecao.formaIngressoHint || obterFormaIngressoAtivaSaga();

			const requestData = {
				oferta: oferta,
				descricao_curso: descricao_curso,
				nome: contatoSaga.nome || '',
				email: contatoSaga.email || '',
				telefone: contatoSaga.telefone || '',
				forma_ingresso: contatoSaga.forma || contatoSaga.forma_ingresso || formaIngressoAtiva || '',
				modalidade: obterSlugModalidadeSaga(),
				cidade: obterPrimeiroTextoSelecionado(contextoSelecao.cidadeIds),
					unidade: selecaoUnidade.texto,
				turno: obterPrimeiroTextoSelecionado(contextoSelecao.turnoIds)
			};

			enviarDadosParaSagaComFallback(requestData, contextoSelecao)
			.then(resultado => {
				redirecionarUrlApiSaga(resultado.redirectUrl);
			})
			.catch(error => {
				const mensagemErro = obterMensagemErroSaga(error && error.payload) || (error && error.message) || '';
				const contexto = `Oferta: ${requestData.oferta || 'nao definida'} | Unidade: ${requestData.unidade || '-'} | Turno: ${requestData.turno || '-'}`;
				exibirErroSagaNoBotao(btn, mensagemErro || `Nao foi possivel iniciar a matricula agora. ${contexto}`);
			});
		});
	});

//    DISPARO DIRETO DOS MODALS AtÉ O SAGA 
	document.querySelectorAll('.btnComprar-modal').forEach(function(btn) {
		btn.addEventListener('click', async function(e) {
			e.preventDefault();
			const contextoSelecao = obterContextoSelecaoSaga(btn);
			const selecaoUnidade = obterSelecaoUnidadeSaga(contextoSelecao);
			await enviarHubspotBtnComprar(contextoSelecao);

			const oferta = obterOfertaAtualSaga(contextoSelecao);
			if (!oferta) {
				exibirErroSagaNoBotao(btn, 'Selecione unidade e turno para continuar.');
				return;
			}
            
            const descricao_curso = [...new Set(Array.from(document.querySelectorAll('.entry-title'))
                .map(function(el) { return el.textContent.trim(); }))].join(' ');
            
            // Preparar dados para sendAPI_interna.php
            const requestData = {
                oferta: oferta,
				descricao_curso: descricao_curso,
				modalidade: obterSlugModalidadeSaga(),
				cidade: obterPrimeiroTextoSelecionado(contextoSelecao.cidadeIds),
				unidade: selecaoUnidade.texto,
				turno: obterPrimeiroTextoSelecionado(contextoSelecao.turnoIds)
            };

			const contatoSaga = obterDadosContatoSaga(contextoSelecao);
			if (contatoSaga) {
				if (contatoSaga.nome) requestData.nome = contatoSaga.nome;
				if (contatoSaga.email) requestData.email = contatoSaga.email;
				if (contatoSaga.telefone) requestData.telefone = contatoSaga.telefone;
				if (contatoSaga.forma) requestData.forma_ingresso = contatoSaga.forma;
			}
			if (!requestData.forma_ingresso) {
				requestData.forma_ingresso = contextoSelecao.formaIngressoHint || obterFormaIngressoAtivaSaga();
			}
            
			// Enviar para API do SAGA
			enviarDadosParaSagaComFallback(requestData, contextoSelecao)
			.then(resultado => {
				redirecionarUrlApiSaga(resultado.redirectUrl);
            })
            .catch(error => {
				const mensagemErro = obterMensagemErroSaga(error && error.payload) || (error && error.message) || '';
				const contexto = `Oferta: ${requestData.oferta || 'nao definida'} | Unidade: ${requestData.unidade || '-'} | Turno: ${requestData.turno || '-'}`;
				exibirErroSagaNoBotao(btn, mensagemErro || `Nao foi possivel iniciar a matricula agora. ${contexto}`);
            });
        });
    });

	function atualizarFloatingOfferOfertaId(contextoSelecao) {
		const ofertaIdEl = document.getElementById('floatingOfferOfertaId');
		if (!ofertaIdEl) {
			return;
		}

		const ctx = contextoSelecao || {
			unidadeIds: ['floatingOfferUnidade'],
			turnoIds: ['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf'],
			cidadeIds: ['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf']
		};
		const selecaoUnidade = obterSelecaoUnidadeSaga(ctx);
		const unidade = selecaoUnidade.texto || selecaoUnidade.valor || '';
		const horario = obterPrimeiroTextoSelecionado(ctx.turnoIds) || obterPrimeiroValorSelecionado(ctx.turnoIds);
		const cidade = obterPrimeiroTextoSelecionado(ctx.cidadeIds) || obterPrimeiroValorSelecionado(ctx.cidadeIds);
		let oferta = resolverIdOfertaPorInvestimentos(unidade, horario, cidade);
		if (!oferta) {
			oferta = obterOfertaAtualSaga(ctx);
		}

		if (oferta) {
			ofertaIdEl.textContent =  oferta;
			ofertaIdEl.style.display = 'block';
		} else {
			ofertaIdEl.textContent = 'ID da oferta: --';
			ofertaIdEl.style.display = unidade ? 'block' : 'none';
		}

		const card = document.getElementById('floatingOfferCard');
		const form = card ? card.querySelector('.sagaHubForm') : null;
		if (form) {
			const offerIdInput = form.querySelector('input[name="offer_id"]');
			const offerUnidadeInput = form.querySelector('input[name="offer_unidade"]');
			if (offerIdInput) {
				offerIdInput.value = oferta || '';
			}
			if (offerUnidadeInput) {
				offerUnidadeInput.value = selecaoUnidade.texto || '';
			}
		}
	}

	function vincularAtualizacaoFloatingOfferOfertaId() {
		const idsMonitorados = [
			'unidade', 'unidadeEnem', 'unidadeSegunda', 'unidadeTransf',
			'horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf',
			'cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf',
			'floatingOfferUnidade'
		];

		idsMonitorados.forEach(function(id) {
			const el = document.getElementById(id);
			if (!el || el.dataset.floatingOfferOfertaBind === '1') {
				return;
			}

			el.addEventListener('change', function() {
				let ctx = null;
				if (id === 'floatingOfferUnidade') {
					ctx = {
						unidadeIds: ['floatingOfferUnidade'],
						turnoIds: ['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf'],
						cidadeIds: ['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf']
					};
				}
				atualizarFloatingOfferOfertaId(ctx);
			});
			el.dataset.floatingOfferOfertaBind = '1';
		});
	}

	window.obterOfertaAtualSaga = obterOfertaAtualSaga;
	window.resolverIdOfertaPorInvestimentos = resolverIdOfertaPorInvestimentos;
	window.atualizarFloatingOfferOfertaId = atualizarFloatingOfferOfertaId;

	vincularAtualizacaoFloatingOfferOfertaId();
	atualizarFloatingOfferOfertaId();

	if (typeof MutationObserver !== 'undefined' && document.body) {
		const observerFloatingOferta = new MutationObserver(function() {
			vincularAtualizacaoFloatingOfferOfertaId();
		});
		observerFloatingOferta.observe(document.body, { childList: true, subtree: true });
		setTimeout(function() {
			observerFloatingOferta.disconnect();
		}, 60000);
	}
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
    
	// Garantir que valorSDesconto siga desconto de 60% ao carregar a página
    function atualizarValorSDescontoInicial() {
        const valorSaga = document.getElementById('valorSaga');
        const valorSDesconto = document.getElementById('valorSDesconto');
        if (valorSaga && valorSDesconto) {
            const valorSagaText = valorSaga.textContent.replace(/\./g, '').replace(',', '.');
            const valorSagaNumero = parseFloat(valorSagaText);
			const valorSDescontoNumero = valorSagaNumero / 0.4;
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
			// Detecta modalidade pela URL
			const isDigital = window.location.href.includes('-digital');
			const isAoVivo = window.location.href.includes('-aovivo');
            
            // Captura modalidade
            let modalidade = 'presencial'; // padrão
            const modalidadeElement = document.getElementById('modalidadeBox');
            if (modalidadeElement) {
                const modalidadeTexto = modalidadeElement.textContent.trim();
				const modalidadeTextoNormalizado = modalidadeTexto.toLowerCase();
				if (modalidadeTextoNormalizado.includes('ao vivo') || modalidadeTextoNormalizado.includes('semipres')) {
					modalidade = 'digital_ao_vivo';
				} else if (modalidadeTextoNormalizado.includes('digital')) {
					modalidade = 'ead';
				} else {
					modalidade = 'presencial';
				}
			} else if (isAoVivo) {
				modalidade = 'digital_ao_vivo';
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
		// Detecta modalidade pela URL
		const isDigital = window.location.href.includes('-digital');
		const isAoVivo = window.location.href.includes('-aovivo');
        
        // Captura modalidade
        let modalidade = 'presencial'; // padrão
        const modalidadeElement = document.getElementById('modalidadeBox');
        if (modalidadeElement) {
            const modalidadeTexto = modalidadeElement.textContent.trim();
			const modalidadeTextoNormalizado = modalidadeTexto.toLowerCase();
			if (modalidadeTextoNormalizado.includes('ao vivo') || modalidadeTextoNormalizado.includes('semipres')) {
				modalidade = 'digital_ao_vivo';
			} else if (modalidadeTextoNormalizado.includes('digital')) {
				modalidade = 'ead';
			} else {
				modalidade = 'presencial';
			}
		} else if (isAoVivo) {
			modalidade = 'digital_ao_vivo';
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
	// Incluir unidade apenas para presencial
	try {
		if (dados && dados.modalidade === 'presencial' && dados.unidade) {
			window.vestibularExtraData.unidade = dados.unidade;
		}
	} catch (e) {}
    
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
		if ((t.includes('ao vivo') || t.includes('semipresencial')) && !t.includes('ead')) return 'digital_ao_vivo';
		if (t.includes('digital')) return 'ead';
    if (t.includes('presencial')) return 'presencial';
		if (window.location.href.indexOf('-aovivo') > -1) return 'digital_ao_vivo';
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
		modalidade: getModalidade(),                // 'ead' | 'digital_ao_vivo' | 'presencial'
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
		if ((t.indexOf('ao vivo') > -1 || t.indexOf('semipresencial') > -1) && t.indexOf('ead') === -1) return 'digital_ao_vivo';
		if (t.indexOf('digital') > -1) return 'ead';
		if (t.indexOf('presencial') > -1) return 'presencial';
		if (window.location.href.indexOf('-aovivo') > -1) return 'digital_ao_vivo';
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
			modalidade: getModalidade(),               // 'ead' | 'digital_ao_vivo' | 'presencial'
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
		if ((t.indexOf('ao vivo') > -1 || t.indexOf('semipresencial') > -1) && t.indexOf('ead') === -1) return 'digital_ao_vivo';
		if (t.indexOf('digital') > -1) return 'ead';
		if (t.indexOf('presencial') > -1) return 'presencial';
		if (window.location.href.indexOf('-aovivo') > -1) return 'digital_ao_vivo';
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
	const isSemipresencial = modalidadeSlug.indexOf('semipresencial') !== -1 || modalidadeSlug.indexOf('digitalaovivo') !== -1 || modalidadeSlug.indexOf('ao vivo') !== -1;
	const isPresencial = modalidadeSlug.indexOf('presencial') !== -1 && !isSemipresencial;

	const MAPA_PARCELA_SEMIPRESENCIAL_18X = {
		'298.50': '199,00',
		'448.50': '299,00',
		'598.50': '399,00'
	};
	const MAPA_PARCELA_PRESENCIAL_18X = {
		'448.50': '299,00',
		'598.50': '399,00',
		'898.50': '599,00'
	};
	const ateFinalElements = document.querySelectorAll('.ateFinal');
	const TEXTO_CUPOM = 'até o fim do curso mediante aplicação do cupom: <i><b>AGORA99</b></i>';
	const TEXTO_PROVA = 'até o Final do curso<br>O valor varia conforme resultado na prova';
	const TEXTO_ENEM = 'até o Final do curso<br>O valor varia conforme resultado ENEM.';
	const TEXTO_CR = 'até o Final do curso<br>O valor varia conforme a média do seu CR.';

	function paraNumeroMoeda(valor) {
		if (valor === null || valor === undefined) return NaN;
		var texto = String(valor).trim();
		if (!texto) return NaN;

		if (/^-?\d+(\.\d+)?$/.test(texto)) {
			var numeroDireto = parseFloat(texto);
			return isFinite(numeroDireto) ? numeroDireto : NaN;
		}

		texto = texto
			.replace(/R\$\s*/i, '')
			.replace(/\./g, '')
			.replace(',', '.')
			.replace(/[^0-9.-]/g, '');

		var numero = parseFloat(texto);
		return isFinite(numero) ? numero : NaN;
	}

	function aplicarRegraPresencial18x() {
		if (!isPresencial) return;

		const alvo = document.getElementById('valorCDesconto');
		if (!alvo) return;

		var valorBase = paraNumeroMoeda(alvo.getAttribute('data-valor-base'));
		if (!isFinite(valorBase) || valorBase <= 0) {
			valorBase = paraNumeroMoeda(alvo.textContent);
		}
		if (!isFinite(valorBase) || valorBase <= 0) return;

		var chaveParcela12x = Number(valorBase).toFixed(2);
		if (!Object.prototype.hasOwnProperty.call(MAPA_PARCELA_PRESENCIAL_18X, chaveParcela12x)) return;

		alvo.textContent = MAPA_PARCELA_PRESENCIAL_18X[chaveParcela12x];
		var prefixoParcela = document.querySelector('.versaoGlobal .valorParcela .dePorNovo');
		if (prefixoParcela) {
			prefixoParcela.textContent = '18x de ';
		}
	}

	function travarValorVestibularSemip() {
		if (!isSemipresencial) return;
		const alvo = document.getElementById('valorCDesconto');
		if (!alvo) return;

		var valorBase = paraNumeroMoeda(alvo.getAttribute('data-valor-base'));
		if (!isFinite(valorBase) || valorBase <= 0) {
			valorBase = paraNumeroMoeda(alvo.textContent);
		}
		if (!isFinite(valorBase) || valorBase <= 0) return;

		var chaveParcela12x = Number(valorBase).toFixed(2);
		var prefixoParcela = document.querySelector('.versaoGlobal .valorParcela .dePorNovo');
		if (Object.prototype.hasOwnProperty.call(MAPA_PARCELA_SEMIPRESENCIAL_18X, chaveParcela12x)) {
			alvo.textContent = MAPA_PARCELA_SEMIPRESENCIAL_18X[chaveParcela12x];
			if (prefixoParcela) {
				prefixoParcela.textContent = '18x de ';
			}
		} else {
			alvo.textContent = Number(valorBase).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
			if (prefixoParcela) {
				prefixoParcela.textContent = '18x de ';
			}
		}
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
		aplicarRegraPresencial18x();
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
		aplicarRegraPresencial18x();
		travarValorVestibularSemip();
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
- Resolver o curso atual por nome/mneumonico usando apenas dados da API.
- Aplicar a mesma regra de oferta usada na home.

Rollback simples:
- Remover TODO o conteudo entre os marcadores:
  INICIO BLOCO TEMPORARIO: CARD FIXO LATERAL DIREITA (ROLLBACK FACIL)
  FIM BLOCO TEMPORARIO: CARD FIXO LATERAL DIREITA (ROLLBACK FACIL)

Redirect pos-envio (temporario):
- Busque REDIRECT_POS_APOS_ENVIO_TEMP no arquivo.
- PHP: $floating_card_redirect_pos_apos_envio_url (URL) e _ativo (liga/desliga).
- URL atual: https://inscricao.unisuam.edu.br/pos
-->

<?php
	/*
	 * PREPARACAO SERVER-SIDE TEMPORARIA
	 * - Le os dados da pagina atual (titulo/mneumonico/modalidade).
	 * - Usa somente dados vindos da API carregados em $data.
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

$preco_numerico_oferta = function($valor) {
	if ($valor === null || $valor === '') {
		return -1;
	}

	if (is_numeric($valor)) {
		return (float) $valor;
	}

	$texto = (string) $valor;
	$texto = str_replace('R$', '', $texto);
	$texto = preg_replace('/[^0-9,\.\-]/', '', $texto);

	if (strpos($texto, ',') !== false) {
		$texto = str_replace('.', '', $texto);
		$texto = str_replace(',', '.', $texto);
	}

	$numero = (float) $texto;
	return is_finite($numero) ? $numero : -1;
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

// Leitura de dadosHome.json removida: card lateral usa somente campos vindos de API.

$oferta_info_modalidade_api = is_string($modalidade_api_label) ? trim($modalidade_api_label) : '';
if ($oferta_info_modalidade_api === '') {
	$oferta_info_modalidade_api = is_string($modalidade_box_value) ? trim($modalidade_box_value) : '';
}

$normalizar_texto_info_oferta = function($valor) {
	if (is_bool($valor)) {
		return $valor ? '1' : '0';
	}
	if (is_numeric($valor)) {
		$valor = (string) $valor;
	}
	if (!is_string($valor)) {
		return '';
	}
	$valor = wp_strip_all_tags($valor);
	$valor = preg_replace('/\s+/', ' ', trim($valor));
	return is_string($valor) ? $valor : '';
};

$ler_caminho_info_oferta = function($origem, $caminho) {
	$cursor = $origem;
	foreach ($caminho as $parte) {
		if (!is_array($cursor) || !array_key_exists($parte, $cursor)) {
			return null;
		}
		$cursor = $cursor[$parte];
	}
	return $cursor;
};

$buscar_valor_api_info_oferta = function($origem, $caminhos) use ($normalizar_texto_info_oferta, $ler_caminho_info_oferta) {
	foreach ($caminhos as $caminho) {
		$valor = $ler_caminho_info_oferta($origem, $caminho);
		if (is_array($valor)) {
			foreach (array('valor', 'label', 'nome', 'descricao', 'texto') as $chave_texto) {
				if (!array_key_exists($chave_texto, $valor)) {
					continue;
				}
				$texto_item = $normalizar_texto_info_oferta($valor[$chave_texto]);
				if ($texto_item !== '') {
					return $texto_item;
				}
			}
			continue;
		}

		$texto = $normalizar_texto_info_oferta($valor);
		if ($texto !== '') {
			return $texto;
		}
	}

	return '';
};

$buscar_valor_lista_info_oferta = function($lista, $chaves) use ($normalizar_texto_info_oferta) {
	if (!is_array($lista)) {
		return '';
	}

	foreach ($lista as $item_lista) {
		if (!is_array($item_lista)) {
			continue;
		}
		foreach ($chaves as $chave_item) {
			if (!array_key_exists($chave_item, $item_lista)) {
				continue;
			}
			$texto = $normalizar_texto_info_oferta($item_lista[$chave_item]);
			if ($texto !== '') {
				return $texto;
			}
		}
	}

	return '';
};

$oferta_info_duracao_api = $buscar_valor_api_info_oferta(
	$data,
	array(
		array('resumo', 'duracao'),
		array('curso', 'duracao'),
		array('duracao'),
		array('resumo', 'duracao_curso'),
		array('curso', 'duracao_curso'),
		array('resumo', 'tempo'),
		array('curso', 'tempo'),
		array('resumo', 'semestres'),
		array('curso', 'semestres'),
		array('semestres'),
		array('resumo', 'periodos'),
		array('curso', 'periodos'),
		array('periodos')
	)
);

if ($oferta_info_duracao_api === '') {
	$oferta_info_duracao_api = $buscar_valor_lista_info_oferta(
		$data['investimentos'] ?? array(),
		array('duracao', 'semestres', 'periodos', 'prazo')
	);
}

if ($oferta_info_duracao_api !== '') {
	$oferta_info_duracao_api = preg_replace('/\bsemestres?\b/iu', 'meses', $oferta_info_duracao_api);
}

if ($oferta_info_duracao_api !== '' && preg_match('/^\d+$/', $oferta_info_duracao_api)) {
	$oferta_info_duracao_api .= ' meses';
}

$oferta_info_inicio_api = $buscar_valor_api_info_oferta(
	$data,
	array(
		array('resumo', 'próxima-turma'),
		array('curso', 'próxima-turma'),
		array('próxima-turma'),
		array('resumo', 'próxima_turma'),
		array('curso', 'próxima_turma'),
		array('próxima_turma'),
		array('resumo', 'próximaturma'),
		array('curso', 'próximaturma'),
		array('próximaturma'),
		array('resumo', 'proxima-turma'),
		array('curso', 'proxima-turma'),
		array('proxima-turma'),
		array('resumo', 'proxima_turma'),
		array('curso', 'proxima_turma'),
		array('proxima_turma'),
		array('resumo', 'proximaturma'),
		array('curso', 'proximaturma'),
		array('proximaturma'),
		array('resumo', 'inicio'),
		array('curso', 'inicio'),
		array('inicio'),
		array('resumo', 'inicio_aulas'),
		array('curso', 'inicio_aulas'),
		array('inicio_aulas'),
		array('resumo', 'data_inicio'),
		array('curso', 'data_inicio'),
		array('data_inicio'),
		array('resumo', 'inicio_imediato'),
		array('curso', 'inicio_imediato'),
		array('inicio_imediato')
	)
);

if ($oferta_info_inicio_api === '') {
	$oferta_info_inicio_api = $buscar_valor_lista_info_oferta(
		$data['investimentos'] ?? array(),
		array('próxima-turma', 'próxima_turma', 'próximaturma', 'proxima-turma', 'proxima_turma', 'proximaturma', 'inicio', 'inicio_aulas', 'data_inicio', 'inicio_imediato')
	);
}

$oferta_info_inicio_api_norm = function_exists('remove_accents') ? remove_accents($oferta_info_inicio_api) : $oferta_info_inicio_api;
$oferta_info_inicio_api_norm = strtolower(trim((string) $oferta_info_inicio_api_norm));
if (in_array($oferta_info_inicio_api_norm, array('1', 'sim', 'true', 'imediato', 'inicio imediato'), true)) {
	$oferta_info_inicio_api = 'Início imediato';
}

$oferta_info_titulo_api = $buscar_valor_api_info_oferta(
	$data,
	array(
		array('resumo', 'nome_curso'),
		array('resumo', 'curso'),
		array('resumo', 'nome'),
		array('curso', 'nome'),
		array('curso', 'curso'),
		array('curso', 'titulo'),
		array('nome_curso'),
		array('curso_nome'),
		array('nome'),
		array('titulo')
	)
);

if ($oferta_info_titulo_api === '') {
	$oferta_info_titulo_api = trim((string) get_the_title($post_id_oferta));
}

$normalizar_numero_moeda_info_oferta = function($valor) {
	if ($valor === null || $valor === '') {
		return -1;
	}

	if (is_numeric($valor)) {
		$numero = (float) $valor;
		return is_finite($numero) ? $numero : -1;
	}

	$texto = (string) $valor;
	$texto = str_replace('R$', '', $texto);
	$texto = preg_replace('/[^0-9,\.\-]/', '', $texto);

	if (strpos($texto, ',') !== false) {
		$texto = str_replace('.', '', $texto);
		$texto = str_replace(',', '.', $texto);
	}

	$numero = (float) $texto;
	return is_finite($numero) ? $numero : -1;
};

$normalizar_modalidade_card_slug = function($valor) {
	$slug = normalizar_modalidade_slug((string) $valor);
	if ($slug === 'semipresencial') {
		return 'digitalaovivo';
	}
	if ($slug === 'digital') {
		return 'digital';
	}
	return 'presencial';
};

$oferta_info_modalidade_card_api = is_string($modalidade_api_label) ? trim($modalidade_api_label) : '';
$modalidade_card_alvo_slug = $normalizar_modalidade_card_slug($oferta_info_modalidade_card_api !== '' ? $oferta_info_modalidade_card_api : $page_modalidade_slug);

$investimento_card_api = null;
$investimento_card_score = -1;
$investimento_card_valor = -1;

if (!empty($data['investimentos']) && is_array($data['investimentos'])) {
	foreach ($data['investimentos'] as $investimento_item) {
		if (!is_array($investimento_item)) {
			continue;
		}

		$valor_item = $normalizar_numero_moeda_info_oferta(
			$investimento_item['valor']
				?? ($investimento_item['preco']
					?? ($investimento_item['mensalidade']
						?? ($investimento_item['parcela']
							?? ($investimento_item['valor_parcela'] ?? '')
						)
					)
				)
		);
		if ($valor_item <= 0) {
			continue;
		}

		$score_item = 0;
		$parcelas_item = !empty($investimento_item['parcelas']) ? (int) $investimento_item['parcelas'] : 0;
		$modalidade_item_slug = '';
		if (!empty($investimento_item['modalidade'])) {
			$modalidade_item_slug = $normalizar_modalidade_card_slug($investimento_item['modalidade']);
		}
		if ($modalidade_card_alvo_slug !== '' && $modalidade_item_slug !== '' && $modalidade_item_slug === $modalidade_card_alvo_slug) {
			$score_item += 50;
		}
		if ($parcelas_item === 18) {
			$score_item += 40;
		} elseif ($parcelas_item > 0) {
			$score_item += 5;
		}

		if ($score_item > $investimento_card_score || ($score_item === $investimento_card_score && $valor_item > $investimento_card_valor)) {
			$investimento_card_api = $investimento_item;
			$investimento_card_score = $score_item;
			$investimento_card_valor = $valor_item;
		}
	}
}

if ($oferta_info_modalidade_card_api === '' && is_array($investimento_card_api) && !empty($investimento_card_api['modalidade'])) {
	$oferta_info_modalidade_card_api = formatar_modalidade_label($investimento_card_api['modalidade']);
}

if ($oferta_info_modalidade_card_api === '') {
	$oferta_info_modalidade_card_api = is_string($oferta_info_modalidade_api) ? trim($oferta_info_modalidade_api) : '';
}

$oferta_info_modalidade_slug_api = $normalizar_modalidade_card_slug($oferta_info_modalidade_card_api !== '' ? $oferta_info_modalidade_card_api : $page_modalidade_slug);

$oferta_info_preco_parcela_api_num = $normalizar_numero_moeda_info_oferta(
	is_array($investimento_card_api)
		? ($investimento_card_api['valor']
			?? ($investimento_card_api['preco']
				?? ($investimento_card_api['mensalidade']
					?? ($investimento_card_api['parcela']
						?? ($investimento_card_api['valor_parcela'] ?? '')
					)
				)
			)
		)
		: ''
);

if (
	$oferta_info_preco_parcela_api_num > 0 &&
	is_array($investimento_card_api) &&
	!empty($investimento_card_api['parcelas']) &&
	(int) $investimento_card_api['parcelas'] > 0 &&
	(int) $investimento_card_api['parcelas'] !== 18
) {
	$oferta_info_preco_parcela_api_num = ($oferta_info_preco_parcela_api_num * (int) $investimento_card_api['parcelas']) / 18;
}

$oferta_info_preco_parcela_api = $oferta_info_preco_parcela_api_num > 0 ? number_format($oferta_info_preco_parcela_api_num, 2, ',', '.') : '';

$oferta_info_parcelas_api = 18;

$oferta_info_preco_de_api_num = $normalizar_numero_moeda_info_oferta(
	$buscar_valor_api_info_oferta(
		$data,
		array(
			array('resumo', 'valor_sem_desconto'),
			array('resumo', 'valor_cheio'),
			array('resumo', 'de'),
			array('curso', 'valor_sem_desconto'),
			array('curso', 'valor_cheio'),
			array('curso', 'de'),
			array('valor_sem_desconto'),
			array('valor_cheio'),
			array('preco_cheio'),
			array('de')
		)
	)
);

if ($oferta_info_preco_de_api_num <= 0 && is_array($investimento_card_api)) {
	foreach (array('valor_sem_desconto', 'valorCheio', 'valor_cheio', 'preco_cheio', 'precoDe', 'preco_de', 'de', 'valor_original') as $chave_preco_de) {
		if (!array_key_exists($chave_preco_de, $investimento_card_api)) {
			continue;
		}
		$valor_preco_de_item = $normalizar_numero_moeda_info_oferta($investimento_card_api[$chave_preco_de]);
		if ($valor_preco_de_item > 0) {
			$oferta_info_preco_de_api_num = $valor_preco_de_item;
			break;
		}
	}
}

if ($oferta_info_preco_de_api_num <= 0 && $oferta_info_preco_parcela_api_num > 0) {
	$oferta_info_preco_de_api_num = round($oferta_info_preco_parcela_api_num / 0.4, 2);
}

$oferta_info_preco_de_api = $oferta_info_preco_de_api_num > 0 ? number_format($oferta_info_preco_de_api_num, 2, ',', '.') : '';

$oferta_info_cupom_api = $buscar_valor_api_info_oferta(
	$data,
	array(
		array('resumo', 'cupom'),
		array('resumo', 'codigo_cupom'),
		array('curso', 'cupom'),
		array('curso', 'codigo_cupom'),
		array('cupom'),
		array('codigo_cupom')
	)
);

if ($oferta_info_cupom_api === '' && is_array($investimento_card_api)) {
	foreach (array('cupom', 'codigo_cupom', 'coupon', 'codigoCupom') as $chave_cupom) {
		if (!array_key_exists($chave_cupom, $investimento_card_api)) {
			continue;
		}
		$valor_cupom = $normalizar_texto_info_oferta($investimento_card_api[$chave_cupom]);
		if ($valor_cupom !== '') {
			$oferta_info_cupom_api = $valor_cupom;
			break;
		}
	}
}

$oferta_info_cta_url_api = $buscar_valor_api_info_oferta(
	$data,
	array(
		array('resumo', 'url_matricula'),
		array('resumo', 'link_matricula'),
		array('resumo', 'matricula_url'),
		array('curso', 'url_matricula'),
		array('curso', 'link_matricula'),
		array('curso', 'matricula_url'),
		array('url_matricula'),
		array('link_matricula'),
		array('matricula_url'),
		array('url'),
		array('link')
	)
);

if ($oferta_info_cta_url_api === '' && is_array($investimento_card_api)) {
	foreach (array('url_matricula', 'link_matricula', 'matricula_url', 'url', 'link', 'href') as $chave_link) {
		if (!array_key_exists($chave_link, $investimento_card_api)) {
			continue;
		}
		$valor_link = trim((string) $investimento_card_api[$chave_link]);
		if ($valor_link !== '') {
			$oferta_info_cta_url_api = $valor_link;
			break;
		}
	}
}

$oferta_info_cta_url_api = trim((string) $oferta_info_cta_url_api);
if ($oferta_info_cta_url_api !== '' && strpos($oferta_info_cta_url_api, '/') === 0) {
	$oferta_info_cta_url_api = home_url($oferta_info_cta_url_api);
}
if ($oferta_info_cta_url_api === '') {
	$oferta_info_cta_url_api = '#';
}

/*
 * [REDIRECT_POS_APOS_ENVIO_TEMP_START]
 * Redirecionamento temporario apos envio bem-sucedido do formulario do card flutuante.
 *
 * URL padrao (por enquanto): https://inscricao.unisuam.edu.br/pos
 *
 * COMO TROCAR A URL DEPOIS:
 *   Altere $floating_card_redirect_pos_apos_envio_url abaixo.
 *
 * COMO VOLTAR AO COMPORTAMENTO ANTERIOR (URL do CTA / API SAGA / campo redirectHref):
 *   Defina $floating_card_redirect_pos_apos_envio_ativo = false;
 *
 * O JS consome estas variaveis em REDIRECT_POS_APOS_ENVIO_TEMP_* (busque no arquivo).
 * [REDIRECT_POS_APOS_ENVIO_TEMP_END]
 */
$floating_card_redirect_pos_apos_envio_ativo = true;
$floating_card_redirect_pos_apos_envio_url = 'https://inscricao.unisuam.edu.br/pos';
$floating_card_redirect_pos_apos_envio_target = '_self';

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
		<p class="floatingOfferCartao" id="floatingOfferCartao">no cartão de crédito</p>
		<p class="floatingOfferCupom" id="floatingOfferCupom">com o cupom 300NAPOS</p>
	</div>

	<div class="floatingOfferGrid">
		<div>
			<h4>Modalidade:</h4>
			<p id="floatingOfferModalidade">--</p>
		</div>
		<div>
			<h4>Duração:</h4>
			<p id="floatingOfferDuracao">--</p>
		</div>
		<div>
			<h4>Início:</h4>
			<p id="floatingOfferInicio">--</p>
		</div>
	</div>

	<div id="floatingOfferSelectors" class="floatingOfferSelectors" aria-live="polite">
		<label class="floatingOfferSelectField" for="floatingOfferUnidade">
			Unidade*
			<select id="floatingOfferUnidade" name="floating_offer_unidade">
				<option value="">Selecione a unidade</option>
			</select>
		</label>
		<!-- <p id="floatingOfferOfertaHint" class="floatingOfferOfertaHint">Selecione a unidade para gerar o ID da oferta.</p> -->
		<p id="floatingOfferOfertaId" class="floatingOfferOfertaId" style="display:none">--</p>
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
			<input type="hidden" name="offer_id" value="">
			<input type="hidden" name="offer_unidade" value="">
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

	#floatingOfferCard .floatingOfferCartao {
		display: none;
		margin: 5px 0 0 0;
		font-size: 13px;
		line-height: 1.2;
		font-weight: 600;
		color: #6c7684;
		text-transform: lowercase;
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
		grid-template-columns: 1fr 1fr;
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

	#floatingOfferCard .floatingOfferSelectors {
		margin-top: 12px;
		padding-top: 10px;
		border-top: 1px solid #dfe4eb;
		display: none;
		gap: 8px;
	}

	#floatingOfferCard.tema-presencial .floatingOfferSelectors,
	#floatingOfferCard.tema-aovivo .floatingOfferSelectors {
		display: grid;
	}

	#floatingOfferCard .floatingOfferSelectField {
		display: flex;
		flex-direction: column;
		font-size: 12px;
		font-weight: 700;
		color: #586476;
		gap: 4px;
	}

	#floatingOfferCard .floatingOfferSelectField select {
		height: 36px;
		padding: 0 10px;
		border: 1px solid #cfd6de;
		border-radius: 4px;
		font-size: 13px;
		color: #2f3b4f;
		background: #fff;
	}

	#floatingOfferCard .floatingOfferSelectField select:focus {
		outline: none;
		border-color: var(--offer-accent-strong);
		box-shadow: 0 0 0 2px rgba(15, 150, 174, 0.12);
	}

	#floatingOfferCard .floatingOfferOfertaHint {
		margin: 0;
		font-size: 11px;
		line-height: 1.3;
		color: #667286;
	}

	#floatingOfferCard .floatingOfferOfertaId {
		margin: 0;
		font-size: 12px;
		line-height: 1.2;
		font-weight: 700;
		color: var(--offer-accent-strong);
		display: none;
	}

	#floatingOfferCard .floatingOfferOfertaId.is-visible {
		display: block;
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

		#floatingOfferCard .floatingOfferCartao {
			font-size: 12px;
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

	var MAPA_PARCELA_PRESENCIAL_18X = {
		'448.50': 299.00,
		'598.50': 399.00,
		'898.50': 599.00
	};

	var MAPA_PARCELA_DIGITAL_AOVIVO_18X = {
		'298.50': 199.00,
		'448.50': 299.00,
		'598.50': 399.00
	};

	var OFERTA_JSON_PRECO = <?php echo wp_json_encode($oferta_json_preco); ?>;
	var OFERTA_JSON_MODALIDADE = <?php echo wp_json_encode($oferta_json_modalidade); ?>;
	var OFERTA_JSON_MNEMONICO = <?php echo wp_json_encode($oferta_json_mnemonico); ?>;
	var OFERTA_JSON_ENCONTRADO = <?php echo $oferta_json_encontrado ? 'true' : 'false'; ?>;
	var OFERTA_INFO_TITULO_API = <?php echo wp_json_encode($oferta_info_titulo_api); ?>;
	var OFERTA_INFO_MODALIDADE_API = <?php echo wp_json_encode($oferta_info_modalidade_card_api); ?>;
	var OFERTA_INFO_MODALIDADE_SLUG_API = <?php echo wp_json_encode($oferta_info_modalidade_slug_api); ?>;
	var OFERTA_INFO_PRECO_DE_API = <?php echo wp_json_encode($oferta_info_preco_de_api); ?>;
	var OFERTA_INFO_PRECO_PARCELA_API = <?php echo wp_json_encode($oferta_info_preco_parcela_api); ?>;
	var OFERTA_INFO_PARCELAS_API = <?php echo wp_json_encode($oferta_info_parcelas_api); ?>;
	var OFERTA_INFO_CUPOM_API = <?php echo wp_json_encode($oferta_info_cupom_api); ?>;
	var OFERTA_INFO_CTA_URL_API = <?php echo wp_json_encode($oferta_info_cta_url_api); ?>;
	var OFERTA_INFO_DURACAO_API = <?php echo wp_json_encode($oferta_info_duracao_api); ?>;
	var OFERTA_INFO_INICIO_API = <?php echo wp_json_encode($oferta_info_inicio_api); ?>;
    var OFERTA_INFO_INVESTIMENTOS_API = <?php echo wp_json_encode($data['investimentos'] ?? array()); ?>;
	var MNEUMONICO_PAGINA = <?php echo wp_json_encode($mneumonico); ?>;
	var TITULO_PAGINA_PHP = <?php echo wp_json_encode(get_the_title()); ?>;
	var MODALIDADE_SLUG_PHP = <?php echo wp_json_encode($page_modalidade_slug); ?>;
	var MODALIDADE_LABEL_PHP = <?php echo wp_json_encode($modalidade_box_value); ?>;
	var HUB_ENDPOINT = 'https://api.hsforms.com/submissions/v3/integration/submit/3462868/709c003b-fb18-4cff-beac-fbd2af676bcb';
	var SAGA_ENDPOINT = <?php echo wp_json_encode($graduacao_send_api_url); ?>;
	var HUBSPOT_POS_FIELDS = {
		modalidade: ['pos_modalidade', 'modalidade'],
		cursoPorModalidade: {
			presencial: ['pos___curso_de_interesse___presencial'],
			digital: ['pos___curso_de_interesse___ead'],
			digitalaovivo: ['pos___curso_de_interesse___webconferencia']
		},
		cursoFallback: ['curso_de_interesse']
	};
	var sagaFormState = {
		lastRedirectUrl: null,
		lastRedirectTarget: null,
		submitting: false
	};

	/*
	 * [REDIRECT_POS_APOS_ENVIO_TEMP_START]
	 * Espelha $floating_card_redirect_pos_apos_envio_* (PHP acima).
	 * Busque REDIRECT_POS_APOS_ENVIO_TEMP neste arquivo para localizar rollback/troca de URL.
	 * [REDIRECT_POS_APOS_ENVIO_TEMP_END]
	 */
	var REDIRECT_POS_APOS_ENVIO_TEMP_ATIVO = <?php echo !empty($floating_card_redirect_pos_apos_envio_ativo) ? 'true' : 'false'; ?>;
	var REDIRECT_POS_APOS_ENVIO_TEMP_URL = <?php echo wp_json_encode($floating_card_redirect_pos_apos_envio_url ?? ''); ?>;
	var REDIRECT_POS_APOS_ENVIO_TEMP_TARGET = <?php echo wp_json_encode($floating_card_redirect_pos_apos_envio_target ?? '_self'); ?>;

	function obterUrlRedirectPosEnvioCard(form) {
		if (REDIRECT_POS_APOS_ENVIO_TEMP_ATIVO) {
			var urlTemporaria = String(REDIRECT_POS_APOS_ENVIO_TEMP_URL || '').trim();
			if (urlTemporaria && urlTemporaria !== '#') {
				return urlTemporaria;
			}
		}

		var redirectUrl = '';
		if (form) {
			var redirectUrlInput = form.querySelector('input[name="redirectHref"]');
			if (redirectUrlInput && redirectUrlInput.value) {
				redirectUrl = String(redirectUrlInput.value || '').trim();
			}
		}
		if (!redirectUrl) {
			redirectUrl = String(sagaFormState.lastRedirectUrl || '').trim();
		}
		if (!redirectUrl || redirectUrl === '#') {
			return '';
		}
		return redirectUrl;
	}

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

	function possuiDataDefinida(valor) {
		var texto = String(valor || '').trim();
		if (!texto || texto === '--') return false;

		var normalizado = normalizarTexto(texto);
		if (!normalizado || normalizado === '--') return false;

		// 'Início imediato' nao e data de calendario; nesse caso, oculta o bloco de inicio.
		if (normalizado === 'inicio imediato' || normalizado === 'imediato') return false;

		if (/\b\d{4}[\/\.-]\d{1,2}[\/\.-]\d{1,2}\b/.test(normalizado)) return true;
		if (/\b\d{1,2}[\/\.-]\d{1,2}(?:[\/\.-]\d{2,4})?\b/.test(normalizado)) return true;
		if (/\b\d{1,2}\s+de\s+[a-z]+\s+de\s+\d{2,4}\b/.test(normalizado)) return true;
		if (/\b[a-z]+\s+de\s+\d{4}\b/.test(normalizado)) return true;

		return false;
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

	function pushHubspotFieldSafe(fields, name, value) {
		var fieldName = String(name || '').trim();
		var fieldValue = String(value || '').trim();
		if (!fieldName || !fieldValue) return;

		for (var i = 0; i < fields.length; i++) {
			if (fields[i] && fields[i].name === fieldName) {
				fields[i].value = fieldValue;
				return;
			}
		}

		fields.push({ name: fieldName, value: fieldValue });
	}

	function pushHubspotFieldAliases(fields, aliases, value) {
		if (!Array.isArray(aliases) || !aliases.length) return;
		for (var i = 0; i < aliases.length; i++) {
			pushHubspotFieldSafe(fields, aliases[i], value);
		}
	}

	function campoCursoPosPorModalidade(modalidadeSlug) {
		var slug = modalidadePorSlug(modalidadeSlug);
		if (slug === 'digitalaovivo') return HUBSPOT_POS_FIELDS.cursoPorModalidade.digitalaovivo;
		if (slug === 'digital') return HUBSPOT_POS_FIELDS.cursoPorModalidade.digital;
		return HUBSPOT_POS_FIELDS.cursoPorModalidade.presencial;
	}

	function modalidadeHubspotValor(modalidadeSlug, modalidadeNome) {
		var slug = modalidadePorSlug(modalidadeSlug);
		if (!slug) slug = normalizarModalidade(modalidadeNome);
		if (slug === 'digitalaovivo') return 'Digital ao vivo';
		return rotuloModalidadePorSlug(slug);
	}

	function resolverModalidadeSlugHubspot(modalidadeNome) {
		var slugUrl = String(MODALIDADE_SLUG_PHP || '').trim();
		if (slugUrl) {
			return modalidadePorSlug(slugUrl);
		}

		var slugApi = String(OFERTA_INFO_MODALIDADE_SLUG_API || '').trim();
		if (slugApi) {
			return modalidadePorSlug(slugApi);
		}

		var modalidadeTexto = String(modalidadeNome || OFERTA_INFO_MODALIDADE_API || MODALIDADE_LABEL_PHP || '').trim();
		if (modalidadeTexto) {
			return normalizarModalidade(modalidadeTexto);
		}

		return 'presencial';
	}

	function getFormRefs(card) {
		return {
			shell: card.querySelector('.sagaFormShell'),
			form: card.querySelector('.sagaHubForm'),
			status: card.querySelector('.sagaFormStatus')
		};
	}

	function normalizeKey(str) {
		return normalizarTexto(str).replace(/[^a-z0-9]/g, '');
	}

	function valueToText(value) {
		if (value == null) return '';
		if (typeof value === 'string' || typeof value === 'number' || typeof value === 'boolean') {
			return String(value).trim();
		}
		if (Array.isArray(value)) {
			for (var i = 0; i < value.length; i++) {
				var txtLista = valueToText(value[i]);
				if (txtLista) return txtLista;
			}
			return '';
		}
		if (typeof value === 'object') {
			var preferidas = ['valor', 'value', 'texto', 'text', 'nome', 'name', 'label', 'descricao', 'description', 'data'];
			for (var p = 0; p < preferidas.length; p++) {
				if (Object.prototype.hasOwnProperty.call(value, preferidas[p])) {
					var txtObj = valueToText(value[preferidas[p]]);
					if (txtObj) return txtObj;
				}
			}
			for (var chaveObj in value) {
				if (!Object.prototype.hasOwnProperty.call(value, chaveObj)) continue;
				var txtAny = valueToText(value[chaveObj]);
				if (txtAny) return txtAny;
			}
		}
		return '';
	}

	function getFlexibleValue(obj, candidateKeys) {
		if (!obj || typeof obj !== 'object') return null;
		for (var i = 0; i < candidateKeys.length; i++) {
			var keyDireta = candidateKeys[i];
			if (Object.prototype.hasOwnProperty.call(obj, keyDireta)) {
				return obj[keyDireta];
			}
		}

		var mapNorm = Object.create(null);
		for (var kObj in obj) {
			if (!Object.prototype.hasOwnProperty.call(obj, kObj)) continue;
			var nk = normalizeKey(kObj);
			if (nk && !Object.prototype.hasOwnProperty.call(mapNorm, nk)) {
				mapNorm[nk] = kObj;
			}
		}

		for (var j = 0; j < candidateKeys.length; j++) {
			var keyNorm = normalizeKey(candidateKeys[j]);
			if (keyNorm && Object.prototype.hasOwnProperty.call(mapNorm, keyNorm)) {
				return obj[mapNorm[keyNorm]];
			}
		}

		return null;
	}

	function normalizarComparacaoInvestimento(valor) {
		return String(valor || '')
			.normalize('NFD')
			.replace(/[\u0300-\u036f]/g, '')
			.trim()
			.toLowerCase();
	}

	function extrairIdOfertaInvestimentoCard(item) {
		if (!item || typeof item !== 'object') {
			return '';
		}
		if (item.id != null && String(item.id).trim() !== '') {
			return String(item.id).trim();
		}
		var campos = ['Id', 'ID', 'idCombinacao', 'id_oferta', 'oferta_id', 'oferta'];
		for (var i = 0; i < campos.length; i++) {
			var bruto = item[campos[i]];
			if (bruto == null || bruto === '') {
				continue;
			}
			var texto = String(bruto).trim();
			if (texto) {
				return texto;
			}
		}
		return '';
	}

	function obterInvestimentosCardFlutuante() {
		if (Array.isArray(OFERTA_INFO_INVESTIMENTOS_API) && OFERTA_INFO_INVESTIMENTOS_API.length) {
			return OFERTA_INFO_INVESTIMENTOS_API;
		}
		if (Array.isArray(window.investimentos) && window.investimentos.length) {
			return window.investimentos;
		}
		return [];
	}

	function paginaCardEhDigital() {
		if (MODALIDADE_SLUG_PHP === 'digital') {
			return true;
		}
		return window.location.href.indexOf('-digital') !== -1;
	}

	function obterHorarioSelecionadoPagina() {
		var ids = ['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf'];
		for (var i = 0; i < ids.length; i++) {
			var el = document.getElementById(ids[i]);
			if (el && String(el.value || '').trim() !== '') {
				return String(el.value).trim();
			}
		}
		return '';
	}

	function obterCidadeSelecionadaPagina() {
		var ids = ['cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf'];
		for (var i = 0; i < ids.length; i++) {
			var el = document.getElementById(ids[i]);
			if (el && String(el.value || '').trim() !== '') {
				return String(el.value).trim();
			}
		}
		return '';
	}

	// Mesma regra do single-graduacao de referencia: investimentos.find por unidade/turno.
	function buscarIdOfertaPorSelecao(unidadeSelecionada, horarioSelecionado, cidadeSelecionada) {
		var investimentos = obterInvestimentosCardFlutuante();
		if (!investimentos.length || !String(unidadeSelecionada || '').trim()) {
			return '';
		}

		var isDigital = paginaCardEhDigital();
		var unidadeN = normalizarComparacaoInvestimento(unidadeSelecionada);
		var horarioN = normalizarComparacaoInvestimento(horarioSelecionado);
		var cidadeN = normalizarComparacaoInvestimento(cidadeSelecionada);
		var encontrado = null;

		if (isDigital) {
			if (cidadeN && unidadeN) {
				encontrado = investimentos.find(function(item) {
					return normalizarComparacaoInvestimento(item.cidade) === cidadeN
						&& normalizarComparacaoInvestimento(item.unidade) === unidadeN;
				}) || null;
			}
			if (!encontrado && unidadeN) {
				encontrado = investimentos.find(function(item) {
					return normalizarComparacaoInvestimento(item.unidade) === unidadeN;
				}) || null;
			}
		} else {
			if (unidadeSelecionada && horarioSelecionado) {
				encontrado = investimentos.find(function(item) {
					return item.unidade === unidadeSelecionada && item.horario === horarioSelecionado;
				}) || null;
			}
			if (!encontrado && unidadeSelecionada) {
				encontrado = investimentos.find(function(item) {
					return item.unidade === unidadeSelecionada;
				}) || null;
			}
			if (!encontrado && unidadeN && horarioN) {
				encontrado = investimentos.find(function(item) {
					return normalizarComparacaoInvestimento(item.unidade) === unidadeN
						&& normalizarComparacaoInvestimento(item.horario) === horarioN;
				}) || null;
			}
			if (!encontrado && unidadeN) {
				encontrado = investimentos.find(function(item) {
					return normalizarComparacaoInvestimento(item.unidade) === unidadeN;
				}) || null;
			}
		}

		return extrairIdOfertaInvestimentoCard(encontrado);
	}

	function atualizarOfertaIdCardFlutuante() {
		var selectUnidade = document.getElementById('floatingOfferUnidade');
		var ofertaIdEl = document.getElementById('floatingOfferOfertaId');
		if (!selectUnidade || !ofertaIdEl) {
			return;
		}

		var unidadeSelecionada = String(selectUnidade.value || '').trim();
		var horarioSelecionado = obterHorarioSelecionadoPagina();
		var cidadeSelecionada = obterCidadeSelecionadaPagina();
		var ofertaId = buscarIdOfertaPorSelecao(unidadeSelecionada, horarioSelecionado, cidadeSelecionada);

		if (ofertaId) {
			ofertaIdEl.textContent = ofertaId;
			ofertaIdEl.classList.add('is-visible');
		} else {
			ofertaIdEl.textContent = unidadeSelecionada ? 'ID da oferta: --' : 'ID da oferta: --';
			ofertaIdEl.classList.toggle('is-visible', !!unidadeSelecionada);
		}

		var card = document.getElementById('floatingOfferCard');
		var form = card ? card.querySelector('.sagaHubForm') : null;
		if (form) {
			var offerIdInput = form.querySelector('input[name="offer_id"]');
			var offerUnidadeInput = form.querySelector('input[name="offer_unidade"]');
			if (offerIdInput) {
				offerIdInput.value = ofertaId || '';
			}
			if (offerUnidadeInput) {
				offerUnidadeInput.value = unidadeSelecionada || '';
			}
		}
	}

	window.atualizarOfertaIdCardFlutuante = atualizarOfertaIdCardFlutuante;

	function configurarSeletorUnidadeCard(card) {
		var selectUnidade = document.getElementById('floatingOfferUnidade');
		if (!selectUnidade) return;

		var investimentos = obterInvestimentosCardFlutuante();
		var unidades = [];
		investimentos.forEach(function(item) {
			if (!item || typeof item !== 'object') return;
			var unidade = item.unidade != null ? String(item.unidade).trim() : '';
			if (!unidade) {
				unidade = getFlexibleValue(item, ['unidade', 'unidade_nome', 'unidadeNome', 'local', 'campus', 'polo']);
				unidade = valueToText(unidade);
			}
			if (unidade && unidades.indexOf(unidade) === -1) {
				unidades.push(unidade);
			}
		});

		unidades.sort(function(a, b) {
			return a.localeCompare(b, 'pt-BR');
		});

		var valorAnterior = String(selectUnidade.value || '').trim();
		selectUnidade.innerHTML = '<option value="">Selecione a unidade</option>';
		unidades.forEach(function(unidade) {
			var opt = document.createElement('option');
			opt.value = unidade;
			opt.textContent = unidade;
			selectUnidade.appendChild(opt);
		});

		if (valorAnterior) {
			for (var i = 0; i < selectUnidade.options.length; i++) {
				var option = selectUnidade.options[i];
				if (normalizarTexto(option.value || option.text || '') === normalizarTexto(valorAnterior)) {
					selectUnidade.selectedIndex = i;
					break;
				}
			}
		} else if (unidades.length === 1) {
			selectUnidade.value = unidades[0];
		}

		if (selectUnidade.dataset.floatingOfferChangeBind !== '1') {
			selectUnidade.addEventListener('change', atualizarOfertaIdCardFlutuante);
			selectUnidade.dataset.floatingOfferChangeBind = '1';
		}

		['horario', 'horarioEnem', 'horarioSegunda', 'horarioTransf', 'cidade', 'cidadeEnem', 'cidadeSegunda', 'cidadeTransf'].forEach(function(id) {
			var el = document.getElementById(id);
			if (!el || el.dataset.floatingOfferSyncBind === '1') {
				return;
			}
			el.addEventListener('change', atualizarOfertaIdCardFlutuante);
			el.dataset.floatingOfferSyncBind = '1';
		});

		atualizarOfertaIdCardFlutuante();
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

	function obterFormaIngressoAtualCard() {
		var ativo = document.querySelector('.wrap-modalidade-menu .btnModalidade.active');
		var forma = ativo ? String(ativo.textContent || '').trim() : '';
		return forma || 'VESTIBULAR';
	}

	function modalidadeCardParaSaga(modalidadeNormalizada) {
		if (modalidadeNormalizada === 'digital') return 'ead';
		if (modalidadeNormalizada === 'digitalaovivo') return 'digital_ao_vivo';
		return 'presencial';
	}

	function valorFlexivelCardTexto(valor) {
		if (valor == null) return '';
		if (typeof valor === 'string' || typeof valor === 'number' || typeof valor === 'boolean') {
			return String(valor).trim();
		}
		if (Array.isArray(valor)) {
			for (var i = 0; i < valor.length; i++) {
				var textoLista = valorFlexivelCardTexto(valor[i]);
				if (textoLista) return textoLista;
			}
			return '';
		}
		if (typeof valor === 'object') {
			var chavesPreferidas = ['valor', 'value', 'texto', 'text', 'nome', 'name', 'label', 'descricao', 'description', 'data'];
			for (var j = 0; j < chavesPreferidas.length; j++) {
				if (!Object.prototype.hasOwnProperty.call(valor, chavesPreferidas[j])) continue;
				var textoObj = valorFlexivelCardTexto(valor[chavesPreferidas[j]]);
				if (textoObj) return textoObj;
			}
		}
		return '';
	}

	function obterCampoFlexivelCard(obj, chavesCandidatas) {
		if (!obj || typeof obj !== 'object') return '';

		for (var i = 0; i < chavesCandidatas.length; i++) {
			var chaveDireta = chavesCandidatas[i];
			if (!Object.prototype.hasOwnProperty.call(obj, chaveDireta)) continue;
			var valorDireto = valorFlexivelCardTexto(obj[chaveDireta]);
			if (valorDireto) return valorDireto;
		}

		var mapaNormalizado = Object.create(null);
		Object.keys(obj).forEach(function(chaveObj) {
			var chaveNorm = normalizarTexto(chaveObj).replace(/[^a-z0-9]/g, '');
			if (chaveNorm && !Object.prototype.hasOwnProperty.call(mapaNormalizado, chaveNorm)) {
				mapaNormalizado[chaveNorm] = chaveObj;
			}
		});

		for (var j = 0; j < chavesCandidatas.length; j++) {
			var chaveNormalizada = normalizarTexto(chavesCandidatas[j]).replace(/[^a-z0-9]/g, '');
			if (!chaveNormalizada || !Object.prototype.hasOwnProperty.call(mapaNormalizado, chaveNormalizada)) continue;
			var chaveReal = mapaNormalizado[chaveNormalizada];
			var valorFlex = valorFlexivelCardTexto(obj[chaveReal]);
			if (valorFlex) return valorFlex;
		}

		return '';
	}

	function normalizarTurnoCard(valor) {
		var texto = normalizarTexto(valor);
		if (!texto) return '';
		if (texto.indexOf('manha') !== -1 || texto.indexOf('matut') !== -1) return 'manha';
		if (texto.indexOf('tarde') !== -1 || texto.indexOf('vespert') !== -1) return 'tarde';
		if (texto.indexOf('noite') !== -1 || texto.indexOf('noturn') !== -1) return 'noite';
		if (texto.indexOf('integral') !== -1) return 'integral';
		return texto;
	}

	function extrairRedirectUrlCard(payload) {
		if (!payload || typeof payload !== 'object') return '';
		var candidatos = [
			payload.redirect_url,
			payload.redirectUrl,
			payload.url,
			payload.link,
			payload.data && payload.data.redirect_url,
			payload.data && payload.data.redirectUrl,
			payload.data && payload.data.url,
			payload.result && payload.result.redirect_url,
			payload.result && payload.result.redirectUrl,
			payload.result && payload.result.url
		];

		for (var i = 0; i < candidatos.length; i++) {
			var valor = String(candidatos[i] || '').trim();
			if (!valor) continue;
			valor = valor.replace(/&amp;/g, '&').replace(/^['"]+|['"]+$/g, '');
			return valor;
		}
		return '';
	}

	function enviarDadosParaSagaCard(requestData) {
		return window.fetch(SAGA_ENDPOINT, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json'
			},
			body: JSON.stringify(requestData)
		}).then(function(response) {
			return response.text().then(function(responseText) {
				var parsed = {};
				if (responseText) {
					try {
						parsed = JSON.parse(responseText);
					} catch (parseError) {
						parsed = { raw: responseText };
					}
				}

				if (!response.ok) {
					var msgErro = (parsed && (parsed.message || parsed.error)) || ('Falha no sendAPI (' + response.status + ').');
					throw new Error(msgErro);
				}

				var redirectUrl = extrairRedirectUrlCard(parsed);
				if (!redirectUrl) {
					throw new Error('URL de redirecionamento nao retornada pela API de matricula.');
				}

				return {
					redirectUrl: redirectUrl,
					data: parsed
				};
			});
		});
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

	function handleSubmitSuccess(card, form) {
		var redirectUrl = obterUrlRedirectPosEnvioCard(form);
		if (!redirectUrl) return;

		var redirectTarget = REDIRECT_POS_APOS_ENVIO_TEMP_ATIVO
			? String(REDIRECT_POS_APOS_ENVIO_TEMP_TARGET || '_self').trim()
			: String(sagaFormState.lastRedirectTarget || '_self').trim();

		setTimeout(function() {
			if (redirectTarget === '_blank') {
				window.open(redirectUrl, '_blank', 'noopener');
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

		var titulo = String(OFERTA_INFO_TITULO_API || '').trim();
		var modalidade = String(OFERTA_INFO_MODALIDADE_API || '').trim();
		var modalidadeNormalizada = modalidadePorSlug(MODALIDADE_SLUG_PHP) || modalidadePorSlug(OFERTA_INFO_MODALIDADE_SLUG_API) || normalizarModalidade(modalidade || MODALIDADE_LABEL_PHP || 'Presencial');

		card.classList.remove('tema-digital', 'tema-aovivo', 'tema-presencial');
		if (modalidadeNormalizada === 'digital') {
			card.classList.add('tema-digital');
		} else if (modalidadeNormalizada === 'digitalaovivo') {
			card.classList.add('tema-aovivo');
		} else {
			card.classList.add('tema-presencial');
		}

		configurarSeletorUnidadeCard(card);

		var cartaoCreditoEl = document.getElementById('floatingOfferCartao');
		if (cartaoCreditoEl) {
			cartaoCreditoEl.style.display = modalidadeNormalizada === 'presencial' ? 'none' : '';
		}

		if (titulo) {
			document.getElementById('floatingOfferTitle').textContent = titulo;
		}
		var modalidadeLabelFinal = modalidade || rotuloModalidadePorSlug(modalidadeNormalizada);
		if (modalidadeNormalizada === 'digitalaovivo') {
			modalidadeLabelFinal = 'Digital ao Vivo';
		}
		document.getElementById('floatingOfferModalidade').textContent = modalidadeLabelFinal || '--';
		var duracaoGridApi = String(OFERTA_INFO_DURACAO_API || '').trim();
		var inicioGridApi = String(OFERTA_INFO_INICIO_API || '').trim();
		var duracaoEl = document.getElementById('floatingOfferDuracao');
		if (duracaoEl) {
			duracaoEl.textContent = duracaoGridApi || '--';
		}
		var inicioEl = document.getElementById('floatingOfferInicio');
		if (inicioEl) {
			var inicioExibicao = inicioGridApi || '';
			var modalidadePagina = modalidadePorSlug(MODALIDADE_SLUG_PHP) || normalizarModalidade(MODALIDADE_LABEL_PHP || '');
			if (modalidadePagina === 'presencial') {
				try {
					var investimentosApi = Array.isArray(OFERTA_INFO_INVESTIMENTOS_API) ? OFERTA_INFO_INVESTIMENTOS_API : [];

					function pad(n) {
						return (n < 10 ? '0' : '') + n;
					}

					function normalizeText(str) {
						if (str == null) return '';
						if (typeof str !== 'string') str = String(str);
						if (typeof str.normalize === 'function') {
							str = str.normalize('NFD');
						}
						return str.replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
					}

					function normalizeKey(str) {
						return normalizeText(str).replace(/[^a-z0-9]/g, '');
					}

					function valueToText(value) {
						if (value == null) return '';
						if (typeof value === 'string' || typeof value === 'number' || typeof value === 'boolean') {
							return String(value).trim();
						}
						if (Array.isArray(value)) {
							for (var i = 0; i < value.length; i++) {
								var txtLista = valueToText(value[i]);
								if (txtLista) return txtLista;
							}
							return '';
						}
						if (typeof value === 'object') {
							var preferidas = ['valor', 'value', 'texto', 'text', 'nome', 'name', 'label', 'descricao', 'description', 'data'];
							for (var p = 0; p < preferidas.length; p++) {
								if (Object.prototype.hasOwnProperty.call(value, preferidas[p])) {
									var txtObj = valueToText(value[preferidas[p]]);
									if (txtObj) return txtObj;
								}
							}
							for (var chaveObj in value) {
								if (!Object.prototype.hasOwnProperty.call(value, chaveObj)) continue;
								var txtAny = valueToText(value[chaveObj]);
								if (txtAny) return txtAny;
							}
						}
						return '';
					}

					function getFlexibleValue(obj, candidateKeys) {
						if (!obj || typeof obj !== 'object') return null;
						for (var i = 0; i < candidateKeys.length; i++) {
							var keyDireta = candidateKeys[i];
							if (Object.prototype.hasOwnProperty.call(obj, keyDireta)) {
								return obj[keyDireta];
							}
						}

						var mapNorm = Object.create(null);
						for (var kObj in obj) {
							if (!Object.prototype.hasOwnProperty.call(obj, kObj)) continue;
							var nk = normalizeKey(kObj);
							if (nk && !Object.prototype.hasOwnProperty.call(mapNorm, nk)) {
								mapNorm[nk] = kObj;
							}
						}

						for (var j = 0; j < candidateKeys.length; j++) {
							var keyNorm = normalizeKey(candidateKeys[j]);
							if (keyNorm && Object.prototype.hasOwnProperty.call(mapNorm, keyNorm)) {
								return obj[mapNorm[keyNorm]];
							}
						}

						return null;
					}

					function tryParseDateObject(val) {
						if (val == null) return null;
						var s = (typeof val === 'string') ? val.trim() : String(val);
						if (s === '') return null;

						var m;
						m = s.match(/^(\d{1,2})[\/\.\-](\d{1,2})[\/\.\-](\d{2,4})$/);
						if (m) {
							var day = parseInt(m[1],10), month = parseInt(m[2],10), year = parseInt(m[3],10);
							if (year < 100) year += 2000;
							if (month >= 1 && month <= 12 && day >= 1 && day <= 31) {
								return new Date(year, month-1, day);
							}
							return null;
						}
						m = s.match(/^(\d{4})[\/\.\-](\d{1,2})[\/\.\-](\d{1,2})/);
						if (m) {
							var yearIso = parseInt(m[1],10), monthIso = parseInt(m[2],10), dayIso = parseInt(m[3],10);
							if (monthIso >= 1 && monthIso <= 12 && dayIso >= 1 && dayIso <= 31) {
								return new Date(yearIso, monthIso-1, dayIso);
							}
							return null;
						}

						var digits = s.replace(/[^0-9]/g,'');
						if (/^\d{8}$/.test(digits)) {
							var y = digits.slice(0,4), mo = digits.slice(4,6), d = digits.slice(6,8);
							var monthDigits = parseInt(mo,10), dayDigits = parseInt(d,10);
							if (monthDigits >= 1 && monthDigits <= 12 && dayDigits >= 1 && dayDigits <= 31) {
								return new Date(parseInt(y,10), monthDigits-1, dayDigits);
							}
							return null;
						}

						if (/^\d{10,13}$/.test(digits)) {
							var n = parseInt(digits, 10);
							var dts = digits.length === 10 ? new Date(n * 1000) : new Date(n);
							if (!isNaN(dts.getTime())) {
								return new Date(dts.getFullYear(), dts.getMonth(), dts.getDate());
							}
						}

						return null;
					}

					function formatDateBRFromObj(dateObj) {
						if (!dateObj || isNaN(dateObj.getTime())) return '';
						return pad(dateObj.getDate()) + '/' + pad(dateObj.getMonth()+1) + '/' + dateObj.getFullYear();
					}

					var unidadeAlvo = 'bonsucesso';
					var investimentosBonsucesso = investimentosApi.filter(function(it) {
						if (!it || typeof it !== 'object') return false;
						var unidadeValor = getFlexibleValue(it, ['unidade', 'unidade_nome', 'unidadeNome', 'local', 'campus', 'polo']);
						var unidadeTexto = valueToText(unidadeValor);
						return normalizeText(unidadeTexto).indexOf(unidadeAlvo) !== -1;
					});

					var contador = Object.create(null);
					var sampleDateObj = Object.create(null);
					investimentosBonsucesso.forEach(function(it) {
						if (!it || typeof it !== 'object') return;
						var val = getFlexibleValue(it, ['inicio', 'data_inicio', 'dataInicio', 'inicio_aulas', 'inicioAulas', 'proxima_turma', 'proximaTurma', 'próxima_turma', 'proxima-turma', 'próxima-turma', 'data']);
						var s = valueToText(val);
						if (s === '') return;

						var dateObj = tryParseDateObject(s);
						var key;
						if (dateObj) {
							key = dateObj.getFullYear() + '-' + pad(dateObj.getMonth()+1) + '-' + pad(dateObj.getDate());
							sampleDateObj[key] = dateObj;
						} else {
							key = 'raw:' + s;
						}
						contador[key] = (contador[key] || 0) + 1;
					});

					var maisFreqKey = '';
					var maisFreqCount = 0;
					for (var k in contador) {
						if (!Object.prototype.hasOwnProperty.call(contador, k)) continue;
						if (contador[k] > maisFreqCount) {
							maisFreqKey = k;
							maisFreqCount = contador[k];
						}
					}

					if (maisFreqCount > 0) {
						if (maisFreqKey.indexOf('raw:') === 0) {
							inicioExibicao = maisFreqKey.slice(4);
						} else if (sampleDateObj[maisFreqKey]) {
							inicioExibicao = formatDateBRFromObj(sampleDateObj[maisFreqKey]);
						}
					} else if (inicioGridApi) {
						var parsedFallback = tryParseDateObject(inicioGridApi);
						inicioExibicao = parsedFallback ? formatDateBRFromObj(parsedFallback) : inicioGridApi;
					}
				} catch (e) { }
			}
			inicioEl.textContent = inicioExibicao || '--';

			var inicioParentEl = inicioEl.parentElement;
			if (inicioParentEl) {
				inicioParentEl.style.display = possuiDataDefinida(inicioExibicao) ? '' : 'none';
			}
		}

		// Sincroniza modalidade exibida no topo (#innerMod) com nome e cor da modalidade atual.
		var innerModEl = document.getElementById('innerMod');
		if (innerModEl) {
			innerModEl.textContent = modalidadeLabelFinal;
			innerModEl.style.color = corModalidadePorSlug(modalidadeNormalizada);
		}

		var precoDeApiNumero = numeroPorTextoMoeda(OFERTA_INFO_PRECO_DE_API);
		var precoParcelaApiNumero = numeroPorTextoMoeda(OFERTA_INFO_PRECO_PARCELA_API);
		var ofertaParcelasApi = Number(OFERTA_INFO_PARCELAS_API) || 18;
		// Tratar 'digitalaovivo' como presencial: apenas 'digital' usa 12x temporariamente
		var parcelasApi = (modalidadeNormalizada === 'digital') ? 12 : 18;

		var precoParcelaExibicao = (isFinite(precoParcelaApiNumero) && precoParcelaApiNumero > 0 && ofertaParcelasApi > 0)
			? (precoParcelaApiNumero * (ofertaParcelasApi / parcelasApi))
			: NaN;

		var precoDeCalculadoComDesconto60 = (isFinite(precoParcelaExibicao) && precoParcelaExibicao > 0)
			? (precoParcelaExibicao / 0.4)
			: NaN;

		// Override temporário para cursos do tipo 'digital'
		if (modalidadeNormalizada === 'digital') {
			precoParcelaExibicao = 99.0;
			precoDeCalculadoComDesconto60 = 2400.0;
		}

		var deEl = document.getElementById('floatingOfferDe');
		if (deEl) {
			var prefixParcelas = (isFinite(parcelasApi) && parcelasApi > 0 && modalidadeNormalizada !== 'digital') ? (parcelasApi + 'x de ') : '';
			var precoDeFinal = (isFinite(precoDeCalculadoComDesconto60) && precoDeCalculadoComDesconto60 > 0)
				? precoDeCalculadoComDesconto60
				: precoDeApiNumero;
			deEl.textContent = (isFinite(precoDeFinal) && precoDeFinal > 0) ? (prefixParcelas + formatarMoedaBR(precoDeFinal)) : '--';
		}

		var parcelaEl = document.getElementById('floatingOfferParcela');
		if (parcelaEl) {
			parcelaEl.textContent = (isFinite(precoParcelaExibicao) && precoParcelaExibicao > 0)
				? (parcelasApi + 'x de ' + formatarMoedaBR(precoParcelaExibicao))
				: '--';
		}

		var cupomEl = document.getElementById('floatingOfferCupom');
		var cupomApi = String(OFERTA_INFO_CUPOM_API || '').trim();
		if (cupomEl) {
			if (cupomApi) {
				cupomEl.style.display = 'block';
				cupomEl.textContent = 'com o cupom ' + cupomApi;
			} else {
				cupomEl.style.display = 'none';
			}
		}

		var cta = document.getElementById('floatingOfferCta');
		var hrefPreferencial = String(OFERTA_INFO_CTA_URL_API || '').trim() || '#';
		var targetPreferencial = hrefPreferencial !== '#' ? '_blank' : '_self';
		cta.setAttribute('href', hrefPreferencial || '#');
		cta.setAttribute('target', targetPreferencial || '_self');
		cta.setAttribute('rel', 'noopener');

		// Espelha a logica do formulario HubSpot da referencia .DadosCurso.
		var refs = getFormRefs(card);
		if (refs.form && refs.shell) {
			cta.addEventListener('click', function(event) {
				event.preventDefault();
				var href = REDIRECT_POS_APOS_ENVIO_TEMP_ATIVO
					? String(REDIRECT_POS_APOS_ENVIO_TEMP_URL || '').trim()
					: (cta.getAttribute('href') || '').trim();
				var target = REDIRECT_POS_APOS_ENVIO_TEMP_ATIVO
					? String(REDIRECT_POS_APOS_ENVIO_TEMP_TARGET || '_self').trim()
					: (cta.getAttribute('target') || '').trim();

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
				var cursoNome = textOf('#floatingOfferTitle').replace(/\s+/g, ' ').trim();
				var modalidadeNome = (textOf('#floatingOfferModalidade') || String(OFERTA_INFO_MODALIDADE_API || '')).replace(/\s+/g, ' ').trim();
				var modalidadeSlugEnvio = resolverModalidadeSlugHubspot(modalidadeNome);
				var modalidadeValorHubspot = modalidadeHubspotValor(modalidadeSlugEnvio, modalidadeNome);
				var hubspotFields = [
					{ name: 'firstname', value: firstNameField.value.trim() },
					{ name: 'lastname', value: lastNameField.value.trim() },
					{ name: 'email', value: emailField.value.trim() },
					{ name: 'mobilephone', value: phoneDigits }
				];

				if (cursoNome) {
					// Mantem o campo legado e adiciona o campo especifico por modalidade (print CRM de Pos).
					pushHubspotFieldAliases(hubspotFields, HUBSPOT_POS_FIELDS.cursoFallback, cursoNome);
					pushHubspotFieldAliases(hubspotFields, campoCursoPosPorModalidade(modalidadeSlugEnvio), cursoNome);
				}

				if (modalidadeValorHubspot) {
					// Prioriza o campo novo de Pos, sem perder compatibilidade com o campo legado.
					pushHubspotFieldAliases(hubspotFields, HUBSPOT_POS_FIELDS.modalidade, modalidadeValorHubspot);
				}

				var payload = {
					fields: hubspotFields.concat(collectUTMFields()),
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
						handleSubmitSuccess(card, refs.form);
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

<style>
	section.cursar {
		margin-top: 70px !important;
	}
</style>
<script>
	window.onload = function() {
		const aovivoUrl = window.location.href;
		if(aovivoUrl.match("-aovivo")) {
			document.getElementById("floatingOfferSelectors").style.display = "none";
		}
		if(aovivoUrl.match("-digital")) {
			document.getElementById("floatingOfferCartao").style.display = "block";
		}
	}
</script>