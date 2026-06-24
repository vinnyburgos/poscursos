<!-- nova  -->

<!-- Carrossel: CONHEÇA NOSSAS ESCOLAS -->

<!-- Swiper JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Swiper CSS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
/* === Setas personalizadas: CONHEÇA NOSSAS ESCOLAS === */
.conhecaEscolasWrap {
  position: relative;
  padding: 0;
}

.swiper-button-next.conhecaEscolasNext,
.swiper-button-prev.conhecaEscolasPrev {
  position: absolute !important;
  top: 40% !important;
  transform: translateY(-50%) !important;
  margin-top: 0 !important;
  width: 48px !important;
  height: 48px !important;
  border-radius: 50% !important;
  background: #ffffff !important;
  border: 1px solid rgba(0,0,0,0.08) !important;
  box-shadow: 0 4px 16px rgba(0,0,0,0.12) !important;
  color: transparent !important;
  overflow: visible !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  z-index: 10 !important;
}
.swiper-button-prev.conhecaEscolasPrev { left: -28px !important; }
.swiper-button-next.conhecaEscolasNext { right: -28px !important; }

/* Zera o ícone padrão do Swiper */
.swiper-button-next.conhecaEscolasNext::after,
.swiper-button-prev.conhecaEscolasPrev::after {
  content: "" !important;
  font-family: inherit !important;
  font-size: 0 !important;
}

/* Chevron azul via ::before */
.swiper-button-next.conhecaEscolasNext::before,
.swiper-button-prev.conhecaEscolasPrev::before {
  content: "";
  display: block;
  width: 22px;
  height: 22px;
  background-repeat: no-repeat;
  background-position: center;
  background-size: contain;
  flex-shrink: 0;
}
.swiper-button-next.conhecaEscolasNext::before {
  background-image: url('data:image/svg+xml;utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"%3E%3Cpath d="M8 4l8 8-8 8" fill="none" stroke="%230076A8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/%3E%3C/svg%3E');
}
.swiper-button-prev.conhecaEscolasPrev::before {
  background-image: url('data:image/svg+xml;utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"%3E%3Cpath d="M16 4l-8 8 8 8" fill="none" stroke="%230076A8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/%3E%3C/svg%3E');
}

@media (max-width: 600px) {
  .swiper-button-next.conhecaEscolasNext::after,
  .swiper-button-prev.conhecaEscolasPrev::after { display: none; }

  /* Centraliza o card no slide em mobile */
  .conhecaEscolasSwiper .swiper-slide {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
  }
}
</style>

