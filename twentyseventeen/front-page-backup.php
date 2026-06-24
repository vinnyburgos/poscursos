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

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/home.css">
<script src="<?php echo get_template_directory_uri(); ?>/home.js"></script>

<!-- <?php include 'icons.php' ?> -->
<?php $upload_dir = wp_upload_dir(); ?>

<br><br><br>
<section class="bgHome">
	<div class="breadcrumb"></div>
	<div class="wrapInfos">
		<p class="modalidadeTop">PRESENCIAL | DIGITAL AO VIVO | DIGITAL (EAD)</p>
		<h1 class="titleHome">PÓS-GRADUAÇÃO</h1>
		<p class="subTitleHome">Lorem ipsum dolor sit amet consectetur. Mauris massa odio sed in velit egestas iaculis et nibh. Arcu elementum ultricies euismod vehicula eget.</p>
	</div>
</section>

<style>
	.bgHome {
		background: url(<?php echo $upload_dir['baseurl']; ?>/2025/01/bgHome.png);
	}
</style>


<!-- area de busca  -->
	<div class="findBar">
		<input type="text" id="findCurso" class="findCurso" placeholder="PESQUISE UM CURSO">
		<div class="btnFindCurso btn" id="btnFindCurso">PESQUISAR</div>
	</div>

	<div class="filtrosBar">
		<div class="innerFiltros">
			<div class="lineOne">
				<p class="filtrarPor">FILTRAR POR:</p>
				<p class="limparFiltros btn">LIMPAR FILTROS <?php echo $iconBorracha; ?></p>
			</div>
			<div class="lineTwo">
				<form action="#" id="filtrarCursos">

					<div class="innerSelect">
						<label for="areaInteresse" class="interesse">Área de Interesse</label>
						<select name="areaInteresse" id="areaInteresse">
							<option value="">Ciências Humanas</option>
							<option value="">Gastronomia</option>
						</select>
					</div>

					<div class="innerSelect">
						<label for="areaNomeCurso" class="nomeCurso">Nome do Curso</label>
						<select name="areaNomeCurso" id="areaNomeCurso">
							<option value="">Direto Constitucional</option>
							<option value="">Direto Civil</option>
						</select>
					</div>

					<div class="innerSelect">
						<label for="modalidade" class="modalidade">Modalidade de Ensino</label>
						<select name="modalidade" id="modalidade">
							<option value="">Presencial</option>
							<option value="">Online</option>
						</select>
					</div>

				</form>
			</div>
		</div>
	</div>
<!-- area de busca  -->


