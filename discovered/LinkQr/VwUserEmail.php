<?php declare(strict_types=1);
namespace LinkQr;

/**
 * vw_user_email – Persistant DB object
 * string    $username;
 * string    $email;
 */
final class VwUserEmail
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?string    $username;
	protected ?string    $email;

	// Persist functions
	static public function getPrimaryKey():string { return 'id'; }
	static public function isPrimaryKeyAutoIncrement():bool { return false; }
	static public function getTableName():string { return '`vw_user_email`'; }
	static public function getFields():array {
		return [
			'username'           => ['string', 255 ],
			'email'              => ['string', 255 ],
		];
	}
}