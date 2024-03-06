<?php declare(strict_types=1);
namespace LinkQr;

/**
 * uuid_test – Persistant DB object
 * string    $uuid_testcol;
 */
final class UuidTest
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?string    $uuid_testcol;

	// Persist functions
	static public function getPrimaryKey():string { return 'id'; }
	static public function getTableName():string { return '`uuid_test`'; }
	static public function getFields():array {
		return [
			'uuid_testcol'       => ['string', 45 ],
		];
	}
}