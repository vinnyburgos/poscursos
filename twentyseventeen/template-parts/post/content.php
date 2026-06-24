<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.2
 */

?>

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
			<h4>PÓS-GRADUAÇÃO</h4> <div class="innerMod" id="innerMod">PRESENCIAL</div>
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
			<p>Lorem ipsum dolor sit amet consectetur. Mauris massa odio sed in velit egestas iaculis et nibh. Arcu elementum ultricies euismod vehicula eget. Tristique velit consequat cursus mattis.</p>
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


<?php
// Função para obter o token de autenticação
function getToken($url, $login, $password) {
    $data = json_encode(['login' => $login, 'password' => $password]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, rtrim($url, '/') . '/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro ao obter o token: ' . curl_error($ch));
        return false;
    }
    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['token'] ?? false;
}

// Função para buscar os dados de um curso específico pelo código
function getCursoPorCodigo($url, $token, $tipo, $codigo) {
    // Monta a URL para a requisição com base no código do curso
    $urlRequisicao = rtrim($url, '/') . '/api/curso/' . $tipo . '/' . $codigo;
    
    // Inicia a requisição CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlRequisicao);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro ao obter o curso: ' . curl_error($ch));
        return false;
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Configurações de autenticação da API
$apiUrl = 'https://apisite.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

$mneumonico = 'ANC'; // pegar a variável do código aqui

// Obtendo o token de autenticação
$token = getToken($apiUrl, $login, $password);

if ($token) {
    // Código do curso e tipo para requisitar
    $codigoCurso = $mneumonico;  // Ajuste o código conforme necessário
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';  // Ajuste o tipo conforme necessário

    // Obtendo os dados do curso específico pelo código
    $cursoData = getCursoPorCodigo($apiUrl, $token, $tipoCurso, $codigoCurso);

    // Verificar se os dados do curso foram retornados corretamente
    if ($cursoData === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Falha ao obter dados do curso.']);
        exit;
    }

    // Log para verificar a resposta da API
    error_log('Dados da API: ' . print_r($cursoData, true));

    if (!empty($cursoData['data'])) {
        // Se houver dados do curso encontrados, processamos os dados
        $data = $cursoData['data'];
    } else {
        // Caso não haja dados do curso encontrados
        http_response_code(404);
        echo json_encode(['error' => 'Nenhum dado do curso encontrado.']);
        exit;
    }
} else {
    // Erro ao obter o token
    http_response_code(500);
    echo json_encode(['error' => 'Falha na autenticação.']);
    exit;
}
?>


    <!-- Dados já puxados da API -->
		<?php foreach ($data['investimentos'] as $investimento) { 
		if ($investimento['parcelas'] == 18) {
			$valorSemDesconto = $investimento['valor'] * 2.5; 

			// var_dump($data);
		?>
       
        <section class="box">
            <div class="innerBox">
				<div class="btnNovo btnAluno active" id="btnNovo"><span>NOVO ALUNO</span></div>
				<div class="btnEx btnAluno" id="btnEx"><span>EX ALUNO</span></div>
    
				<p class="nameCurso">
					<?php
						if ( is_single() ) {
							the_title( '<span class="entry-title">', '</span>' );
						}
					?>
				</p>

				<p class="dePor">A partir de:<span><br>
				18x de R$</span> <span id="valorSDesconto"><?php echo number_format($valorSemDesconto, 2, ',', '.'); ?></span></p>

                <p class="valorParcela"><span><?php echo $investimento['parcelas']; ?>x de R$</spanR$<span id="valorCDesconto"><?php echo number_format($investimento['valor'], 2, ',', '.'); ?></span></p>


				<select name="unidade" id="unidade" class="unidade">
					<option value="">Selecione a Unidade em que deseja estudar</option>
					<option value="<?php echo $investimento['unidade']; ?>"><?php echo $investimento['unidade']; ?></option>
				</select>

                <div class="wrapSides">
                    <div class="left">
                        <h3>Modalidade:</h3>
                        <p class="conteudoBox"><span id="modalidade">Presencial</span></p>
                        <h3>Dias e horários:</h3>
                        <p class="conteudoBox"><span id="modalidade">Sábados quinzenais<br>das 8h às 17h</span></p>
                    </div>
                    <div class="right">
                        <h3>Carga horária:</h3>
                        <p class="conteudoBox"><span id="cargaHoraria"><?php echo $grupo['carga-horaria']; ?></span></p>
						<h3>Próxima Turma:</h3>
                        <p class="conteudoBox"><span id="dataTurma"><?php echo $investimento['datainicio']; ?></span></p>
                        <!-- <h3>Unidade(s):</h3>
                        <p class="conteudoBox"><span id="unidadesBox"><?php echo $investimento['unidade']; ?></span></p> -->
                    </div>
                </div>

                <a href="#"><div class="btnComprar">
                    <span>INSCREVA-SE JÁ!</span>
                </div></a>
            </div>
        </section>

		<?php 
			}
		} ?>
	<!-- Dados já puxados da API -->


	<script>
		$(document).ready(function () {
			// Captura o SRC da imagem
			let bgCapture = $(".single-featured-image-header img").attr("src");

			// Define a imagem e o gradiente como background em camadas
			$(".single-featured-image-header").css("background", 
				`linear-gradient(262deg, #CFEAEF 0%, #076B8F 50%), url(${bgCapture})`
			);
			$(".single-featured-image-header").css({
				"background-size": "cover", /* Faz a imagem preencher o espaço */
				"background-blend-mode": "overlay" /* Combina gradiente e imagem */
			});
		});
	</script>	

	
	<style>
		.breadcrumb {
			margin-bottom: 90px;
		}
		.breadcrumb p span {
			margin-right: 10px;
			color: #fff !important;
			margin-left: 10px;
		}
		.breadcrumb p span:nth-child(1) {
			margin-left: 0px;
		}
		.breadcrumb a {
			color: #fff !important;
		}
		.wrapModalidade {
			display: block;
			vertical-align: middle;
			margin-bottom: 10px;
			margin-top: 50px;
		}
		.wrapModalidade  h4 {
			position: relative;
			display: inline-block;
			vertical-align: middle;
			margin-right: 5px;
			color: #fff;
			font-size: 14px;
			font-weight: 400;
		}
		.innerMod {
			top: -5px;
			position: relative;
			display: inline-block;
			vertical-align: middle;
			padding: 2px 8px;
			justify-content: center;
			align-items: center;
			gap: 8px;
			border-radius: 16px;
			color: var(--Expand-Palete-US-Sky-Blue-US-Sky-Blue-US, #0F96AE);
			font-family: Ubuntu;
			font-size: 15px;
			font-style: normal;
			font-weight: 700;
			text-transform: uppercase;
			background: var(--Expand-Palete-US-Grayscale-US-White, #FFF);
		}
		.entry-title {
			color: #fff !important;
			font-size: 25px;
			line-height: 30px;
			font-weight: 700;
			text-transform: uppercase !important;
		}
		.box .entry-title {
			color: #57606F !important;
			font-size: 18px;
			line-height: 18px;
			text-transform: capitalize !important;
		}
		.headerCurso {
			position: absolute;
			top: -70%;
			color: #fff;
			width: 45%;
		}
		.unidade {
			border-radius: 4px;
			border: 1px solid var(--Expand-Palete-US-Grayscale-US-Light-Gray-2-US, #CED6E0);
			background: transparent !important;
			font-size: 12px;
			color: #57606F;
		}
		.nameCurso {
			font-weight: 700;
			color: #57606F;
			margin-bottom: 10px;
			margin-top: 5px;
		}
		.btnNovo {
			border-radius: 16px 0px 0px 0px;
			background: var(--Expand-Palete-US-Grayscale-US-Light-Gray-4-US, #F1F2F6);
			text-align: center;
			position: absolute;
			top: 0;
			left: 0;
			width: 50%;
			height: 50px;
			line-height: 52px;
			color: var(--Expand-Palete-US-Grayscale-US-Light-Gray-1-US, #A4B0BE);
			font-family: Ubuntu;
			font-size: 14px;
			font-style: normal;
			font-weight: 700;
			cursor: pointer;
			transition: all .4s;
		}
		.btnNovo:hover{
			border-radius: 16px 0px 0px 0px;
			background: var(--Expand-Palete-US-Grayscale-US-Gray-US, #747D8C);
			text-align: center;
			position: absolute;
			top: 0;
			left: 0;
			width: 50%;
			height: 50px;
			line-height: 52px;
			color: #fff;
			font-family: Ubuntu;
			font-size: 14px;
			font-style: normal;
			font-weight:700;
			cursor: pointer;
			transition: all .4s;
		}
		.btnNovo.active{
			border-radius: 16px 0px 0px 0px;
			background: var(--Expand-Palete-US-Grayscale-US-Gray-US, #747D8C);
			text-align: center;
			position: absolute;
			top: 0;
			left: 0;
			width: 50%;
			height: 50px;
			line-height: 52px;
			color: #fff;
			font-family: Ubuntu;
			font-size: 14px;
			font-style: normal;
			font-weight:700;
			cursor: pointer;
			transition: all .4s;
		}
		.btnEx {
			border-radius: 0px 16px 0px 0px;
			background: var(--Expand-Palete-US-Grayscale-US-Light-Gray-4-US, #F1F2F6);
			text-align: center;
			position: absolute;
			top: 0;
			right: 0;
			width: 50%;
			height: 50px;
			line-height: 52px;
			color: var(--Expand-Palete-US-Grayscale-US-Light-Gray-1-US, #A4B0BE);
			font-family: Ubuntu;
			font-size: 14px;
			font-style: normal;
			font-weight: 700;
			cursor: pointer;
			transition: all .4s;
		}
		.btnEx:hover {
			border-radius: 0px 16px 0px 0px;
			background: var(--Expand-Palete-US-Grayscale-US-Gray-US, #747D8C);
			text-align: center;
			position: absolute;
			top: 0;
			right: 0;
			width: 50%;
			height: 50px;
			line-height: 52px;
			color: #fff;
			font-family: Ubuntu;
			font-size: 14px;
			font-style: normal;
			font-weight: 700;
			cursor: pointer;
			transition: all .4s;
		}
		.btnEx.active {
			border-radius: 0px 16px 0px 0px;
			background: var(--Expand-Palete-US-Grayscale-US-Gray-US, #747D8C);
			text-align: center;
			position: absolute;
			top: 0;
			right: 0;
			width: 50%;
			height: 50px;
			line-height: 52px;
			color: #fff;
			font-family: Ubuntu;
			font-size: 14px;
			font-style: normal;
			font-weight: 700;
			cursor: pointer;
			transition: all .4s;
		}
		.box {
			position: fixed;
			z-index: 999999999;
			right: 10%;
			top: 18%;
			background: #dcdcdc;
			font-family: Ubuntu, sans-serif;
			max-width: 400px;
			padding: 60px 30px 30px 30px;
			font-size: 12px;
			border-radius: 16px;
			border: 2px solid var(--Expand-Palete-US-Grayscale-US-Light-Gray-2-US, #CED6E0);
			background: var(--Expand-Palete-US-Grayscale-US-White, #FFF);
			box-shadow: 0px 4px 16px 0px rgba(0, 0, 0, 0.12);
		}
		.dePor {
			position: relative;
			display: block;
			width: 100%;
			margin: 0 auto;
			color: #57606F;
			color: var(--Expand-Palete-US-Grayscale-US-Light-Gray-1-US, #A4B0BE);
			leading-trim: both;
			text-edge: cap;
			font-family: Ubuntu;
			font-style: normal;
			font-weight: 700;
			line-height: normal;
		}
		#valorSDesconto {
			text-decoration: line-through;
		}
		.valorParcela {
			position: relative;
			display: block;
			width: 100%;
			margin: -5px auto 10px auto;
			font-size: 35px;
			font-weight: 800;
		}
		.vezes {
			position: relative;
			display: block;
			width: 100%;
			margin: 0 auto;
			font-weight: 500;
		}
		.wrapSides {
			position: relative;
			margin-top: 18px;
			color: #57606F;
		}
		.innerBox .left {
			position: relative;
			display: inline-block;
			width: 49%;
			vertical-align: top;
		}
		.innerBox .right {
			position: relative;
			display: inline-block;
			width: 49%;
			vertical-align: top;
		}
		.innerBox h3 {
			font-size: 13px;
			font-weight: 600;
			margin-bottom: 0;
			color: #57606F;
		}
		.innerBox .conteudoBox {
			position: relative;
			margin-bottom: -5px;
			font-size: 12px;
		}
		.btnComprar {
			position: relative;
			background-color: #EF7D00;
			color: #fff;
			text-align: center;
			font-weight: 600;
			font-size: 14px;
			padding: 12px;
			margin-top: 20px;
			width: 100%;
			border: 5px solid #EF7D00;
			/* opacity: .5; */
			cursor: pointer;
			transition: all .4s;
		}
		.btnComprar:hover {
			opacity: 1;
			transition: all .4s;
		}
		.single-featured-image-header {
			position: relative !important;
			display: block !important;
			background-size: cover !important;
			background-repeat: no-repeat !important;
			width: !important;
			height: 500px !important;
			background-color: #414141 !important;
		}
		.single-featured-image-header img {
			display: none;
		}
	</style>



	<script>
		window.onload = function() {
			const iniciais = document.querySelectorAll('a');
			const modalid = document.getElementById('modalidade').innerHTML;
			iniciais.forEach((inicial) => {
				if(inicial.innerText == 'Home') {
					inicial.innerText = 'Início';
				}
			});
			document.getElementById('innerMod').innerHTML = modalid;
		};
	</script>