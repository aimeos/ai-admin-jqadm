<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


namespace Aimeos\Admin\JQAdm\Catalog\Text;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$langManager = \Aimeos\MShop::create( $this->context, 'locale/language' );

		$this->view->pageLanguages = $langManager->search( $langManager->filter() );
		$this->view->item = \Aimeos\MShop::create( $this->context, 'catalog' )->create();

		$this->object = new \Aimeos\Admin\JQAdm\Catalog\Text\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'item-text', $result );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->find( 'cafe', array( 'text' ) );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'item-text', $result );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->create();
		$result = $this->object->delete();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->find( 'cafe', array( 'text' ) );
		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'item-text', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );
		$item = $manager->create();

		$param = array(
			'site' => 'unittest',
			'text' => array(
				array(
					'text.id' => '',
					'text.content' => 'test name',
					'text.languageid' => 'de',
					'text.type' => 'name',
					'catalog.lists.type' => 'default',
				),
				array(
					'text.id' => '',
					'text.content' => 'short desc',
					'text.languageid' => 'de',
					'text.type' => 'name',
					'catalog.lists.type' => 'default',
				),
				array(
					'text.id' => '',
					'text.content' => 'long desc',
					'text.languageid' => 'de',
					'text.type' => 'name',
					'catalog.lists.type' => 'default',
				),
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 3, count( $this->view->item->getListItems() ) );

		foreach( $this->view->item->getListItems( 'text' ) as $listItem )
		{
			$this->assertEquals( 'text', $listItem->getDomain() );

			$refItem = $listItem->getRefItem();
			$this->assertEquals( 'de', $refItem->getLanguageId() );
		}
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Catalog\Text\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelper::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'catalog' )->create();

		$object->setView( $this->view );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Catalog\Text\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelper::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'catalog' )->create();

		$object->setView( $this->view );

		$this->expectException( \Aimeos\MShop\Exception::class );
		$object->save();
	}


	public function testSearch()
	{
		$this->assertEmpty( $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}
}
