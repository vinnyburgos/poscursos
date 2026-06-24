<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

get_header(); 
?>

<?php include 'icons.php' ?>

<?php
	if (!function_exists('normalizar_modalidade_home')) {
		function normalizar_modalidade_home($rotulo) {
			$base = $rotulo ?? '';
			if (function_exists('remove_accents')) {
				$base = remove_accents($base);
			}
			$base = strtolower(trim($base));
			if ($base === '') return 'presencial';
			if (strpos($base, 'semipresencial') !== false || strpos($base, 'semi presencial') !== false) {
				return 'digitalaovivo';
			}
			if (
				strpos($base, 'webconferencia') !== false ||
				strpos($base, 'web conferencia') !== false ||
				strpos($base, 'digital ao vivo') !== false
			) {
				return 'digitalaovivo';
			}
			if (strpos($base, 'ead') !== false || strpos($base, 'digital') !== false) {
				return 'digital';
			}
			return 'presencial';
		}
	}

	if (!function_exists('rotulo_modalidade_home')) {
		function rotulo_modalidade_home($rotuloOriginal, $modalidadeNormalizada) {
			$base = $rotuloOriginal ?? '';
			if (function_exists('remove_accents')) {
				$base = remove_accents($base);
			}
			$base = strtolower(trim($base));

			if ($modalidadeNormalizada === 'digital') {
				return 'DIGITAL (EAD)';
			}

			if ($modalidadeNormalizada === 'digitalaovivo') {
				return 'DIGITAL AO VIVO';
			}

			return 'PRESENCIAL';
		}
	}

	if (!function_exists('aplicar_sufixo_modalidade_home_url')) {
		function aplicar_sufixo_modalidade_home_url($url, $modalidadeNormalizada) {
			$url = (string) $url;
			if ($url === '' || $url === '#') {
				return $url;
			}

			$sufixo = '';
			if ($modalidadeNormalizada === 'digital') {
				$sufixo = '-digital';
			} elseif ($modalidadeNormalizada === 'digitalaovivo') {
				$sufixo = '-aovivo';
			}

			if ($sufixo === '') {
				$partes = wp_parse_url($url);
				$path = isset($partes['path']) ? (string) $partes['path'] : '';
				if ($path === '') {
					return $url;
				}

				$path_sem_barra = untrailingslashit($path);
				$path_sem_sufixo = preg_replace('/-(digital|aovivo)$/', '', $path_sem_barra);
				$novo_path = $path_sem_sufixo . '/';

				return str_replace($path, $novo_path, $url);
			}

			$partes = wp_parse_url($url);
			$path = isset($partes['path']) ? (string) $partes['path'] : '';
			if ($path === '') {
				return $url;
			}

			$path_sem_barra = untrailingslashit($path);
			$path_sem_sufixo = preg_replace('/-(digital|aovivo)$/', '', $path_sem_barra);
			$novo_path = $path_sem_sufixo . $sufixo . '/';

			return str_replace($path, $novo_path, $url);
		}
	}

	// Filtro funcional: se a URL contiver uma modalidade específica, exibe apenas aquela modalidade
	$filtrar_modalidade_unica = null;
	if (isset($_GET['modalidade'])) {
		$mod = strtolower($_GET['modalidade']);
		if ($mod === 'semipresencial') {
			$mod = 'digitalaovivo';
		}
		if (in_array($mod, array('digital', 'presencial', 'digitalaovivo'), true)) {
			$filtrar_modalidade_unica = $mod;
		}
	}

	// Detector custom: quando a URL indicar o filtro "MBA+Digital" (via query string ou trecho na URI)
	// Exemplo de teste: ?filtrar=mba-digital  ou ?mba_digital=1  ou /mba-digital na path
	$filtrar_mba = false;
	$uri_atual = $_SERVER['REQUEST_URI'] ?? '';
	$filtrar_param = isset($_GET['filtrar']) ? strtolower(trim((string) $_GET['filtrar'])) : '';
	if (
		$filtrar_param === 'mba-digital' ||
		(isset($_GET['mba_digital']) && trim((string) $_GET['mba_digital']) !== '') ||
		(is_string($uri_atual) && (stripos($uri_atual, 'mba-digital') !== false || (stripos($uri_atual, 'mba') !== false && stripos($uri_atual, 'digital') !== false)))
	) {
		// força mostrar apenas modalidade digital e ativa filtro por MBA
		$filtrar_modalidade_unica = 'digital';
		$filtrar_mba = true;
	}
?>

<?php
	$home_js_version = @filemtime(__DIR__ . '/home.js') ?: time();
	$home_css_version = @filemtime(__DIR__ . '/home.css') ?: time();
?>
<script src="<?php echo esc_url(get_template_directory_uri() . '/home.js?ver=' . $home_js_version); ?>"></script>
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/home.css?ver=' . $home_css_version); ?>">

<?php $upload_dir = wp_upload_dir(); ?>

<?php if (!empty($filtrar_mba)): ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		try {
			// Preenche busca com 'MBA'
			var findInput = document.getElementById('findCurso');
			if (findInput) {
				findInput.value = 'MBA';
				findInput.dispatchEvent(new Event('input', { bubbles: true }));
			}

			// Seleciona modalidade digital (cria opção se necessário)
			var modalidadeSelect = document.getElementById('modalidade');
			if (modalidadeSelect) {
				var opt = Array.from(modalidadeSelect.options).find(function(o){
					var v = (o.value||'').toString().toLowerCase();
					var t = (o.textContent||'').toString().toLowerCase();
					return v === 'digital' || t.indexOf('digital') !== -1;
				});
				if (!opt) {
					opt = document.createElement('option');
					opt.value = 'digital';
					opt.text = 'DIGITAL (EAD)';
					modalidadeSelect.appendChild(opt);
				}
				modalidadeSelect.value = opt.value;
				modalidadeSelect.dispatchEvent(new Event('change', { bubbles: true }));
			}

			// Tenta disparar a ação de pesquisa (se existir botão)
			var btn = document.getElementById('btnFindCurso') || document.querySelector('.btnFindCurso');
			if (btn && typeof btn.click === 'function') {
				btn.click();
			}
		} catch (e) {
			// fail silently
		}
	});
</script>
<?php endif; ?>

<!-- CURSOS EM DESTAQUE  -->
<?php
	if (!function_exists('home_normalizar_nome_curso_destaque')) {
		function home_normalizar_nome_curso_destaque($texto) {
			$texto = (string) $texto;
			if (function_exists('remove_accents')) {
				$texto = remove_accents($texto);
			}

			$texto = str_replace('&', ' e ', $texto);
			$texto = strtolower($texto);
			$texto = preg_replace('/[^a-z0-9\s]/iu', ' ', $texto);
			$texto = preg_replace('/\s+/', ' ', trim($texto));

			return $texto;
		}
	}

	if (!function_exists('home_get_selo_cursos_destaque_normalized')) {
		function home_get_selo_cursos_destaque_normalized() {
			$raw = function_exists('get_field') ? get_field('selo_cursos_destaque', 'option') : null;

			if (is_string($raw) && trim($raw) !== '') {
				$cursos = preg_split('/[\r\n,;]+/', $raw);
				$cursos = array_map('trim', $cursos);
			} elseif (is_array($raw) && !empty($raw)) {
				$cursos = $raw;
			} else {
				$cursos = array(
					'Design de Interiores: Pensamento e Produção do Espaço',
					'Inteligência Artificial e Transformação de Negócios',
					'Jornalismo e Marketing Esportivo',
					'Nutrição Esportiva, Estética e Emagrecimento',
					'Pâtisserie e Boulangerie',
					'Mba em Gestão Hospitalar',
					'Engenharia Legal e Diagnóstica',
					'Neurociência, Aprendizagem e Inclusão',
					'Terapia Cognitivo Comportamental',
					'Governança, Gestão e Projetos da Tecnologia da Informação',
					'Engenharia de Segurança do Trabalho',
					'MBA em Gestão de Negócios',
					'MBA em Gestão de Processos e Projetos com Ênfase em Qualidade de Produtos e Serviços',
					'MBA em Logística Empresarial e Supply Chain Management',
					'MBA em Marketing Estratégico com Ênfase em Marketing Digital',
					'Neuropsicopedagogia'
				);
			}

			$normalized = array();
			foreach ($cursos as $curso) {
				$curso_norm = home_normalizar_nome_curso_destaque($curso);
				if ($curso_norm !== '') {
					$normalized[] = $curso_norm;
				}
			}

			return array_values(array_unique($normalized));
		}
	}

	if (!function_exists('home_match_curso_destaque')) {
		function home_match_curso_destaque($curso_titulo_norm, $lista_destaque_norm) {
			$curso_titulo_norm = home_normalizar_nome_curso_destaque($curso_titulo_norm);
			if ($curso_titulo_norm === '' || !is_array($lista_destaque_norm) || empty($lista_destaque_norm)) {
				return false;
			}

			// Delimita com espacos para evitar match parcial no meio de palavras.
			$curso_titulo_busca = ' ' . $curso_titulo_norm . ' ';

			foreach ($lista_destaque_norm as $kw) {
				$kw = home_normalizar_nome_curso_destaque($kw);
				if ($kw === '') {
					continue;
				}

				// Match por contem termo da lista no titulo, mantendo ordem exata dos termos.
				$kw_busca = ' ' . $kw . ' ';
				if (strpos($curso_titulo_busca, $kw_busca) !== false) {
					return true;
				}
			}

			return false;
		}
	}

	$selo_cursos_destaque_normalized = home_get_selo_cursos_destaque_normalized();
?>

<?php
	$tipo_modalidade = get_field('tipo_modalidade', 'option');
	$titulo_da_home = get_field('titulo_da_home', 'option');
	$texto_do_subtitulo = get_field('texto_do_subtitulo', 'option');
	$imagem_de_fundo_header_home = get_field('imagem_de_fundo_header_home', 'option');
?>

<section class="bgHome" style="background: url('<?php echo esc_url($imagem_de_fundo_header_home); ?>');">
	<!-- <div class="breadcrumb"></div> -->
	<div class="wrapInfos">
		<p class="modalidadeTop">PRESENCIAL | DIGITAL AO VIVO | DIGITAL (EAD)</p>
		<!-- <h1 class="titleHome"><?php echo esc_html($titulo_da_home); ?></h1> -->
		<h1 class="titleHome">PÓS-GRADUAÇÃO <span style="">UNISUAM</span></h1>
		<!-- <p class="subTitleHome"><?php echo esc_html($texto_do_subtitulo); ?></p> -->
		 <p class="subTitleHome">Conheça os cursos de Pós-Graduação da UNISUAM<br>e transforme sua carreira.</p>
	</div>
</section>

<style>
	.seloTypeTop10 {
		text-transform: uppercase !important;
	}
	@media(max-width:1131px) {
		.wrapInfos {
			margin-top: 5px;
		}
	}
</style>

<!-- area de busca  -->
	<div class="findBar" id="findBar"><img class="findInterBar" src="<?php echo $upload_dir['baseurl']; ?>/2025/06/find.png" alt="">
		<input type="text" id="findCurso" class="findCurso" placeholder="PESQUISE UM CURSO">
		<div class="btnFindCurso btn" id="btnFindCurso">PESQUISAR</div>

        <div class="mobFiltros" id="mobFiltros">
            <span style="display: flex; align-items: center;"><?php echo $mobFiltros; ?></span>
            <span style="display: flex; align-items: center;">FILTROS</span>
        </div>
	</div>

	<div class="filtrosBar">
		<div class="innerFiltros">
			<div class="lineOne">
				<p class="filtrarPor">FILTRAR POR:</p>
                <p class="limparFiltros btn" id="limparFiltros" style="display: flex; align-items: center;">
                    LIMPAR FILTROS 
					<span class="icon-borracha" style="display: flex; align-items: center; margin-left: 6px;"><?php echo $iconBorracha; ?></span>
					<style>
						.limparFiltros:hover .icon-borracha svg,
						.limparFiltros:hover .icon-borracha svg *,
						.limparFiltros:hover .icon-borracha svg path {
							stroke: #57606F !important;
							transition: all .4s;
						}
					</style>
                </p>
				
			</div>
			<div class="lineTwo">
				<form action="#" id="filtrarCursos" class="filtrarCursos">

					<div class="innerSelect">
						<label for="areaInteresse" class="interesse">Área de Interesse</label>
						<?php
							// Busca todas as categorias da taxonomia 'category'
							$categorias = get_categories(array(
								'taxonomy'   => 'category',
								'hide_empty' => false,
							));

							// Define categorias a serem ignoradas (case-insensitive)
							$ignorar = array('presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo');

						?>
						<select name="areaInteresse" id="areaInteresse">
							<?php if (!empty($selo_cursos_destaque_normalized)): ?>
								<option class="option-cursos-destaque" value="cursos-destaque">Cursos em Destaque</option>
							<?php endif; ?>
							<option value="">Todas</option>
							<?php foreach ($categorias as $categoria): ?>
								<?php
									$nome_cat = trim($categoria->name);
									$nome_cat_normalizado = strtolower($nome_cat);
									if (in_array($nome_cat_normalizado, $ignorar, true)) continue;
									if (preg_match('/^\d+$/', $nome_cat)) continue;
								?>
								<option class="optCat" value="<?php echo esc_attr($categoria->term_id); ?>">
									<?php echo esc_html($nome_cat); ?>
								</option>
							<?php endforeach; ?>
						</select>

						<style>
							#areaInteresse.areaInteresseDestaqueSelecionado {
								background-image:
									url('https://poscursos.unisuam.edu.br/wp-content/uploads/2026/04/star-icon-destaque.png'),
									url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath d='M4 6l4 4 4-4' fill='none' stroke='%2357606F' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
								background-repeat: no-repeat;
								background-size: 16px 16px, 16px 16px;
								background-position: 10px center, calc(100% - 12px) center;
								padding-left: 34px;
								padding-right: 36px;
							}
						</style>

						<script>
						(function() {
							var BLOQUEADAS = ['presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo'];
							var VALOR_DESTAQUE = 'cursos-destaque';
							var CLASSE_SELECT_DESTAQUE = 'areaInteresseDestaqueSelecionado';
							var normalizar = function(valor) {
								return (valor || '')
									.toString()
									.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
									.toLowerCase()
									.trim();
							};
							var atualizarVisualDestaque = function(select) {
								if (!select) return;
								if (String(select.value || '') === VALOR_DESTAQUE) {
									select.classList.add(CLASSE_SELECT_DESTAQUE);
								} else {
									select.classList.remove(CLASSE_SELECT_DESTAQUE);
								}
							};
							var removerOpcoesBloqueadas = function(select) {
								if (!select) return;
								Array.from(select.options).forEach(function(opt) {
									var htmlOpcao = String(opt.innerHTML || opt.textContent || '').replace(/\s+/g, ' ').trim();
									var opcaoSomenteNumerica = /^\d+$/.test(htmlOpcao);
									if (BLOQUEADAS.indexOf(normalizar(opt.textContent)) !== -1 || opcaoSomenteNumerica) {
										opt.remove();
									}
								});
							};

							document.addEventListener('DOMContentLoaded', function() {
								var selectArea = document.getElementById('areaInteresse');
								if (!selectArea) {
									return;
								}

								var observer = new MutationObserver(function() {
									removerOpcoesBloqueadas(selectArea);
									atualizarVisualDestaque(selectArea);
								});
								observer.observe(selectArea, { childList: true });
								removerOpcoesBloqueadas(selectArea);
								atualizarVisualDestaque(selectArea);

								selectArea.addEventListener('change', function() {
									atualizarVisualDestaque(selectArea);
								});
							});
						})();
						</script>

					</div>

					<div class="innerSelect">
						<label for="modalidade" class="modalidade">Modalidade de Ensino</label>
						<select name="modalidade" id="modalidade">
							<option value="">Todas</option>
						</select>
					</div>

					<div class="innerSelect selectUnidade">
						<label for="unidade" class="unidade">Unidade</label>
						<select name="unidade" id="unidade">
							<option value="">Todas</option>
						</select>
					</div>

				</form>

                <div class="btnRedcolher">
                    RECOLHER 
                    <span style="display:inline-block; vertical-align:middle;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-left:4px;" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 10L8 6L12 10" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
			</div>
		</div>
	</div>

