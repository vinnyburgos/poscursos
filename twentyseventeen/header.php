<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">


<!-- mantem qualquer URI presete em toda a navegação  -->
	<?php
	add_action('wp_head', function () {
		?>
		<script>
		(function () {
			if (typeof URL === 'undefined' || typeof URLSearchParams === 'undefined') {
				return;
			}
			var storageKey = 'unisuamPersistentQuery';
			var currentSearch = window.location.search;
			var persistedQuery = '';
			try {
				if (currentSearch) {
					sessionStorage.setItem(storageKey, currentSearch);
				}
				persistedQuery = sessionStorage.getItem(storageKey) || '';
			} catch (error) {
				persistedQuery = currentSearch;
			}
			var queryToApply = currentSearch || persistedQuery;
			if (!queryToApply) {
				return;
			}
			if (!currentSearch && persistedQuery) {
				var updatedUrl = window.location.pathname + persistedQuery + window.location.hash;
				window.history.replaceState(null, '', updatedUrl);
			}
			var origin = window.location.origin || (window.location.protocol + '//' + window.location.host);
			document.addEventListener('DOMContentLoaded', function () {
				var persistentParams = new URLSearchParams(queryToApply);
				document.querySelectorAll('a[href]').forEach(function (anchor) {
					var href = anchor.getAttribute('href');
					if (!href || href.indexOf('#') === 0 || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0 || href.indexOf('javascript:') === 0) {
						return;
					}
					var url;
					try {
						url = new URL(href, origin);
					} catch (err) {
						return;
					}
					if (url.origin !== origin) {
						return;
					}
					var linkParams = new URLSearchParams(url.search);
					persistentParams.forEach(function (value, key) {
						if (!linkParams.has(key)) {
							linkParams.append(key, value);
						}
					});
					var newSearch = linkParams.toString();
					url.search = newSearch ? '?' + newSearch : '';
					anchor.href = url.pathname + url.search + url.hash;
				});
			});
		})();
		</script>
		<?php
	});
	?>
<!-- mantem qualquer URI presete em toda a navegação  -->

<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/3462868.js"></script>
<!-- End of HubSpot Embed Code -->


<head>

<?php $unisuam_home_url = esc_url(home_url('/')); ?>
<script>
(function () {
	var path = (window.location.pathname || '').toLowerCase();
	if (path.indexOf('/blog') !== -1) {
		window.location.replace('https://www.unisuam.edu.br/blog/');
	}
})();
</script>

	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-5CDT7BP');
	</script>

	
	<!-- End Google Tag Manager -->

	<!-- Start of HubSpot Embed Code -->
	<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/3462868.js"></script>
	<!-- End of HubSpot Embed Code -->

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="google-site-verification" content="gtFDKDWKW6m0TRSSAipNyAJ1G1n_bMSU-npr3LQa72Q" />
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700&display=swap" rel="stylesheet">
	<link rel="icon" href="https://www.unisuam.edu.br/wp-content/themes/unisuam/favicon.png" type="image/x-icon">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<title>UNISUAM | Pós-Graduação</title>

	<!-- Font Awesome CDN -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-papm6b+1kQw6QZkQ0r6u8z6Qw6QZkQ0r6u8z6Qw6QZkQ0r6u8z6Qw6QZkQ0r6u8z6Qw6QZkQ0r6u8z6Qw6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- jQuery CDN via Google -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>






	<!-- contador infinito	 -->
