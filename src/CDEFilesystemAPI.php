<?php

namespace Ixolit\CDE;

use Ixolit\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\CDE\Exceptions\DirectoryExpectedException;
use Ixolit\CDE\Exceptions\FileNotFoundException;
use Ixolit\CDE\Exceptions\UnexpectedFilesystemEntryException;
use Ixolit\CDE\Interfaces\FilesystemAPI;
use Ixolit\CDE\WorkingObjects\DirectoryFilesystemEntry;
use Ixolit\CDE\WorkingObjects\FileFilesystemEntry;
use Ixolit\CDE\WorkingObjects\FilesystemEntry;

class CDEFilesystemAPI implements FilesystemAPI {
	/**
	 * {@inheritdoc}
	 */
	public function exists($path) {
		if (!\function_exists('getPathInfo')) {
			throw new CDEFeatureNotSupportedException('getPathInfo');
		}
		$path = preg_replace('/^vfs\:/', '/', $path);
		return (\getPathInfo($path) !== null);
	}

	/**
	 * @param string    $name
	 * @param \stdClass $entry
	 *
	 * @return DirectoryFilesystemEntry|FileFilesystemEntry
	 * @throws UnexpectedFilesystemEntryException
	 */
	private function entryToObject($name, $entry) {
		$name = preg_replace('/^vfs\:/', '/', $name);
		switch ($entry->type) {
			case 'file':
				$modified = new \DateTime();
				$modified->setTimestamp($entry->modified);
				return new FileFilesystemEntry($name, $entry->size, $modified);
				break;
			case 'directory':
				return new DirectoryFilesystemEntry($name);
				break;
			default:
				throw new UnexpectedFilesystemEntryException($name, $entry->type);
				break;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function pathInfo($path) {
		if (!\function_exists('getPathInfo')) {
			throw new CDEFeatureNotSupportedException('getPathInfo');
		}
		$path = preg_replace('/^vfs\:/', '/', $path);
		$entry = getPathInfo($path);
		if ($entry === null) {
			throw new FileNotFoundException($path);
		}
		return $this->entryToObject($path, $entry);
	}

	/**
	 * {@inheritdoc}
	 */
	public function listDirectory($directory) {
		if (!\function_exists('getPathInfo')) {
			throw new CDEFeatureNotSupportedException('getPathInfo');
		}
		if (!\function_exists('listDirectory')) {
			throw new CDEFeatureNotSupportedException('listDirectory');
		}
		$directory = preg_replace('/^vfs\:/', '/', $directory);
		if ($this->pathInfo($directory)->getType() == FilesystemEntry::TYPE_FILE) {
			throw new DirectoryExpectedException($directory);
		}
		$result = [];
		foreach (listDirectory($directory) as $name => $entry) {
			$result[] = $this->entryToObject(\rtrim($directory, '/') . '/' . $name, $entry);
		}
		return $result;
	}
}
