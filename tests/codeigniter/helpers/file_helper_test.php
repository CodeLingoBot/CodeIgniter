<?php

class File_helper_Test extends CI_TestCase {

	public function set_up()
	{
		$this->helper('file');

		vfsStreamWrapper::register();
		vfsStreamWrapper::setRoot(new vfsStreamDirectory('testDir'));

		$this->_test_dir = vfsStreamWrapper::getRoot();
	}

	// --------------------------------------------------------------------

	public function test_octal_permissions()
	{
		$content = 'Jack and Jill went up the mountain to fight a billy goat.';

		$file = vfsStream::newFile('my_file.txt', 0777)
			->withContent($content)
			->lastModified(time() - 86400)
			->at($this->_test_dir);

		$this->assertEquals('777', octal_permissions($file->getPermissions()));
	}

	// --------------------------------------------------------------------

	/**
	 * More tests should happen here, since I'm not hitting the whole function.
	 */
	public function test_symbolic_permissions()
	{
		$content = 'Jack and Jill went up the mountain to fight a billy goat.';

		$file = vfsStream::newFile('my_file.txt', 0777)
			->withContent($content)
			->lastModified(time() - 86400)
			->at($this->_test_dir);

		$this->assertEquals('urwxrwxrwx', symbolic_permissions($file->getPermissions()));
	}

	// --------------------------------------------------------------------

	public function test_get_mime_by_extension()
	{
		$content = 'Jack and Jill went up the mountain to fight a billy goat.';

		$file = vfsStream::newFile('my_file.txt', 0777)
			->withContent($content)
			->lastModified(time() - 86400)
			->at($this->_test_dir);

		$this->assertEquals('text/plain', get_mime_by_extension(vfsStream::url('my_file.txt')));

		// Test a mime with an array, such as png
		$file = vfsStream::newFile('foo.png')->at($this->_test_dir);

		$this->assertEquals('image/png', get_mime_by_extension(vfsStream::url('foo.png')));

		// Test a file not in the mimes array
		$file = vfsStream::newFile('foo.blarfengar')->at($this->_test_dir);

		$this->assertFalse(get_mime_by_extension(vfsStream::url('foo.blarfengar')));
	}

	// --------------------------------------------------------------------

	public function test_get_file_info()
	{
		// Test Bad File
		$this->assertFalse(get_file_info('i_am_bad_boo'));

		// Test the rest

		// First pass in an array
		$vals = array(
			'name', 'server_path', 'size', 'date',
			'readable', 'writable', 'executable', 'fileperms'
		);

		$this->_test_get_file_info($vals);

		// Test passing in vals as a string.
		$this->_test_get_file_info(implode(', ', $vals));
	}

	

	// --------------------------------------------------------------------

	 public function test_write_file()
	 {
		$content = 'Jack and Jill went up the mountain to fight a billy goat.';

		$file = vfsStream::newFile('write.txt', 0777)
			->withContent('')
			->lastModified(time() - 86400)
			->at($this->_test_dir);

		$this->assertTrue(write_file(vfsStream::url('write.txt'), $content));
	 }

}
