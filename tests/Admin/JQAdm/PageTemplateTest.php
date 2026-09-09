<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */

namespace Aimeos\Admin\JQAdm;


class PageTemplateTest extends \PHPUnit\Framework\TestCase
{
	public function testLocaleIsAttributeEncoded()
	{
		$path = dirname( __DIR__, 3 ) . '/templates/admin/jqadm/page.php';
		$template = file_get_contents( $path );

		$this->assertStringContainsString(
			'lang="<?= $enc->attr( $this->param( \'locale\' ) ) ?>"',
			$template
		);
		$this->assertStringNotContainsString( 'lang="<?= $this->param( \'locale\' ) ?>"', $template );
	}
}
