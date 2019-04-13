<?php
namespace App\Service;
use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;
class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;
    private $isDebug;
    private $security;
    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown,
                                LoggerInterface $markdownLogger, Security $security)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = true;//add de eu
        $this->security = $security;
    }

}