<section class="encontreCurso">

    <div class="center"> 
        <!-- BOXES -->
        
		<?php
			if (!function_exists('dadoshome_extract_lista_cursos')) {
				function dadoshome_extract_lista_cursos($payload) {
					if (!is_array($payload)) {
						return array();
					}

					if (isset($payload['posgraduacao']) && is_array($payload['posgraduacao']) && !empty($payload['posgraduacao'])) {
						return $payload['posgraduacao'];
					}

					if (isset($payload['data']) && is_array($payload['data']) && !empty($payload['data'])) {
						return $payload['data'];
					}

					$first = reset($payload);
					if (
						is_array($first) &&
						(!empty($first['curso']) || !empty($first['nome']) || !empty($first['mnemonico']) || !empty($first['mneumonico']))
					) {
						return $payload;
					}

					return array();
				}
			}

			$timezone_string = '';
			if (function_exists('wp_timezone_string')) {
				$timezone_string = (string) wp_timezone_string();
			}
			if ($timezone_string === '' && function_exists('get_option')) {
				$timezone_string = (string) get_option('timezone_string');
			}
			if ($timezone_string === '') {
				$timezone_string = 'America/Sao_Paulo';
			}

			try {
				$timezone = new DateTimeZone($timezone_string);
			} catch (Exception $e) {
				$timezone = new DateTimeZone('UTC');
			}
			$now = new DateTime('now', $timezone);

			// Fulltime: carregar cards direto da API (sem leitura de dadosHome.json)
			$api_cards_base_url = 'https://apimatricula.unisuam.edu.br';
			$api_cards_login = 'frog';
			$api_cards_senha = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';
			$cursos_data = array();
			$api_cards_token = '';
			$api_cards_cache_key = 'poscursos_home_api_cards_v1';
			$api_cards_cache_ttl = defined('MINUTE_IN_SECONDS') ? 5 * MINUTE_IN_SECONDS : 300;

			$cached_cards_data = get_transient($api_cards_cache_key);
			if (is_array($cached_cards_data) && !empty($cached_cards_data)) {
				$cursos_data = $cached_cards_data;
			} else {
				$token_response = wp_remote_post(
					trailingslashit($api_cards_base_url) . 'api/v1/token',
					array(
						'timeout' => 10,
						'headers' => array(
							'accept' => 'application/json',
							'Content-Type' => 'application/json',
						),
						'body' => wp_json_encode(array(
							'login' => $api_cards_login,
							'senha' => $api_cards_senha,
						)),
					)
				);

				if (!is_wp_error($token_response) && (int) wp_remote_retrieve_response_code($token_response) >= 200 && (int) wp_remote_retrieve_response_code($token_response) < 300) {
					$token_body = json_decode((string) wp_remote_retrieve_body($token_response), true);
					if (is_array($token_body) && !empty($token_body['token'])) {
						$api_cards_token = (string) $token_body['token'];
					}
				}

				if ($api_cards_token !== '') {
					$cards_response = wp_remote_get(
						trailingslashit($api_cards_base_url) . 'api/v1/cards/posgraduacao',
						array(
							'timeout' => 15,
							'headers' => array(
								'Authorization' => 'Bearer ' . $api_cards_token,
								'Content-Type' => 'application/json',
							),
						)
					);

					if (!is_wp_error($cards_response) && (int) wp_remote_retrieve_response_code($cards_response) >= 200 && (int) wp_remote_retrieve_response_code($cards_response) < 300) {
						$cards_body = json_decode((string) wp_remote_retrieve_body($cards_response), true);
						if (is_array($cards_body)) {
							$cursos_data = (isset($cards_body['data']) && is_array($cards_body['data'])) ? $cards_body['data'] : $cards_body;
						}
					}
				}

				if (!empty($cursos_data) && is_array($cursos_data)) {
					set_transient($api_cards_cache_key, $cursos_data, $api_cards_cache_ttl);
				}
			}

			if (empty($cursos_data) || !is_array($cursos_data)) {
				$cursos_data = array();
			}

			$cursos = dadoshome_extract_lista_cursos($cursos_data);
			$cursos_por_titulo_norm_home = array();
			if (!empty($cursos) && is_array($cursos)) {
				foreach ($cursos as $curso_tmp) {
					if (!is_array($curso_tmp)) {
						continue;
					}
					$titulo_tmp = trim((string) ($curso_tmp['curso'] ?? $curso_tmp['nome'] ?? ''));
					if ($titulo_tmp === '') {
						continue;
					}
					$titulo_tmp_norm = function_exists('mb_strtolower') ? mb_strtolower($titulo_tmp, 'UTF-8') : strtolower($titulo_tmp);
					if (!isset($cursos_por_titulo_norm_home[$titulo_tmp_norm])) {
						$cursos_por_titulo_norm_home[$titulo_tmp_norm] = $curso_tmp;
					}
				}
			}

			// Área de configuração: sobrescrever modalidade para cursos específicos
			// Para manter comportamento padrão, deixe o array vazio. Para definir,
			// use a chave como o nome exato do curso e o valor como 'digital', 'presencial',
			// 'digitalaovivo' ou qualquer rótulo que a função normalizar_modalidade_home consiga reconhecer.
			// Exemplo: para forçar um curso a aparecer apenas em uma modalidade específica.
			// Defina pares 'Nome do Curso' => 'modalidade' aqui.
			// Exemplo:
			// $home_cursos_modalidade_overrides = array(
			//     'Engenharia de Segurança do Trabalho' => 'digital',
			// );
			$home_cursos_modalidade_overrides = array();

			if (!empty($home_cursos_modalidade_overrides) && is_array($home_cursos_modalidade_overrides)) {
				$__ovr_norm = array();
				foreach ($home_cursos_modalidade_overrides as $k => $v) {
					$k_norm = home_normalizar_nome_curso_destaque((string) $k);
					if ($k_norm === '') continue;
					$__ovr_norm[$k_norm] = $v;
				}

				if (!empty($__ovr_norm)) {
					foreach ($cursos as $ix => $curso_item) {
						if (!is_array($curso_item)) continue;
						$titulo = trim((string) ($curso_item['curso'] ?? $curso_item['nome'] ?? ''));
						$titulo_norm = home_normalizar_nome_curso_destaque($titulo);
						if ($titulo_norm !== '' && isset($__ovr_norm[$titulo_norm])) {
							// Aplica override de modalidade apenas quando definido
							$cursos[$ix]['modalidade'] = $__ovr_norm[$titulo_norm];
						}
					}
				}
			}
		?>
		<?php
			$find_cursos_wp_template = function_exists('locate_template') ? locate_template('findCursosWP.php', false, false) : '';
			if (is_string($find_cursos_wp_template) && $find_cursos_wp_template !== '' && file_exists($find_cursos_wp_template)) {
				include $find_cursos_wp_template;
			}
			$wp_cursos_map = [];
			$wp_cursos_map_by_slug = [];
			$normalizar_titulo_exato_home = function($valor) {
				$valor = (string) $valor;
				$valor = function_exists('remove_accents') ? remove_accents($valor) : $valor;
				$valor = strtolower($valor);
				$valor = preg_replace('/\s+/', ' ', trim($valor));
				return $valor;
			};
			$buscar_posts_por_titulo_exato_home = function($titulo) use ($normalizar_titulo_exato_home) {
				$titulo = trim((string) $titulo);
				if ($titulo === '') {
					return array();
				}

				$ids = get_posts(array(
					'post_type' => 'any',
					'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
					'posts_per_page' => 200,
					'fields' => 'ids',
					's' => $titulo,
					'suppress_filters' => true,
				));

				if (!is_array($ids) || empty($ids)) {
					return array();
				}

				$alvo = $normalizar_titulo_exato_home($titulo);
				$filtrados = array();
				foreach ($ids as $id_post) {
					$id_post = (int) $id_post;
					if ($id_post <= 0) {
						continue;
					}
					$titulo_post = get_the_title($id_post);
					if ($normalizar_titulo_exato_home($titulo_post) === $alvo) {
						$filtrados[] = $id_post;
					}
				}

				return array_values(array_unique(array_map('intval', $filtrados)));
			};
			if (!empty($cursos) && is_array($cursos)) {
				foreach ($cursos as $item) {
					if (!is_array($item)) {
						continue;
					}

					$key = $item['mneumonico'] ?? ($item['mnemonico'] ?? null);
					$perma = $item['permalink'] ?? null;
					$slug = $item['slug'] ?? null;
					$item_post_type = $item['post_type'] ?? '';

					// Normalizar modalidade do item para mapear chaves compostas
					$modal_raw_item = $item['modalidade'] ?? ($item['categoria'] ?? ($item['tipo'] ?? ''));
					$modal_norm_item = '';
					if ($modal_raw_item !== '') {
						$modal_norm_item = normalizar_modalidade_home($modal_raw_item);
					}

					if ($key && $perma) {
						$comp = $key . '|' . $modal_norm_item;
						if (!isset($wp_cursos_map[$comp]) || $item_post_type === 'graduacao') {
							$wp_cursos_map[$comp] = $perma;
						}
						if (!isset($wp_cursos_map[$key]) || $item_post_type === 'graduacao') {
							$wp_cursos_map[$key] = $perma;
						}
					}
					if ($slug && $perma) {
						$comp_slug = $slug . '|' . $modal_norm_item;
						if (!isset($wp_cursos_map_by_slug[$comp_slug]) || $item_post_type === 'graduacao') {
							$wp_cursos_map_by_slug[$comp_slug] = $perma;
						}
						if (!isset($wp_cursos_map_by_slug[$slug]) || $item_post_type === 'graduacao') {
							$wp_cursos_map_by_slug[$slug] = $perma;
						}
					}
				}
			}

			$cursos_para_cards = array();
			if (!empty($cursos) && is_array($cursos)) {
				$cursos_agrupados = array();
				$normalizar_chave_mneumonico_home = function($valor) {
					$valor = (string) $valor;
					if (function_exists('remove_accents')) {
						$valor = remove_accents($valor);
					}
					$valor = strtolower(trim($valor));
					$valor = preg_replace('/\s+/', '', $valor);
					return $valor;
				};

				foreach ($cursos as $indice_curso => $curso_item) {
					if (!is_array($curso_item)) {
						continue;
					}

					$mneumonico_item = trim((string) ($curso_item['mnemonico'] ?? ($curso_item['mneumonico'] ?? '')));
					$chave_mneumonico_normalizada = $normalizar_chave_mneumonico_home($mneumonico_item);
					// Normalizar modalidade e usar como parte da chave de agrupamento
					$modal_raw = $curso_item['modalidade'] ?? ($curso_item['categoria'] ?? ($curso_item['tipo'] ?? ''));
					$modal_norm = '';
					if ($modal_raw !== '') {
						$modal_norm = normalizar_modalidade_home($modal_raw);
					}
					$chave_grupo = $chave_mneumonico_normalizada !== ''
						? ($chave_mneumonico_normalizada . '|' . $modal_norm)
						: ('__sem_mneumonico_' . $indice_curso . '|' . $modal_norm);

					if (!isset($cursos_agrupados[$chave_grupo])) {
						$curso_base = $curso_item;
						$curso_base['__unidades_agrupadas'] = array();

						$campus_base = trim((string) ($curso_base['campus'] ?? ''));
						if ($campus_base !== '') {
							$curso_base['__unidades_agrupadas'][$campus_base] = $campus_base;
						}

						$cursos_agrupados[$chave_grupo] = $curso_base;
						continue;
					}

					$curso_agrupado = $cursos_agrupados[$chave_grupo];

					$campus_item = trim((string) ($curso_item['campus'] ?? ''));
					if ($campus_item !== '') {
						$curso_agrupado['__unidades_agrupadas'][$campus_item] = $campus_item;
					}

					if (
						isset($curso_item['precos']) && is_numeric($curso_item['precos']) &&
						(!isset($curso_agrupado['precos']) || !is_numeric($curso_agrupado['precos']) || (float) $curso_item['precos'] > (float) $curso_agrupado['precos'])
					) {
						$curso_agrupado['precos'] = (float) $curso_item['precos'];
					}

					if ((empty($curso_agrupado['permalink']) || $curso_agrupado['permalink'] === '#') && !empty($curso_item['permalink'])) {
						$curso_agrupado['permalink'] = $curso_item['permalink'];
					}

					$cursos_agrupados[$chave_grupo] = $curso_agrupado;
				}

				foreach ($cursos_agrupados as $curso_agrupado_final) {
					$unidades_agrupadas = array_values($curso_agrupado_final['__unidades_agrupadas'] ?? array());
					unset($curso_agrupado_final['__unidades_agrupadas']);

					if (!empty($unidades_agrupadas)) {
						$curso_agrupado_final['campus'] = implode(' | ', $unidades_agrupadas);
					}

					$cursos_para_cards[] = $curso_agrupado_final;
				}
			}
		?>
		<style>
			/* Selo em destaque no topo-direito dos cards selecionados */
			.box-item { position: relative; }
			.box-item .box-badge { position: absolute; top: -2.5px; right: -6px; width: 84px; height: auto; z-index: 12; pointer-events: none; background: transparent !important;}
			@media (max-width: 767px) {
				.box-item .box-badge { width: 56px; top: 8px; right: 8px; }
			}
		</style>


		<?php
			$home_cache_irmaos_por_mneumonico = array();
			$home_cache_titulo_exato = array();
		?>
		<div class="box-container">
		<?php if (!empty($cursos_para_cards) && is_array($cursos_para_cards)): ?>
			<?php foreach ($cursos_para_cards as $curso): ?>
							<?php
								// Filtro funcional: exibe apenas a modalidade solicitada pela URL (se houver)
								$modalidade_original = $curso['modalidade'] ?? 'Presencial';
								$modalidade_normalizada = normalizar_modalidade_home($modalidade_original);
								if ($filtrar_modalidade_unica && $modalidade_normalizada !== $filtrar_modalidade_unica) continue;
								// Quando ativo, filtrar apenas cursos cujo título contenha 'MBA'
								if (!empty($filtrar_mba)) {
									$titulo_curso_tmp = trim((string) ($curso['curso'] ?? $curso['nome'] ?? ''));
									if (stripos($titulo_curso_tmp, 'mba') === false) continue;
								}
								$modalidade = rotulo_modalidade_home($modalidade_original, $modalidade_normalizada);
							?>
				<?php
							// Força "EAD" para "Digital (EaD)" e controla classes
							$modalidade_original = $curso['modalidade'] ?? 'Presencial';
							$modalidade = rotulo_modalidade_home($modalidade_original, $modalidade_normalizada);
							$is_digital = ($modalidade_normalizada === 'digital');
							$is_digital_ao_vivo = ($modalidade_normalizada === 'digitalaovivo');
					$colorClass = $is_digital_ao_vivo ? 'colorPurple' : ($is_digital ? 'colorRed' : 'colorGreen');
					$bgClass = $is_digital_ao_vivo ? 'bgPurple' : ($is_digital ? 'bgRed' : 'bgGreen');
					$innerClass = $is_digital_ao_vivo ? 'innerPurple' : ($is_digital ? 'innerRed' : 'innerGreen');

							$mneumonico_curso = $curso['mnemonico'] ?? ($curso['mneumonico'] ?? '');
							$slug_curso = sanitize_title($curso['curso'] ?? $curso['nome'] ?? '');
							$permalink_curso = '#';
							$post_id_curso_card = 0;

							// Tentar resolver permalink por chave composta (mnemonico|modalidade)
							$modal_lookup = normalizar_modalidade_home($curso['modalidade'] ?? ($curso['categoria'] ?? ($curso['tipo'] ?? '')));
							$comp_key = $mneumonico_curso . '|' . $modal_lookup;
							$comp_slug = $slug_curso . '|' . $modal_lookup;
							if (!empty($mneumonico_curso) && isset($wp_cursos_map[$comp_key])) {
								$permalink_curso = $wp_cursos_map[$comp_key];
							} elseif (isset($wp_cursos_map[$mneumonico_curso])) {
								$permalink_curso = $wp_cursos_map[$mneumonico_curso];
							} elseif ($slug_curso && isset($wp_cursos_map_by_slug[$comp_slug])) {
								$permalink_curso = $wp_cursos_map_by_slug[$comp_slug];
							} elseif ($slug_curso && isset($wp_cursos_map_by_slug[$slug_curso])) {
								$permalink_curso = $wp_cursos_map_by_slug[$slug_curso];
							}

							if (function_exists('garantir_pagina_curso_por_card_home')) {
								$post_id_gerado = garantir_pagina_curso_por_card_home($curso, $modalidade_normalizada);
								if ($post_id_gerado) {
									$post_id_curso_card = (int) $post_id_gerado;
									$permalink_curso = get_permalink($post_id_gerado);
									// Atualizar mapeamentos simples e compostos com a modalidade atual
									$modal_lookup_set = normalizar_modalidade_home($curso['modalidade'] ?? ($curso['categoria'] ?? ($curso['tipo'] ?? '')));
									$comp_set = $mneumonico_curso . '|' . $modal_lookup_set;
									$comp_slug_set = $slug_curso . '|' . $modal_lookup_set;
									if ($mneumonico_curso) {
										$wp_cursos_map[$mneumonico_curso] = $permalink_curso;
										$wp_cursos_map[$comp_set] = $permalink_curso;
									}
									if ($slug_curso) {
										$wp_cursos_map_by_slug[$slug_curso] = $permalink_curso;
										$wp_cursos_map_by_slug[$comp_slug_set] = $permalink_curso;
									}
								}
							}

							if (!$post_id_curso_card && is_string($permalink_curso) && $permalink_curso !== '#') {
								$post_id_curso_card = (int) url_to_postid($permalink_curso);
							}

							$categorias_modalidade_bloqueadas = array('presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo');
							$categorias_card_map = array();
							$debug_source_posts = array();
							$debug_source_terms = array();

							$adicionar_categorias_area_do_post = function($post_id_local) use (&$categorias_card_map, $categorias_modalidade_bloqueadas, &$debug_source_posts, &$debug_source_terms) {
								$post_id_local = (int) $post_id_local;
								if ($post_id_local <= 0) {
									return;
								}

								if (!in_array((string) $post_id_local, $debug_source_posts, true)) {
									$debug_source_posts[] = (string) $post_id_local;
								}

								$categorias_local = get_the_terms($post_id_local, 'category');
								if (!is_wp_error($categorias_local) && !empty($categorias_local)) {
									foreach ($categorias_local as $categoria_local) {
										$nome_categoria = (string) $categoria_local->name;
										$debug_source_terms[] = 'category:' . $nome_categoria;
										$nome_cat_val = strtolower(trim($nome_categoria));
										if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
											continue;
										}
										$categorias_card_map[(string) $categoria_local->term_id] = $nome_categoria;
									}
								}

								// Fallback: quando a pagina usa outra taxonomia de area, mapeia por nome/slug para termos da taxonomia category.
								if (empty($categorias_card_map)) {
									$post_type_local = get_post_type($post_id_local);
									$taxonomias = get_object_taxonomies($post_type_local, 'objects');
									if (is_array($taxonomias)) {
										foreach ($taxonomias as $taxonomia_obj) {
											if (!($taxonomia_obj instanceof WP_Taxonomy)) {
												continue;
											}
											$tax_nome = (string) $taxonomia_obj->name;
											if ($tax_nome === 'category' || $tax_nome === 'post_tag' || $tax_nome === 'post_format') {
												continue;
											}

											$termos_outros = get_the_terms($post_id_local, $tax_nome);
											if (is_wp_error($termos_outros) || empty($termos_outros)) {
												continue;
											}

											foreach ($termos_outros as $termo_outro) {
												$nome_outro = trim((string) $termo_outro->name);
												$slug_outro = trim((string) $termo_outro->slug);
												if ($nome_outro === '' && $slug_outro === '') {
													continue;
												}

												$debug_source_terms[] = $tax_nome . ':' . ($nome_outro !== '' ? $nome_outro : $slug_outro);

												$termo_categoria = null;
												if ($slug_outro !== '') {
													$termo_categoria = get_term_by('slug', $slug_outro, 'category');
												}
												if (!$termo_categoria instanceof WP_Term && $nome_outro !== '') {
													$termo_categoria = get_term_by('name', $nome_outro, 'category');
												}
												if ($termo_categoria instanceof WP_Term) {
													$nome_categoria_mapeada = (string) $termo_categoria->name;
													$nome_cat_val = strtolower(trim($nome_categoria_mapeada));
													if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
														continue;
													}
													$categorias_card_map[(string) $termo_categoria->term_id] = $nome_categoria_mapeada;
												}
											}
										}
									}
								}

								// Fallback adicional: tenta campos/meta de area/categoria/escola no post fonte.
								if (empty($categorias_card_map)) {
									$metas = get_post_meta($post_id_local);
									if (is_array($metas) && !empty($metas)) {
										foreach ($metas as $meta_chave => $meta_valores) {
											$chave_norm = strtolower((string) $meta_chave);
											if (!preg_match('/(area|categoria|category|escola|segmento|eixo|interesse)/', $chave_norm)) {
												continue;
											}

											if (!is_array($meta_valores)) {
												$meta_valores = array($meta_valores);
											}

											foreach ($meta_valores as $meta_valor) {
												if (is_array($meta_valor) || is_object($meta_valor)) {
													continue;
												}
												$partes = preg_split('/[,|;\/]+/', (string) $meta_valor);
												foreach ($partes as $parte_meta) {
													$parte_meta = trim((string) $parte_meta);
													if ($parte_meta === '') {
														continue;
													}

													$debug_source_terms[] = 'meta:' . $meta_chave . '=' . $parte_meta;
													$termo_categoria = get_term_by('name', $parte_meta, 'category');
													if (!$termo_categoria instanceof WP_Term) {
														$termo_categoria = get_term_by('slug', sanitize_title($parte_meta), 'category');
													}

													if ($termo_categoria instanceof WP_Term) {
														$nome_categoria_mapeada = (string) $termo_categoria->name;
														$nome_cat_val = strtolower(trim($nome_categoria_mapeada));
														if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
															continue;
														}
														$categorias_card_map[(string) $termo_categoria->term_id] = $nome_categoria_mapeada;
													}
												}
											}
										}
									}
								}
							};

							// Regra global:
							// 1) categorias da pagina vinculada ao proprio card (href do Saiba Mais)
							// 2) agrega categorias dos posts-irmaos do mesmo curso (mesmo mneumonico)
							$post_ids_fontes = array();
							$post_id_fonte_principal = (int) $post_id_curso_card;

							if ($post_id_fonte_principal <= 0 && $mneumonico_curso !== '' && function_exists('buscar_curso_graduacao_por_mneumonico_e_modalidade')) {
								$post_por_mneumonico = buscar_curso_graduacao_por_mneumonico_e_modalidade($mneumonico_curso, $modalidade_normalizada);
								if ($post_por_mneumonico instanceof WP_Post) {
									$post_id_fonte_principal = (int) $post_por_mneumonico->ID;
								}
							}

							if ($post_id_fonte_principal > 0) {
								$post_ids_fontes[] = $post_id_fonte_principal;
								if ($permalink_curso === '#' || $permalink_curso === '') {
									$permalink_curso = get_permalink($post_id_fonte_principal);
								}
							}

							if ($mneumonico_curso !== '') {
								if (!array_key_exists($mneumonico_curso, $home_cache_irmaos_por_mneumonico)) {
									$home_cache_irmaos_por_mneumonico[$mneumonico_curso] = array();
									$query_irmaos = new WP_Query(array(
										'post_type' => 'any',
										'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
										'posts_per_page' => 80,
										'fields' => 'ids',
										'suppress_filters' => true,
										'meta_query' => array(
											'relation' => 'OR',
											array(
												'key' => 'mneumonico',
												'value' => $mneumonico_curso,
												'compare' => '=',
											),
											array(
												'key' => 'mnemonico',
												'value' => $mneumonico_curso,
												'compare' => '=',
											),
										),
									));
									if (!empty($query_irmaos->posts)) {
										$home_cache_irmaos_por_mneumonico[$mneumonico_curso] = array_values(array_unique(array_map('intval', $query_irmaos->posts)));
									}
									wp_reset_postdata();
								}

								if (!empty($home_cache_irmaos_por_mneumonico[$mneumonico_curso])) {
									foreach ($home_cache_irmaos_por_mneumonico[$mneumonico_curso] as $id_irmao) {
										$post_ids_fontes[] = (int) $id_irmao;
									}
								}
							}

							$post_ids_fontes = array_values(array_unique(array_filter(array_map('intval', $post_ids_fontes))));
							foreach ($post_ids_fontes as $post_id_fonte) {
								$adicionar_categorias_area_do_post($post_id_fonte);
							}

							// Fallback global seguro: usa somente posts com titulo exatamente igual ao do card.
							if (empty($categorias_card_map)) {
								$titulo_curso_card = trim((string) ($curso['curso'] ?? $curso['nome'] ?? ''));
								$titulo_curso_card_norm = $normalizar_titulo_exato_home($titulo_curso_card);

								if (!array_key_exists($titulo_curso_card_norm, $home_cache_titulo_exato)) {
									$home_cache_titulo_exato[$titulo_curso_card_norm] = $buscar_posts_por_titulo_exato_home($titulo_curso_card);
								}

								$ids_titulo_exato = $home_cache_titulo_exato[$titulo_curso_card_norm];
								if (!empty($ids_titulo_exato)) {
									$debug_source_terms[] = 'title-exato:' . $titulo_curso_card;
									foreach ($ids_titulo_exato as $id_titulo_exato) {
										$adicionar_categorias_area_do_post($id_titulo_exato);
									}
								}
							}

							// Remove categorias de modalidade do filtro de area (presencial/digital/aovivo).
							$categorias_card_ids = array();
							$categorias_card_nomes = array();
							foreach ($categorias_card_map as $cat_id_val => $nome_cat_original) {
								$nome_cat_val = strtolower(trim((string) $nome_cat_original));
								if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
									continue;
								}
								$cat_id_val = (string) $cat_id_val;
								if (!in_array($cat_id_val, $categorias_card_ids, true)) {
									$categorias_card_ids[] = $cat_id_val;
								}
								if ($nome_cat_val !== '' && !in_array($nome_cat_original, $categorias_card_nomes, true)) {
									$categorias_card_nomes[] = $nome_cat_original;
								}
							}

							$categorias_card_nomes_norm = array();
							foreach ($categorias_card_nomes as $nome_cat_card) {
								$nome_norm_card = function_exists('remove_accents') ? remove_accents((string) $nome_cat_card) : (string) $nome_cat_card;
								$nome_norm_card = strtolower(trim($nome_norm_card));
								if ($nome_norm_card !== '' && !in_array($nome_norm_card, $categorias_card_nomes_norm, true)) {
									$categorias_card_nomes_norm[] = $nome_norm_card;
								}
							}

							$categorias_card_nomes_texto = implode(', ', $categorias_card_nomes);
							$categorias_card_ids_texto = implode(',', $categorias_card_ids);
							$categorias_card_nomes_norm_texto = implode('|', $categorias_card_nomes_norm);
							$permalink_curso = aplicar_sufixo_modalidade_home_url($permalink_curso, $modalidade_normalizada);
					?>
							<?php
								// Inserir selo apenas para cursos específicos (normaliza e verifica substring)
								$curso_titulo_bruto = trim((string) ($curso['curso'] ?? $curso['nome'] ?? ''));
								$curso_titulo_norm = function_exists('remove_accents') ? remove_accents($curso_titulo_bruto) : $curso_titulo_bruto;
								$curso_titulo_norm = strtolower(trim(preg_replace('/[^a-z0-9\s]/iu', ' ', $curso_titulo_norm)));
								$mostrar_selo = home_match_curso_destaque($curso_titulo_norm, $selo_cursos_destaque_normalized);
							?>
							<div class="box-item" data-mneumonico="<?php echo esc_attr($mneumonico_curso); ?>" data-modalidade="<?php echo esc_attr($modalidade_normalizada); ?>" data-category-ids="<?php echo esc_attr($categorias_card_ids_texto); ?>" data-category-names="<?php echo esc_attr($categorias_card_nomes_norm_texto); ?>">
								<?php if ($mostrar_selo): ?>
									<img class="box-badge" src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/04/selo-em-destaque.png" alt="Selo em destaque" loading="lazy" aria-hidden="true">
								<?php endif; ?>
								<span class="selecionaCategoria" style="opacity:0;position:absolute;z-index:-999">
									<?php echo esc_html($categorias_card_nomes_texto); ?>
								</span>
								<div class="boxColor <?php echo esc_attr($bgClass); ?>"></div>
					<p class="seloType <?php echo esc_attr($colorClass); ?>"><?php echo esc_html($curso['tipo'] ?? 'PÓS-GRADUAÇÃO'); ?></p>
					<div class="innerMod <?php echo esc_attr($innerClass); ?>">
						<span class="categoria"><?php echo esc_html($modalidade); ?></span>
					</div>
					<h3 class="titleBox" style="font-size: 15px;line-height: 16px;">
						<a class="nameCurso" href="<?php echo esc_url($permalink_curso); ?>" style="text-decoration: none; color: #333;">
							<?php echo esc_html($curso['curso'] ?? $curso['nome'] ?? ''); ?>
						</a>

					</h3>

					<div class="apiGets">
						<p class="partir <?php echo esc_attr($colorClass); ?>">A partir de:</p>
						<span class="valorSDesconto">
							<?php
							if (isset($curso['precos']) && is_numeric($curso['precos'])) {
								$preco = (float) $curso['precos'];
								$valorIntegral = number_format($preco / 0.4, 2, ',', '.');
								$modalidade_tipo = $curso['modalidade'] ?? 'Presencial';
								$modalidade_norm = normalizar_modalidade_home($modalidade_tipo);
								
								if ($modalidade_norm === 'presencial' || $modalidade_norm === 'digitalaovivo') {
									echo "18x de R$ {$valorIntegral}";
								} else {
									echo "R$ {$valorIntegral}";
								}
							}
							?>
						</span>
						<span class="dezoitoxSDesconto" style="text-decoration:none">&nbsp;por:</span>
						<div class="pula"></div>
						<!-- <span class="dozexCDesconto apenasPresencial <?php echo esc_attr($colorClass); ?>">R$</span> -->
						<span class="valorCDesconto" style="display: inline;" data-valor-base="<?php echo esc_attr(isset($curso['precos']) && is_numeric($curso['precos']) ? number_format((float) $curso['precos'], 2, '.', '') : ''); ?>">
							<?php
							$preco_base_card = (isset($curso['precos']) && is_numeric($curso['precos'])) ? (float) $curso['precos'] : 0;
							if ($preco_base_card > 0) {
								if (in_array($modalidade_normalizada, array('presencial', 'digitalaovivo'), true)) {
									$parcela_base_card = (float) $preco_base_card;
									$chave_parcela_base_card = number_format($parcela_base_card, 2, '.', '');
									$mapa_parcela_presencial_18x = array(
										'448.50' => 299.00,
										'598.50' => 399.00,
										'898.50' => 599.00,
									);
									$mapa_parcela_digitalaovivo_18x = array(
										'298.50' => 199.00,
										'448.50' => 299.00,
										'598.50' => 399.00,
									);

									if ($modalidade_normalizada === 'presencial' && isset($mapa_parcela_presencial_18x[$chave_parcela_base_card])) {
										echo 'A partir de 18x de R$ ' . number_format((float) $mapa_parcela_presencial_18x[$chave_parcela_base_card], 2, ',', '.');
									} elseif ($modalidade_normalizada === 'digitalaovivo' && isset($mapa_parcela_digitalaovivo_18x[$chave_parcela_base_card])) {
										echo 'A partir de 18x de R$ ' . number_format((float) $mapa_parcela_digitalaovivo_18x[$chave_parcela_base_card], 2, ',', '.');
									} else {
										echo 'A partir de 12x de R$ ' . number_format($parcela_base_card, 2, ',', '.');
									}
								} else {
									echo 'R$ ' . number_format($preco_base_card, 2, ',', '.');
								}
							}
							?>
						</span>
						<p class="ateFinal">no cartão de crédito</p>
						<div class="pula"></div>
						<span class="apenasEAD"></span>
						<div class="wrapThings">
							<h6 class="carga <?php echo esc_attr($colorClass); ?>">Duração:</h6>
							<p class="conteudoBox">
								<span class="cargaHoraria"><?php echo esc_html($curso['semestres'] ?? ''); ?></span>
								<span> meses</span>
							</p>
						</div>
						<?php
						$unidade = $curso['campus'] ?? '';
						$mostrarUnidade = true;

						if (stripos($unidade, 'Polo') !== false) {
							$mostrarUnidade = false;
						}

						if ($mostrarUnidade) {
							if (stripos($unidade, 'Campus') !== false) {
								$unidade = trim(str_ireplace('Campus', '', $unidade));
							}
							$unidade = preg_replace('/\s*\(.*?\)\s*/', '', $unidade);

							$unidade_normalizada = function_exists('remove_accents') ? remove_accents((string) $unidade) : (string) $unidade;
							$unidade_normalizada = strtolower(trim($unidade_normalizada));
							if ($unidade_normalizada === 'webconferencia') {
								$unidade = 'Digital ao Vivo';
							}
							?>
							<div class="wrapThings apenasPresencial apenasPresencialUnidade" style="">
								<h6 class="unidades <?php echo esc_attr($colorClass); ?>">Unidades:</h6>
								<div class="unidadeContent"><?php echo esc_html($unidade); ?></div>
							</div>
							<?php
						}
						?>
					</div>
					<div class="innerInscreva">
						<a href="<?php echo esc_url($permalink_curso); ?>" class="cursoPage">
							<div id="btnInscreva" class="btnInscreva btn">SAIBA MAIS</div>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<p>Nenhum curso encontrado.</p>
		<?php endif; ?>

		<!-- BOXES -->
		</div>
		<div class="btnVerMaisCursos btn">VER MAIS +</div>
