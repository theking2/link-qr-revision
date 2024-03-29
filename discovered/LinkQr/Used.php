<?php declare(strict_types=1);
namespace LinkQr;

/**
 * used – Persistant DB object
 * int       $used;
 * int       $total;
 */
final class Used
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?int       $used;
	protected ?int       $total;

	// Persist functions
	static public function getPrimaryKey():string { return 'code'; }
	static public function isPrimaryKeyAutoIncrement():bool { return false; }
	static public function getTableName():string { return '`used`'; }
	static public function getFields():array {
		return [
			'used'               => ['int', 21 ],
			'total'              => ['int', 21 ],
		];
	}
}