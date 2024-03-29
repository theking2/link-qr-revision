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
  public function __toString()
  {
    return sprintf( '%s [%s %s]',
      $this->username, $this-> vorname, $this-> nachname
    );
  }

  /**
   * Have the database create a new UUID for this user
   * @return bool
   */
  public function createUUID()
  {
    $this-> __set( 'uuid', base64url_encode(random_bytes(48)) );
  }
  /**
   * Set the hash for this user's password
   */
  public function setPasswordHash(string $password) {
    // use the setter to mark as dirty
    $this-> __set('hash', password_hash($password, PASSWORD_ARGON2ID) );
  }
  /**
   * Check the password against the hash or to the old style password
   */
  public function checkPassword(?string $password): bool
  {
    return password_verify($password, $this->hash);
  }

	
}
