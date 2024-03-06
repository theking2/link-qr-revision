<?php declare(strict_types=1);
namespace LinkQr;

/**
 * code – Persistant DB object
 * int       $user_id;
 * string    $code;
 * string    $url;
 * \DateTime $last_used;
 * int       $hits;
 * int       $url_md5_l;
 * int       $url_md5_r;
 */
final class Code
	extends \Kingsoft\Persist\Base
	implements \Kingsoft\Persist\IPersist
{
	use \Kingsoft\Persist\Db\DBPersistTrait;

	protected ?int       $user_id;
	protected ?string    $code;
	protected ?string    $url;
	protected ?\DateTime $last_used;
	protected ?int       $hits;
	protected ?int       $url_md5_l;
	protected ?int       $url_md5_r;

	// Persist functions
	static public function getPrimaryKey():string { return 'code'; }
	static public function getTableName():string { return '`code`'; }
	static public function getFields():array {
		return [
			'user_id'            => ['int', 10 ],
			'code'               => ['string', 5 ],
			'url'                => ['string', 4096 ],
			'last_used'          => ['\DateTime', 0 ],
			'hits'               => ['int', 10 ],
			'url_md5_l'          => ['int', 20 ],
			'url_md5_r'          => ['int', 20 ],
		];
	}
}