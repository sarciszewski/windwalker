<?php
namespace Windwalker\DataMapper\Test;

use Windwalker\Utilities\ArrayHelper;
use Windwalker\Data\Data;
use Windwalker\Data\DataSet;
use Windwalker\DataMapper\DataMapper;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-02-19 at 08:46:39.
 */
class DataMapperTest extends DatabaseTest
{
	/**
	 * @var DataMapper
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->db = static::$dbo;

		$this->object = new DataMapper('ww_flower');
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::find
	 * @todo   Implement testFind().
	 */
	public function testFind()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::findAll
	 * @todo   Implement testFindAll().
	 */
	public function testFindAll()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::findOne
	 * @todo   Implement testFindOne().
	 */
	public function testFindOne()
	{
		// Test find by primary key
		$data = $this->object->findOne(3);

		$this->assertInstanceOf('Windwalker\\Data\\Data', $data, 'Return not Data object.');

		$this->assertEquals($data->title, 'Article Categories Module', 'Title not matched');

		// Test find by other key
		$data = $this->object->findOne(array('catid' => 64, 'created_by' => 256));

		$this->assertEquals($data->title, 'Latest Articles Module', 'Title not matched');
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::create
	 * @todo   Implement testCreate().
	 */
	public function testCreate()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::createOne
	 * @todo   Implement testCreateOne().
	 */
	public function testCreateOne()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::update
	 * @todo   Implement testUpdate().
	 */
	public function testUpdate()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::updateOne
	 * @todo   Implement testUpdateOne().
	 */
	public function testUpdateOne()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::updateAll
	 * @todo   Implement testUpdateAll().
	 */
	public function testUpdateAll()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::updateAll
	 * @todo   Implement testUpdateAll().
	 */
	public function testFlush()
	{
		$mapper = new DataMapper('ww_content_tags');

		$dataset = new DataSet;

		$dataset[] = new Data(array('content_id' => 4, 'tag_id' => 1));
		$dataset[] = new Data(array('content_id' => 4, 'tag_id' => 2));
		$dataset[] = new Data(array('content_id' => 4, 'tag_id' => 4));
		$dataset[] = new Data(array('content_id' => 4, 'tag_id' => 5));

		$mapper->flush($dataset, array('content_id' => 4));

		$tagMaps = $mapper->find(array('content_id' => 4));

		$this->assertEquals(
			array(1,2,4,5),
			ArrayHelper::getColumn((array) $tagMaps, 'tag_id'),
			'Flush data wrong.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::save
	 * @todo   Implement testSave().
	 */
	public function testSave()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::delete
	 * @todo   Implement testDelete().
	 */
	public function testDelete()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::getPrimaryKey
	 * @todo   Implement testGetPrimaryKey().
	 */
	public function testGetPrimaryKey()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::getTable
	 * @todo   Implement testGetTable().
	 */
	public function testGetTable()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::setTable
	 * @todo   Implement testSetTable().
	 */
	public function testSetTable()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::getDataClass
	 * @todo   Implement testGetDataClass().
	 */
	public function testGetDataClass()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::setDataClass
	 * @todo   Implement testSetDataClass().
	 */
	public function testSetDataClass()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::getDatasetClass
	 * @todo   Implement testGetDatasetClass().
	 */
	public function testGetDatasetClass()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::setDatasetClass
	 * @todo   Implement testSetDatasetClass().
	 */
	public function testSetDatasetClass()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
