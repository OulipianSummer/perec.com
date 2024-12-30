<?php

declare(strict_types=1);

namespace Drupal\perec_builder;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a tour entity type.
 */
interface TourInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
