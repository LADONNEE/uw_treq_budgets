<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\Input;
use Utilws\Formkit\InputView;

class InputViewRequiredTest extends TestCase
{

    public function test_html_required_is_included_as_attribute()
    {
        $it = new InputView(new Input('foo'));
        $it->set('required', true);
        $vars = $it->vars();

        $this->assertRegExp('/\brequired="required"/', $vars['htmlAttributes']);
    }

    public function test_html_required_attribute_is_rendered_when_required_is_truthy()
    {
        $it = new InputView(new Input('foo'));
        $it->set('required', true);
        $vars = $it->vars();

        $this->assertRequiredWasRendered($vars['htmlAttributes'], 'required=true');

        $it = new InputView(new Input('foo'));
        $it->set('required', 'required');
        $vars = $it->vars();

        $this->assertRequiredWasRendered($vars['htmlAttributes'], 'required=required');

        $it = new InputView(new Input('foo'));
        $it->set('required', 1);
        $vars = $it->vars();

        $this->assertRequiredWasRendered($vars['htmlAttributes'], 'required=1');
    }

    public function test_html_required_attribute_is_not_rendered_when_required_is_falsey()
    {
        $it = new InputView(new Input('foo'));
        $it->set('required', false);
        $vars = $it->vars();

        $this->assertRequiredWasNotRendered($vars['htmlAttributes'], 'required=false');

        $it = new InputView(new Input('foo'));
        $it->set('required', '');
        $vars = $it->vars();

        $this->assertRequiredWasNotRendered($vars['htmlAttributes'], 'required=""');

        $it = new InputView(new Input('foo'));
        $it->set('required', null);
        $vars = $it->vars();

        $this->assertRequiredWasNotRendered($vars['htmlAttributes'], 'required=null');
    }

    private function assertRequiredWasRendered($htmlString, $message = '')
    {
        $this->assertRegExp('/\brequired="required"/', $htmlString, $message);
    }

    private function assertRequiredWasNotRendered($htmlString, $message = '')
    {
        $this->assertNotRegExp('/\brequired=/', $htmlString, $message);
    }
}