</section>

<!-- ---- TOP 10 ---- -->	

<section class="top10" style="display:none !important">
		<div class="center">
			<h2 class="top10 comBarra">Top Cursos mais procurados</h2>
			<div class="swiper top10Sw">
			<div class="swiper-wrapper">
			
			<?php
			// Lista dos cursos desejados na ordem específica, com modalidade explícita

			// Busca o array de cursos do ACF (nome_do_curso, modalidade_do_curso, url_do_curso)
			$cursos_get_geral = get_field('top_cursos', 'option');
			$cursos_desejados = array();

			if (!empty($cursos_get_geral) && is_array($cursos_get_geral)) {
				foreach ($cursos_get_geral as $curso) {
					$nome = isset($curso['nome_do_curso']) ? $curso['nome_do_curso'] : '';
					$modalidade = isset($curso['modalidade_do_curso']) ? $curso['modalidade_do_curso'] : '';
					$url = isset($curso['url_do_curso']) ? $curso['url_do_curso'] : null;
					$bloqueio = isset($curso['bloqueio']) ? $curso['bloqueio'] : 'Habilitado';

					// Só adiciona se não estiver bloqueado
					if ($nome && $modalidade && strtolower($bloqueio) === 'habilitado') {
						$item = array('modalidade' => $modalidade);
						if (!empty($url)) {
							$item['vem_ai_url'] = $url;
						}
						$cursos_desejados[$nome] = $item;
					}
				}
			}

			// Busca os cursos na ordem desejada e modalidade correta
			$cursos_top = array();
			foreach ($cursos_desejados as $curso_nome => $info) {
				$modalidade_desejada = $info['modalidade'];
				$vem_ai_url = isset($info['vem_ai_url']) ? $info['vem_ai_url'] : null;
				$args = array(
					'post_type'      => 'graduacao',
					'title'          => $curso_nome,
					'posts_per_page' => 1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'name',
							'terms'    => $modalidade_desejada,
						),
					),
				);
				$query = new WP_Query($args);
				if ($query->have_posts()) {
					$query->the_post();
					$curso_id = get_the_ID();
					$cursos_top[] = array(
						'id' => $curso_id,
						'modalidade' => $modalidade_desejada,
						'nome' => $curso_nome,
						'vem_ai_url' => $vem_ai_url
					);
					wp_reset_postdata();
				} else {
					// Adiciona card "VEM AÍ" se não existir
					$cursos_top[] = array(
						'id' => null,
						'modalidade' => $modalidade_desejada,
						'nome' => $curso_nome,
						'vem_ai_url' => $vem_ai_url
					);
				}
			}

				// Exibe os cursos na ordem desejada, com a classe correta na categoriaTop
				if (!empty($cursos_top)) :
					foreach ($cursos_top as $curso) :
							// Filtro funcional: exibe apenas modalidade solicitada
						$modalidade_top_bruta = $curso['modalidade'];
						$modalidade_top_normalizada = normalizar_modalidade_home($modalidade_top_bruta);
						if ($filtrar_modalidade_unica && $modalidade_top_normalizada !== $filtrar_modalidade_unica) continue;
					if ($curso['id']) {
						$post = get_post($curso['id']);
						setup_postdata($post);
						$modalidade = $curso['modalidade'];
						?>
						<div class="swiper-slide innerProcurado" data-mneumonico-top="<?php echo esc_attr((string) (get_post_meta(get_the_ID(), 'mneumonico', true) ?: get_post_meta(get_the_ID(), 'mnemonico', true))); ?>">
							<?php 
								$permalink_top = get_permalink();
							?>
							<a class="clickTop" href="<?php echo esc_url($permalink_top); ?>">
								<div class="wrapProcurado">
									<p class="seloTypeTop10">PÓS-GRADUAÇÃO</p>
									<div class="innerModTop10">
										<?php
										if ($modalidade === 'Presencial') {
											echo '<span class="categoriaTop presencial">Presencial</span> ';
										} elseif ($modalidade === 'Digital (EaD)') {
											echo '<span class="categoriaTop">Digital (EaD)</span> ';
										} elseif ($modalidade === 'Semipresencial' || $modalidade === 'Digital ao Vivo') {
											echo '<span class="categoriaTop">Digital ao Vivo</span> ';
										}
										?>
									</div>
									<h3 class="titleTop10" style="font-size: 17px;"><?php the_title(); ?></h3>
									<p><?php the_excerpt(); ?></p>
									<?php if (has_post_thumbnail()) : ?>
										<div class="course-thumbnail">
											<?php the_post_thumbnail('medium_large', array('loading' => 'lazy', 'decoding' => 'async')); ?>
										</div>
									<?php endif; ?>
									<?php
									// Busca a carga horária do curso na API pelo nome
									$carga_horaria = '';
											$titulo_top_norm = function_exists('mb_strtolower') ? mb_strtolower(trim((string) get_the_title()), 'UTF-8') : strtolower(trim((string) get_the_title()));
											if ($titulo_top_norm !== '' && isset($cursos_por_titulo_norm_home[$titulo_top_norm])) {
												$carga_horaria = $cursos_por_titulo_norm_home[$titulo_top_norm]['semestres'] ?? '';
									}
									if (empty($carga_horaria)) {
										$carga_horaria = '8';
									}
									?>
															<p class="cargaHorariaTop">Duração: <?php echo esc_html($carga_horaria); ?> meses</p>
									<p class="course-price">

									<!-- comentado a parte que diferencia entre modalidades A partir de:  -->
										<!-- <?php if ($modalidade === 'Presencial'): ?>
											<span class="apenasPresencialTop" style="font-weight:600;">A partir de: R$</span>
										<?php elseif ($modalidade === 'Digital (EaD)'): ?>
											<span class="apenasEADTop" style="font-weight:600;">A partir de: R$</span>
										<?php elseif ($modalidade === 'Semipresencial' || $modalidade === 'Digital ao Vivo'): ?>
											<span class="apenasSemipresencialTop" style="font-weight:600;">A partir de: R$</span>
										<?php endif; ?> -->
									<!-- comentado a parte que diferencia entre modalidades A partir de:  -->
										<br>
										<span style="font-weight:600;">A partir de: R$</span>
										<span class="valorTop10"></span>
									</p>
								</div>
							</a>
						</div>
						<?php
					} else {
						// Card "VEM AÍ"
						$modalidade = $curso['modalidade'];
						$vem_ai_url = $curso['vem_ai_url'] ?? '#';
						?>
						<div class="swiper-slide innerProcurado vem-ai" data-mneumonico-top="">
							<a class="clickTop" href="<?php echo esc_url($vem_ai_url); ?>" target="_blank">
								<div class="wrapProcurado">
									<p class="seloTypeTop10">PÓS-GRADUAÇÃO</p>
									<div class="innerModTop10">
										<?php
										if ($modalidade === 'Presencial') {
											echo '<span class="categoriaTop presencial">Presencial</span> ';
										} elseif ($modalidade === 'Digital (EaD)') {
											echo '<span class="categoriaTop">Digital (EaD)</span> ';
										} elseif ($modalidade === 'Semipresencial' || $modalidade === 'Digital ao Vivo') {
											echo '<span class="categoriaTop">Digital ao Vivo</span> ';
										}
										?>
									</div>
									<h3 class="titleTop10" style="margin-top:-18px;font-size: 17px;"><?php echo esc_html($curso['nome']); ?></h3>
									<div class="course-thumbnail">
										<img src="https://cursos.unisuam.edu.br/wp-content/uploads/2025/07/imagem_assistente-social-1.png" alt="">
									</div>
									<div class="vemAiText">VEM AÍ</div>
								</div>
							</a>
						</div>
						<?php
					}
				endforeach;
				wp_reset_postdata();
			else :
				echo '<p>Nenhum curso encontrado.</p>';
			endif;
			?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
	<div class="button-next buttonsSwiper"><?php echo $arrowRightTop10; ?></div>
	<div class="button-prev buttonsSwiper"><?php echo $arrowLeftTop10; ?></div>
