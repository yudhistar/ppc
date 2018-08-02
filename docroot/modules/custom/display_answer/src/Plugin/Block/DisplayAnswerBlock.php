<?php
/**
 * @file
 * Contains \Drupal\article\Plugin\Block\XaiBlock.
 */
namespace Drupal\display_answer\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'answer' block.
 *
 * @Block(
 *   id = "display_answer_block",
 *   admin_label = @Translation("Display Answer Block"),
 *   category = @Translation("Custom Answer block for Question Content Type")
 * )
 */
class DisplayAnswerBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
   public function build() {

     $markup = '';
     // Get nid of a curre node.
     $node = \Drupal::routeMatch()->getParameter('node');
     if ($node instanceof \Drupal\node\NodeInterface) {
       // You can get nid and anything else you need from the node object.
       $nid = $node->id();
     }
     $uid = \Drupal::currentUser()->id();

    // Query to get answer markup from webform submissions.
    $query = \Drupal::entityQuery('webform_submission')
                ->condition('entity_type', 'node')
                ->condition('entity_id', $nid, '=')
                ->condition('uid',$uid,'=');
    $results = $query->execute();
    $results = \Drupal\webform\Entity\WebformSubmission::loadMultiple($results);
    foreach( $results as $key => $value){
     $markup .= $value->getData()['answer']['value'];
    }

     return [
       '#type' => 'markup',
       '#markup' => $markup,
       '#cache' => ['max-age' => 0],
     ];
   }
}
