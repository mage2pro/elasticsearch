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
			$cb = new CB;
			$cb->setHosts(self::hosts($o));
			$r = OM::getInstance()->create(MC7::class, [
				'elasticsearchClient' => $cb->build(), 'options' => $o
			]);
		}
		return $r;
	}

	/**
	 * 2022-09-15
	 * @see \Magento\Elasticsearch7\Model\Client\Elasticsearch::buildESConfig()
	 * https://github.com/magento/magento2/blob/2.4.3-p1/app/code/Magento/Elasticsearch7/Model/Client/Elasticsearch.php#L132-L163
	 * @used-by aroundCreate()
	 * @param array $o
	 * @return array
	 */
	private static function hosts(array $o) {
		$r = [];
		if (is_string($o['hostname'])) {
			$o['hostname'] = explode(',', $o['hostname']);
		}
		foreach ($o['hostname'] as $host) {
			if (!empty($host)) {
				$hostWOProtocol = preg_replace('/http[s]?:\/\//i', '', $host);
				[$domain, $port] = array_pad(explode(':', trim($hostWOProtocol), 2), 2, 9200);
				$currentHostConfig = [
					'host' => $domain,
					'port' => $port,
					'scheme' => parse_url($host, PHP_URL_SCHEME) ?: 'http',
				];
				if (!empty($o['enableAuth']) && (int)$o['enableAuth'] === 1) {
					$currentHostConfig['user'] = $o['username'];
					$currentHostConfig['pass'] = $o['password'];
				}
				$r[] = $currentHostConfig;
			}
		}
		return $r;
	}
}