</section>

<!-- ---- TOP 10 ---- -->

<div id="lancamentos"></div>
<!-- bloco novidades  -->
 <?php include 'novos-cursos.php' ?>
<!-- bloco novidades  -->


<section class="sobrePos" id="sobre">
	<div class="center">
		<h2 class="sobre comBarra">Sobre a Pós UNISUAM</h2>
		<div class="wrapContent">
			
			<div class="column column1">
				<h4 class="titleColumn" style="font-size:23px;">Dê o próximo passo na sua carreira. <span style="color:#EF7D00">Comece agora!</span></h4>
				<p class="contentColumn">
					Com mais de 55 anos de excelência educacional e nota máxima pelo MEC (5), a UNISUAM oferece mais de 45 cursos de Pós-Graduação Lato Sensu nas áreas de gestão, saúde, educação, direito, arquitetura, engenharia, estética e gastronomia com formações a partir de 6 meses, nas modalidades presencial, digital ao vivo ou digital (EAD).<br><br>
					Nossos cursos contam com laboratórios modernos, salas equipadas para aulas práticas e um corpo docente formado por mestres e doutores referência em suas áreas.<br><br> Aqui, aprendizado e conexão andam juntos e sua rede cresce junto com você.
				</p>
			</div>

			<div class="column column2">
				<div class="wrapIcon"><?php echo $iconEnsino; ?></div>
				<h4 class="titleColumn">Modalidades de Ensino</h4>
				<p class="contentColumn">
				Você escolhe como estudar: <b>presencial, digital ao vivo ou Digital (EaD).</b>
				</p><br>

				<div class="wrapIcon"><?php echo $iconFormacao; ?></div>
				<h4 class="titleColumn">Formação Acelerada</h4>
				<p class="contentColumn">
				A partir de <b>6 meses</b>, você recebe o seu certificado de especialista.
				</p><br>

				<div class="wrapIcon"><?php echo $iconConexao; ?></div>
				<h4 class="titleColumn">Conexão e Networking</h4>
				<p class="contentColumn">
				Construa uma rede sólida com profissionais de diferentes áreas e <b>amplie suas oportunidades.</b>
				</p><br>
			</div>

			<div class="column column3">
				<div class="wrapIcon"><?php echo $iconDiversidade; ?></div>
				<h4 class="titleColumn">Diversidade de Cursos</h4>
				<p class="contentColumn">
				<b>Mais de 50 opções</b> de pós-graduação para você encontrar a especialização certa para o seu próximo passo.
				</p><br>

				<div class="wrapIcon"><?php echo $iconPonta; ?></div>
				<h4 class="titleColumn">Infraestrutura moderna</h4>
				<p class="contentColumn">
				Laboratórios modernos e salas equipadas para uma <b>experiência de aprendizado prática e aplicada.</b>
				</p><br>

				<div class="wrapIcon"><?php echo $iconDocente; ?></div>
				<h4 class="titleColumn">Corpo Docente Especializado</h4>
				<p class="contentColumn">
				<b>Mestres e doutores</b> referência em suas áreas, prontos para preparar você para os desafios do mercado.</p><br>
			</div>

		</div>
	</div>
