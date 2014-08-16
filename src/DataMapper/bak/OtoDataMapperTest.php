<?php
namespace Windwalker\DataMapper\Test;

use Joomla\Utilities\ArrayHelper;
use Windwalker\Compare\Compare;
use Windwalker\Compare\EqCompare;
use Windwalker\Data\Data;
use Windwalker\Data\DataSet;
use Windwalker\DataMapper\DataMapper;
use Windwalker\DataMapper\OtoDataMapper;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-02-20 at 03:28:45.
 */
class OtoDataMapperTest extends DatabaseTest
{
	/**
	 * @var OtoDataMapper
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

		$this->object = new OtoDataMapper('ww_content');

		$this->object->addRelation('b', new DataMapper('ww_content2'), array('id' => 'content_id'));
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::find
	 * @todo   Implement testFind().
	 */
	public function testFind()
	{
		$dataset = $this->object->find(array('id' => array(5, 6)));

		$compareContents = $this->loadToDataset(
<<<SQL
SELECT *
FROM ww_content
WHERE id IN(5, 6)
SQL
		);

		$cMapper = new DataMapper('ww_content2');

		foreach ($compareContents as $compareContent)
		{
			$compareContent->b = $cMapper->findOne(array('content_id' => $compareContent->id));
		}

		$this->assertEquals($compareContents, $dataset, 'Record not matches.');
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
		$data = $this->object->findOne(array('id' => array(5, 6)));

		$compareContent = $this->loadToData(
			<<<SQL
SELECT *
FROM ww_content
WHERE id IN(5, 6)
LIMIT 1
SQL
		);

		$cMapper = new DataMapper('ww_content2');

		$compareContent->b = $cMapper->findOne(array('content_id' => $compareContent->id));

		$this->assertEquals($compareContent, $data, 'Record not matches.');
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::create
	 * @todo   Implement testCreate().
	 */
	public function testCreate()
	{
		$data1 = new Data;
		$data1->title = 'Flower';
		$data1->catid = 10;
		$data1->b     = new Data(
			array(
				'mark' => 'Sakura'
			)
		);

		$data2 = new Data;
		$data2->title = 'Flower2';
		$data2->catid = 10;
		$data2->b     = new Data(
			array(
				'mark' => 'Sakura2'
			)
		);

		$dataset = new DataSet(array($data1, $data2));

		$this->object->create($dataset);

		$compareContent = $this->db->setQuery(
<<<SQL
SELECT *
FROM ww_content
WHERE title LIKE 'Flower%'
SQL
		)->loadObjectList();

		$this->assertNotEmpty($compareContent, 'Record not inserted.');

		$ids = implode(',', ArrayHelper::getColumn($compareContent, 'id'));

		$compareContent2 = $this->db->setQuery(
<<<SQL
SELECT *
FROM ww_content2
WHERE content_id IN ({$ids})
SQL
		)->loadObjectList();

		$this->assertNotEmpty($compareContent2, 'Record not inserted.');

		$this->assertEquals($compareContent2[0]->mark, 'Sakura', 'Content2 value wrong');
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::createOne
	 * @todo   Implement testCreateOne().
	 */
	public function testCreateOne()
	{
		$data = new Data;
		$data->title = 'Flower3';
		$data->catid = 10;
		$data->b     = new Data(
			array(
				'mark' => 'Sakura3'
			)
		);

		$this->object->createOne($data);

		$compareContent = $this->loadToData(
			<<<SQL
SELECT *
FROM ww_content
WHERE title = 'Flower3'
SQL
		);

		$this->assertNotEmpty($compareContent, 'Record not inserted.');

		$compareContent2 = $this->loadToData(
			<<<SQL
SELECT *
FROM ww_content2
WHERE content_id = {$compareContent->id}
SQL
		);

		$this->assertNotEmpty($compareContent2, 'Record not inserted.');
	}

	/**
	 * @covers Windwalker\DataMapper\AbstractDataMapper::update
	 * @todo   Implement testUpdate().
	 */
	public function testUpdate()
	{
		$dataset = $this->object->find(array(new Compare('title', 'Flower%', 'LIKE')), null, 0, 2);

		$dataset[0]->title   = 'Rose 1';
		$dataset[0]->b->mark = 5566;
		$dataset[1]->title   = 'Rose 2';

		$this->object->update($dataset);

		$updatedSet = $this->object->find(array(new Compare('title', 'Rose%', 'LIKE')));

		$this->assertEquals(count($updatedSet), 2, 'Updated row count wrong');

		$this->assertEquals($updatedSet[0]->b->mark, 5566, 'content2 not updated.');
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
	 * @covers Windwalker\DataMapper\AbstractDataMapper::delete
	 * @todo   Implement testDelete().
	 */
	public function testDelete()
	{
		$dataset = $this->object->find(array(new Compare('title', 'Rose%', 'LIKE')));

		$this->object->delete(array(new Compare('title', 'Rose%', 'LIKE')));

		$ids = implode(',', ArrayHelper::getColumn((array) $dataset, 'id'));

		$compareContent = $this->db->setQuery(
			<<<SQL
SELECT *
FROM ww_content
WHERE id IN ({$ids})
SQL
		)->loadObjectList();

		$compareContent2 = $this->db->setQuery(
			<<<SQL
SELECT *
FROM ww_content2
WHERE content_id IN ({$ids})
SQL
		)->loadObjectList();

		$this->assertEmpty($compareContent, 'Records not deleted.');

		$this->assertEmpty($compareContent2, 'Records not deleted.');
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