<!-- <a href="/" target="_blank" style="text-decoration: none;"> -->
	<div class="wrapContadorInfinito unisuamDay" id="wrapContadorInfinito" style="display: block;">
		<div class="innerCOntadorInfinito">
			<div class="contadorInfinito">
				<p class="txt-contador-before">Faltam&nbsp;&nbsp;</p>
					<div class="diasInfinito boxNumbers">
						<span id="diasInfinito"></span>
						<span class="textNumber">DIAS</span>
					</div>
					<div class="horaInfinita boxNumbers">
						<span id="horaInfinita"></span>
						<span class="textNumber">HORAS</span>
					</div>
					<div class="minutosInfinito boxNumbers">
						<span id="minutosInfinito"></span>
						<span class="textNumber">MINUTOS</span>
					</div>
					<!-- <div class="segundosInfinito boxNumbers">
						<span id="segundosInfinito"></span>
						<span class="textNumber">SEGUNDOS</span>
					</div> -->
				<p class="txt-contador-next">para o início das aulas da Pós-Graduação Digital!</p>
			</div>
		</div>
	</div>
	<script>
	(function() {
		const targetDate = new Date('2026-02-02T09:00:00-03:00'); // Data final correta
		const wrap = document.getElementById('wrapContadorInfinito');
		const dias = document.getElementById('diasInfinito');
		const hora = document.getElementById('horaInfinita');
		const min = document.getElementById('minutosInfinito');
		const seg = document.getElementById('segundosInfinito');

		function pad(n) { return String(n).padStart(2, '0'); }

		function updateCountdown() {
			const now = new Date();
			const diff = targetDate - now;
			if (diff <= 0) {
				if (wrap) wrap.style.display = 'none';
				clearInterval(timer);
				return;
			}
			const totalSeconds = Math.floor(diff / 1000);
			const days = Math.floor(totalSeconds / (60 * 60 * 24));
			const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / 3600);
			const minutes = Math.floor((totalSeconds % 3600) / 60);
			const seconds = totalSeconds % 60;
			if (dias) dias.textContent = pad(days);
			if (hora) hora.textContent = pad(hours);
			if (min) min.textContent = pad(minutes);
			if (seg) seg.textContent = pad(seconds);
		}
		updateCountdown();
		const timer = setInterval(updateCountdown, 1000);
	})();
	</script>
	<!-- </a> -->

	<style>
		span.laranja {
			color: #000;
		}
		.imgClock {
			display: inline-block;
			width: 270px;
			vertical-align: center;
			/* float: left; */
			margin-left: 10%;
			margin-right: 10%;
		}
		.wrapContadorInfinito {
			display: none;
			/* background: url(https://www.unisuam.edu.br/wp-content/uploads/2026/01/bg-contador-lista-desejos-1.png); */
			background-color: #F05200;
			/* padding-top: 10px; */
			/* padding-bottom: 5px; */
			background-position: 100%;
		    background-size: 100%;
		    background-repeat: no-repeat;
		}
		.contandorEAD {
			background: linear-gradient(to bottom left, rgba(26, 26, 26, 1), rgba(47, 53, 66, 1));
			padding-top: 3px;
			padding-right: 0px;
			padding-bottom: 3px;
			padding-left: 0px;
			font-family: Ubuntu, sans-serif;
			font-style: normal;
			font-weight: normal;
			text-decoration: none;
			font-size: 16px;
			color: #F1F2F6;
			display: block;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}
		.innerContadorInfinito {
			width: 90%;
			margin: 0 auto;
			text-align: center;
		}
		.unisuamDay {
			/* background: url(https://www.unisuam.edu.br/wp-content/uploads/2026/01/bg-contador-lista-desejos-1.png) !important; */
			background-color: #F05200;
			background-size: cover !important;
		}
		.alinhamento-direita {
			text-align: right;
			margin-right: 15px;
			position: relative;
			display: inline-block;
			vertical-align: middle;
		}
		.alinhamento-esquerda {
			text-align: left;
			margin-left: 15px;
			position: relative;
			display: inline-block;
			vertical-align: middle;
		}
		.innerCOntadorInfinito {
		    display: flex;
		    margin: 0 auto;
		    max-width: 1300px;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
		}
		.innerCOntadorInfinito img {
			width: 200px;			
			display: inline-block;			
		}
		.contadorInfinito {			
			margin: 0 auto;
			width: 100%;
			text-align: center;
			padding-top: 10px;
			padding-bottom: 10px;
		}
		.contadorInfinito p {
			/* color: #fff;
		    position: relative;
		    display: inline-block;
		    width: 40%;
		    font-weight: 600;
		    font-size: 20px;
		    font-family: 'Ubuntu';
			text-align: left;	
			top: -5px;		 */
		}
		.txt-contador-before {
			color: #fff;
			width: 25%;
			font-weight: 600;
			font-size: 21px;
			font-family: 'Ubuntu';
			text-align: right;
			position: relative;
			display: inline-block;
			vertical-align: middle;
			top: 12px;
		}
		.txt-contador-next{
			font-weight: 600;
			font-size: 21px;
			font-family: 'Ubuntu';
			text-align: left;
			position: relative;
			display: inline-block;
			width: 45%;
			color: #fff;
			vertical-align: middle;
			top: 12px;
			left: 8px;
		}
		.boxNumbers {
			position: relative;
			display: inline-flex;
			width: auto;
			padding: 10px;
			padding-top: 11px;
			padding-bottom: 2px;
			margin: auto 2px;
			border-radius: 8px;
			color: #000;
			font-weight: 600;
			top: 0;
			line-height: 20px;
			background: #fff;
		}
		.boxNumbers span {
			font-size: 25px;
			font-family: 'Ubuntu';
			display: block;
			text-align: center;
			font-weight: 500;
		}
		span.textNumber {
		    font-size: 10px;
		    position: relative;
		    top: -5px;
			font-family: 'Ubuntu';
			padding-top: 6px;
    		padding-left: 4px;
		}
		.wrapDatas {
			position: relative;
			display: inline-block;
			width: 40%;
			text-align: right;
		}
		@media(max-width:1260px) {
			.innerContadorInfinito {
				height: 160px;
			}
			.wrapContadorInfinito {
				background-position: 100% 90%;
			    background-repeat: no-repeat;
				padding-bottom: 15px;
			}
			.contandorEAD {
				padding-top: 5px;
				padding-bottom: 5px;
			    background-size: cover;
			}
			.txt-contador-before {
				top: 12px;
			}
			.unisuamDay {
				/* background: url(https://www.unisuam.edu.br/wp-content/uploads/2026/01/bg-contador-lista-desejos-mobile.jpg) !important; */
				background-color: #F05200;
				background-size: cover !important;
			}
			/* .unisuamDay {
				background: url(https://www.unisuam.edu.br/wp-content/uploads/2025/03/bg-contador-volta-as-aulas-mobile-2.png) !important;
				background-size: cover !important;
				background-repeat: no-repeat !important;
			} */
			.alinhamento-direita {
				margin-right: 0;
				display: block;
				margin-top: 15px;
			}
			.alinhamento-esquerda {
				margin-left: 0;
				display: block;
				margin-top: 15px;
			}
			.innerCOntadorInfinito img {
			    display: block;
			    width: 38%;
			    margin: 0 auto;
			}
			.contadorInfinito {
				width: 100%;
				text-align: center;
			}
			.contadorInfinito p {
				width: 100%;
				text-align: center;
				font-size: 16px;
			}
			.wrapDatas {
				text-align: center;
			}

			.txt-contador-next{
				color: #fff;		    
				width: 88%;
				font-size: 18px;
				text-align: center;
				margin: 0 auto;
    			margin-top: 10px;
			}
		}
		@media(max-width:768px) {
			.unisuamDay {
				/* background: url(https://www.unisuam.edu.br/wp-content/uploads/2026/01/bg-contador-lista-desejos-mobile.jpg) !important; */
				background-color: #F05200;
				background-size: cover !important;
			}
			.contadorInfinito {
				padding-top: 0;
				padding-bottom: 0;
			}
			.txt-contador-before {
				padding-bottom: 10px;
			}
			.contandorEAD {
				padding-top: 5px;
				padding-bottom: 5px;
			    background-size: cover;
			}
		}
	</style>