</section>

<section class="modalidades">
	<div class="center">
		<h2 class="sobre comBarra">Modalidades de Ensino</h2>
		<div class="wrapContent">
			<div class="boxEnsino boxEnsino1">
				<h4 class="titleBoxEnsino">Cursos Presenciais</h4>
				<p class="contentBoxEnsino">
				<!-- <ul> -->
					<!-- <li>Ensino prático e multidisciplinar</li>
					<li>Infraestrutura completa e moderna</li>
					<li>Laboratórios de última geração</li>
					<li>Projetos integradores, aplicando a teoria em prática</li>
					<li>Núcleos voltados para o desenvolvimento de projetos e soluções</li> -->

					Contamos com salas equipadas para aulas práticas e laboratórios modernos. Oferecemos um espaço acolhedor para estudo, garantindo uma experiência completa e enriquecedora.

				<!-- </ul> -->
				</p>
				</p>
			</div>

			<div class="boxEnsino boxEnsino3">
				<h4 class="titleBoxEnsino">Cursos Digitais ao Vivo</h4>
				<p class="contentBoxEnsino">
				<!-- <ul> -->
					<!-- <li>Flexibilidade</li>
					<li>Aulas práticas presenciais semanais</li>
					<li>Aulas síncronas semanais</li>
					<li>Ambientação Digital</li>
					<li>Acesso a laboratórios, eventos, feiras, biblioteca</li> -->

					Para aqueles que, mesmo com a rotina corrida, desejam aquele encontro marcado com seu professor e sua turma. Nessa modalidade, você terá aulas ao vivo quinzenais, de forma online.

				<!-- </ul> -->
				</p>
				</p>
			</div>

			<div class="boxEnsino BoxEnsino2">
				<h4 class="titleBoxEnsino">Cursos Digital (EaD)</h4>
				<p class="contentBoxEnsino">
				<!-- <ul> -->
					<!-- <li>Flexibilidade</li>
					<li>Laboratórios Virtuais</li>
					<li>Conteúdo estruturado trimestralmente</li>
					<li>Aulas Ao Vivo</li>
					<li>Ambientação Digital</li>
					<li>Acesso liberado para a estrutura presencial</li> -->

					Praticidade, flexibilidade, baixo custo, alto retorno. Faça sua pós-graduação 100% Digital e estude onde quiser, de acordo com a sua disponibilidade. Certificação com a mesma validação dos cursos presenciais.

				<!-- </ul> -->
				</p>
			</div><br><br>



			<p class="contentBoxEnsino" style="color:rgba(87, 96, 111, 1) ;margin: 20px auto;max-width:700px;line-height: 23px;">Seja qual for a sua escolha de pós-graduação, aqui na UNISUAM você encontrará a flexibilidade, a qualidade e o suporte necessários para alcançar seus objetivos profissionais. Inscreva-se agora mesmo e dê o próximo passo na sua carreira!</p>
		</div>
</section>

<?php include 'nossas_escolas.php'; ?>

<style>
	.cardNew {
		background: #F7F8FA; 
		border-radius: 18px; 
		padding: 24px 24px 18px 24px;
		/* flex: 1 1 340px;  */
		min-width: 280px; 
		width: 48%;
		/* max-width: 420px;  */
		display: flex; 
		align-items: 
		/* flex-start; gap: 18px; */
	}

	.boxConheca {
		position: relative;
		display: inline-block;
		align-items: center;
		padding: 18px 24px;
		background: #FFFFFF;
		border-radius: 12px;
		width: 220px;
		height: 130px;
		/* float: left; */
		flex-basis: content;
	}
	.boxConheca svg {
		display: block;
		text-align: center;
		margin: 0 auto 12px auto;
		position: relative;
	}
	.boxConheca span {
		display: block;
		text-align: center;
		margin: 0 auto 12px auto;
		position: relative;
	}

</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
	// Swiper para o carrossel de escolas
	document.addEventListener('DOMContentLoaded', function() {
		new Swiper('.conhecaEscolasSwiper', {
			slidesPerView: 1,
			spaceBetween: 32,
			navigation: {
				nextEl: '.conhecaEscolasNext',
				prevEl: '.conhecaEscolasPrev',
			},
			pagination: {
				el: '.conhecaEscolasPag',
				clickable: true,
			},
			breakpoints: {
				1200: {
					slidesPerView: 4,
				},
				900: {
					slidesPerView: 3,
				},
				600: {
					slidesPerView: 1,
				}
			},
			loop: true,
			centeredSlides: false,
			grabCursor: true,
		});
	});
</script>

<section class="nossasUnidades" id="nossasUnidades">
    <div class="center">
        <h2 class="sobre comBarra">Nossas Unidades e Polos Digital (EaD)</h2>
        <div class="wrapContent">

        <div class="boxUnidade boxUnidade1">
            <div class="iconMapa"><?php echo $iconMap; ?></div>		
            <p class="unidade">UNIDADE</p>
            <h4 class="titleUnidade">Bangu</h4>
            <p class="endUndade">
            Rua Fonseca 240, Bangu Shopping
            </p>
        </div>

        <div class="boxUnidade boxUnidade1">
            <div class="iconMapa"><?php echo $iconMap; ?></div>		
            <p class="unidade">UNIDADE</p>
            <h4 class="titleUnidade">Bonsucesso</h4>
            <p class="endUndade">
            Av. Paris, 84
            </p>
        </div>

        <div class="boxUnidade boxUnidade1">
            <div class="iconMapa"><?php echo $iconMap; ?></div>		
            <p class="unidade">UNIDADE</p>
            <h4 class="titleUnidade">Campo Grande</h4>
            <p class="endUndade">
            Av. Cesário de Melo, 2541 P
            </p>
        </div>		

        <div class="wrapPolosD naoclicado">
            <div class="iconMapa"><?php echo $iconMap; ?></div><h4 class="titleUnidade">Polos Digital (EaD)</h4>
            <div class="wrapPolos"><br><br><br><br>
				<?php include 'polos-lista.php'; ?>
            </div>
            <div class="static">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 7L9 12L14 7" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>


        </div>
    </div>
</section>

