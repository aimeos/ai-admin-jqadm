<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\DataProvider;


#[AllowMockObjectsWithoutExpectations]
class PolicyTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $mock;


	protected function setUp() : void
	{
		$this->mock = $this->getMockBuilder( 'Aimeos\Admin\JQAdm\Product\Standard' )
			->onlyMethods( array( 'batch', 'delete', 'import', 'save' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->context = \TestHelper::context();
		$this->context->setPolicy( new class implements \Aimeos\MShop\PolicyIface {
			public function allowed( \Aimeos\MShop\ContextIface $context, string $resource, string $action ) : ?bool {
				return $resource === 'product' && in_array( $action, ['import', 'save'] );
			}
		} );

		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Policy( $this->mock, $this->context, 'product' );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->mock, $this->context );
	}


	public function testBatch()
	{
		$this->mock->expects( $this->once() )->method( 'batch' )->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->batch() );
	}


	public function testDeleteDenied()
	{
		$this->mock->expects( $this->never() )->method( 'delete' );

		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionCode( 403 );
		$this->expectExceptionMessageMatches( '/^Action "delete" is not allowed for "product"$/' );
		$this->object->delete();
	}


	public function testImport()
	{
		$this->mock->expects( $this->once() )->method( 'import' )->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->import() );
	}


	public function testImportDenied()
	{
		// saving items doesn't allow importing files
		$this->context->setPolicy( new class implements \Aimeos\MShop\PolicyIface {
			public function allowed( \Aimeos\MShop\ContextIface $context, string $resource, string $action ) : ?bool {
				return $resource === 'product' && $action === 'save';
			}
		} );

		$this->mock->expects( $this->never() )->method( 'import' );

		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionCode( 403 );
		$this->expectExceptionMessageMatches( '/^Action "import" is not allowed for "product"$/' );
		$this->object->import();
	}


	public function testSave()
	{
		$this->mock->expects( $this->once() )->method( 'save' )->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->save() );
	}


	public static function saveMethods() : array
	{
		return [
			'batch' => ['batch', 'save'],
			'import' => ['import', 'import'],
			'save' => ['save', 'save'],
		];
	}


	#[DataProvider('saveMethods')]
	public function testDeniedWithoutSave( string $method, string $action )
	{
		$object = new \Aimeos\Admin\JQAdm\Common\Decorator\Policy( $this->mock, $this->context, 'catalog' );
		$this->mock->expects( $this->never() )->method( $method );

		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionCode( 403 );
		$this->expectExceptionMessageMatches( '/^Action "' . $action . '" is not allowed for "catalog"$/' );
		$object->$method();
	}
}
