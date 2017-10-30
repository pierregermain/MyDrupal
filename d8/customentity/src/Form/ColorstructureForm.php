<?php

namespace Drupal\customentity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Colorstructure edit forms.
 *
 * @ingroup customentity
 */
class ColorstructureForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\customentity\Entity\Colorstructure */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Colorstructure.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Colorstructure.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.colorstructure.canonical', ['colorstructure' => $entity->id()]);
  }

}