<!-- 
	<script>
		function startDailyCountdown() {
			const pad = (num) => String(num).padStart(2, '0');
			const diasEl = document.getElementById("diasInfinito");
			const horasEl = document.getElementById("horaInfinita");
			const minutosEl = document.getElementById("minutosInfinito");
			const segundosEl = document.getElementById("segundosInfinito") || document.getElementById("segundosEad");

			const getDeadline = () => {
				const now = new Date();
				const deadline = new Date(now);
				deadline.setHours(23, 0, 0, 0);
				if (now >= deadline) {
					deadline.setDate(deadline.getDate() + 1);
				}
				return deadline;
			};

			let deadline = getDeadline();

			const tick = () => {
				const now = new Date();
				let diff = deadline - now;

				if (diff <= 0) {
					deadline = getDeadline();
					diff = deadline - now;
				}

				const totalSeconds = Math.floor(diff / 1000);
				const hours = Math.floor(totalSeconds / 3600);
				const minutes = Math.floor((totalSeconds % 3600) / 60);
				const seconds = totalSeconds % 60;

				if (diasEl) diasEl.textContent = "00";
				if (horasEl) horasEl.textContent = pad(hours);
				if (minutosEl) minutosEl.textContent = pad(minutes);
				if (segundosEl) segundosEl.textContent = pad(seconds);
			};

			tick();
			setInterval(tick, 1000);
		}

		startDailyCountdown();


		// startCountdown();
	</script> -->

	<!-- contador infinito   -->


	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CDT7BP"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<!-- BARRA SUPERIOR VIP -->
	<!-- <a href="/" target="_blank">
		<section class="topBar" style="position:relative;display:block;background: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/topbar-texture.png') repeat center top; padding: 10px 82px; text-align: center; color: #fff; font-size: 20px; font-family: 'Ubuntu', Arial, sans-serif; letter-spacing: 0.02em; border-top: 6px solid #181818; line-height: 25px; margin-top: -5px;">
			<span>VOCÊ TEM [<span id="cd-h">00</span>h<span id="cd-m">00</span>m] PARA GARANTIR PREÇO FIXO + 70% OFF
		</section>
	</a>

	<script>
		// Contagem regressiva até 00:00 (meia-noite), reinicia diariamente
		document.addEventListener('DOMContentLoaded', function () {
			const hEl = document.getElementById('cd-h');
			const mEl = document.getElementById('cd-m');

			function updateCountdown() {
				const now = new Date();
				const target = new Date(now);
				// próximo 00:00 local
				target.setHours(24, 0, 0, 0);

				let diff = target - now;
				if (diff < 0) diff = 0;

				const totalSeconds = Math.floor(diff / 1000);
				const hours = Math.floor(totalSeconds / 3600);
				const minutes = Math.floor((totalSeconds % 3600) / 60);

				if (hEl) hEl.textContent = String(hours).padStart(2, '0');
				if (mEl) mEl.textContent = String(minutes).padStart(2, '0');
			}

			updateCountdown();
			setInterval(updateCountdown, 1000);
		});
	</script>