<section class="conheca-escolas" style="background: #F7F8FA; padding: 48px 0 32px 0;">
	<div class="center" style="max-width: 1200px; margin: 0 auto;">
		<div style="text-align: center;">
			<span style="display: inline-block; width: 48px; height: 4px; background: #FF8C2B; border-radius: 2px; margin-bottom: 16px;"></span>
			<h2 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 18px; color: #23272F; letter-spacing: 0.01em;">CONHEÇA NOSSAS ESCOLAS</h2>
		</div>
		<div class="conhecaEscolasWrap">
		<div class="swiper conhecaEscolasSwiper" style="margin: 32px 0 18px 0;">
			<div class="swiper-wrapper" style="min-height:220px">
				<!-- Slide 1 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-saude/" class="href">
						<div class="boxConheca" style="border: 2px solid #18A0B6;">
							<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
								<path d="M4 19.0001C4.00004 16.7745 4.67519 14.6012 5.93627 12.7674C7.19735 10.9336 8.98503 9.5254 11.0632 8.72888C13.1414 7.93236 15.4123 7.78497 17.576 8.30617C19.7397 8.82737 21.6944 9.99264 23.182 11.6481C23.2868 11.7601 23.4134 11.8494 23.5542 11.9105C23.6949 11.9715 23.8466 12.003 24 12.003C24.1534 12.003 24.3051 11.9715 24.4458 11.9105C24.5866 11.8494 24.7132 11.7601 24.818 11.6481C26.3009 9.98188 28.256 8.80682 30.4233 8.27928C32.5905 7.75175 34.867 7.89677 36.9497 8.69505C39.0325 9.49332 40.8228 10.907 42.0822 12.7479C43.3417 14.5888 44.0106 16.7696 44 19.0001C44 23.5801 41 27.0001 38 30.0001L27.016 40.6261C26.6433 41.0541 26.1839 41.3979 25.6681 41.6347C25.1523 41.8715 24.5921 41.9958 24.0246 41.9994C23.4571 42.003 22.8953 41.8858 22.3766 41.6555C21.8579 41.4253 21.3941 41.0873 21.016 40.6641L10 30.0001C7 27.0001 4 23.6001 4 19.0001Z" stroke="#0F96AE" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M6.43994 26H18.9999L19.9999 24L23.9999 33L27.9999 19L30.9999 26H41.5399" stroke="#0F96AE" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span style="font-weight: 700; color: #18A0B6; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE SAÚDE<br>E BEM-ESTAR</span>
						</div>
					</a>
				</div>
				<!-- Slide 2 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-comunicacao-e-marketing/" class="href">
						<div class="boxConheca" style="border: 2px solid #E5457A;">
							<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
								<path d="M22 12C28.0828 12.1567 34.0278 10.175 38.8 6.4C39.0971 6.17715 39.4505 6.04144 39.8204 6.00808C40.1903 5.97473 40.5622 6.04504 40.8944 6.21115C41.2266 6.37725 41.506 6.63259 41.7013 6.94854C41.8966 7.26449 42 7.62858 42 8V32C42 32.3714 41.8966 32.7355 41.7013 33.0515C41.506 33.3674 41.2266 33.6227 40.8944 33.7889C40.5622 33.955 40.1903 34.0253 39.8204 33.9919C39.4505 33.9586 39.0971 33.8229 38.8 33.6C34.0278 29.825 28.0828 27.8433 22 28H10C8.93913 28 7.92172 27.5786 7.17157 26.8284C6.42143 26.0783 6 25.0609 6 24V16C6 14.9391 6.42143 13.9217 7.17157 13.1716C7.92172 12.4214 8.93913 12 10 12H22Z" stroke="#E5457A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12 28C12 33.1929 13.6843 38.2457 16.8 42.4C17.4365 43.2487 18.3841 43.8098 19.4343 43.9598C20.4845 44.1098 21.5513 43.8365 22.4 43.2C23.2487 42.5635 23.8098 41.6159 23.9598 40.5657C24.1098 39.5155 23.8365 38.4487 23.2 37.6C21.1228 34.8305 20 31.4619 20 28" stroke="#E5457A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M16 12V28" stroke="#E5457A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span style="font-weight: 700; color: #E04A7F; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE COMUNICAÇÃO<br>E MARKETING</span>
						</div>
					</a>
				</div>
				<!-- Slide 3 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-educacao/" class="href">
						<div class="boxConheca" style="border: 2px solid #EF7D00;">
							<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
								<path d="M24 14V42" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M32 24H36" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M32 16H36" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M6 36C5.46957 36 4.96086 35.7893 4.58579 35.4142C4.21071 35.0391 4 34.5304 4 34V8C4 7.46957 4.21071 6.96086 4.58579 6.58579C4.96086 6.21071 5.46957 6 6 6H16C18.1217 6 20.1566 6.84285 21.6569 8.34315C23.1571 9.84344 24 11.8783 24 14C24 11.8783 24.8429 9.84344 26.3431 8.34315C27.8434 6.84285 29.8783 6 32 6H42C42.5304 6 43.0391 6.21071 43.4142 6.58579C43.7893 6.96086 44 7.46957 44 8V34C44 34.5304 43.7893 35.0391 43.4142 35.4142C43.0391 35.7893 42.5304 36 42 36H30C28.4087 36 26.8826 36.6321 25.7574 37.7574C24.6321 38.8826 24 40.4087 24 42C24 40.4087 23.3679 38.8826 22.2426 37.7574C21.1174 36.6321 19.5913 36 18 36H6Z" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12 24H16" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12 16H16" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span style="font-weight: 700; color: #FFA726; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE EDUCAÇÃO</span>
						</div>
					</a>
				</div>
				<!-- Slide 4 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-tecnologia/" class="href">
						<div class="boxConheca" style="border: 2px solid #7D378D;">
							<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
								<path d="M24 40V44" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M24 4V8" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M34 40V44" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M34 4V8" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M4 24H8" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M4 34H8" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M4 14H8" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M40 24H44" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M40 34H44" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M40 14H44" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M14 40V44" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M14 4V8" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M36 8H12C9.79086 8 8 9.79086 8 12V36C8 38.2091 9.79086 40 12 40H36C38.2091 40 40 38.2091 40 36V12C40 9.79086 38.2091 8 36 8Z" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M30 16H18C16.8954 16 16 16.8954 16 18V30C16 31.1046 16.8954 32 18 32H30C31.1046 32 32 31.1046 32 30V18C32 16.8954 31.1046 16 30 16Z" stroke="#7D378D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span style="font-weight: 700; color: #8E44AD; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE GESTÃO<br>E TECNOLOGIA</span>
						</div>
					</a>
				</div>
				<!-- Slide 5 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-gastronomia/" class="href">
						<div class="boxConheca" style="border: 2px solid #EF7D00;">
							<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M4 24H44" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M40 24V40C40 41.0609 39.5786 42.0783 38.8284 42.8284C38.0783 43.5786 37.0609 44 36 44H12C10.9391 44 9.92172 43.5786 9.17157 42.8284C8.42143 42.0783 8 41.0609 8 40V24" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M8 16L40 8" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M17.72 13.5601L16.82 9.94008C16.6911 9.43055 16.6638 8.9006 16.7398 8.38052C16.8157 7.86044 16.9935 7.36043 17.2628 6.90908C17.5321 6.45773 17.8878 6.06388 18.3094 5.75005C18.731 5.43622 19.2103 5.20856 19.72 5.08009L23.6 4.12009C24.1109 3.99146 24.6421 3.96501 25.1633 4.04227C25.6844 4.11953 26.1851 4.29898 26.6367 4.5703C27.0883 4.84162 27.4818 5.19947 27.7947 5.62331C28.1076 6.04715 28.3337 6.52863 28.46 7.04009L29.36 10.6401" stroke="#EF7D00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>


							<span style="font-weight: 700; color: #EF7D00; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE GASTRONOMIA</span>
						</div>
					</a>
				</div>
				<!-- Slide 6 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-engenharia/" class="href">
						<div class="boxConheca" style="border: 2px solid #004070;">
							<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M26.0001 13.9996L17.4001 5.3996C16.4968 4.50072 15.2744 3.99609 14.0001 3.99609C12.7258 3.99609 11.5033 4.50072 10.6001 5.3996L5.40009 10.5996C4.50121 11.5029 3.99658 12.7253 3.99658 13.9996C3.99658 15.2739 4.50121 16.4964 5.40009 17.3996L14.0001 25.9996" stroke="#004070" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M16 12L20 8" stroke="#004070" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M36 32L40 28" stroke="#004070" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M34 22L42.6 30.6C44.48 32.48 44.48 35.52 42.6 37.4L37.4 42.6C35.52 44.48 32.48 44.48 30.6 42.6L22 34" stroke="#004070" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M42.3479 13.6238C43.4053 12.5666 43.9994 11.1327 43.9996 9.63749C43.9998 8.14227 43.406 6.7082 42.3489 5.65079C41.2917 4.59337 39.8578 3.99921 38.3626 3.99902C36.8673 3.99884 35.4333 4.59263 34.3759 5.64979L7.68387 32.3478C7.21951 32.8108 6.8761 33.3808 6.68387 34.0078L4.04187 42.7118C3.99018 42.8848 3.98628 43.0685 4.03057 43.2435C4.07487 43.4185 4.16571 43.5782 4.29346 43.7058C4.42121 43.8333 4.58111 43.9239 4.75619 43.9679C4.93126 44.012 5.11499 44.0078 5.28787 43.9558L13.9939 41.3158C14.6202 41.1253 15.1902 40.784 15.6539 40.3218L42.3479 13.6238Z" stroke="#004070" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M30 10L38 18" stroke="#004070" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>

							<span style="font-weight: 700; color: #004070; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE ENGENHARIA<br>& ARQUITETURA</span>
						</div>
					</a>
				</div>
				<!-- Slide 7 -->
				<!-- <div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-finacas/" class="href">
						<div class="boxConheca" style="border: 2px solid #07AD6A;">
							<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7.6999 17.2402C7.40798 15.9252 7.4528 14.5579 7.83021 13.2649C8.20762 11.9719 8.90539 10.7951 9.85883 9.84364C10.8123 8.89219 11.9905 8.19688 13.2843 7.82218C14.5781 7.44748 15.9456 7.40552 17.2599 7.70019C17.9833 6.5688 18.9799 5.63771 20.1578 4.99277C21.3357 4.34782 22.657 4.00977 23.9999 4.00977C25.3428 4.00977 26.6641 4.34782 27.842 4.99277C29.0199 5.63771 30.0165 6.5688 30.7399 7.70019C32.0562 7.40424 33.426 7.44601 34.7219 7.82162C36.0178 8.19723 37.1976 8.89448 38.1516 9.8485C39.1056 10.8025 39.8029 11.9823 40.1785 13.2782C40.5541 14.5741 40.5958 15.9439 40.2999 17.2602C41.4313 17.9836 42.3624 18.9802 43.0073 20.1581C43.6523 21.336 43.9903 22.6573 43.9903 24.0002C43.9903 25.3431 43.6523 26.6644 43.0073 27.8423C42.3624 29.0202 41.4313 30.0168 40.2999 30.7402C40.5946 32.0545 40.5526 33.422 40.1779 34.7158C39.8032 36.0096 39.1079 37.1878 38.1565 38.1413C37.205 39.0947 36.0282 39.7925 34.7352 40.1699C33.4422 40.5473 32.0748 40.5921 30.7599 40.3002C30.0374 41.4359 29.0401 42.371 27.8601 43.0188C26.6802 43.6667 25.356 44.0063 24.0099 44.0063C22.6638 44.0063 21.3396 43.6667 20.1596 43.0188C18.9797 42.371 17.9824 41.4359 17.2599 40.3002C15.9456 40.5949 14.5781 40.5529 13.2843 40.1782C11.9905 39.8035 10.8123 39.1082 9.85883 38.1568C8.90539 37.2053 8.20762 36.0285 7.83021 34.7355C7.4528 33.4425 7.40798 32.0751 7.6999 30.7602C6.55981 30.0387 5.62073 29.0405 4.96999 27.8586C4.31926 26.6767 3.97803 25.3494 3.97803 24.0002C3.97803 22.651 4.31926 21.3237 4.96999 20.1418C5.62073 18.9598 6.55981 17.9617 7.6999 17.2402Z" stroke="#07AD6A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M32 16H20C18.9391 16 17.9217 16.4214 17.1716 17.1716C16.4214 17.9217 16 18.9391 16 20C16 21.0609 16.4214 22.0783 17.1716 22.8284C17.9217 23.5786 18.9391 24 20 24H28C29.0609 24 30.0783 24.4214 30.8284 25.1716C31.5786 25.9217 32 26.9391 32 28C32 29.0609 31.5786 30.0783 30.8284 30.8284C30.0783 31.5786 29.0609 32 28 32H16" stroke="#07AD6A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M24 36V12" stroke="#07AD6A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>


							<span style="font-weight: 700; color: #07AD6A; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE FINANÇAS,<br>AUDITORIA & CONTROLADORIA</span>
						</div>
					</a>
				</div> -->
				<!-- Slide 8 -->
				<div class="swiper-slide">
					<a href="https://poscursos.unisuam.edu.br/escola-de-direito/" class="href">
						<div class="boxConheca" style="border: 2px solid #F05200;">
							<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M24 6V42" stroke="#F05200" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M38 16L44 32C42.269 33.2982 40.1637 34 38 34C35.8363 34 33.731 33.2982 32 32L38 16ZM38 16V14" stroke="#F05200" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M6 14H8C13.5808 14 19.0758 12.6263 24 10C28.9242 12.6263 34.4192 14 40 14H42" stroke="#F05200" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M10 16L16 32C14.269 33.2982 12.1637 34 10 34C7.8363 34 5.73096 33.2982 4 32L10 16ZM10 16V14" stroke="#F05200" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M14 42H34" stroke="#F05200" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>



							<span style="font-weight: 700; color: #F05200; font-size: 1.08rem; text-transform: uppercase;">ESCOLA DE SOCIAIS<br>& DIREITO</span>
						</div>
					</a>
				</div>
			</div>
			<div class="swiper-pagination conhecaEscolasPag"></div>
		</div><!-- /.swiper -->
		<!-- Setas fora do swiper para não sobrepor os cards -->
		<div class="swiper-button-prev conhecaEscolasPrev"></div>
		<div class="swiper-button-next conhecaEscolasNext"></div>
		</div><!-- /.conhecaEscolasWrap -->
	</div>
</section>

<script>
/* Remove do carrossel o card da escola cuja página está sendo visualizada */
(function () {
	var currentPath = window.location.pathname.replace(/\/+$/, '');
	document.querySelectorAll('.conhecaEscolasSwiper .swiper-slide').forEach(function (slide) {
		var link = slide.querySelector('a');
		if (!link) return;
		try {
			var linkPath = new URL(link.href).pathname.replace(/\/+$/, '');
			if (linkPath === currentPath) slide.remove();
		} catch (e) {}
	});
})();
</script>

<!-- nova  -->