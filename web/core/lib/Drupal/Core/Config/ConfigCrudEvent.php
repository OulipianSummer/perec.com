<?php

namespace Drupal\Core\Config;

use Drupal\Component\EventDispatcher\Event;

/**
 * Wraps a configuration event for event listeners.
 */
class ConfigCrudEvent extends Event {

  /**
   * Configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a configuration event object.
   *
   * @param \Drupal\Core\Config\StorableConfigBase $config
   *   Configuration object.
   */
  public function __construct(StorableConfigBase $config) {
    $this->config = $config;
  }

  /**
   * Gets configuration object.
   *
   * @return \Drupal\Core\Config\StorableConfigBase
   *   The configuration object that caused the event to fire.
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * Checks to see if the provided configuration key's value has changed.
   *
   * @param string $key
   *   The configuration key to check if it has changed.
   *
   * @return bool
   *   TRUE if the value of the given key has changed, FALSE otherwise.
   */
  public function isChanged($key) {
    return $this->config->get($key) !== $this->config->getOriginal($key);
  }

}
