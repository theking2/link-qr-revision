<?php declare(strict_types=1);
namespace LinkQr;

/**
 * user – Persistant DB object
 * int       $id;
 * string    $username;
 * string    $vorname;
 * string    $nachname;
 * string    $hash;
 * \DateTime $last_login;
 */
final class User
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?int       $id;
	protected ?string    $username;
	protected ?string    $vorname;
	protected ?string    $nachname;
	protected ?string    $hash;
	protected ?\DateTime $last_login;

	// Persist functions
	static public function getPrimaryKey():string { return 'id'; }
	static public function isPrimaryKeyAutoIncrement():bool { return false; }
	static public function getTableName():string { return '`user`'; }
	static public function getFields():array {
		return [
			'id'                 => ['int', 10 ],
			'username'           => ['string', 255 ],
			'vorname'            => ['string', 30 ],
			'nachname'           => ['string', 30 ],
			'hash'               => ['string', 255 ],
			'last_login'         => ['\DateTime', 0 ],
		];
	}
}