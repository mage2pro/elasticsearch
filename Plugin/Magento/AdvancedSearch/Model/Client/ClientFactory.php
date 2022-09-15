<?php
namespace Dfe\Elasticsearch\Plugin\Magento\AdvancedSearch\Model\Client;
use Elasticsearch\Client as C;
use Elasticsearch\ClientBuilder as CB;
use Magento\AdvancedSearch\Model\Client\ClientFactory as Sb;
use Magento\AdvancedSearch\Model\Client\ClientInterface as IClient;
use Magento\Elasticsearch7\Model\Client\Elasticsearch as MC7;
use Magento\Framework\App\ObjectManager as OM;
# 2022-09-15
final class ClientFactory {
	/**
	 * 2022-09-15
	 * @see \Magento\AdvancedSearch\Model\Client\ClientFactory::create()
	 * https://github.com/magento/magento2/blob/2.4.3-p1/app/code/Magento/AdvancedSearch/Model/Client/ClientFactory.php#L34-L46
	 * @param Sb $sb
	 * @param \Closure $f
     * @param array $o
	 * @return IClient|MC7
	 */
	function aroundCreate(Sb $sb, \Closure $f, array $o) {
		$r = $f($o);
		if ($r instanceof MC7) {
			$r = OM::getInstance()->create(MC7::class, [
				'elasticsearchClient' => CB::fromConfig(self::buildESConfig($o), true), 'options' => $o
			]);
		}
		return $r;
	}

	/**
	 * 2022-09-15
	 * @see \Magento\Elasticsearch7\Model\Client\Elasticsearch::buildESConfig()
	 * https://github.com/magento/magento2/blob/2.4.3-p1/app/code/Magento/Elasticsearch7/Model/Client/Elasticsearch.php#L132-L163
	 * @used-by aroundCreate()
	 * @param array $r
	 * @return array
	 */
	private static function buildESConfig(array $r) {
		$hostname = preg_replace('/http[s]?:\/\//i', '', $r['hostname']);
		$protocol = parse_url($r['hostname'], PHP_URL_SCHEME);
		if (!$protocol) {
			$protocol = 'http';
		}
		$authString = '';
		if (!empty($r['enableAuth']) && (int)$r['enableAuth'] === 1) {
			$authString = "{$r['username']}:{$r['password']}@";
		}
		$portString = '';
		if (!empty($r['port'])) {
			$portString = ':' . $r['port'];
		}
		$host = $protocol . '://' . $authString . $hostname . $portString;
		$r['hosts'] = [$host];
		return $r;
	}
}
