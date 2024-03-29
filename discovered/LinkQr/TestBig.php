<?php declare(strict_types=1);
namespace LinkQr;

/**
 * test-big – Persistant DB object
 * int       $longlong;
 */
final class TestBig
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?int       $longlong;

	// Persist functions
	static public function getPrimaryKey():string { return 'code'; }
	static public function isPrimaryKeyAutoIncrement():bool { return false; }
	static public function getTableName():string { return '`test-big`'; }
	static public function getFields():array {
		return [
			'longlong'           => ['int', 20 ],
		];
	}
}