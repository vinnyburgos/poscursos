
<?php
/*
Template Name: Escola de Saúde
Template Post Type: page
*/

/**
 * Template para a página "Escola de Saúde" (escola-de-saude).
 *
 * Este arquivo exibe a página da Escola de Saúde.
 */

get_header(); 
?>
<!-- TOPO ESTILO ESCOLA DE SAÚDE (igual print 2) -->
<section class="escola-header-bg">
	<div class="escola-header-content">
		<div class="escola-breadcrumb">Home &gt; Pós-Graduação &gt; Escola de Saúde</div>
		<div class="escola-header-title">ESCOLA DE SAÚDE</div>

		<div class="escola-header-sub">Especialize-se e transforme sua atuação na saúde: da prática clínica à liderança hospitalar.</div><br><br><br>
	</div>
</section>

<style>
	.body.admin-bar #masthead.site-header {
		padding-top: 0 !important;
	}
.escola-header-bg {
	background: linear-gradient(90deg, #0076A8 0%, #009FC6 100%);
	background: url("https://poscursos.unisuam.edu.br/wp-content/uploads/2026/04/saude.png") no-repeat center center / cover;
	padding: 0;
	min-height: 220px;
	position: relative;
	display: block;
	align-items: flex-end;
	box-shadow: 0 4px 24px rgba(0,0,0,0.07);
}
@media(max-width: 768px) {
	.escola-header-bg {
		background: linear-gradient(90deg, #0076A8 0%, #009FC6 100%);
	}
}
.escola-header-content {
	width: 100%;
	max-width: 1200px;
	margin: 0 auto;
	padding: 48px 32px 32px 32px;
	color: #fff;
	position: relative;
}
.escola-breadcrumb {
	font-size: 0.95rem;
	/* color: #B2E6F7; */
	margin-bottom: 12px;
	letter-spacing: 0.02em;
}
.escola-header-title {
	font-size: 2.6rem;
	font-weight: 800;
	margin: 0 0 8px 0;
	letter-spacing: 0.01em;
	line-height: 1.1;
}
.escola-header-sub {
	font-size: 1.25rem;
	font-weight: 500;
	margin: 0 0 8px 0;
	letter-spacing: 0.01em;
	line-height: 1.2;
}
</style>

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
?>

<?php
	$home_js_version = @filemtime(__DIR__ . '/escolas.js') ?: time();
	$home_css_version = @filemtime(__DIR__ . '/escolas.css') ?: time();
?>
<script src="<?php echo esc_url(get_template_directory_uri() . '/escolas.js?ver=' . $home_js_version); ?>"></script>
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/escolas.css?ver=' . $home_css_version); ?>">

<?php $upload_dir = wp_upload_dir(); ?>

<?php
	$tipo_modalidade = get_field('tipo_modalidade', 'option');
	$titulo_da_home = get_field('titulo_da_home', 'option');
	$texto_do_subtitulo = get_field('texto_do_subtitulo', 'option');
	$imagem_de_fundo_header_home = get_field('imagem_de_fundo_header_home', 'option');
?>



<style>
	@media(max-width:1131px) {
		.wrapInfos {
			margin-top: 5px;
		}
	}
</style>

<!-- area de busca  -->
	<!-- <div class="findBar" id="findBar"><img class="findInterBar" src="<?php echo $upload_dir['baseurl']; ?>/2025/06/find.png" alt="" style="display:none">
		<input type="text" id="findCurso" class="findCurso" placeholder="PESQUISE UM CURSO">
		<div class="btnFindCurso btn" id="btnFindCurso">PESQUISAR</div>

        <div class="mobFiltros" id="mobFiltros">
            <span style="display: flex; align-items: center;"><?php echo $mobFiltros; ?></span>
            <span style="display: flex; align-items: center;">FILTROS</span>
        </div>
	</div> -->

	<div class="filtrosBar" style="display:none">
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
							<option value="">Todas</option>
							<?php foreach ($categorias as $categoria): ?>
								<?php
									$nome_cat = trim($categoria->name);
									$nome_cat_normalizado = strtolower($nome_cat);
									if (in_array($nome_cat_normalizado, $ignorar, true)) continue;
								?>
								<option class="optCat" value="<?php echo esc_attr($categoria->term_id); ?>">
									<?php echo esc_html($nome_cat); ?>
								</option>
							<?php endforeach; ?>
						</select>

						<script>
						(function() {
							var BLOQUEADAS = ['presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo'];
							var normalizar = function(valor) {
								return (valor || '')
									.toString()
									.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
									.toLowerCase()
									.trim();
							};
							var removerOpcoesBloqueadas = function(select) {
								if (!select) return;
								Array.from(select.options).forEach(function(opt) {
									if (BLOQUEADAS.indexOf(normalizar(opt.textContent)) !== -1) {
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
								});
								observer.observe(selectArea, { childList: true });
								removerOpcoesBloqueadas(selectArea);
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


<?php  include 'cursos-slide.php'; ?>


<script>
		document.addEventListener('DOMContentLoaded', function () {
			const tituloConheca = document.querySelector('.titleConheca');
			if (tituloConheca) {
				tituloConheca.textContent = 'CONHEÇA OS CURSOS DA ESCOLA DE SAÚDE';
			}
		});
</script>


<script>
	function verMais() {
		const btnVerMais = document.querySelector(".btnVerMaisCursos");
		const areaCursos = document.querySelector(".box-container");
		let currentHeight = 380;

		btnVerMais.addEventListener("click", function() {
			currentHeight += 380;
			areaCursos.style.maxHeight = `${currentHeight}px`;

			if (areaCursos.scrollHeight <= currentHeight) {
				btnVerMais.style.display = 'none';
			}
		});
	}
	window.addEventListener('load', function() {
		verMais();
	});
</script>

<!-- <script>
	document.addEventListener('DOMContentLoaded', function() {
		var digitaisAoVivo = document.querySelectorAll('.box-item[data-modalidade="digitalaovivo"]');
		if (!digitaisAoVivo.length) {
			return;
		}

		digitaisAoVivo.forEach(function(box) {
			var partir = box.querySelector('.partir');
			if (partir) {
				partir.textContent = 'De:';
			}

			var valor = box.querySelector('.valorCDesconto');
			if (valor) {
				valor.textContent = 'R$ 399,00';
			}
 
			var ateFinal = box.querySelector('.ateFinal');
			if (ateFinal) {
				ateFinal.style.marginBottom = '0';
				ateFinal.innerHTML = 'até o fim do curso mediante aplicação do cupom: <span style=""><i>PRIMEIROS</i></span>';
			}
		});

		// Aplica valor 399 para cursos Digital ao Vivo na seção top10 TEMPORÁRIO!!!!
		setTimeout(function() {
			var topDigitaisAoVivo = $('.innerProcurado');
			topDigitaisAoVivo.each(function() {
				var $box = $(this);
				var $categoriaTop = $box.find('.categoriaTop');
				if ($categoriaTop.text().trim() === 'Digital ao Vivo') {
					var $valorTop = $box.find('.valorCDescontoTop');
					if ($valorTop.length) {
						$valorTop.text('399');
					}
				}
			});
		}, 5400);


	}); -->
</script>




<!-- Bloco institucional: A Pós é para você? -->
<section class="pos-para-voce" style="background: #fff; padding: 40px 0;">
	<div class="center" style="display: flex; align-items: center; justify-content: center; gap: 48px; flex-wrap: wrap;">
		<div class="pos-para-voce-ilustracao" style="flex: 0 0 320px; max-width: 340px; min-width: 220px; text-align: center;">
			<!-- Ilustração: substitua o src abaixo pela imagem desejada se necessário -->
			<img src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/03/saude-person.png" alt="Profissional de saúde" style="max-width: 100%; height: auto;" />
		</div>
		<div class="pos-para-voce-texto" style="flex: 1 1 400px; min-width: 280px;">
			<h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 18px; color: #23272F; display: flex; align-items: center; gap: 10px;">
				<span style="display: inline-block; width: 32px; height: 4px; background: #FF8C2B; border-radius: 2px; margin-right: 8px;"></span>
				A Pós-Graduação é para você?
			</h2>
			<p style="font-size: 1.08rem; color: #23272F; margin-bottom: 18px; line-height: 1.6;">
				As especializações da UNISUAM são feitas para quem vive os desafios da saúde de perto  e sabe que conhecimento salva, transforma e previne. <br><br>
				<b>Nossos cursos foram pensados para profissionais que:</b>
			</p>
			<ul class="lista-fa" style="font-size: 1.08rem; color: #23272F; margin-left: 0; padding-left: 0; line-height: 1.7; list-style: none;">
				<li><i class="fa-solid fa-diamonds-4 lista-fa-icon" aria-hidden="true"></i>Estão na linha de frente do cuidado e querem evoluir na assistência com mais técnica e segurança clínica;</li>
				<li><i class="fa-solid fa-diamonds-4 lista-fa-icon" aria-hidden="true"></i>Atuam em clínicas, unidades básicas, hospitais ou ambulatórios e buscam especialização prática e aplicável;</li>
				<li><i class="fa-solid fa-diamonds-4 lista-fa-icon" aria-hidden="true"></i>Querem assumir cargos de liderança em enfermagem, farmácia, gestão hospitalar ou saúde pública;</li>
				<li><i class="fa-solid fa-diamonds-4 lista-fa-icon" aria-hidden="true"></i>Desejam se aprofundar em áreas como terapia intensiva, saúde coletiva, análises clínicas, estética ou saúde mental;</li>
				<li><i class="fa-solid fa-diamonds-4 lista-fa-icon" aria-hidden="true"></i>Sabem que, em saúde, atualização não é um diferencial, é uma necessidade..</li>
			</ul>
		</div>
	</div>
</section>

<style>
	<style>
	.pos-para-voce ul.lista-fa {
		list-style: none;
		margin-left: 0;
		padding-left: 0;
	}

	.pos-para-voce ul.lista-fa li {
		margin-top: 5px;
		font-size: 13px;
		display: flex;
		align-items: flex-start;
		gap: 8px;
	}

	.pos-para-voce .lista-fa-icon {
		font-size: 0;
		line-height: 1;
		margin-top: 6px;
		color: #23272F;
		min-width: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.pos-para-voce .lista-fa-icon::before {
		content: "❖" !important;
		font-family: inherit !important;
		font-size: 10px;
		font-weight: 700;
		line-height: 1;
		color: #23272F;
	}
</style>
</style>



<section class="modalidades">
	<div class="center">
		<h2 class="sobre comBarra">Modalidades de Ensino</h2>
		<div class="wrapContent">
			<div class="boxEnsino boxEnsino1">
				<h4 class="titleBoxEnsino">PRESENCIAL</h4>
				<p class="contentBoxEnsino">
				<!-- <ul> -->
					<!-- <li>Ensino prático e multidisciplinar</li>
					<li>Infraestrutura completa e moderna</li>
					<li>Laboratórios de última geração</li>
					<li>Projetos integradores, aplicando a teoria em prática</li>
					<li>Núcleos voltados para o desenvolvimento de projetos e soluções</li> -->

					<b>Vivencie o aprendizado em laboratórios de última geração</b> e aulas 100% práticas. <br><br>
					É a escolha ideal para quem busca <b>networking de alto nível</b> com professores que são referência no mercado e troca direta de experiências em tempo real.

				<!-- </ul> -->
				</p>
				</p>
			</div>

			<div class="boxEnsino boxEnsino3">
			<h4 class="titleBoxEnsino">DIGITAL AO VIVO</h4>
				<p class="contentBoxEnsino">
				<!-- <ul> -->
					<!-- <li>Flexibilidade</li>
					<li>Aulas práticas presenciais semanais</li>
					<li>Aulas síncronas semanais</li>
					<li>Ambientação Digital</li>
					<li>Acesso a laboratórios, eventos, feiras, biblioteca</li> -->

					<b>O equilíbrio perfeito entre tecnologia e conexão.</b> <br><br>Participe de aulas em tempo real, interaja com grandes nomes da saúde e tire dúvidas na hora, unindo a flexibilidade do digital à força do aprendizado colaborativo.


				<!-- </ul> -->
				</p>
				</p>
			</div>

			<div class="boxEnsino BoxEnsino2">
				<h4 class="titleBoxEnsino">DIGITAL (EaD)</h4>
				<p class="contentBoxEnsino">
				<!-- <ul> -->
					<!-- <li>Flexibilidade</li>
					<li>Laboratórios Virtuais</li>
					<li>Conteúdo estruturado trimestralmente</li>
					<li>Aulas Ao Vivo</li>
					<li>Ambientação Digital</li>
					<li>Acesso liberado para a estrutura presencial</li> -->

					<b>A solução definitiva para quem precisa conciliar plantões e rotina intensa.</b> <br><br>Estude com <b>conteúdo de excelência</b> onde e quando quiser, garantindo uma especialização de peso com a flexibilidade que a sua agenda exige.


				<!-- </ul> -->
				</p>
			</div><br><br>



			<p class="contentBoxEnsino" style="color:rgba(87, 96, 111, 1) ;margin: 20px auto;max-width:700px;line-height: 23px;">Seja qual for a sua escolha, na UNISUAM você encontra flexibilidade, qualidade e o suporte necessário para alcançar seus objetivos profissionais. Matricule-se e dê o próximo passo na sua carreira.</p>
		</div>
</section>




<!-- Bloco institucional: LOREM IPSUM DOLOR -->
<section class="bloco-lorem-institucional" style="background: #fff; padding: 48px 0 32px 0;">
	<div class="center" style="max-width: 1100px; margin: 0 auto;">
		<div style="text-align: center;">
			<span style="display: inline-block; width: 48px; height: 4px; background: #FF8C2B; border-radius: 2px; margin-bottom: 16px;"></span>
			<h2 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 18px; color: #23272F; letter-spacing: 0.01em; text-transform: uppercase;">DIFERENCIAIS QUE IMPULSIONAM SUA CARREIRA</h2>
			<!-- <p style="font-size: 1.13rem; color: #23272F; margin-bottom: 32px; line-height: 1.6; max-width: 700px; margin-left: auto; margin-right: auto;">
				Lorem ipsum dolor sit amet consectetur. Ac in sit donec at. Blandit adipiscing mauris quis erat erat odio commodo cras. Mus massa sed in rhoncus vulputate. Pharetra facilisi maecenas tellus nunc et lectus.
			</p> --><br>
		</div>
		<div class="lorem-grid" style="display: flex; flex-wrap: wrap; gap: 24px 32px; justify-content: center; margin-bottom: 36px;">
			<!-- Card 1 -->
			<div class="cardNew">
				<span style="display: flex; align-items: baseline; justify-content: center; min-width: 48px;">
					<img style="width:90%;" src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/icons/icon-formacao-acelerada.svg" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<span>
					<strong style="font-size: 1.13rem; color: #23272F; display: block; margin-bottom: 4px;">Formação acelerada:</strong>
					<span style="font-size: 1rem; color: #23272F;">
					Conclua sua Pós a partir de <b>6 meses (EAD)</b> ou em <b>1 ano (Presencial e Ao Vivo)</b>. Qualificação rápida e robusta para atuar com segurança na assistência e gestão.</span>
				</span>
			</div>
			<!-- Card 2 -->
			<div class="cardNew">
				<span style="display: flex; align-items: baseline; justify-content: center; min-width: 48px;">
					<img style="width:90%;" src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/icons/icon-infra.svg" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<span>
					<strong style="font-size: 1.13rem; color: #23272F; display: block; margin-bottom: 4px;">Infraestrutura de ponta: </strong>
					<span style="font-size: 1rem; color: #23272F;">Acesse laboratórios de última geração para simulações e práticas. Desenvolva habilidades técnicas em um ambiente que reproduz os desafios reais das unidades de saúde.</span>
				</span>
			</div>
			<!-- Card 3 -->
			<div class="cardNew">
				<span style="display: flex; align-items: baseline; justify-content: center; min-width: 48px;">
					<img style="width:90%;" src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/icons/icon-corpo-docente.svg" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<span>
					<strong style="font-size: 1.13rem; color: #23272F; display: block; margin-bottom: 4px;">Corpo docente referência: </strong>
					<span style="font-size: 1rem; color: #23272F;">Aprenda com <b>especialistas, mestres e doutores atuantes</b> no mercado. Vá além dos livros: estude protocolos atualizados e o que há de mais novo nas práticas de saúde.</span>
				</span>
			</div>
			<!-- Card 4 -->
			<div class="cardNew">
				<span style="display: flex; align-items: baseline; justify-content: center; min-width: 48px;">
					<img style="width:90%;" src="https://poscursos.unisuam.edu.br/wp-content/uploads/2026/icons/icon-conteudo.svg" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<span>
					<strong style="font-size: 1.13rem; color: #23272F; display: block; margin-bottom: 4px;">Do jeito que cabe na sua rotina:</strong>	
					<span style="font-size: 1rem; color: #23272F;">Flexibilidade para quem vive em plantões. Escolha entre o aprendizado na unidade, aulas gravadas ou interatividade ao vivo, garantindo evolução constante sem parar de trabalhar.</span>
				</span>
			</div>
		</div>
	</div>
</section>

<section class="historias">
	<h2 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 18px; color: #23272F; letter-spacing: 0.01em; text-transform: uppercase;text-align:center;">HISTÓRIAS QUE INSPIRAM</h2>
	
	<div style="text-align: center; margin-top: 36px;">
		<p style="font-size: 1.35rem; color: #23272F; margin-bottom: 0; max-width:900px;margin: 0 auto;">
			 A aluna <b>Gabriela Ramaciote</b> conta como a especialização em Estética Clínica com Procedimentos Intradérmicos e Injetáveis deu a segurança e o destaque que ela precisava para dominar o mercado de estética.
		</p><br><br>
	</div>
	<div style="text-align:center;">

		<iframe width="560" height="315" src="https://www.youtube.com/embed/buJy569fnkU?si=ZkzDZUskqAgqLOrU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

</div>

<br><br>


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
	body {
		overflow: hidden;
	}

</style>


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


<style>
	@media(max-width:768px) {
		.swiper-horizontal {
			text-align: center;
		}
		.conheca-escolas {
			margin-top: -110px;
		}
	}
	.footer {
		margin-bottom: -100px !important;
	}
	.page:not(.home) #content {
        padding-bottom: 0;
    }
	section.pos-para-voce {
		max-width: 1300px !important;
		margin: 0 auto !important;
	}
	/* .conheca-escolas {
		background-color: #F1F2F6 !important;
	}
	.nossasUnidades {
		background-color: #fff;
	} */
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

	// Diagnostico direcionado: exibe no console o mapeamento de categoria do curso ANC.
	setTimeout(function() {
		var cardAnc = document.querySelector('.box-item[data-mneumonico="ANC"]');
		if (!cardAnc) {
			return;
		}
		console.log('[FILTRO-ANC]', {
			titulo: ((cardAnc.querySelector('.nameCurso') || {}).textContent || '').trim(),
			categoryIds: cardAnc.getAttribute('data-category-ids') || '',
			categoryNames: cardAnc.getAttribute('data-category-names') || '',
			sourcePostIds: cardAnc.getAttribute('data-source-post-ids') || '',
			sourceTerms: cardAnc.getAttribute('data-source-terms') || ''
		});
	}, 1200);

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
		// ...existing code...
				if (mapNomeParaId.has(nome)) {
					ids.add(mapNomeParaId.get(nome));
				}
			});

			box.setAttribute('data-category-ids', Array.from(ids).join(','));
			box.setAttribute('data-category-names', Array.from(nomes).join('|'));
			box.classList.add('selecionados');
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
        const boxes = document.querySelectorAll('.box-item');
        boxes.forEach(function(box) {
			const categoryIds = (box.getAttribute('data-category-ids') || '').split(',').map(function(v) { return v.trim(); }).filter(Boolean);
			const hasIdMatch = selectedValue !== '' && categoryIds.indexOf(selectedValue) !== -1;

            box.classList.remove('selecionados');
            box.style.display = 'none';

			if (selectedValue === '' || hasIdMatch) {
                box.classList.add('selecionados');
            } else {
                box.classList.remove('selecionados');
            }
        });
        atualizarModalidades();
        filtrarModalidade();
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
	observer.observe(selectUnidade, { childList: true, subtree: true });
	ocultarWebconferencia();
});
// REGRA FÁCIL DE ENCONTRAR: oculta no #unidade qualquer option com value "Webconferência".
</script>


<!-- elimina o card, caso a página não exista ou esteja desativada de alguma forma -->
<script>



				/**
				 * REGRA DE FILTRO FIXO POR ÁREA DE INTERESSE
				 * -------------------------------------------
				 * Exibe apenas os .box-item que possuem a área de interesse desejada no atributo data-category-names.
				 * Para alterar a área, basta modificar o valor da variável AREA_DESEJADA abaixo.
				 * Para desativar, basta comentar ou remover este bloco.
				 *
				 * Como funciona:
				 * - Normaliza o nome da área de interesse para facilitar a comparação.
				 * - Verifica o atributo data-category-names de cada .box-item.
				 * - Exibe apenas os cards que possuem a área desejada.
				 */
				document.addEventListener('DOMContentLoaded', function() {
					var AREA_DESEJADA = 'Saúde e Bem-Estar'; // <<< ALTERE AQUI O NOME DA ÁREA DESEJADA
					var normalizar = function(valor) {
						return (valor || '')
							.toString()
							.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
							.toLowerCase()
							.trim();
					};
					var areaDesejadaNorm = normalizar(AREA_DESEJADA);
					var cards = document.querySelectorAll('.box-item');
					cards.forEach(function(card) {
						var nomes = (card.getAttribute('data-category-names') || '').split('|').map(normalizar);
						if (nomes.includes(areaDesejadaNorm)) {
							card.style.display = '';
						} else {
							card.style.display = 'none';
						}
					});
				});
				/**
				 * REGRA DE FILTRO FIXO POR ÁREA DE INTERESSE
				 * -------------------------------------------
				 * Exibe apenas os .box-item que possuem a área de interesse desejada no atributo data-category-names.
				 * Para alterar a área, basta modificar o valor da variável AREA_DESEJADA abaixo.
				 * Para desativar, basta comentar ou remover este bloco.
				 *
				 * Como funciona:
				 * - Normaliza o nome da área de interesse para facilitar a comparação.
				 * - Verifica o atributo data-category-names de cada .box-item.
				 * - Exibe apenas os cards que possuem a área desejada.
				 */



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

	var CLASSE_REGRA = 'apiGetsCupomRegraAtiva';
	var ATTR_ORIGINAL = 'data-api-gets-original-html';

	function normalizarValorNumerico(valorTexto) {
		if (!valorTexto) return '';
		var base = String(valorTexto)
			.replace(/\s+/g, '')
			.replace('R$', '')
			.replace(/\./g, '')
			.replace(',', '.');

		var numero = parseFloat(base);
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
		parcela.textContent = '12x de ' + oferta.parcela;
		wrap.appendChild(parcela);

		var cupom = document.createElement('p');
		cupom.className = 'ofertaCupom';
		cupom.textContent = 'com o cupom 300NAPOS';
		wrap.appendChild(cupom);

		return wrap;
	}

	function criarBlocoOfertaDigitalEad() {
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
		deEl.textContent = 'R$ 2.400,00';
		linha.appendChild(deEl);

		var porEl = document.createElement('span');
		porEl.className = 'ofertaPor';
		porEl.textContent = ' por:';
		linha.appendChild(porEl);

		wrap.appendChild(linha);

		var parcela = document.createElement('p');
		parcela.className = 'ofertaParcela';
		parcela.textContent = '12x de R$ 99,00';
		wrap.appendChild(parcela);

		var cupom = document.createElement('p');
		cupom.className = 'ofertaCupom';
		cupom.textContent = 'com o cupom POSDIG99';
		wrap.appendChild(cupom);

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
	}

	function aplicarRegraNoCard(card) {
		if (!card || card.nodeType !== 1) return;

		var apiGets = card.querySelector('.apiGets');
		var valorNode = card.querySelector('.valorCDesconto');
		var modalidadeCard = (card.getAttribute('data-modalidade') || '').toLowerCase();
		if (!apiGets || !valorNode) return;

		if (!apiGets.getAttribute(ATTR_ORIGINAL)) {
			apiGets.setAttribute(ATTR_ORIGINAL, apiGets.innerHTML);
		}

		if (modalidadeCard === 'digital') {
			var blocosPermitidosDigital = capturarBlocosPermitidos(apiGets);

			apiGets.innerHTML = '';
			apiGets.classList.add(CLASSE_REGRA);
			apiGets.appendChild(criarBlocoOfertaDigitalEad());

			blocosPermitidosDigital.forEach(function(bloco) {
				apiGets.appendChild(bloco);
			});
			return;
		}

		var chaveOferta = normalizarValorNumerico(valorNode.textContent || '');
		var oferta = OFERTAS[chaveOferta];

		if (!oferta) {
			if (apiGets.classList.contains(CLASSE_REGRA)) {
				restaurarOriginal(apiGets);
			}
			return;
		}

		var blocosPermitidos = capturarBlocosPermitidos(apiGets);

		// Reconstroi a area para remover qualquer outro item inserido por outras regras.
		apiGets.innerHTML = '';
		apiGets.classList.add(CLASSE_REGRA);
		apiGets.appendChild(criarBlocoOfertaVisual(oferta));

		blocosPermitidos.forEach(function(bloco) {
			apiGets.appendChild(bloco);
		});
	}

	function aplicarRegraEmTodosCards() {
		document.querySelectorAll('.box-item').forEach(aplicarRegraNoCard);
	}

	function iniciarRegra() {
		aplicarRegraEmTodosCards();

		// Reaplica quando outros scripts modificarem os cards/API area.
		var observer = new MutationObserver(function(mutations) {
			for (var i = 0; i < mutations.length; i++) {
				var m = mutations[i];
				if (m.type === 'childList' || m.type === 'characterData') {
					aplicarRegraEmTodosCards();
					break;
				}
			}
		});

		observer.observe(document.body, {
			childList: true,
			subtree: true,
			characterData: true
		});

		// Camada extra de garantia contra scripts externos tardios.
		setInterval(aplicarRegraEmTodosCards, 1200);
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
		font-size: 28px;
		font-weight: 600;
		text-decoration: line-through;
		color: #8f8f95;
		letter-spacing: -0.2px;
	}

	.apiGets.apiGetsCupomRegraAtiva .ofertaPor {
		font-size: 17px;
		font-weight: 500;
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
		margin-bottom: 10px;
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

		if (unidadeParam) {
			var tentativas = 0;
			while (!existeOpcaoCompativel(selectUnidade, unidadeParam, false) && tentativas < 60) {
				await esperar(200);
				tentativas++;
			}
			if (selecionarOpcao(selectUnidade, unidadeParam, false)) {
				dispararChange(selectUnidade);
			}
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', aplicarFiltrosViaUrl);
	} else {
		aplicarFiltrosViaUrl();
	}
})();
</script>

<?php
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
<!--
LISTA COMPLETA DE URLS (UMA POR OPCAO EXISTENTE)

AREA DE INTERESSE
https://poscursos.unisuam.edu.br/?area=Direito
https://poscursos.unisuam.edu.br/?area=Educa%C3%A7%C3%A3o
https://poscursos.unisuam.edu.br/?area=Engenharia
https://poscursos.unisuam.edu.br/?area=Gastronomia
https://poscursos.unisuam.edu.br/?area=Gest%C3%A3o
https://poscursos.unisuam.edu.br/?area=Sa%C3%BAde
https://poscursos.unisuam.edu.br/?area=Tecnologia

MODALIDADE
https://poscursos.unisuam.edu.br/?modalidade=presencial
https://poscursos.unisuam.edu.br/?modalidade=digitalaovivo
https://poscursos.unisuam.edu.br/?modalidade=digital

UNIDADE
https://poscursos.unisuam.edu.br/?unidade=Bangu
https://poscursos.unisuam.edu.br/?unidade=Bonsucesso
https://poscursos.unisuam.edu.br/?unidade=Campo+Grande
-->
<!-- FIM BLOCO TEMPORARIO: URLS DE FILTRO (ROLLBACK FACIL) -->