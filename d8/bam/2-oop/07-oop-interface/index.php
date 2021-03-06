<?php

class Form {

    public $settings;
    public $form_number = 1;

    function __construct($settings) {
        $this->settings = $settings;
    }

    /**
     * Builds a form from an array.
     */
    function build() {
        $output = '';

        // For multiple forms, create a counter.
        $this->form_number++;

        // Check for submitted form and validate
        if (isset($_POST['action']) && $_POST['action'] == 'submit_' . $this->form_number) {
            if ($this->validate()) {
                $this->submit();
            }
        }

        // Loop through each form element and render it.
        foreach ($this->settings as $name => $settings) {
            $label = '<label>' . $settings['title'] . '</label>';
            switch ($settings['type']) {
                case 'textarea':
                    $input = '<textarea name="' . $name . '" ></textarea>';
                    break;
                case 'submit':
                    $input = '<input type="submit" name="' . $name . '" value="' . $settings['title'] . '">';
                    $label = '';
                    break;
                default:
                    $input = '<input type="' . $settings['type'] . '" name="' . $name . '" />';
                    break;
            }
            $output .= $label . '<p>' . $input . '</p>';
        }

        // Wrap a form around the inputs.
        $output = '
      <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
        <input type="hidden" name="action" value="submit_' . $this->form_number . '" />
        ' . $output . '
      </form>';

        // Return the form.
        return $output;
    }

    /**
     * Validates the form based on the 'validations' attribute in the form array.
     */
    function validate() {
        foreach ($this->settings as $name => $settings) {
            $value = $_POST[$name];
            if (isset($settings['validations'])) {
                foreach ($settings['validations'] as $validation) {
                    switch ($validation) {

                        case 'not_empty':
                            if (!Validator::notEmpty($value)) {
                                return false;
                            }
                            break;

                        case 'is_valid_email':
                            if (!Validator::isValidEmail($value)) {
                                return false;
                            }
                            break;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Once validated, this processes the form.
     */
    function submit() {
        $output = '';
        foreach ($this->settings as $name => $settings) {
            $value = $_POST[$name];
            $output .= '<li>' . $settings['title'] . ': ' . $value . '</li>';
        }
        $output = '<p>You submitted the following:</p><ul>' . $output . '</ul><br />';
        print $output;
    }
}

interface Page {
    function build();
    function theme();
    //function iDoNotExist();
}

class DefaultPage implements Page {

    public $settings;
    public $title;
    public $output;

    function __construct($settings, $title) {
        $this->settings = $settings;
        $this->title = $title;
    }

    /**
     * Builds out the content of a page based on the type of elements passed to it.
     */
    function build() {
        $builder = new Builder();
        $this->output = $builder->render($this->settings);
    }

    /**
     * Renders the page content based on a simple template.
     */
    function theme() {
        return '
      <html>
        <head>
          <title>' . $this->title . '</title>
        </head>
        <body>
          ' . $this->output . '
        </body>
      </html>';
    }
}

class PrintedPage implements Page {

    public $settings;
    public $title;
    public $output;

    function __construct($settings, $title) {
        $this->settings = $settings;
        $this->title = $title;
    }

    /**
     * Builds out the content of a page based on the type of elements passed to it.
     */
    function build() {
        $builder = new Builder();
        $this->output = $builder->render($this->settings);
    }

    /**
     * Renders the page content based on a simple template.
     */
    function theme() {
        return '
      <html>
        <head>
          <title>FOR PRINT: ' . $this->title . '</title>
        </head>
        <body>
          <div style="width:800px;border:5px solid black;margin-left:auto;margin-right:auto;padding:20px;">' . $this->output . '</div>
        </body>
      </html>';
    }
}

class Validator {

    static function notEmpty($value) {
        if (trim($value) == '') {
            return false;
        }
        return true;
    }

    static function isValidEmail($value) {
        if (!strstr($value, '@')) {
            return false;
        }
        return true;
    }
}

class Builder {

    public $settings = array();
    public $output = '';

    function render($settings) {
        $this->settings = $settings;
        foreach ($this->settings as $id => $values) {
            switch ($values['type']) {
                case 'html':
                    $this->output .= '<div id="' . $id . '">' . $values['value'] . '</div>';
                    break;
                case 'form':
                    $form = new Form($values['value']);
                    $this->output .= $form->build();
                    break;
            }
        }
        return $this->output;
    }
}

class ContactUsController {
    static function ContactUsPage($page_elements) {
        if (isset($_GET['print'])) {
            $page = new PrintedPage($page_elements, 'Contact Us');
        } else {
            $page = new DefaultPage($page_elements, 'Contact Us');
        }
        $page->build();
        return $page->theme();
    }
}


// Instantiate a Builder object to use below.
$builder = new Builder();

// Create an array for the contact form.
$contact_form = array(
    'name' => array(
        'title' => 'Name',
        'type' => 'text',
        'validations' => array('not_empty'),
    ),
    'email' => array(
        'title' => 'Email',
        'type' => 'email',
        'validations' => array('not_empty', 'is_valid_email'),
    ),
    'comment' => array(
        'title' => 'Comments',
        'type' => 'textarea',
        'validations' => array('not_empty'),
    ),
    'submit' => array(
        'title' => 'Submit me!',
        'type' => 'submit',
    ),
);

// Create an array for the footer content and render it.
$footer_content = array(
    'divider' => array(
        'type' => 'html',
        'value' => '<hr />',
    ),
    'content' => array(
        'type' => 'html',
        'value' => '<div style="text-align:center">&copy; ' . date('Y') . ' BuildAModule</div>',
    ),
);
$footer = $builder->render($footer_content);

// Create an array for the page.
$page_elements = array(
    'header' => array(
        'type' => 'html',
        'value' => '<p>Please submit this form. You will make my day if you do.</p>',
    ),
    'contact_form' => array(
        'type' => 'form',
        'value' => $contact_form,
    ),
    'footer' => array(
        'type' => 'html',
        'value' => $footer,
    ),
);

print ContactUsController::ContactUsPage($page_elements);