</span>
	</section></a> -->
	<!-- BARRA SUPERIOR VIP -->


	<div id="page" class="site">

		<a class="skip-link screen-reader-text" href="#content">
			<?php
			/* translators: Hidden accessibility text. */
			_e('Skip to content', 'twentyseventeen');
			?>
		</a>

		<header id="masthead" class="site-header">
			<?php include 'icons.php' ?>
			<?php if (has_nav_menu('top')) : ?>
				<div class="navigation-top" style="background:rgba(32, 36, 45, 1)" >
					<div class="wrap">
						<div class="left">
							<a href="https://www.unisuam.edu.br/">
								<div class="logoHeader"><img src="https://cursos.unisuam.edu.br/wp-content/uploads/2024/12/logoWhite.png" alt=""></div>
							</a>
						</div>
						<div class="right">
							<div class="btnMenuMobileTop"><?php echo $menuMobile; ?></div>
							<div class="wrapContentTop">
								<div class="oMenu has-temp-pos-cta">
									<?php get_template_part('template-parts/navigation/navigation', 'top'); ?>
									<div class="extras">
										<!-- <a href="https://matricula.unisuam.edu.br/">
											<div class="btAmbiente btnMenu btLaranja">&nbsp;&nbsp;&nbsp;CONTINUAR MATRÍCULA&nbsp;&nbsp;&nbsp;</div>
										</a> -->
										<a href="https://login.unisuam.edu.br/login" target="_blank">
											<div class="btAmbiente btnMenu btLaranja">&nbsp;&nbsp;&nbsp;AMBIENTE ACADÊMICO&nbsp;&nbsp;&nbsp;</div>
										</a>
										<!-- INICIO BLOCO TEMPORARIO: CTA INSCREVA-SE NO TOPO -->
										<!--
											Para remover este CTA rapidamente:
											1) Apague este bloco HTML (INICIO/FIM).
											2) Remova as regras CSS do bloco TEMPORARIO no style principal.
											3) Retire a classe `has-temp-pos-cta` do container `.oMenu`.
										-->
										<a href="#" id="topoInscrever" target="_blank" class="header-pos-cta" aria-label="Inscreva-se na Pos-Graduacao">
											<div class="btAmbiente btnMenu btLaranja header-pos-cta-btn">INSCREVA-SE</div>
										</a>
										<!-- FIM BLOCO TEMPORARIO: CTA INSCREVA-SE NO TOPO -->
										<style>
											.btLaranja {
												/* background: #fff !important; */
												color: #fff !important;
												border: 2px solid #fff !important;
												transition: all 0.3s !important;
											}

											.btLaranja:hover {
												/* background: #EF7D00 !important; */
												color: #fff !important;
												border: 2px solid #EF7D00 !important;
												/* sempre laranja */
												transition: all 0.3s !important;
											}
										</style>
										<script>
											$("#topoInscrever").click(function(e){
												e.preventDefault();
												window,location.href = "https://inscricao.unisuam.edu.br/pos";
											});
										</script>
										<!-- 
										<div class="btnMenu menuAcess" style="cursor:pointer; opacity: 1;">
											<?php echo $logoAcessTopo ?>
										</div>
										<style>
											.menuAcess:hover {
												opacity: 0.8 !important;
												transition: all 0.3s !important;
											}
										</style>

										<script>
											$(".menuAcess").click(function(){
												window.open("https://login.unisuam.edu.br/login", "_blank");
											});
										</script> -->
										<!-- <div class="wrapLinguas">
											<?php echo do_shortcode('[gtranslate]'); ?>
										</div> -->
									</div>
								</div>
							</div>
						</div>
					</div><!-- .wrap -->
				</div><!-- .navigation-top -->
			<?php endif; ?>
		</header><!-- #masthead -->

		<script>
		(function () {
			var MENU_LABEL_FIXO = 'Sobre a Pós';
			var LOCK_ATTR = 'data-menu-lock-sobre-pos';

			function normalizarTexto(valor) {
				return (valor || '')
					.toString()
					.normalize('NFD')
					.replace(/[\u0300-\u036f]/g, '')
					.toLowerCase()
					.replace(/\s+/g, ' ')
					.trim();
			}

			function ehItemSobrePos(anchor) {
				if (!anchor) return false;

				var texto = normalizarTexto(anchor.textContent || '');
				if (texto === 'sobre a pos' || texto === 'sobre pos' || texto === 'sobre a pos graduacao' || texto === 'sobre a graduacao') {
					return true;
				}

				var href = (anchor.getAttribute('href') || '').toLowerCase();
				if (href.indexOf('#sobre') !== -1 && (texto.indexOf('sobre') === 0 || texto === '')) {
					return true;
				}

				if (href.indexOf('sobre-a-pos') !== -1 || href.indexOf('sobre-pos') !== -1) {
					return true;
				}

				return false;
			}

			function aplicarLockSobrePos() {
				var linksMenu = document.querySelectorAll('.main-navigation a, #site-navigation a, .menu a, .HeaderMobile a');
				if (!linksMenu.length) return;

				linksMenu.forEach(function (anchor) {
					var jaTravado = anchor.getAttribute(LOCK_ATTR) === '1';
					if (!jaTravado && !ehItemSobrePos(anchor)) {
						return;
					}

					if ((anchor.textContent || '').trim() !== MENU_LABEL_FIXO) {
						anchor.textContent = MENU_LABEL_FIXO;
					}
					anchor.setAttribute(LOCK_ATTR, '1');
				});
			}

			var lockAgendado = false;
			function agendarAplicacaoLock() {
				if (lockAgendado) return;
				lockAgendado = true;
				window.requestAnimationFrame(function () {
					lockAgendado = false;
					aplicarLockSobrePos();
				});
			}

			document.addEventListener('DOMContentLoaded', function () {
				aplicarLockSobrePos();

				var observer = new MutationObserver(function () {
					agendarAplicacaoLock();
				});
				observer.observe(document.body, {
					childList: true,
					subtree: true,
					characterData: true
				});
			});
		})();
		</script>

		<script>
		(function () {
			var EXCLUDE_SELECTOR = '.main-navigation, #site-navigation, .menu, .HeaderMobile, .navigation-top';
			var ATTRS_TO_NORMALIZE = ['title', 'aria-label', 'placeholder', 'alt'];

			function isInsideExcludedArea(element) {
				if (!element || !element.closest) return false;
				return !!element.closest(EXCLUDE_SELECTOR);
			}

			function normalizePosGraduacaoText(value) {
				var text = (value || '').toString();
				if (!text) return text;
				if (text.toLowerCase().indexOf('gradua') === -1 && text.toLowerCase().indexOf('pos') === -1 && text.toLowerCase().indexOf('pós') === -1) {
					return text;
				}

				text = text.replace(/\b(?:P[oó]s[\s\-]*){2,}Gradua(?:ç|c)[aã]o\b/giu, 'Pós-graduação');
				text = text.replace(/\bP[oó]s[\s\-]*Gradua(?:ç|c)[aã]o\b/giu, 'Pós-graduação');
				text = text.replace(/\bPOS[\s\-]*GRADUACAO\b/g, 'Pós-graduação');
				text = text.replace(/\bPos[\s\-]*graduacao\b/g, 'Pós-graduação');
				text = text.replace(/\bpos[\s\-]*graduacao\b/g, 'Pós-graduação');

				return text;
			}

			function normalizeTextNodes(root) {
				if (!root) return;
				if (root.nodeType === 1 && isInsideExcludedArea(root)) return;

				var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
					acceptNode: function (node) {
						if (!node || !node.parentElement) return NodeFilter.FILTER_REJECT;
						if (isInsideExcludedArea(node.parentElement)) return NodeFilter.FILTER_REJECT;
						var tag = node.parentElement.tagName;
						if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'NOSCRIPT' || tag === 'TEXTAREA') return NodeFilter.FILTER_REJECT;
						if (!node.nodeValue || node.nodeValue.trim() === '') return NodeFilter.FILTER_REJECT;
						return NodeFilter.FILTER_ACCEPT;
					}
				});

				var current = walker.nextNode();
				while (current) {
					var normalized = normalizePosGraduacaoText(current.nodeValue);
					if (normalized !== current.nodeValue) {
						current.nodeValue = normalized;
					}
					current = walker.nextNode();
				}
			}

			function normalizeAttributes(root) {
				if (!root || root.nodeType !== 1) return;
				if (isInsideExcludedArea(root)) return;

				if (root.matches) {
					ATTRS_TO_NORMALIZE.forEach(function (attr) {
						var original = root.getAttribute(attr);
						if (!original) return;
						var normalized = normalizePosGraduacaoText(original);
						if (normalized !== original) {
							root.setAttribute(attr, normalized);
						}
					});
				}

				var selector = ATTRS_TO_NORMALIZE.map(function (attr) { return '[' + attr + ']'; }).join(', ');
				var elements = root.querySelectorAll ? root.querySelectorAll(selector) : [];
				elements.forEach(function (el) {
					if (isInsideExcludedArea(el)) return;
					ATTRS_TO_NORMALIZE.forEach(function (attr) {
						var original = el.getAttribute(attr);
						if (!original) return;
						var normalized = normalizePosGraduacaoText(original);
						if (normalized !== original) {
							el.setAttribute(attr, normalized);
						}
					});
				});
			}

			function normalizeAll(root) {
				normalizeTextNodes(root);
				normalizeAttributes(root && root.nodeType === 1 ? root : document.body);
			}

			var normalizeScheduled = false;
			function scheduleNormalize(root) {
				if (normalizeScheduled) return;
				normalizeScheduled = true;
				window.requestAnimationFrame(function () {
					normalizeScheduled = false;
					normalizeAll(root || document.body);
				});
			}

			document.addEventListener('DOMContentLoaded', function () {
				normalizeAll(document.body);

				var observer = new MutationObserver(function (mutations) {
					mutations.forEach(function (mutation) {
						if (mutation.type === 'characterData') {
							scheduleNormalize(document.body);
							return;
						}
						mutation.addedNodes.forEach(function (node) {
							if (node && node.nodeType === 1) {
								scheduleNormalize(node);
							}
						});
					});
				});
				observer.observe(document.body, {
					childList: true,
					subtree: true,
					characterData: true
				});
			});
		})();
		</script>


		<?php
		if (twentyseventeen_should_show_featured_image()) :
			echo '<div class="single-featured-image-header">';
			echo get_the_post_thumbnail(get_queried_object_id(), 'twentyseventeen-featured-image');
			echo '</div><!-- .single-featured-image-header -->';
		endif;
		?>

		<style>
			.wrapModalidade h4 {
				text-transform: uppercase !important;
			}
			#site-navigation {
				position: relative !important;
				text-align: center !important;
				margin: 0 auto !important;
				left: auto !important;
				float: none !important;
			}

			.gt_float_switcher {
				box-shadow: rgba(0, 0, 0, 0) 0 5px 15px !important;
				background: transparent !important;
			}

			.gt_float_switcher .gt-selected {
				background: transparent !important;
			}

			.gt_options a {
				color: #fff !important;
				font-size: 12px;
			}

			.gt_options a img {
				width: 22px;
			}

			.gt-current-lang {
				font-size: 12px;
			}

			.gt-lang-code {
				position: absolute !important;
				display: none !important;
			}

			.gt_float_switcher-arrow {
				display: none !important;
				position: absolute !important;
			}

			.gt_float_switcher .gt_options {
				position: absolute !important;
				top: 45px !important;
				left: 0px !important;
				transition: all .4s;
				width: 140px;
			}

			.wrapLinguas {
				position: relative;
				top: 9px;
				left: 11px;
			}

			.wrapLinguas.lMobile {
				top: 0;
				margin-left: -20px;
			}

			.gt_float_switcher .gt_options a {
				padding-bottom: 10px !important;
			}

			.gt_float_switcher-arrow .gt_arrow_rotate {
				display: none !important;
				position: absolute !important;
			}

			.gt-current-lang img {
				width: 22px;
				float: right;
			}

			@media(max-width:600px) {
				.gt_float_switcher .gt_options {
					position: fixed !important;
					top: 74px !important;
					right: 0px !important;
					transition: all.4s;
					width: 140px;
					left: auto !important;
				}

				.gtranslate_wrapper {
					margin-left: -22px;
					margin-right: -10px;
				}

				.gt-current-lang img {
					width: 25px;
					float: right;
				}

				.OpenMenuMoblile {
					position: relative;
				}

				.wrapBtnMobile {
					width: 50px;
					text-align: center;
					height: 50px;
					background-color: #747b8c;
					line-height: 60px;
					transition: all .4s;
					border-radius: 2px;
					margin-right: -20px;
				}

				.HeaderMobile ul li a i {
					color: #747b8c !important;
				}

				.OpenMenuMoblile i.fal.fa-bars {
					color: #fff !important;
				}

				.LogoMobile img {
					top: 12px;
					width: 65px;
					position: relative;
				}

				.HeaderMobile ul {
					margin-right: -24px;
					position: relative;
				}

			}

			.gt_float_switcher .gt-selected .gt-current-lang span.gt_float_switcher-arrow {
				background-size: 8px;
				margin-left: -4px;
			}

			.gt_options.gt-open {
				background: rgba(0, 0, 0, .3);
				border-radius: 2px;
			}



			a:hover {
				transition: all .3s;
				opacity: 0.8;
			}

			.btAmbiente {
				border: 2px solid #57606F;
				padding: 5px 5px;
				padding-top: 8px;
				padding-bottom: 8px;
				margin-right: 5px;
				position: relative;
				border-radius: 5px;
				top: -2px;
				transition: all 0.3s;
				cursor: pointer;
			}

			.btAmbiente:hover {
				background: #fff;
				color: #000;
				transition: all 0.3s;
			}

			.navigation-top {
				/* background: var(--Expand-Palete-US-Grayscale-US-Dark-Gray-3-US, #20242D);
			box-shadow: 0px 8px 8px 0px rgba(0, 0, 0, 0.16); */
				position: relative;
			}

			@media (min-width: 800px) {
				.navigation-top {
					position: fixed;
					top: 0;
					left: 0;
					right: 0;
					width: 100%;
					z-index: 9997;
				}

				#masthead.site-header {
					padding-top: 86px;
				}

				body.admin-bar .navigation-top {
					top: 32px;
				}

				body.admin-bar #masthead.site-header {
					padding-top: 118px;
				}
			}

			.navigation-top .wrap .left {
				position: relative;
				display: inline-block;
				width: 14%;
				vertical-align: middle;
			}

			.navigation-top .wrap .right {
				position: relative;
				display: inline-block;
				width: 85%;
				vertical-align: middle;
				color: #57606F;
			}

			.navigation-top .wrap .right a {
				color: #fff;
				font-weight: 400;
			}

			.main-navigation {
				width: 50%;
				display: inline-block;
				text-align: right;
			}

			#top-menu {
				text-align: right;
			}

			.extras {
				display: flex;
				justify-content: flex-end;
				align-items: center;
				gap: 10px;
				float: right;
				top: 9px !important;
				position: relative;
				width: auto !important;
			}

			/* INICIO BLOCO TEMPORARIO: ESTILOS CTA INSCREVA-SE NO TOPO */
			/*
				Para rollback, remover este bloco inteiro e a classe `has-temp-pos-cta` no HTML.
				Escopo: CTA laranja no fim da `.navigation-top`, visivel apenas no desktop.
			*/
			.header-pos-cta {
				display: none;
				text-decoration: none;
			}

			.header-pos-cta .header-pos-cta-btn {
				display: inline-block;
				min-width: 210px;
				text-align: center;
				background: #EF7D00 !important;
				border: 2px solid #ffffff !important;
				border-radius: 5px;
				color: #ffffff;
				font-size: inherit;
				font-weight: inherit;
				line-height: inherit;
				letter-spacing: 0.2px;
				transition: all 0.3s !important;
			}

			.header-pos-cta:hover .header-pos-cta-btn {
				color: #ffffff;
				opacity: 1;
			}

			@media (min-width: 992px) {
				.oMenu.has-temp-pos-cta {
					display: flex;
					align-items: center;
					justify-content: flex-end;
					gap: 12px;
				}

				.oMenu.has-temp-pos-cta .main-navigation {
					width: auto;
					/* flex: 1 1 auto; */
					min-width: 0;
				}

				.oMenu.has-temp-pos-cta .extras {
					flex: 0 0 auto;
					top: 0 !important;
				}

				.oMenu.has-temp-pos-cta .header-pos-cta {
					display: inline-flex;
				}
			}

			@media (max-width: 991px) {
				.btnMenuMobileTop {
					top: 2% !important;
				}
				.oMenu {
					right: 56px !important;
					height: 300px !important;
				}
				.navigation-top .wrap .right {
					width: 100% !important;
				}

				.navigation-top .wrap .right .wrapContentTop {
					padding: 12px 16px 16px !important;
					position: relative !important;
					overflow: visible !important;
				}

				.oMenu.has-temp-pos-cta {
					display: flex !important;
					flex-direction: column !important;
					align-items: stretch !important;
					justify-content: flex-start !important;
					gap: 12px !important;
				}

				.oMenu.has-temp-pos-cta .main-navigation {
					order: 1 !important;
					width: 100% !important;
					text-align: left !important;
					display: flex !important;
					flex-direction: column !important;
					gap: 0 !important;
					float: none !important;
					position: static !important;
					left: auto !important;
					right: auto !important;
					transform: none !important;
					margin: 0 !important;
					clear: both !important;
				}

				.oMenu.has-temp-pos-cta .main-navigation .innerButtomTop {
					display: block;
					width: 100%;
					padding: 10px 0 !important;
					margin: 0 !important;
					line-height: 1.25;
					position: static !important;
					z-index: 1;
				}

				.oMenu.has-temp-pos-cta .main-navigation .innerButtomTop::after {
					display: none !important;
				}

				.oMenu.has-temp-pos-cta .extras {
					order: 99 !important;
					float: none !important;
					width: 100% !important;
					top: 0 !important;
					position: static !important;
					display: flex !important;
					flex-direction: column !important;
					align-items: stretch !important;
					gap: 10px !important;
					margin: 12px 0 0 0 !important;
					padding-top: 10px !important;
					border-top: 1px solid rgba(255, 255, 255, 0.18);
					clear: both !important;
					z-index: 2;
				}

				.oMenu.has-temp-pos-cta .extras > a {
					display: block;
					width: 100%;
					position: static !important;
				}

				.oMenu.has-temp-pos-cta .extras .btAmbiente {
					display: block;
					width: 100%;
					box-sizing: border-box;
					text-align: center;
					top: 0;
					margin-right: 0;
					padding-left: 12px;
					padding-right: 12px;
					padding-top: 10px;
					padding-bottom: 10px;
					line-height: 1.2;
				}

				.oMenu.has-temp-pos-cta .header-pos-cta {
					display: block !important;
					order: 2;
				}
			}
			/* FIM BLOCO TEMPORARIO: ESTILOS CTA INSCREVA-SE NO TOPO */

			@media(max-width:490px) {
				.navigation-top .wrap .right a {
					color: #fff;
					font-weight: 400;
				}
			}
		</style>

		<div class="topo" id="btnTopo" style="display:none; cursor:pointer;">&#8593;</div>
		<script>
			jQuery(document).ready(function($) {
				let shown = false;
				$(window).on('scroll', function() {
					if (!shown && $(window).scrollTop() > 50) {
						$('#btnTopo').fadeIn();
						shown = true;
					}
				});
				$('#btnTopo').on('click', function() {
					$('html, body').animate({
						scrollTop: 0
					}, 500);
				});
			});
		</script>
		<style>
			.topo#btnTopo {
				width: 50px;
				height: 50px;
				position: fixed;
				right: 20px;
				bottom: 90px;
				background: rgba(255, 140, 0, 0.8);
				color: #fff;
				padding: 12px 18px;
				border-radius: 50%;
				font-size: 28px;
				z-index: 9999;
				transition: opacity .3s;
				opacity: 0.8;
				box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
				line-height: 24px;

			}

			.topo#btnTopo:hover {
				opacity: 1;
			}
		</style>

		<script>
			// btn mobbile
			$(".btnMenuMobileTop").click(function() {
				$(".wrapContentTop").slideToggle();
			});
		</script>

		<div class="site-content-contain">
			<div id="content" class="site-content">