<script>
    var swiper = new Swiper(".top10Sw", {
        slidesPerView: 1,
        spaceBetween: 30,
        cssMode: true,
        navigation: {
            nextEl: ".button-next",
            prevEl: ".button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        mousewheel: true,
        keyboard: true,
        breakpoints: {
            600: {
                slidesPerView: 3,
                spaceBetween: 50,
            },
        },
    });
  </script>
  <script>
	// control filtros mobile 
	$(".mobFiltros").click(function(){
		$(".lineTwo").addClass("active");
	});
	$(".btnRedcolher").click(function(){
		$(".lineTwo").removeClass("active");
	});
  </script>

  
<script>
	document.addEventListener('DOMContentLoaded', function() {
		if (window.__filtroAreaInteresseInicializado) {
			return;
		}
		window.__filtroAreaInteresseInicializado = true;

		const areaInteresse = document.getElementById('areaInteresse');
		const modalidade = document.getElementById('modalidade');
		const limparFiltros = document.getElementById('limparFiltros');
		if (!areaInteresse || !modalidade) {
			return;
		}

		const normalizar = function(valor) {
			return (valor || '')
				.toString()
				.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
				.toLowerCase()
				.trim();
		};

		const VALOR_CURSOS_DESTAQUE = 'cursos-destaque';
		const boxContainer = document.querySelector('.box-container');

		function atualizarVisualAreaInteresseDestaque() {
			areaInteresse.classList.toggle('areaInteresseDestaqueSelecionado', areaInteresse.value === VALOR_CURSOS_DESTAQUE);
		}

		function ordenarBoxesPorDestaque(ativarDestaque) {
			if (!boxContainer) {
				return;
			}

			var boxes = Array.from(boxContainer.querySelectorAll('.box-item'));
			boxes.sort(function(a, b) {
				var ordemA = parseInt(a.getAttribute('data-original-order') || '0', 10);
				var ordemB = parseInt(b.getAttribute('data-original-order') || '0', 10);

				if (!ativarDestaque) {
					return ordemA - ordemB;
				}

				var aEhDestaque = !!a.querySelector('.box-badge');
				var bEhDestaque = !!b.querySelector('.box-badge');
				if (aEhDestaque !== bEhDestaque) {
					return aEhDestaque ? -1 : 1;
				}

				return ordemA - ordemB;
			});

			boxes.forEach(function(box) {
				boxContainer.appendChild(box);
			});
		}

		function hidratarCategoriasDosCards() {
			const mapNomeParaId = new Map();
			Array.from(areaInteresse.options).forEach(function(opt) {
				const val = String(opt.value || '').trim();
				if (!val) return;
				const nome = normalizar(opt.text || '');
				if (!nome) return;
				if (!mapNomeParaId.has(nome)) {
					mapNomeParaId.set(nome, val);
				}
			});

			document.querySelectorAll('.box-item').forEach(function(box, index) {
				if (!box.hasAttribute('data-original-order')) {
					box.setAttribute('data-original-order', String(index));
				}

				const ids = new Set((box.getAttribute('data-category-ids') || '').split(',').map(function(v){ return v.trim(); }).filter(Boolean));
				const nomes = new Set((box.getAttribute('data-category-names') || '').split('|').map(normalizar).filter(Boolean));
				const legacy = (box.querySelector('.selecionaCategoria') || {}).textContent || '';
				legacy.split(',').forEach(function(parte) {
					const nome = normalizar(parte);
					if (!nome) return;
					nomes.add(nome);
					if (mapNomeParaId.has(nome)) {
						ids.add(mapNomeParaId.get(nome));
					}
				});

				box.setAttribute('data-category-ids', Array.from(ids).join(','));
				box.setAttribute('data-category-names', Array.from(nomes).join('|'));
				box.classList.add('selecionados');
			});

			// Propaga categorias entre todos os .box-item com o mesmo data-mneumonico
			// (mesmo curso em modalidades diferentes). Garante que ao filtrar por área,
			// ambas as modalidades apareçam, mesmo que só uma tenha categorias atribuídas.
			const mneumonicoMap = new Map();
			document.querySelectorAll('.box-item[data-mneumonico]').forEach(function(box) {
				const mnem = (box.getAttribute('data-mneumonico') || '').trim();
				if (!mnem) return;
				if (!mneumonicoMap.has(mnem)) mneumonicoMap.set(mnem, []);
				mneumonicoMap.get(mnem).push(box);
			});
			mneumonicoMap.forEach(function(boxes) {
				if (boxes.length < 2) return;
				// Coleta todas as categorias de todas as modalidades do mesmo curso
				const allIds = new Set();
				const allNames = new Set();
				boxes.forEach(function(box) {
					(box.getAttribute('data-category-ids') || '').split(',').map(function(v){ return v.trim(); }).filter(Boolean).forEach(function(id) { allIds.add(id); });
					(box.getAttribute('data-category-names') || '').split('|').map(normalizar).filter(Boolean).forEach(function(n) { allNames.add(n); });
				});
				if (!allIds.size && !allNames.size) return;
				// Aplica as categorias combinadas em todas as modalidades
				boxes.forEach(function(box) {
					box.setAttribute('data-category-ids', Array.from(allIds).join(','));
					box.setAttribute('data-category-names', Array.from(allNames).join('|'));
				});
			});
		}


		function atualizarModalidades() {
			const modalidadeAtual = modalidade.value;
			const modalidadesSet = new Set();
			document.querySelectorAll('.box-item.selecionados').forEach(function(box) {
				const cat = box.querySelector('.categoria');
				if (cat) {
					modalidadesSet.add(cat.textContent.trim());
				}
			});
			modalidade.innerHTML = '<option value="">Todas</option>';
			modalidadesSet.forEach(function(mod) {
				const opt = document.createElement('option');
				opt.value = mod;
				opt.textContent = mod;
				modalidade.appendChild(opt);
			});
			if (modalidadeAtual && modalidadesSet.has(modalidadeAtual)) {
				modalidade.value = modalidadeAtual;
			}
		}

		// Filtro de área de interesse
		function onAreaInteresseChange(event) {
			if (event) {
				event.stopImmediatePropagation();
			}
			const selectedOption = areaInteresse.options[areaInteresse.selectedIndex];
			const selectedValue = selectedOption ? String(selectedOption.value || '') : '';
			const isCursosDestaque = selectedValue === VALOR_CURSOS_DESTAQUE;
			if (isCursosDestaque) {
				// Comporta-se como "Todas" para modalidade, alterando apenas a ordem dos cards.
				modalidade.value = '';
			}
			const boxes = document.querySelectorAll('.box-item');
			boxes.forEach(function(box) {
				const categoryIds = (box.getAttribute('data-category-ids') || '').split(',').map(function(v) { return v.trim(); }).filter(Boolean);
				const hasIdMatch = selectedValue !== '' && categoryIds.indexOf(selectedValue) !== -1;

				box.classList.remove('selecionados');
				box.style.display = 'none';

				if (selectedValue === '' || hasIdMatch || isCursosDestaque) {
					box.classList.add('selecionados');
				} else {
					box.classList.remove('selecionados');
				}
			});
			atualizarVisualAreaInteresseDestaque();
			atualizarModalidades();
			if (isCursosDestaque) {
				modalidade.value = '';
			}
			filtrarModalidade();
			ordenarBoxesPorDestaque(isCursosDestaque);
			setTimeout(function() {
				ordenarBoxesPorDestaque(isCursosDestaque);
			}, 0);
		}

		// Filtro de modalidade
		function filtrarModalidade(event) {
			if (event) {
				event.stopImmediatePropagation();
			}
			const selectedModalidade = modalidade.value;
			document.querySelectorAll('.box-item').forEach(function(box) {
				if (!box.classList.contains('selecionados')) {
					box.style.display = 'none';
					return;
				}
				const cat = box.querySelector('.categoria');
				if (!selectedModalidade || (cat && cat.textContent.trim() === selectedModalidade)) {
					box.style.display = '';
				} else {
					box.style.display = 'none';
				}
			});
		}

		areaInteresse.addEventListener('change', onAreaInteresseChange, true);
		modalidade.addEventListener('change', filtrarModalidade, true);

		if (limparFiltros) {
			limparFiltros.addEventListener('click', function(event) {
				event.stopImmediatePropagation();
				areaInteresse.value = '';
				modalidade.value = '';
				onAreaInteresseChange();
			}, true);
		}

		// Inicializa ao carregar a página
		hidratarCategoriasDosCards();
		atualizarVisualAreaInteresseDestaque();
		onAreaInteresseChange();
	});
	</script>

	<script>
	// REGRA FÁCIL DE ENCONTRAR: oculta no #unidade qualquer option com value "Webconferência".
	document.addEventListener('DOMContentLoaded', function() {
		var selectUnidade = document.getElementById('unidade');
		if (!selectUnidade) {
			return;
		}

		var normalizar = function(valor) {
			return (valor || '')
				.toString()
				.normalize('NFD')
				.replace(/[\u0300-\u036f]/g, '')
				.toLowerCase()
				.trim();
		};

		var ocultarWebconferencia = function() {
			Array.from(selectUnidade.options).forEach(function(opt) {
				if (normalizar(opt.value) === 'webconferencia') {
					opt.hidden = true;
					opt.style.display = 'none';
					if (selectUnidade.value === opt.value) {
						selectUnidade.value = '';
					}
				}
			});
		};

		var observer = new MutationObserver(ocultarWebconferencia);
		observer.observe(selectUnidade, { childList: true });
		setTimeout(function() {
			observer.disconnect();
		}, 30000);

		selectUnidade.addEventListener('focus', ocultarWebconferencia);
		selectUnidade.addEventListener('change', ocultarWebconferencia);
		ocultarWebconferencia();
	});
	// REGRA FÁCIL DE ENCONTRAR: oculta no #unidade qualquer option com value "Webconferência".
</script>

<!-- elimina o card, caso a página não exista ou esteja desativada de alguma forma -->
<script>
	$(document).ready(function(){
		// $(".box-item .cursoPage").each(function(){
		// 	if($(this).attr("href") === "#"){
		// 		$(this).closest('.box-item').remove();
		// 	}
		// });

		// muda o nome do curso de letras na home
		const namesCurso = document.querySelectorAll(".nameCurso");
		for(let nameCurso of namesCurso) {
			if(nameCurso.textContent.trim() === "Português/Literatura") {
				nameCurso.textContent = "Letras - Português/Literatura";
			};
			if(nameCurso.textContent.trim() === "Gestão de Cooperativas") {
				nameCurso.textContent = "Empreendedorismo Cooperativo";
			};
		};
		// muda o nome do curso de letras na home

		document.querySelectorAll(".nameCurso").forEach(function(el) {
			if (el.textContent.trim().length > 60) {
				el.style.setProperty("font-size", "11px", "important");
				el.style.setProperty("line-height", "0px", "important");
				var titleBox = el.closest(".titleBox");
				if (titleBox) {
					titleBox.style.setProperty("line-height", "13px", "important");
				}
			}
		});
		
	});
</script>
<!-- elimina o card, caso a página não exista ou esteja desativada de alguma forma -->

<style>
	.vemAiText {
		margin-top: 20px;
		position: relative;
		top: 50px;
		float: left;
	}
	.nameCurso {
		font-size: 14px !important;
	}

	/* Top 10: esconde qualquer <p> sem class dentro do card */
	.top10 .innerProcurado .wrapProcurado p:not([class]) {
		display: none !important;
	}
</style>

<script>
	const clicksTop = document.querySelectorAll(".clickTop");
	clicksTop.forEach(click => {
		if(click.getAttribute("href") === "#"){
			click.addEventListener("click", function(e){
				e.preventDefault();
			});
			click.style.cursor = "not-allowed";
		};
	});

	// Modal da Calculadora - Aparece quando URL contém &calculadora (apenas uma vez por sessão)
	function verificarCalculadoraURL() {
		// Verificar se modal já foi exibido nesta sessão
		if (sessionStorage.getItem('modalCalculadoraExibido')) {
			return; // Não exibir novamente
		}

		const urlParams = new URLSearchParams(window.location.search);
		if (urlParams.has('calculadora') || window.location.href.includes('&calculadora')) {
			document.getElementById('modalCalculadora').style.display = 'flex';
			// Marcar que modal foi exibido nesta sessão
			sessionStorage.setItem('modalCalculadoraExibido', 'true');
		}
	}

	// Fechar modal
	function fecharModalCalculadora() {
		document.getElementById('modalCalculadora').style.display = 'none';
	}

	// Executar verificação quando página carregar
	document.addEventListener('DOMContentLoaded', verificarCalculadoraURL);
</script>

<!-- Modal da Calculadora -->
<div id="modalCalculadora" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center;">
	<div style="background: white; border-radius: 15px; padding: 40px; max-width: 700px; width: 90%; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
		
		<!-- Botão X para fechar -->
		<button onclick="fecharModalCalculadora()" style="position: absolute; top: 0px; right: -20px; background: none; border: none; color: #333; font-size: 24px; cursor: pointer; font-weight: bold; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background-color 0.3s;">
			×
		</button>


		<!-- CONTEÚDO DO MODAL -->
		<div style="text-align: center; color: #333;" id="conteudoCalculadoraModal">
			<!-- Título Principal -->
			<h1 style="font-size: 32px; font-weight: bold; margin: 0 0 5px 0; line-height: 1.2; color: #2196f3;">
				ENCONTRE A MELHOR CONDIÇÃO PARA SUA <strong style="color: #e91e63; background-color: rgba(30, 111, 233, 0.3); padding: 2px 6px; border-radius: 4px;">PÓS-GRADUAÇÃO</strong><br>
			</h1>
			
			<!-- Subtitle com destaque -->
			<h2 style="font-size: 32px; font-weight: bold; margin: 0 0 5px 0; color: #e91e63; line-height: 1.2;">
				<strong style="color: #e91e63; background-color: rgba(30, 111, 233, 0.3); padding: 2px 6px; border-radius: 4px;">Escolha o seu curso e calcule o seu desconto agora!</strong>
			</h2><br><br>
		
			
			<!-- Botão principal -->
			<button onclick="fecharModalCalculadora()" class="btn btnInscreva" style="background-color: #2196f3; color: white; border: none; padding: 15px 40px; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; transition: all 0.3s; text-transform: uppercase;line-height: 19px;max-width: 300px;left:0;">
				ESCOLHER CURSO
			</button><br><br>

			<p style="font-size:12px;margin-bottom:0;">Consulte condições vigentes para o curso selecionado.</p>
		</div>
		<!-- CONTEÚDO DO MODAL -->




	</div>
</div>

<style>
	/* Efeitos hover para os botões do modal */
	#modalCalculadora button:hover {
		transform: translateY(-2px);
		/* box-shadow: 0 6px 20px rgba(0,0,0,0.3); */
	}

	#modalCalculadora button:first-of-type:hover {
		background-color: rgba(0,0,0,0.1);
	}

	#modalCalculadora .btn:hover {
		background-color: #1976d2 !important;
	}

	/* Responsivo */
	@media (max-width: 768px) {
		#modalCalculadora > div {
			padding: 30px 20px;
			margin: 20px;
			max-width: 400px;
		}
		
		#modalCalculadora h1 {
			font-size: 26px !important;
		}
		
		#modalCalculadora h2 {
			font-size: 26px !important;
		}
		
		#modalCalculadora h3 {
			font-size: 22px !important;
		}
		
		#modalCalculadora p {
			font-size: 16px !important;
		}
	}

	@media (max-width: 480px) {
		#modalCalculadora > div {
			padding: 25px 15px;
			margin: 15px;
		}
		
		#modalCalculadora h1 {
			font-size: 22px !important;
		}
		
		#modalCalculadora h2 {
			font-size: 22px !important;
		}
		
		#modalCalculadora h3 {
			font-size: 18px !important;
		}
		
		#modalCalculadora p {
			font-size: 14px !important;
		}
		
		#modalCalculadora button {
			padding: 12px 30px !important;
			font-size: 14px !important;
		}
	}

	/* Estilização específica para modal igual aos .box */
	#modalCalculadora .btnInscreva:hover {
		background-color: #e91e63 !important;
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(233,30,99,0.1);
	}
