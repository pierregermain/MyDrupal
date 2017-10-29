<?php

namespace Drupal\content_entity_example\Tests;

use Drupal\content_entity_example\Entity\Colorstr;
use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Tests the basic functions of the Content Entity Example module.
 *
 * @ingroup content_entity_example
 *
 * @group content_entity_example
 * @group examples
 */
class ContentEntityExampleTest extends ExamplesBrowserTestBase {

  public static $modules = ['content_entity_example', 'block', 'field_ui'];

  /**
   * Basic tests for Content Entity Example.
   */
  public function testContentEntityExample() {
    $assert = $this->assertSession();

    $web_user = $this->drupalCreateUser([
      'add colorstr entity',
      'edit colorstr entity',
      'view colorstr entity',
      'delete colorstr entity',
      'administer colorstr entity',
      'administer content_entity_example_colorstr display',
      'administer content_entity_example_colorstr fields',
      'administer content_entity_example_colorstr form display',
    ]);

    // Anonymous User should not see the link to the listing.
    $assert->pageTextNotContains('Content Entity Example: Colorstrs Listing');

    $this->drupalLogin($web_user);

    // Web_user user has the right to view listing.
    $assert->linkExists('Content Entity Example: Colorstrs Listing');

    $this->clickLink('Content Entity Example: Colorstrs Listing');

    // WebUser can add entity content.
    $assert->linkExists('Add Colorstr');

    $this->clickLink(t('Add Colorstr'));

    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');

    $user_ref = $web_user->name->value . ' (' . $web_user->id() . ')';
    $assert->fieldValueEquals('user_id[0][target_id]', $user_ref);

    // Post content, save an instance. Go back to list after saving.
    $edit = [
      'name[0][value]' => 'test name',
      'first_name[0][value]' => 'test first name',
      'gender' => 'male',
      'role' => 'administrator',
    ];
    $this->drupalPostForm(NULL, $edit, t('Save'));

    // Entity listed.
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    $this->clickLink('test name');

    // Entity shown.
    $assert->pageTextContains('test name');
    $assert->pageTextContains('test first name');
    $assert->pageTextContains('administrator');
    $assert->pageTextContains('male');
    $assert->linkExists('Add Colorstr');
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    // Delete the entity.
    $this->clickLink('Delete');

    // Confirm deletion.
    $assert->linkExists('Cancel');
    $this->drupalPostForm(NULL, [], 'Delete');

    // Back to list, must be empty.
    $assert->pageTextNotContains('test name');

    // Settings page.
    $this->drupalGet('admin/structure/content_entity_example_colorstr_settings');
    $assert->pageTextContains('Colorstr Settings');

    // Make sure the field manipulation links are available.
    $assert->linkExists('Settings');
    $assert->linkExists('Manage fields');
    $assert->linkExists('Manage form display');
    $assert->linkExists('Manage display');
  }

  /**
   * Test all paths exposed by the module, by permission.
   */
  public function testPaths() {
    $assert = $this->assertSession();

    // Generate a colorstr so that we can test the paths against it.
    $colorstr = Colorstr::create(
      [
        'name' => 'somename',
        'first_name' => 'Joe',
        'gender' => 'female',
        'role' => 'administrator',
      ]
    );
    $colorstr->save();

    // Gather the test data.
    $data = $this->providerTestPaths($colorstr->id());

    // Run the tests.
    foreach ($data as $datum) {
      // drupalCreateUser() doesn't know what to do with an empty permission
      // array, so we help it out.
      if ($datum[2]) {
        $user = $this->drupalCreateUser([$datum[2]]);
        $this->drupalLogin($user);
      }
      else {
        $user = $this->drupalCreateUser();
        $this->drupalLogin($user);
      }
      $this->drupalGet($datum[1]);
      $assert->statusCodeEquals($datum[0]);
    }
  }

  /**
   * Data provider for testPaths.
   *
   * @param int $colorstr_id
   *   The id of an existing Colorstr entity.
   *
   * @return array
   *   Nested array of testing data. Arranged like this:
   *   - Expected response code.
   *   - Path to request.
   *   - Permission for the user.
   */
  protected function providerTestPaths($colorstr_id) {
    return [
      [
        200,
        '/content_entity_example_colorstr/' . $colorstr_id,
        'view colorstr entity',
      ],
      [
        403,
        '/content_entity_example_colorstr/' . $colorstr_id,
        '',
      ],
      [
        200,
        '/content_entity_example_colorstr/list',
        'view colorstr entity',
      ],
      [
        403,
        '/content_entity_example_colorstr/list',
        '',
      ],
      [
        200,
        '/content_entity_example_colorstr/add',
        'add colorstr entity',
      ],
      [
        403,
        '/content_entity_example_colorstr/add',
        '',
      ],
      [
        200,
        '/content_entity_example_colorstr/' . $colorstr_id . '/edit',
        'edit colorstr entity',
      ],
      [
        403,
        '/content_entity_example_colorstr/' . $colorstr_id . '/edit',
        '',
      ],
      [
        200,
        '/colorstr/' . $colorstr_id . '/delete',
        'delete colorstr entity',
      ],
      [
        403,
        '/colorstr/' . $colorstr_id . '/delete',
        '',
      ],
      [
        200,
        'admin/structure/content_entity_example_colorstr_settings',
        'administer colorstr entity',
      ],
      [
        403,
        'admin/structure/content_entity_example_colorstr_settings',
        '',
      ],
    ];
  }

  /**
   * Test add new fields to the colorstr entity.
   */
  public function testAddFields() {
    $web_user = $this->drupalCreateUser([
      'administer colorstr entity',
      'administer content_entity_example_colorstr display',
      'administer content_entity_example_colorstr fields',
      'administer content_entity_example_colorstr form display',
    ]);

    $this->drupalLogin($web_user);
    $entity_name = 'content_entity_example_colorstr';
    $add_field_url = 'admin/structure/' . $entity_name . '_settings/fields/add-field';
    $this->drupalGet($add_field_url);
    $field_name = 'test_name';
    $edit = [
      'new_storage_type' => 'list_string',
      'label' => 'test name',
      'field_name' => $field_name,
    ];

    $this->drupalPostForm(NULL, $edit, t('Save and continue'));
    $expected_path = $this->buildUrl('admin/structure/' . $entity_name . '_settings/fields/' . $entity_name . '.' . $entity_name . '.field_' . $field_name . '/storage');

    // Fetch url without query parameters.
    $current_path = strtok($this->getUrl(), '?');
    $this->assertEquals($expected_path, $current_path);
  }

}
