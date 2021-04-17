<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();


?>
<div class="dashboard-order row">
	<?= $this->get( 'orderBody' ) ?>

	<script src="https://cdn.jsdelivr.net/combine/npm/moment@2,npm/chart.js@2,npm/chartjs-chart-matrix@0.1.3,npm/chartjs-chart-geo@2,npm/chartjs-plugin-doughnutlabel@2/dist/chartjs-plugin-doughnutlabel.min.js"></script>
</div>
