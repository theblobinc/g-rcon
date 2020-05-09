<?php

namespace Knik\GRcon;

use Knik\GRcon\Exceptions\ProtocolNotSupportedException;
use Knik\GRcon\Interfaces\ConfigurableAdapterInterface;
use Knik\GRcon\Interfaces\ProtocolAdapterInterface;
use Knik\GRcon\Protocols\GoldSourceAdapter;
use Knik\GRcon\Protocols\SourceAdapter;

class EasyGRcon extends GRconAbstract
{
    protected $protocolMap = [
        // Source rcon protocol games
        'source'        => SourceAdapter::class,
        'csgo'          => SourceAdapter::class,  // Counter-Strike Global Offensive
        'cssv34'        => SourceAdapter::class,  // Counter-Strike Source v34
        'cssource'      => SourceAdapter::class,  // Counter-Strike Source
        'tf'            => SourceAdapter::class,  // Team Fortress 2
        'minecraft'     => SourceAdapter::class,  // Minecraft

        // GoldSource RCON protocol games
        'goldsource'    => GoldSourceAdapter::class,
        'cstrike'       => GoldSourceAdapter::class, // Counter-Strike 1.6
        'valve'         => GoldSourceAdapter::class, // Half-Life
        'halflife'      => GoldSourceAdapter::class, // Half-Life
    ];

    /**
     * PureGRcon constructor.
     * @param string $protocolName protocol or game code
     * @param array|null $options
     *
     * @throws ProtocolNotSupportedException
     */
    public function __construct(string $protocolName, array $options = null)
    {
        $this->adapter = $this->getAdapterInstance($protocolName, $options);
    }

    /**
     * @param string $protocolName
     * @param array|null $options
     * @return ProtocolAdapterInterface
     *
     * @throws ProtocolNotSupportedException
     */
    protected function getAdapterInstance(string $protocolName, array $options = null): ProtocolAdapterInterface
    {
        if (!array_key_exists($protocolName, $this->protocolMap)) {
            throw new ProtocolNotSupportedException;
        }

        $adapterClass = $this->protocolMap[$protocolName];

        if ( ! in_array(ConfigurableAdapterInterface::class, class_implements($adapterClass))) {
            throw new ProtocolNotSupportedException;
        }

        return new $adapterClass($options);
    }
}