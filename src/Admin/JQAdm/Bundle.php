<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm;


/**
 * Generates concatenated CSS/JS files read from a JSB2 package files
 */
class Bundle
{
	/**
	 * Returns the concatenated files for the requested resource
	 *
	 * @param string $resource Name of the resource, i.e. "index-js", "index-css", "index-ltr-css" or "index-rtl-css"
	 * @return string Concatenated files for the requested resource
	 */
	public static function get( array $filenames, string $resource ) : string
	{
		$content = '';
		$manifests = self::manifests( $filenames );
		$packages = self::packages( $manifests, $resource );

		foreach( $packages as $basepath => $package )
		{
			foreach( $package->fileIncludes as $singleFile )
			{
				$file = $basepath . '/' . $singleFile->path . $singleFile->text;

				if( $data = file_get_contents( $file ) ) {
					$content .= $data;
				}
			}
		}

		return $content;
	}


	/**
	 * Get the packages from a JSON decoded manifests
	 *
	 * @param array $manifests List of JSON decoded manifests
	 * @param string $resource Name of the resource, i.e. "index-js", "index-css", "index-ltr-css" or "index-rtl-css"
	 * @return array
	 */
	protected static function packages( array $manifests, string $resource ) : array
	{
		$list = [];

		foreach( $manifests as $basepath => $manifest )
		{
			if( empty( $manifest->pkgs ?? [] ) ) {
				throw new \Aimeos\Admin\JQAdm\Exception( 'No packages found' );
			}

			foreach( $manifest->pkgs as $package )
			{
				if( !isset( $package->file ) || !is_object( $package ) ) {
					throw new \Aimeos\Admin\JQAdm\Exception( 'Invalid package content' );
				}

				if( $package->file !== $resource ) {
					continue;
				}

				if( $package->overwrite ?? false ) {
					$list = [$basepath => $package];
				} else {
					$list[$basepath] = $package;
				}
			}
		}

		return $list;
	}


	/**
	 * Returns the manifest file contents
	 *
	 * @param array $filepaths List of paths to the manifest files
	 * @return array Manifest file contents
	 */
	protected static function manifests( array $filepaths )
	{
		$list = [];

		foreach( $filepaths as $filepath )
		{
			if( !file_exists( $filepath ) ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'File does not exists: "%1$s"', $filepath ) );
			}

			if( ( $content = file_get_contents( $filepath ) ) === false ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Unable to read content from "%1$s"', $filepath ) );
			}

			if( ( $content = json_decode( $content ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( 'File content is not JSON encoded' );
			}

			$list[dirname( $filepath )] = $content;
		}

		return $list;
	}
}
