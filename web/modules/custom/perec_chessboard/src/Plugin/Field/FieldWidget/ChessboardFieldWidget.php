<?php

declare(strict_types=1);

namespace Drupal\perec_chessboard\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Attribute\FieldWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines the 'perec_chessboard_widget' field widget.
 */
#[FieldWidget(
  id: 'perec_chessboard_widget',
  label: new TranslatableMarkup('Chessboard Widget'),
  field_types: ['perec_chessboard_field_type'],
)]
final class ChessboardFieldWidget extends WidgetBase {

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
    $element['size'] = [
      '#type' => 'select',
      '#options' => perec_chessboard_sizes(),
      '#title' => $this->t('Size'),
      '#default_value' => $this->getSetting('size'),
    ];
    return $element;
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
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $size = $this->getSetting('size');
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    if (empty($value)) {
      $value = $size;
    }

    $element['input_wrapper']['value'] = $element + [
      '#type' => 'textfield',
       '#default_value' => $items[$delta]->value ?? NULL,
    ];

    $rows = [];

    // Loop through each row and column to create the chessboard.
    for ($row = 0; $row < $size; $row++) {
      $table_row = [];
      for ($col = 0; $col < $size; $col++) {
        $rank = perec_chessboard_number_to_letter($col + 1);
        $file = ($size + 1) - ($row + 1);
        $cell_id = $rank . $file;
        $table_row[] = [
          'data-cell-id' => $cell_id,
        ];
      }

      // Add the row to the table rows array.
      $rows[] = $table_row;
    }

    $element['input_wrapper']['chessboard'] = [
      '#type' => 'table',
      '#rows' => $rows,
    ];

    dpm($element);

    return $element;

  }

  /**
   * {@inheritdoc}
   */
  public function formElementOld(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $size = $this->getSetting('size');
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    if (empty($value)) {
      $value = $size;
    }
    $element['value'] = $element + [
      '#type' => 'perec_chessboard',
      '#size' => $value,
      '#default_value' => $items[$delta]->value ?? NULL,
    ];
    return $element;
  }
}