</style>

<?php
get_footer();
?>

<script>
	// PRÉ-SELECIONA #AREAINTERESSE BASEADO EM ?ESCOLA=... E DISPARA O FILTRO
	(function() {
		try {
			var params = new URLSearchParams(window.location.search);
			var escolaRaw = params.get('escola');
			if (!escolaRaw) return;

			var select = document.getElementById('areaInteresse');
			if (!select) return;

			// Normaliza removendo acentos, trocando por underscore e deixando minúsculo
			var normalize = function (s) {
				return (s || '')
					.normalize('NFD').replace(/[\u0300-\u036f]/g, '') // remove acentos
					.replace(/[^A-Za-z0-9]+/g, '_')                   // não-alfanumérico -> _
					.replace(/^_+|_+$/g, '')                          // trim underscores
					.toLowerCase();
			};

			var alvo = normalize(escolaRaw);
			var found = false;

			for (var i = 0; i < select.options.length; i++) {
				var optTextNorm = normalize(select.options[i].text);
				if (optTextNorm === alvo) {
					select.selectedIndex = i;
					found = true;
					break;
				}
			}

			if (found) {
				// Dispara change imediatamente e novamente após pequeno delay
				var fire = function() { select.dispatchEvent(new Event('change', { bubbles: true })); };
				fire();
				setTimeout(fire, 100);
			}
		} catch (e) {
			console.warn('Pré-seleção de #areaInteresse falhou:', e);
		}
	})();
</script>

<?php
	if (defined('WP_DEBUG') && WP_DEBUG) {
	// --- URLs para ativar cada opção do #areaInteresse (comentadas no HTML) ---
	$categorias = get_categories(array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
	));
	// Mesma lista de ignore usada na página
	$ignorar = array('Presencial', 'Digital (EaD)', 'Digital (ead)', 'digital (ead)', 'Sem categoria', '100digital');

	if (!function_exists('normalizar_param_escola')) {
		function normalizar_param_escola($str) {
			// remove acentos e caracteres especiais, mantendo padrão "Palavra_Palavra"
			$s = @iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			if ($s === false) $s = $str; // fallback
			// Mantém maiúsculas/minúsculas originais; troca não-alfanuméricos por underscore
			$s = preg_replace('/[^A-Za-z0-9]+/', '_', $s);
			$s = trim($s, '_');
			return $s;
		}
	}

	$base_url = home_url('/');
	$linhas = array();
	foreach ($categorias as $categoria) {
		$nome_cat = trim($categoria->name);
		if (in_array($nome_cat, $ignorar)) continue;
		$param = normalizar_param_escola($nome_cat);
		$linhas[] = " - {$nome_cat}: {$base_url}?escola={$param}";
	}

	// Imprime como comentário HTML no final da página
		if (!empty($linhas)) {
			echo "\n<!-- URLs para ativar cada opção do #areaInteresse:\n";
			echo implode("\n", $linhas);
			echo "\n-->\n";
		}
	}
?>

<script>
	/*
	Ajuste adicional de Área de Interesse quando ?estilo_vida_saude_bem_estar:
	- Após a remoção dos boxes não permitidos, reduz o select #areaInteresse
		somente às categorias dos boxes restantes.
	- Mantém o option "Todas" e os term_id originais (value).
	- Não altera nenhuma outra lógica anterior.
	*/
	(function() {
		// Desativado para nao interferir no filtro padrao por categorias do WordPress.
		return;
	const PARAM = 'estilo_vida_saude_bem_estar';
	if (!new URLSearchParams(location.search).has(PARAM)) return;

	document.addEventListener('DOMContentLoaded', function() {
		try {
		const selArea = document.getElementById('areaInteresse');
		if (!selArea) return;

		// Coleta categorias dos boxes remanescentes
		const categoriasSet = new Set();
		document.querySelectorAll('.box-item').forEach(box => {
			const span = box.querySelector('.selecionaCategoria');
			if (!span) return;
			span.textContent.split(',').forEach(raw => {
			const cat = raw.trim();
			if (cat) categoriasSet.add(cat);
			});
		});

		// Remove opções que não estão presentes nos boxes (preserva term_id dos que ficam)
		Array.from(selArea.options).forEach(opt => {
			const txt = (opt.text || '').trim();
			if (opt.value === '' || txt.toLowerCase() === 'todas') return;
			if (!categoriasSet.has(txt)) {
			selArea.removeChild(opt);
			}
		});

		// Dispara change para integrar com scripts já existentes
		selArea.dispatchEvent(new Event('change', { bubbles: true }));
		} catch (e) {
		console.warn('Ajuste de #areaInteresse falhou:', e);
		}
	});
	})();
</script>

<style>
	.dozexCDesconto {
		display: none !important;
	}
</style>

<!-- INICIO BLOCO TEMPORARIO: REGRA DE OFERTA VISUAL (ROLLBACK FACIL) -->
<script>
	/*
	* REGRA DE EXIBICAO DE PRECOS NOS CARDS (.box-item)
	*
	* Objetivo:
	* - Para cards cujo .valorCDesconto seja 399, 299, 599 ou 199,
	*   substituir o conteudo de .apiGets por um bloco visual na mesma estetica
	*   da referencia (rotulo, valor de riscado, "por:", valor em destaque e cupom).
	* - Manter visiveis APENAS:
	*   1) Duração
	*   2) Unidades
	* - Bloquear temporariamente qualquer outra regra (interna ou externa)
	*   que tente recolocar elementos extras dentro de .apiGets.
	*
	* Rollback:
	* - Remover todo o conteudo entre:
	*   INICIO BLOCO TEMPORARIO: REGRA DE OFERTA VISUAL (ROLLBACK FACIL)
	*   FIM BLOCO TEMPORARIO: REGRA DE OFERTA VISUAL (ROLLBACK FACIL)
	*/
	(function() {
		var OFERTAS = {
			'399': { de: 'R$ 6.463,80', parcela: 'R$ 513,65' },
			'299': { de: 'R$ 4.843,80', parcela: 'R$ 378,58' },
			'599': { de: 'R$ 9.703,80', parcela: 'R$ 783,65' },
			'199': { de: 'R$ 3.223,80', parcela: 'R$ 243,65' }
		};

		var OFERTA_DIGITAL_FIXA = {
			de: 'R$ 2.400,00',
			parcela: 'R$ 99,00',
			vezes: '12x de ',
			cupom: ''
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

		var CLASSE_REGRA = 'apiGetsCupomRegraAtiva';
		var ATTR_ORIGINAL = 'data-api-gets-original-html';
		var ATTR_ESTADO_REGRA = 'data-regra-oferta-estado';

		function paraNumero(valorTexto) {
			if (valorTexto === null || valorTexto === undefined) return NaN;

			var texto = String(valorTexto).trim();
			if (!texto) return NaN;

			if (/^-?\d+(\.\d+)?$/.test(texto)) {
				var numeroDireto = parseFloat(texto);
				return isFinite(numeroDireto) ? numeroDireto : NaN;
			}

			var matchMoeda = texto.match(/R\$\s*([0-9\.,]+)/i);
			if (matchMoeda && matchMoeda[1]) {
				texto = matchMoeda[1];
			}

			texto = texto
				.replace(/\./g, '')
				.replace(',', '.')
				.replace(/[^0-9.-]/g, '');

			var numero = parseFloat(texto);
			return isFinite(numero) ? numero : NaN;
		}

		function formatarMoedaBR(numero) {
			return Number(numero).toLocaleString('pt-BR', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			});
		}

		function normalizarValorNumerico(valorTexto) {
			var numero = paraNumero(valorTexto);
			if (!isFinite(numero)) return '';

			// Compara apenas a parte inteira para bater com 399/299/599/199.
			return String(Math.round(numero));
		}

		function criarBlocoOfertaVisual(oferta) {
			var wrap = document.createElement('div');
			wrap.className = 'ofertaCupomVisual';

			var label = document.createElement('p');
			label.className = 'ofertaLabel';
			label.textContent = 'A partir de:';
			wrap.appendChild(label);

			var linha = document.createElement('p');
			linha.className = 'ofertaLinhaPrecos';

			var deEl = document.createElement('span');
			deEl.className = 'ofertaPrecoDe';
			deEl.textContent = oferta.de;
			linha.appendChild(deEl);

			var porEl = document.createElement('span');
			porEl.className = 'ofertaPor';
			porEl.textContent = ' por:';
			linha.appendChild(porEl);

			wrap.appendChild(linha);

			var parcela = document.createElement('p');
			parcela.className = 'ofertaParcela';
			parcela.textContent = (oferta.vezes || '12x de ') + oferta.parcela;
			wrap.appendChild(parcela);

			var ateFinal = document.createElement('p');
			ateFinal.className = 'ateFinal';
			ateFinal.textContent = 'no cartão de crédito';
			wrap.appendChild(ateFinal);

			var textoCupom = Object.prototype.hasOwnProperty.call(oferta, 'cupom')
				? String(oferta.cupom || '').trim()
				: 'com o cupom 300NAPOS';

			if (textoCupom) {
				var cupom = document.createElement('p');
				cupom.className = 'ofertaCupom';
				cupom.textContent = textoCupom;
				wrap.appendChild(cupom);
			}

			return wrap;
		}

		function capturarBlocosPermitidos(apiGets) {
			var permitidos = [];

			var duracao = null;
			var unidades = apiGets.querySelector('.apenasPresencialUnidade');

			var wraps = Array.prototype.slice.call(apiGets.querySelectorAll('.wrapThings'));
			duracao = wraps.find(function(el) {
				return !!el.querySelector('.cargaHoraria');
			}) || null;

			if (duracao) permitidos.push(duracao.cloneNode(true));
			if (unidades) permitidos.push(unidades.cloneNode(true));

			return permitidos;
		}

		function restaurarOriginal(apiGets) {
			var htmlOriginal = apiGets.getAttribute(ATTR_ORIGINAL);
			if (!htmlOriginal) return;
			apiGets.innerHTML = htmlOriginal;
			apiGets.classList.remove(CLASSE_REGRA);
			apiGets.removeAttribute(ATTR_ESTADO_REGRA);
		}

		function aplicarRegraNoCard(card) {
			if (!card || card.nodeType !== 1) return;

			var apiGets = card.querySelector('.apiGets');
			var valorNode = card.querySelector('.valorCDesconto');
			var modalidadeCard = (card.getAttribute('data-modalidade') || '').toLowerCase();
			var categoriaNode = card.querySelector('.categoria');
			var categoriaTexto = categoriaNode ? categoriaNode.textContent || '' : '';
			var categoriaNormalizada = categoriaTexto
				.toString()
				.normalize('NFD')
				.replace(/[\u0300-\u036f]/g, '')
				.replace(/\s+/g, ' ')
				.trim()
				.toLowerCase();
			var cardEhPresencial = modalidadeCard === 'presencial';
			var cardEhDigitalAoVivo = modalidadeCard === 'digitalaovivo' || categoriaNormalizada === 'digital ao vivo';
			if (!apiGets || !valorNode) return;

			if (!apiGets.getAttribute(ATTR_ORIGINAL)) {
				apiGets.setAttribute(ATTR_ORIGINAL, apiGets.innerHTML);
			}

			if (cardEhPresencial || cardEhDigitalAoVivo) {
				if (apiGets.classList.contains(CLASSE_REGRA)) {
					restaurarOriginal(apiGets);
					valorNode = card.querySelector('.valorCDesconto');
					if (!valorNode) return;
				}

				var valorBase12x = paraNumero(valorNode.getAttribute('data-valor-base'));
				if (!isFinite(valorBase12x)) {
					valorBase12x = paraNumero(valorNode.textContent || '');
				}

				if (isFinite(valorBase12x) && valorBase12x > 0) {
					var parcelaBase12x = valorBase12x;
					var chaveParcelaBase12x = Number(parcelaBase12x).toFixed(2);

					if (
						cardEhPresencial &&
						Object.prototype.hasOwnProperty.call(MAPA_PARCELA_PRESENCIAL_18X, chaveParcelaBase12x)
					) {
						var texto18x = '18x de R$ ' + formatarMoedaBR(MAPA_PARCELA_PRESENCIAL_18X[chaveParcelaBase12x]);
						if ((valorNode.textContent || '').trim() !== texto18x) {
							valorNode.textContent = texto18x;
						}
						apiGets.setAttribute(ATTR_ESTADO_REGRA, 'parcelado-18x-map');
					} else if (
						cardEhDigitalAoVivo &&
						Object.prototype.hasOwnProperty.call(MAPA_PARCELA_DIGITAL_AOVIVO_18X, chaveParcelaBase12x)
					) {
						var texto18xDigitalAoVivo = '18x de R$ ' + formatarMoedaBR(MAPA_PARCELA_DIGITAL_AOVIVO_18X[chaveParcelaBase12x]);
						if ((valorNode.textContent || '').trim() !== texto18xDigitalAoVivo) {
							valorNode.textContent = texto18xDigitalAoVivo;
						}
						apiGets.setAttribute(ATTR_ESTADO_REGRA, 'parcelado-18x-map-digital-aovivo');
					} else {
						var texto12x = '18x de R$ ' + formatarMoedaBR(parcelaBase12x);
						if ((valorNode.textContent || '').trim() !== texto12x) {
							valorNode.textContent = texto12x;
						}
						apiGets.setAttribute(ATTR_ESTADO_REGRA, 'parcelado-12x');
					}
				}

				var textoCartao = apiGets.querySelector('.ateFinal');
				if (textoCartao) {
					textoCartao.textContent = 'no cartão de crédito';
				}

				return;
			}

			var valorBaseOferta = paraNumero(valorNode.getAttribute('data-valor-base'));
			var chaveOferta = normalizarValorNumerico(isFinite(valorBaseOferta) ? valorBaseOferta : (valorNode.textContent || ''));
			var oferta = modalidadeCard === 'digital'
				? OFERTA_DIGITAL_FIXA
				: OFERTAS[chaveOferta];
			var estadoOferta = modalidadeCard + '|' + chaveOferta;

			if (!oferta) {
				if (apiGets.classList.contains(CLASSE_REGRA)) {
					restaurarOriginal(apiGets);
				}
				return;
			}

			if (apiGets.classList.contains(CLASSE_REGRA) && apiGets.getAttribute(ATTR_ESTADO_REGRA) === estadoOferta) {
				return;
			}

			var blocosPermitidos = capturarBlocosPermitidos(apiGets);

			// Reconstroi a area para remover qualquer outro item inserido por outras regras.
			apiGets.innerHTML = '';
			apiGets.classList.add(CLASSE_REGRA);
			apiGets.appendChild(criarBlocoOfertaVisual(oferta));
			apiGets.setAttribute(ATTR_ESTADO_REGRA, estadoOferta);

			blocosPermitidos.forEach(function(bloco) {
				apiGets.appendChild(bloco);
			});
		}

		function aplicarRegraEmTodosCards() {
			document.querySelectorAll('.box-item').forEach(aplicarRegraNoCard);
		}

		function iniciarRegra() {
			aplicarRegraEmTodosCards();

			var agendamento = null;
			var agendarAplicacao = function() {
				if (agendamento) {
					return;
				}
				agendamento = setTimeout(function() {
					agendamento = null;
					aplicarRegraEmTodosCards();
				}, 80);
			};

			// Reaplica quando outros scripts modificarem os cards/API area.
			var observer = new MutationObserver(function(mutations) {
				for (var i = 0; i < mutations.length; i++) {
					if (mutations[i].type === 'childList') {
						agendarAplicacao();
						break;
					}
				}
			});

			var alvoObserver = document.querySelector('.box-container') || document.body;
			observer.observe(alvoObserver, {
				childList: true,
				subtree: true
			});
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', iniciarRegra);
		} else {
			iniciarRegra();
		}
	})();
