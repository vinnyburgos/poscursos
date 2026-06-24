<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.2
 */

?>
<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentyseventeen' ); ?>">
	<?php
		// Get the home URL
		$home_url = esc_url( home_url( '/' ) );
		// Check if we are on the front page
		$is_front_page = is_front_page();
	?>
	<a class="innerButtomTop" href="<?php echo $is_front_page ? '#findBar' : $home_url . '#findBar'; ?>">Escolha seu Curso</a>
	<a class="innerButtomTop" href="<?php echo $is_front_page ? '#sobre' : $home_url . '#sobre'; ?>">Sobre a Pós-Graduação</a>
	<a class="innerButtomTop" href="<?php echo $is_front_page ? '#lancamentos' : $home_url . '#lancamentos'; ?>">Lançamentos</a>
	<a class="innerButtomTop" href="<?php echo $is_front_page ? '#nossasUnidades' : $home_url . '#nossasUnidades'; ?>">Unidades</a>
	<a class="innerButtomTop" href="https://ajuda.unisuam.edu.br/p%C3%B3s-gradua%C3%A7%C3%A3o" target="_blank">Dúvidas</a>
	<!-- <a class="innerButtomTop" href="https://www.unisuam.edu.br/" target="_blank">Portal UNISUAM</a> -->

<style>
	.main-navigation a {
		padding: 1em .7em;
	}
	.oMenu.has-temp-pos-cta {
		gap: 41px;
	}
</style>
	
</nav><!-- #site-navigation -->

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const buttons = document.querySelectorAll('.innerButtomTop');
		if (!buttons.length) return;

		const style = document.createElement('style');
		style.textContent = `
			.innerButtomTop {
				position: relative;
				display: inline-block;
			}
			.innerButtomTop::after {
				content: '';
				position: absolute;
				left: 10%;
				right: 10%;
				bottom: -13px;
				height: 3px;
				width: 80%;
				background: transparent;
				transition: background 0.2s ease;
			}
			.innerButtomTop:hover::after,
			.innerButtomTop--active::after {
				background: #ff7a00;
			}
		`;
		document.head.appendChild(style);

		buttons.forEach((button) => {
			button.addEventListener('click', () => {
				buttons.forEach((el) => el.classList.remove('innerButtomTop--active'));
				button.classList.add('innerButtomTop--active');
			});
		});
	});
</script>
