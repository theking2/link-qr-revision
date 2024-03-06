<?php declare(strict_types=1);
namespace LinkQr;

use \Kingsoft\Utils\Html;

/**
 * user_email – Persistant DB object
 * string    $username;
 * string    $email;
 * string    $uuid;
 * \DateTime $confirm_date;
 * \DateTime $register_date;
 */
final class UserEmail
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?string    $username;
	protected ?string    $email;
	protected ?string    $uuid;
	protected ?\DateTime $confirm_date;
	protected ?\DateTime $register_date;

	// Persist functions
	static public function getPrimaryKey():string { return 'email'; }
	static public function getTableName():string { return '`user_email`'; }
	static public function getFields():array {
		return [
			'username'           => ['string', 255 ],
			'email'              => ['string', 255 ],
			'uuid'               => ['string', 64 ],
			'confirm_date'       => ['\DateTime', 0 ],
			'register_date'      => ['\DateTime', 0 ],
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
   * @return void
   */
  public function createUUID()
  {
    $this-> __set( 'uuid', Html::base64url_encode(random_bytes(48)) );
  }
}