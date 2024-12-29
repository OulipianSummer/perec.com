<?php

namespace Drupal\Core\Render\Element;

use Drupal\Core\Render\Attribute\RenderElement;
use Drupal\Core\Render\Element;
use Drupal\Core\Security\DoTrustedCallbackTrait;
use Drupal\Core\Render\Component\Exception\InvalidComponentDataException;

/**
 * Provides a Single-Directory Component render element.
 *
 * Properties:
 * - #component: The machine name of the component.
 * - #props: an associative array where the keys are the names of the
 *   component props, and the values are the prop values.
 * - #slots: an associative array where the keys are the slot names, and the
 *   values are the slot values. Expected slot values are renderable arrays.
 * - #propsAlter: an array of trusted callbacks. These are used to prepare the
 *   context. Typical uses include replacing tokens in props.
 * - #slotsAlter: an array of trusted callbacks to alter the render array in
 *   #slots.
 *
 * Usage Example:
 *
 * @code
 * $build['component'] = [
 *   '#type' => 'component',
 *   '#component' => 'olivero:button',
 * ];
 * @endcode
 *
 * @see \Drupal\Core\Render\Element\Textarea
 */
#[RenderElement('component')]
class ComponentElement extends RenderElementBase {

  use DoTrustedCallbackTrait;

  /**
   * Expands a component into an inline template with an attachment.
   *
   * @param array $element
   *   The element to process. See main class documentation for properties.
   *
   * @return array
   *   The form element.
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentDataException
   */
  public function preRenderComponent(array $element): array {
    $props = $element['#props'];
    $props_alter_callbacks = $element['#propsAlter'];
    // This callback can be used to prepare the context. For instance to replace
    // tokens in the props.
    $props = array_reduce(
      $props_alter_callbacks,
      fn(array $carry, callable $callback) => $this->doTrustedCallback(
        $callback,
        [$carry],
        '%s is not trusted',
      ),
      $props
    );
    $inline_template = $this->generateComponentTemplate(
      $element['#component'],
      $element['#slots'],
      $element['#slotsAlter'],
      $props,
    );
    $element['inline-template'] = [
      '#type' => 'inline_template',
      '#template' => $inline_template,
      '#context' => $props,
    ];
    return $element;
  }

  /**
   * Generates the template to render the component.
   *
   * @param string $id
   *   The component id.
   * @param array $slots
   *   The contents of any potential embed blocks.
   * @param array $slots_alter_callbacks
   *   The potential callables for altering slots.
   * @param array $context
   *   Inline template context.
   *
   * @return string
   *   The template.
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentDataException
   *   When slots are not render arrays.
   */
  private function generateComponentTemplate(
    string $id,
    array $slots,
    array $slots_alter_callbacks,
    array &$context,
  ): string {
    $template = '{# This template was dynamically generated by single-directory components #}' . PHP_EOL;
    $template .= sprintf('{%% embed \'%s\' %%}', $id);
    $template .= PHP_EOL;
    foreach ($slots as $slot_name => $slot_value) {
      if (\is_scalar($slot_value)) {
        $slot_value = [
          "#plain_text" => (string) $slot_value,
        ];
      }
      if (!Element::isRenderArray($slot_value)) {
        $message = sprintf(
          'Unable to render component "%s". A render array or a scalar is expected for the slot "%s" when using the render element with the "#slots" property',
          $id,
          $slot_name
        );
        throw new InvalidComponentDataException($message);
      }
      $context[$slot_name] = array_reduce(
        $slots_alter_callbacks,
        fn(array $carry, callable $callback) => $this->doTrustedCallback(
          $callback,
          [$carry, $context],
          '%s is not trusted',
        ),
        $slot_value
      );
      $template .= "  {% block $slot_name %}" . PHP_EOL
        . "    {{ $slot_name }}" . PHP_EOL
        . "  {% endblock %}" . PHP_EOL;
    }
    $template .= '{% endembed %}' . PHP_EOL;
    return $template;
  }

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#pre_render' => [
        [$this, 'preRenderComponent'],
      ],
      '#component' => '',
      '#props' => [],
      '#slots' => [],
      '#propsAlter' => [],
      '#slotsAlter' => [],
    ];
  }

}
