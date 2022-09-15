<?php
namespace Dfe\Elasticsearch\Plugin\Magento\AdvancedSearch\Model\Client;
use Magento\AdvancedSearch\Model\Client\ClientFactory as Sb;
use Magento\AdvancedSearch\Model\Client\ClientInterface as IClient;
# 2022-09-15
final class ClientFactory {
	/**
	 * 2022-09-15
	 * @see \Magento\AdvancedSearch\Model\Client\ClientFactory::create()
	 * https://github.com/magento/magento2/blob/2.4.3-p1/app/code/Magento/AdvancedSearch/Model/Client/ClientFactory.php#L34-L46
	 * @param Sb $sb
	 * @param \Closure $f
     * @param array $options
	 * @return IClient
	 */
	function aroundCreate(Sb $sb, \Closure $f, array $options) {
		return $f($options);
	}
}
