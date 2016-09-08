<?php

namespace Tests\Laravel_sdk;

use Sqweb\Laravel_sdk\SqwebController;

class TestSqweb extends \TestCase
{
    public function testCreation()
    {
        $this->assertTrue(true);
    }

    public function testCheckCredits()
    {
        $sqweb = new SqwebController;
        $credits = $sqweb->checkCredits();
        $this->assertEquals('0', $credits);
    }

    public function testGreyButton()
    {
        $sqweb = new SqwebController;
        ob_start();
        $greyButton = $sqweb->button('grey');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div class="sqweb-button sqweb-grey"></div>', $output);
    }

    public function testBlueButton()
    {
        $sqweb = new SqwebController;
        ob_start();
        $greyButton = $sqweb->button();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div class="sqweb-button"></div>', $output);
    }

    public function testScript()
    {
        $sqweb = new SqwebController;
        ob_start();
        $script = $sqweb->script();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<script>
            var _sqw = {
                id_site: '. $sqweb->id_site .',
                debug: '. $sqweb->debug .',
                targeting: '. $sqweb->targeting .',
                beacon: '. $sqweb->beacon .',
                dwide: '. $sqweb->dwide .',
                i18n: "'. $sqweb->lang .'",
                msg: "'. $sqweb->message .'"};
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "//cdn.sqweb.com/sqweb-beta.js";
            document.getElementsByTagName("head")[0].appendChild(script);
        </script>', $output);
    }

    public function testIsTimestampOk()
    {
        $sqweb = new SqwebController;
        $return = $sqweb->isTimestamp('1459959447');
        $this->assertEquals(1, $return);
    }

    public function testIsTimestampKo()
    {
        $sqweb = new SqwebController;
        $return = $sqweb->isTimestamp('fdasofhsa');
        $this->assertEquals(0, $return);
    }

    public function testTransparent10pct()
    {
        $sqweb = new SqwebController;
        $return = $sqweb->transparent('Lorem ipsum test', 10);
        $this->assertEquals($return, '<span style="opacity: 1">Lorem</span>');
    }

    public function testTransparent0pct()
    {
        $sqweb = new SqwebController;
        $return = $sqweb->transparent('Lorem ipsum test', 0);
        $this->assertEquals($return, '');
    }

    public function testTransparent100pct()
    {
        $sqweb = new SqwebController;
        $return = $sqweb->transparent('Lorem ipsum test', 100);
        $this->assertEquals($return, '<span style="opacity: 1">Lorem</span> <span style="opacity: 0.66666666666667">ipsum</span> <span style="opacity: 0.33333333333333">test</span>');
    }

    public function testTransparent50pct()
    {
        $sqweb = new SqwebController;
        $return = $sqweb->transparent('Lorem ipsum test', 50);
        $this->assertEquals($return, '<span style="opacity: 1">Lorem</span> <span style="opacity: 0.5">ipsum</span>');
    }
}