</script>

<style>
	/* Estetica visual da oferta gerada (inspirada no layout de referencia). */
	.apiGets.apiGetsCupomRegraAtiva .ofertaCupomVisual {
		margin: 0 0 8px 0;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaLabel {
		margin: 0;
		font-size: 14px;
		line-height: 1.1;
		color: #d81f3a;
		font-weight: 700;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaLinhaPrecos {
		margin: 2px 0 0 0;
		font-size: 18px;
		line-height: 1.05;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaPrecoDe {
		font-size: 14px;
		font-weight: 400;
		text-decoration: line-through;
		color: #8f8f95;
		letter-spacing: -0.2px;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaPor {
		font-size: 14px;
		font-weight: 400;
		color: #666b73;
		margin-left: 4px;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaParcela {
		margin: 2px 0 0 0;
		font-size: 29px;
		line-height: 1;
		font-weight: 800;
		color: #1e2a37;
		letter-spacing: -0.4px;
		margin-bottom: 4px;
	}

	.box-item .apiGets .ateFinal {
		font-size: 12px;
		line-height: 1.2;
	}

	.box-item[data-modalidade="presencial"] .apiGets .ateFinal {
		display: none !important;
	}

	.box-item[data-modalidade="digitalaovivo"] .apiGets .ateFinal {
		display: none !important;
	}

	.apiGets.apiGetsCupomRegraAtiva .ateFinal {
		margin: 0 0 8px 0;
		font-size: 12px;
		font-weight: 600;
		line-height: 1.2;
		color: #606c7a;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaCupom {
		margin: 2px 0 8px 0;
		font-size: 16px;
		font-weight: 600;
		line-height: 1.2;
		color: #606c7a;
	}

	.apiGets.apiGetsCupomRegraAtiva .wrapThings {
		margin-top: 6px;
	}

	.apiGets.apiGetsCupomRegraAtiva .carga {
		margin-top: 0;
	}

	/* TEMPORARIO (ROLLBACK NO MESMO BLOCO): esconde o preco do Top 10 em .course-price. */
	.top10 .innerProcurado .course-price {
		display: none !important;
	}
	.innerProcurado {
		height: 320px !important;
	}
</style>
<!-- FIM BLOCO TEMPORARIO: REGRA DE OFERTA VISUAL (ROLLBACK FACIL) -->

<!-- INICIO BLOCO TEMPORARIO: URLS DE FILTRO (ROLLBACK FACIL) -->
<script>
	/*
	* BLOCO TEMPORARIO: FILTROS VIA URL
	*
	* Objetivo:
	* - Permitir abrir a home ja filtrada por:
	*   1) area de interesse: ?area=...
	*   2) modalidade: ?modalidade=...
	*   3) unidade: ?unidade=...
	*
	* Regras:
	* - Aplica os filtros na ordem: area -> modalidade -> unidade.
	* - Aceita texto do option (ex.: "Saude", "DIGITAL AO VIVO", "Bangu") ou value exato.
	* - Para modalidade, tambem aceita aliases: semipresencial/webconferencia/aovivo.
	*
	* Rollback:
	* - Remover todo o conteudo entre:
	*   INICIO BLOCO TEMPORARIO: URLS DE FILTRO (ROLLBACK FACIL)
	*   FIM BLOCO TEMPORARIO: URLS DE FILTRO (ROLLBACK FACIL)
	*/
	(function() {
		function normalizar(valor) {
			return (valor || '')
				.toString()
				.normalize('NFD')
				.replace(/[\u0300-\u036f]/g, '')
				.toLowerCase()
				.replace(/\s+/g, ' ')
				.trim();
		}

		function normalizarModalidadeParam(valor) {
			var base = normalizar(valor);
			if (base === 'semipresencial' || base === 'webconferencia' || base === 'aovivo' || base === 'digital ao vivo') {
				return 'digitalaovivo';
			}
			if (base === 'ead' || base === 'digital' || base === 'digital (ead)') {
				return 'digital';
			}
			if (base === 'presencial') {
				return 'presencial';
			}
			return base;
		}

		function selecionarOpcao(select, alvo, ehModalidade) {
			if (!select || !alvo) return false;

			var alvoNorm = ehModalidade ? normalizarModalidadeParam(alvo) : normalizar(alvo);
			var opcoes = Array.prototype.slice.call(select.options || []);
			if (!opcoes.length) return false;

			for (var i = 0; i < opcoes.length; i++) {
				var opt = opcoes[i];
				var valueNorm = ehModalidade ? normalizarModalidadeParam(opt.value) : normalizar(opt.value);
				var textNorm = ehModalidade ? normalizarModalidadeParam(opt.textContent) : normalizar(opt.textContent);
				if (valueNorm === alvoNorm || textNorm === alvoNorm) {
					select.value = opt.value;
					return true;
				}
			}

			return false;
		}

		function dispararChange(select) {
			select.dispatchEvent(new Event('change', { bubbles: true }));
		}

		function esperar(ms) {
			return new Promise(function(resolve) { setTimeout(resolve, ms); });
		}

		function esperarOpcaoCompativel(select, alvo, ehModalidade, timeoutMs) {
			if (!select || !alvo) {
				return Promise.resolve(true);
			}

			if (existeOpcaoCompativel(select, alvo, ehModalidade)) {
				return Promise.resolve(true);
			}

			return new Promise(function(resolve) {
				var resolvido = false;
				var observer = new MutationObserver(function() {
					if (existeOpcaoCompativel(select, alvo, ehModalidade)) {
						if (!resolvido) {
							resolvido = true;
							observer.disconnect();
							clearTimeout(timer);
							resolve(true);
						}
					}
				});

				observer.observe(select, { childList: true });

				var timer = setTimeout(function() {
					if (!resolvido) {
						resolvido = true;
						observer.disconnect();
						resolve(false);
					}
				}, timeoutMs || 8000);
			});
		}

		function existeOpcaoCompativel(select, alvo, ehModalidade) {
			if (!select || !alvo) return true;
			var alvoNorm = ehModalidade ? normalizarModalidadeParam(alvo) : normalizar(alvo);
			return Array.prototype.slice.call(select.options || []).some(function(opt) {
				var valueNorm = ehModalidade ? normalizarModalidadeParam(opt.value) : normalizar(opt.value);
				var textNorm = ehModalidade ? normalizarModalidadeParam(opt.textContent) : normalizar(opt.textContent);
				return valueNorm === alvoNorm || textNorm === alvoNorm;
			});
		}

		async function aplicarFiltrosViaUrl() {
			var params = new URLSearchParams(window.location.search);
			var areaParam = params.get('area') || '';
			var modalidadeParam = params.get('modalidade') || '';
			var unidadeParam = params.get('unidade') || '';

			if (!areaParam && !modalidadeParam && !unidadeParam) {
				return;
			}

			var selectArea = document.getElementById('areaInteresse');
			var selectModalidade = document.getElementById('modalidade');
			var selectUnidade = document.getElementById('unidade');

			if (!selectArea || !selectModalidade || !selectUnidade) {
				return;
			}

			if (areaParam) {
				if (selecionarOpcao(selectArea, areaParam, false)) {
					dispararChange(selectArea);
				}
				await esperar(180);
			}

			if (modalidadeParam) {
				if (selecionarOpcao(selectModalidade, modalidadeParam, true)) {
					dispararChange(selectModalidade);
				}
				await esperar(180);
			}

			// unidade: aplicada em home.js apos popular #unidade (ver aplicarUnidadeDaUrl).
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', aplicarFiltrosViaUrl);
		} else {
			aplicarFiltrosViaUrl();
		}
	})();
</script>

<?php
	if (defined('WP_DEBUG') && WP_DEBUG) {
	/*
	* BLOCO TEMPORARIO: GERADOR DE URLS DE FILTRO (COMENTADAS)
	*
	* Objetivo:
	* - Gerar no HTML (comentario) as URLs prontas para:
	*   1) cada Area de Interesse
	*   2) cada Modalidade
	*   3) cada Unidade
	*
	* Parametros usados:
	* - area=<nome da area>
	* - modalidade=<presencial|digitalaovivo|digital>
	* - unidade=<nome da unidade>
	*/

	if (!function_exists('home_url_filter_build_query')) {
		function home_url_filter_build_query($params) {
			$limpos = array();
			foreach ($params as $k => $v) {
				$v = is_string($v) ? trim($v) : '';
				if ($v !== '') {
					$limpos[$k] = $v;
				}
			}
			return http_build_query($limpos);
		}
	}

	$base_home_url = home_url('/');

	// 1) URLs de Area de Interesse
	$categorias_area = get_categories(array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
	));
	$categorias_bloqueadas = array('presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo');

	$urls_area = array();
	if (is_array($categorias_area)) {
		foreach ($categorias_area as $cat) {
			$nome_area = trim((string) ($cat->name ?? ''));
			$nome_area_norm = strtolower($nome_area);
			if ($nome_area === '' || in_array($nome_area_norm, $categorias_bloqueadas, true)) {
				continue;
			}
			$query = home_url_filter_build_query(array('area' => $nome_area));
			$urls_area[] = array(
				'label' => $nome_area,
				'url'   => $base_home_url . ($query ? ('?' . $query) : ''),
			);
		}
	}
	usort($urls_area, function($a, $b) {
		return strcasecmp($a['label'], $b['label']);
	});

	// 2) URLs de Modalidade
	$modalidades_map = array(
		'PRESENCIAL'      => 'presencial',
		'DIGITAL AO VIVO' => 'digitalaovivo',
		'DIGITAL (EAD)'   => 'digital',
	);
	$urls_modalidade = array();
	foreach ($modalidades_map as $rotulo_modalidade => $valor_modalidade) {
		$query = home_url_filter_build_query(array('modalidade' => $valor_modalidade));
		$urls_modalidade[] = array(
			'label' => $rotulo_modalidade,
			'url'   => $base_home_url . ($query ? ('?' . $query) : ''),
		);
	}

	// 3) URLs de Unidade (a partir do payload de cursos da home)
	$urls_unidade = array();
	$unidades_mapa = array();
	if (!empty($cursos) && is_array($cursos)) {
		foreach ($cursos as $curso_item) {
			if (!is_array($curso_item)) {
				continue;
			}
			$unidade_bruta = trim((string) ($curso_item['campus'] ?? ''));
			if ($unidade_bruta === '') {
				continue;
			}
			if (stripos($unidade_bruta, 'Polo') !== false) {
				continue;
			}
			$unidade_limpa = $unidade_bruta;
			if (stripos($unidade_limpa, 'Campus') !== false) {
				$unidade_limpa = trim(str_ireplace('Campus', '', $unidade_limpa));
			}
			$unidade_limpa = preg_replace('/\s*\(.*?\)\s*/', '', $unidade_limpa);
			$unidade_limpa = trim((string) $unidade_limpa);
			if ($unidade_limpa === '') {
				continue;
			}

			$unidade_norm = function_exists('remove_accents') ? remove_accents($unidade_limpa) : $unidade_limpa;
			$unidade_norm = strtolower(trim($unidade_norm));
			if ($unidade_norm === 'webconferencia') {
				$unidade_limpa = 'Digital ao Vivo';
			}

			$chave = strtolower(function_exists('remove_accents') ? remove_accents($unidade_limpa) : $unidade_limpa);
			$chave = trim((string) $chave);
			if ($chave === '') {
				continue;
			}
			$unidades_mapa[$chave] = $unidade_limpa;
		}
	}

	if (!empty($unidades_mapa)) {
		asort($unidades_mapa, SORT_NATURAL | SORT_FLAG_CASE);
		foreach ($unidades_mapa as $unidade_label) {
			$query = home_url_filter_build_query(array('unidade' => $unidade_label));
			$urls_unidade[] = array(
				'label' => $unidade_label,
				'url'   => $base_home_url . ($query ? ('?' . $query) : ''),
			);
		}
	}

?>

<?php } ?>