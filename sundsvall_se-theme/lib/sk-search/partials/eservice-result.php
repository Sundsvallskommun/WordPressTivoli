<?php

global $sk_eservices, $searchPostMarkup, $sk_search;

$eservices = $sk_eservices->search_eservices($sk_search->search_string);

?>

		<div class="search-module">

			<div class="search-module__header">

				<h2 class="search-module__title">E-tjänster</h2>
				<div class="post-count">
				<?php
					printf('Visar <span class="post-count__count">%s</span> av <span class="post-count__total">%d</span>', count($eservices), count($eservices));
				?>
				</div>
			</div>
			<?php if( !empty( $eservices ) ): ?>
			<ol class="search-module__items">

<?php

				foreach( $eservices as $eservice) {

					printf($searchPostMarkup, 'eservice', $eservice['URL'], get_icon('alignleft'), $eservice['Name'], 'E-tjänst', $eservice['Category']);

				}

?>

			</ol>
			<?php else: ?>

				<p class="m-t-2 text-xs-center text-muted">Inget resultat för E-tjänster</p>

			<?php endif; ?>

			<div class="search-module__footer">
				<a href="//e-tjanster.sundsvall.se/">Alla e-tjänster</a>
			</div>

	</div>
