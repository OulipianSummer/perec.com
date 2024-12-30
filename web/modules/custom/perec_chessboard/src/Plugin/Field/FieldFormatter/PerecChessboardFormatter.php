<?php

declare(strict_types=1);

namespace Drupal\perec_chessboard\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'Chessboard' formatter.
 */
#[FieldFormatter(
  id: 'perec_chessboard_formatter',
  label: new TranslatableMarkup('Chessboard'),
  field_types: ['perec_chessboard_field_type'],
)]
final class PerecChessboardFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    $setting = ['size' => '8'];
    return $setting + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state): array {
    $elements['size'] = [
      '#type' => 'select',
      '#options' => perec_chessboard_sizes(),
      '#title' => $this->t('Size'),
      '#default_value' => $this->getSetting('size'),
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array {
    return [
      $this->t('Size: @size', ['@size' => $this->getSetting('size')]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#markup' => $item->value,
      ];
    }
    return $element;
  }

}