<section class="encontreCurso">
	<div class="center">
    <!-- BOXES -->
    <?php
    // WP_Query para buscar posts de um Custom Post Type específico
    $args = array(
        'post_type' => 'cursos', // Substitua pelo seu Custom Post Type
        'posts_per_page' => 1000,  // Número de boxes a serem exibidos
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="box-container">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="box-item">
                    <div class="boxColor bgGreen"></div>
                    <p class="seloType colorGreen">PÓS-GRADUAÇÃO</p>
                    <div class="innerMod" id="innerMod">
                        <?php echo get_post_meta(get_the_ID(), 'modalidade_type', true); ?>
                    </div>

                    <!-- Título do post -->
                    <h3 class="titleBox">
                        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: #333;">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <?php 
                    $mneumonico = get_post_meta(get_the_ID(), 'mneumonico', true);
                    if ($mneumonico) {
                        require_once 'getAPI.php';
                    }
                    ?>

                    <div class="apiGets">
						<?php 
						if (!empty($data['investimentos'])) {
								$menorValor = min(array_column($data['investimentos'], 'valor'));
								$valorSemDesconto = ceil($menorValor * 2.5);
							?>
							<p class="apenasPresencial partir colorGreen">A partir de:</p>
							<span class="apenasPresencial">18x de R$</span> 
							<span id="valorSDesconto"><?php echo number_format($valorSemDesconto, 2, ',', '.'); ?></span><br>
							<span class="apenasPresencial">18x de R$</span> 
							<span id="valorSDesconto"><?php echo number_format($menorValor, 2, ',', '.'); ?></span>

							<h6>Carga horária:</h6>
							<p class="conteudoBox"><span id="cargaHoraria"><?php echo $data['resumo']['carga-horaria'] ?></span></p>

						
							<h6>Unidades:</h6>
							<?php 
								$unidades = array_unique(array_column($data['investimentos'], 'unidade'));
								foreach ($unidades as $unidade) {
							?>
								<p class="conteudoBox"><span id="cargaHoraria"><?php echo $unidade ?></span></p>
							<?php 
								}
							?>

							<?php 
						} else {
							echo '<p>Em breve.</p>';
						}
						?>
                    </div>

					<div id="btnInscreva" class="btnInscreva">INSCREVA-SE</div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php
        wp_reset_postdata();
    else :
        echo '<p>Nenhum post encontrado.</p>';
    endif;
    ?>
    <!-- BOXES -->

	<div class="btnVerMaisCursos btn">VER MAIS +</div>
	</div>
</section>

<section class="top10">
	<div class="center">
		<h2 class="top10 comBarra">Top 10: Cursos mais procurados</h2>
		<div class="swiper top10Sw">
		<div class="swiper-wrapper">
		
		<?php
			$args = array(
				'post_type' => 'cursos',
				'posts_per_page' => 10,
				'orderby' => 'rand'
			);
			
			$query = new WP_Query($args);
			
			if ($query->have_posts()) :
				while ($query->have_posts()) : $query->the_post(); ?>
					<div class="swiper-slide innerProcurado">
						<h3><?php the_title(); ?></h3>
						<p><?php the_excerpt(); ?></p>
					<?php if (has_post_thumbnail()) : ?>
						<div class="course-thumbnail">
							<?php the_post_thumbnail('full'); ?>
						</div>
					<?php endif; ?>
					</div>
					<?php endwhile;
				wp_reset_postdata();
				else :
					echo '<p>Nenhum curso encontrado.</p>';
				endif;
		?>
		</div>
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
		<div class="swiper-pagination"></div>
	</div>
	</div>
</section>

<section class="sobrePos">

	<div class="center">
		<h2 class="sobre comBarra">Sobre a Pós UNISUAM</h2>
		<div class="wrapContent">
			
			<div class="column column1">
				<h4 class="titleColumn">Inove hoje e transforme seu futuro!</h4>
				<p class="contentColumn">
				Com mais de 50 anos de excelência educacional, a UNISUAM, reconhecida com nota máxima pelo MEC (5), oferece mais de 70 cursos de pós-graduação lato sensu, em modalidades presencial, por webconferência ou online. São programas que abrangem áreas como gestão, saúde, educação, arquitetura, engenharias e gastronomia, com formações a partir de 06 meses.
				Nossos cursos contam com laboratórios modernos, salas equipadas para aulas práticas e um ambiente acolhedor que garante uma experiência de aprendizado completa e transformadora.
				</p>
			</div>

			<div class="column column2">
				<div class="wrapIcon"><?php echo $iconEnsino; ?></div>
				<h4 class="titleColumn">Modalidades de Ensino</h4>
				<p class="contentColumn">
				Escolha entre cursos presenciais, por webconferência ou 00% digital.
				</p><br>

				<div class="wrapIcon"><?php echo $iconFormacao; ?></div>
				<h4 class="titleColumn">Formação Acelerada</h4>
				<p class="contentColumn">
				Conclua sua especialização a partir de 06 meses.
				</p><br>

				<div class="wrapIcon"><?php echo $iconConexao; ?></div>
				<h4 class="titleColumn">Conexão e Networking</h4>
				<p class="contentColumn">
				Interaja com profissionais e colegas de diferentes áreas de atuação e amplie a sua rede de contatos profissionais.
				</p><br>
			</div>

			<div class="column column3">
				<div class="wrapIcon"><?php echo $iconDiversidade; ?></div>
				<h4 class="titleColumn">Diversidade de Cursos</h4>
				<p class="contentColumn">
				Mais de 70 opções de pós-graduação em diversas áreas do conhecimento.
				</p><br>

				<div class="wrapIcon"><?php echo $iconPonta; ?></div>
				<h4 class="titleColumn">Infraestrutura de Ponta</h4>
				<p class="contentColumn">
				Salas e laboratórios modernos, proporcionando uma aprendizagem prática e aplicada.
				</p><br>

				<div class="wrapIcon"><?php echo $iconDocente; ?></div>
				<h4 class="titleColumn">Corpo Docente Especializado</h4>
				<p class="contentColumn">
				Aprenda com mestres e doutores que são referências em suas áreas, garantindo um ensino de qualidade e atualizado com as demandas do mercado.
				</p><br>
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
				Contamos com salas equipadas para aulas práticas e laboratórios modernos. Oferecemos um espaço acolhedor para estudo, garantindo uma experiência completa e enriquecedora.
				</p>
			</div>

			<div class="boxEnsino BoxEnsino2">
				<h4 class="titleBoxEnsino">Cursos por Webconferência</h4>
				<p class="contentBoxEnsino">
				Para aqueles que, mesmo com a rotina corrida, desejam aquele encontro marcado com seu professor e sua turma. Nessa modalidade, você terá aulas ao vivo quinzenais, de forma online.
				</p>
			</div>

			<div class="boxEnsino BoxEnsino3">
				<h4 class="titleBoxEnsino">Cursos Digital</h4>
				<p class="contentBoxEnsino">
				Praticidade, flexibilidade, baixo custo, alto retorno. Faça sua pós-graduação Digital e estude onde quiser, de acordo com a sua disponibilidade. Certificação com a mesma validação dos cursos presenciais.
				</p>
			</div>
		</div>

		<p class="modalidadeContent">
			Seja qual for a sua escolha de pós-graduação, aqui na UNISUAM você encontrará a flexibilidade, a qualidade e o suporte necessários para alcançar seus objetivos profissionais. Inscreva-se agora mesmo e dê o próximo passo na sua carreira!
		</p>

	</div>
</section>

<section class="nossasUnidades">
	<div class="center">
		<h2 class="sobre comBarra">Nossas Unidades e Polos Digitais</h2>
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

		<div class="wrapPolosD btn">
			<div class="iconMapa"><?php echo $iconMap; ?></div><h4 class="titleUnidade">Polos Digitais</h4>
			<div class="static">v</div>
		</div>


		</div>
	</div>
</section>





<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
	var swiper = new Swiper(".top10Sw", {
		slidesPerView: 1,
		spaceBetween: 30,
		cssMode: true,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
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
				spaceBetween: 10,
			},
		},
	});
  </script>



<?php
get_footer();

