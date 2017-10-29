<?php

namespace Drupal\content_entity_example\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a content_entity_example entity.
 *
 * @ingroup content_entity_example
 */
class ColorstrDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {

    return $this->t('Are you sure you want to delete entity %name?', ['%name' => $this->entity->title->value]);
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the colorstr list.
   */
  public function getCancelUrl() {
    return new Url('entity.content_entity_example_colorstr.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   *
   * Delete the entity and log the event. logger() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    $form_state->setRedirect('entity.content_entity_example_colorstr.collection');
  }

}
