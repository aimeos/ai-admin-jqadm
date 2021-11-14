<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Dashboard\Job;


/**
 * Default implementation of dashboard job JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/dashboard/job/name
	 * Name of the job subpart used by the JQAdm dashboard implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Dashboard\Job\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.08
	 * @category Developer
	 */


	/**
	 * Deletes a resource
	 *
	 * @return string|null Output to display or null for none
	 */
	public function delete() : ?string
	{
		$view = $this->view();
		$context = $this->getContext();

		if( ( $id = $view->param( 'id' ) ) === null )
		{
			$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
		}

		$fs = $context->getFileSystemManager()->get( 'fs-admin' );
		$manager = \Aimeos\MAdmin::create( $context, 'job' );
		$item = $manager->get( $id );

		if( ( $path = $item->getPath() ) && $fs->has( $path ) ) {
			$fs->rm( $path );
		}

		$manager->delete( $id );

		return $this->search();
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null Output to display or null for none
	 */
	public function get() : ?string
	{
		$view = $this->getObject()->data( $this->view() );
		$context = $this->getContext();

		if( ( $id = $view->param( 'id' ) ) === null )
		{
			$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
		}

		$fs = $context->getFileSystemManager()->get( 'fs-admin' );
		$item = \Aimeos\MAdmin::create( $context, 'job' )->get( $id );

		if( ( $path = $item->getPath() ) && $fs->has( $path ) )
		{
			$stream = $view->response()->createStream( $fs->reads( $path ) );
			$view->response()->withHeader( 'Content-Disposition', 'attachment; filename="' . $path . '"' );
			$view->response()->withHeader( 'Content-Type', 'text/csv' );
			$view->response()->withBody( $stream );
		}

		return null;
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string Output to display
	 */
	public function search() : ?string
	{
		$view = $this->view();
		$manager = \Aimeos\MAdmin::create( $this->getContext(), 'job' );

		$search = $manager->filter();
		$search->setSortations( [$search->sort( '-', 'job.ctime' ), $search->sort( '-', 'job.id' )] );
		$total = 0;

		$view->jobItems = $manager->search( $search, [], $total );
		$view->jobBody = parent::search();
		$view->jobTotal = $total;

		/** admin/jqadm/dashboard/job/template-list
		 * Relative path to the HTML body template of the job subpart for the dashboard.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2017.08
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/dashboard/job/template-list';
		$default = 'dashboard/list-job-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		/** admin/jqadm/dashboard/job/decorators/excludes
		 * Excludes decorators added by the "common" option from the dashboard JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/dashboard/job/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.08
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/dashboard/job/decorators/global
		 * @see admin/jqadm/dashboard/job/decorators/local
		 */

		/** admin/jqadm/dashboard/job/decorators/global
		 * Adds a list of globally available decorators only to the dashboard JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/dashboard/job/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.08
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/dashboard/job/decorators/excludes
		 * @see admin/jqadm/dashboard/job/decorators/local
		 */

		/** admin/jqadm/dashboard/job/decorators/local
		 * Adds a list of local decorators only to the dashboard JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Dashboard\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/dashboard/job/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Dashboard\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.08
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/dashboard/job/decorators/excludes
		 * @see admin/jqadm/dashboard/job/decorators/global
		 */
		return $this->createSubClient( 'dashboard/job/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/dashboard/job/subparts
		 * List of JQAdm sub-clients rendered within the dashboard job section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The job of the JQAdm sub-clients
		 * determines the job of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the job of the output by reordering the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reordering content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2017.08
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/dashboard/job/subparts', [] );
	}
}
